<?php
header('Content-Type: text/html; charset=UTF-8');
session_start();

if (!isset($_SESSION['autenticado'])) {
    header("Location: index.php");
    exit;
}

include("conexion.php");

$mysqli = new mysqli($host, $user, $pw, $db);

if ($mysqli->connect_error) {
    die("Error de conexión: " . $mysqli->connect_error);
}

$mysqli->set_charset("utf8mb4");

if (!isset($_GET['baby_id']) || trim($_GET['baby_id']) === '') {
    die("Bebé no especificado");
}

$baby_id = trim($_GET['baby_id']);


/* ============================================================
   FUNCIONES AUXILIARES
   ============================================================ */

function e($valor) {
    return htmlspecialchars(
        (string)($valor ?? ''),
        ENT_QUOTES,
        'UTF-8'
    );
}

function fecha_larga($fecha) {
    if (!$fecha) {
        return "Sin registro";
    }

    return date("d/m/Y H:i", strtotime($fecha));
}

function numero($valor, $decimales = 3, $vacio = "—") {
    if ($valor === null || $valor === '' || !is_numeric($valor)) {
        return $vacio;
    }

    return number_format(
        (float)$valor,
        $decimales,
        ',',
        '.'
    );
}

function porcentaje01($valor, $decimales = 1, $vacio = "—") {
    if ($valor === null || $valor === '' || !is_numeric($valor)) {
        return $vacio;
    }

    $v = (float)$valor;

    if ($v <= 1.000001) {
        $v *= 100;
    }

    return number_format(
        $v,
        $decimales,
        ',',
        '.'
    ) . "%";
}

function formatearLatencia($valor) {
    if (
        $valor === null ||
        $valor === '' ||
        !is_numeric($valor) ||
        (float)$valor < 0
    ) {
        return "<span class='muted-text'>No determinada</span>";
    }

    return e(
        number_format(
            (float)$valor,
            2,
            ',',
            '.'
        )
    ) . " s";
}

function textoLado($lado) {
    $lado = strtolower(trim((string)$lado));

    if ($lado === "izquierda") {
        return "Izquierda";
    }

    if ($lado === "derecha") {
        return "Derecha";
    }

    if ($lado === "centro" || $lado === "center") {
        return "Centro";
    }

    return $lado !== "" ? ucfirst($lado) : "—";
}



/* ============================================================
   CONTROL DE VISIBILIDAD POR VIDEO
   ============================================================ */

function columnaExiste($mysqli, $tabla, $columna) {
    $tabla = $mysqli->real_escape_string($tabla);
    $columna = $mysqli->real_escape_string($columna);

    $res = $mysqli->query(
        "SHOW COLUMNS FROM `{$tabla}` LIKE '{$columna}'"
    );

    return $res && $res->num_rows > 0;
}

function videoExisteFisicamente($video_path) {
    $video_path = trim((string)$video_path);

    if ($video_path === '') {
        return false;
    }

    $pathOnly = parse_url($video_path, PHP_URL_PATH);

    if (
        $pathOnly === null
        || $pathOnly === false
        || trim((string)$pathOnly) === ''
    ) {
        $pathOnly = $video_path;
    }

    $pathOnly = urldecode($pathOnly);
    $pathOnly = str_replace("\\", "/", $pathOnly);

    /*
     * Formato esperado en la base:
     * videos/archivo.mp4
     */
    $archivo = basename($pathOnly);

    $rutaFisica =
        __DIR__
        . DIRECTORY_SEPARATOR
        . "videos"
        . DIRECTORY_SEPARATOR
        . $archivo;

    return is_file($rutaFisica);
}

function sincronizarVisibilidadVideos($mysqli, $baby_id) {
    $stmt = $mysqli->prepare("
        SELECT id, video_path, hidden_no_video
        FROM trials
        WHERE baby_id = ?
    ");

    if (!$stmt) {
        return;
    }

    $stmt->bind_param("s", $baby_id);
    $stmt->execute();

    $resultado = $stmt->get_result();

    while ($row = $resultado->fetch_assoc()) {
        $id = (int)$row['id'];

        $debeOcultarse =
            videoExisteFisicamente(
                $row['video_path'] ?? ''
            )
            ? 0
            : 1;

        $actual =
            (int)($row['hidden_no_video'] ?? 0);

        if ($actual === $debeOcultarse) {
            continue;
        }

        $up = $mysqli->prepare("
            UPDATE trials
            SET hidden_no_video = ?
            WHERE id = ?
            LIMIT 1
        ");

        if ($up) {
            $up->bind_param(
                "ii",
                $debeOcultarse,
                $id
            );

            $up->execute();
            $up->close();
        }
    }

    $stmt->close();
}

if (!columnaExiste(
    $mysqli,
    "trials",
    "hidden_no_video"
)) {
    die(
        "Falta la columna hidden_no_video en la tabla trials."
    );
}

/*
 * Antes de calcular resúmenes, gráficas y tablas,
 * verifica qué videos existen físicamente.
 */
sincronizarVisibilidadVideos(
    $mysqli,
    $baby_id
);


/* ============================================================
   CONTROL ADMINISTRATIVO DE VISIBILIDAD DEL BEBÉ
   ============================================================ */

$mysqli->query("
    CREATE TABLE IF NOT EXISTS specialist_baby_visibility (
        baby_id VARCHAR(64) NOT NULL,
        visible_especialista TINYINT(1) NOT NULL DEFAULT 1,
        updated_at TIMESTAMP NOT NULL
            DEFAULT CURRENT_TIMESTAMP
            ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (baby_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
");


/* ============================================================
   CONTROL DE VISIBILIDAD INDIVIDUAL DE ENSAYOS
   ============================================================ */

$mysqli->query("
    CREATE TABLE IF NOT EXISTS specialist_trial_visibility (
        trial_id INT(11) NOT NULL,
        visible_especialista TINYINT(1) NOT NULL DEFAULT 1,
        updated_at TIMESTAMP NOT NULL
            DEFAULT CURRENT_TIMESTAMP
            ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (trial_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
");

$rol_actual =
    strtolower(
        trim(
            (string)(
                $_SESSION['rol']
                ?? ''
            )
        )
    );

$filtro_trial_especialista = "";

if ($rol_actual === 'especialista') {

    $filtro_trial_especialista = "
        AND NOT EXISTS (
            SELECT 1
            FROM specialist_trial_visibility stv
            WHERE stv.trial_id = trials.id
              AND stv.visible_especialista = 0
        )
    ";

}

/* ============================================================
   DEBUG TEMPORAL — quitar cuando se resuelva el problema
   Se activa solo con ?debug=1 en la URL, no afecta nada más.
   ============================================================ */
if (isset($_GET['debug'])) {
    echo "<pre style='background:#111;color:#0f0;padding:15px;font-size:13px'>";
    echo "SESSION rol crudo: " . e(var_export($_SESSION['rol'] ?? null, true)) . "\n";
    echo "rol_actual procesado: " . e($rol_actual) . "\n";
    echo "baby_id solicitado: " . e($baby_id) . "\n";
    echo "filtro_trial_especialista: " . e($filtro_trial_especialista ?: '(VACÍO — no se está aplicando ningún filtro)') . "\n";

    $chk = $mysqli->prepare("SELECT trial_id, visible_especialista FROM specialist_trial_visibility WHERE trial_id = ?");
    $probe_id = 1859;
    $chk->bind_param("i", $probe_id);
    $chk->execute();
    $chk_res = $chk->get_result()->fetch_assoc();
    echo "Estado en specialist_trial_visibility para trial_id=1859: " . e(var_export($chk_res, true)) . "\n";
    echo "</pre>";
}
/* ============================================================
   FIN DEBUG TEMPORAL
   ============================================================ */

if ($rol_actual === 'padre') {
    header(
        "Location: main_usuario_consulta.php"
    );
    exit;
}

if ($rol_actual === 'especialista') {

    $stmt_vis =
        $mysqli->prepare("
            SELECT visible_especialista
            FROM specialist_baby_visibility
            WHERE baby_id = ?
            LIMIT 1
        ");

    if ($stmt_vis) {

        $stmt_vis->bind_param(
            "s",
            $baby_id
        );

        $stmt_vis->execute();

        $vis_result =
            $stmt_vis->get_result();

        if (
            $vis_result
            && $vis_result->num_rows === 1
        ) {
            $vis_row =
                $vis_result->fetch_assoc();

            if (
                (int)(
                    $vis_row[
                        'visible_especialista'
                    ]
                    ?? 1
                ) === 0
            ) {
                $stmt_vis->close();

                header(
                    "Location: main.php"
                );
                exit;
            }
        }

        $stmt_vis->close();
    }
}


/* ============================================================
   RANDOM FOREST — ETIQUETAS OFICIALES
   ============================================================ */

function normalizarEstadoIA($estado, $legacy = "") {
    $estado = strtolower(trim((string)$estado));

    if (
        $estado === "respuesta_conductual_observable" ||
        $estado === "reacciono" ||
        $estado === "reaccionó"
    ) {
        return "respuesta_conductual_observable";
    }

    if (
        $estado === "respuesta_no_concluyente" ||
        $estado === "no_reacciono" ||
        $estado === "no reacciono" ||
        $estado === "no_reaccionó" ||
        $estado === "no reaccionó"
    ) {
        return "respuesta_no_concluyente";
    }

    if ($estado === "modelo_no_disponible") {
        return "modelo_no_disponible";
    }

    /*
     * Compatibilidad con registros históricos.
     * Solo se usa cuando ai_response_status no existe o está vacío.
     */
    $legacy = strtolower(trim((string)$legacy));

    if (
        $legacy === "correcto" ||
        $legacy === "direccion_incorrecta" ||
        $legacy === "dirección_incorrecta" ||
        $legacy === "reacciono" ||
        $legacy === "reaccionó"
    ) {
        return "respuesta_conductual_observable";
    }

    if (
        $legacy === "sin_respuesta" ||
        $legacy === "sin respuesta" ||
        $legacy === "no_reacciono" ||
        $legacy === "no reaccionó"
    ) {
        return "respuesta_no_concluyente";
    }

    return "";
}

function textoIA($estado, $legacy = "") {
    $estado = normalizarEstadoIA($estado, $legacy);

    if ($estado === "respuesta_conductual_observable") {
        return "Respuesta conductual observable";
    }

    if ($estado === "respuesta_no_concluyente") {
        return "Respuesta no concluyente";
    }

    if ($estado === "modelo_no_disponible") {
        return "Modelo no disponible";
    }

    return "Sin clasificación";
}

function claseIA($estado, $legacy = "") {
    $estado = normalizarEstadoIA($estado, $legacy);

    if ($estado === "respuesta_conductual_observable") {
        return "badge-success";
    }

    if ($estado === "respuesta_no_concluyente") {
        return "badge-warning";
    }

    return "badge-neutral";
}


/* ============================================================
   ESPECIALISTA — ETIQUETAS OFICIALES
   ============================================================ */

function normalizarEstadoEspecialista($estado) {
    $estado = strtolower(trim((string)$estado));

    if (
        $estado === "reacciono" ||
        $estado === "reaccionó" ||
        $estado === "correcto" ||
        $estado === "direccion_incorrecta" ||
        $estado === "dirección_incorrecta"
    ) {
        return "reacciono";
    }

    if (
        $estado === "no_reacciono" ||
        $estado === "no reacciono" ||
        $estado === "no_reaccionó" ||
        $estado === "no reaccionó" ||
        $estado === "sin_respuesta" ||
        $estado === "sin respuesta"
    ) {
        return "no_reacciono";
    }

    if (
        $estado === "no_evaluable" ||
        $estado === "no evaluable"
    ) {
        return "no_evaluable";
    }

    return "";
}

function textoEspecialista($estado) {
    $estado = normalizarEstadoEspecialista($estado);

    if ($estado === "reacciono") {
        return "Reaccionó";
    }

    if ($estado === "no_reacciono") {
        return "No reaccionó";
    }

    if ($estado === "no_evaluable") {
        return "No evaluable";
    }

    return "Sin valoración";
}

function claseEspecialista($estado) {
    $estado = normalizarEstadoEspecialista($estado);

    if ($estado === "reacciono") {
        return "badge-success";
    }

    if ($estado === "no_reacciono") {
        return "badge-warning";
    }

    return "badge-neutral";
}


function estadoEspecialistaFila($row) {
    $nuevo = trim(
        (string)(
            $row['specialist_response_status']
            ?? ''
        )
    );

    if ($nuevo !== '') {
        return $nuevo;
    }

    return trim(
        (string)(
            $row['specialist_status']
            ?? ''
        )
    );
}

function badgeRevision($estado) {
    $estado = strtolower(trim((string)$estado));

    if ($estado === "valido") {
        return "<span class='badge badge-success'>Válido</span>";
    }

    if ($estado === "descartado") {
        return "<span class='badge badge-danger'>Descartado</span>";
    }

    return "<span class='badge badge-warning'>Pendiente</span>";
}

function textoTipoRespuesta($tipo) {
    $tipo = strtolower(trim((string)$tipo));

    if ($tipo === "cabeza_y_gesto_facial") {
        return "Cabeza + gesto facial";
    }

    if ($tipo === "cabeza") {
        return "Movimiento cefálico";
    }

    if ($tipo === "gesto_facial") {
        return "Gesto facial";
    }

    if ($tipo === "ninguno") {
        return "Sin evidencia observacional clara";
    }

    return $tipo !== "" ? $tipo : "Sin registro";
}

function textoGestoFacial($gesto) {
    $gesto = strtolower(trim((string)$gesto));

    $map = array(
        "parpadeo" => "Parpadeo",
        "apertura_ojos" => "Apertura ocular",
        "ojos" => "Apertura ocular",
        "cejas" => "Movimiento de cejas",
        "apertura_boca" => "Apertura de boca",
        "boca" => "Apertura de boca",
        "ninguno" => "Ninguno predominante"
    );

    return isset($map[$gesto])
        ? $map[$gesto]
        : ($gesto !== "" ? ucfirst(str_replace("_", " ", $gesto)) : "—");
}



function porcentajeFacial($valor) {
    // Presentación visual únicamente.
    // El valor original permanece intacto en BD y en el modelo.
    if ($valor === null || $valor === "" || !is_numeric($valor)) {
        return "—";
    }

    return number_format(
        ((float)$valor) * 100.0,
        1,
        ",",
        "."
    ) . " %";
}


function iconoFacialSVG($tipo) {

    $comun =
        'width="24" height="24" viewBox="0 0 24 24" '
        . 'fill="none" stroke="currentColor" stroke-width="1.7" '
        . 'stroke-linecap="round" stroke-linejoin="round" '
        . 'aria-hidden="true"';

    if ($tipo === "facial") {
        return '
            <svg ' . $comun . '>
                <circle cx="12" cy="12" r="8"></circle>
                <path d="M8.5 10h.01"></path>
                <path d="M15.5 10h.01"></path>
                <path d="M9 15c1.8 1.2 4.2 1.2 6 0"></path>
            </svg>
        ';
    }

    if ($tipo === "parpadeo") {
        return '
            <svg ' . $comun . '>
                <path d="M3 12c2.4-3.1 5.5-4.7 9-4.7s6.6 1.6 9 4.7"></path>
                <path d="M3 12c2.4 3.1 5.5 4.7 9 4.7s6.6-1.6 9-4.7"></path>
                <path d="M8 12h8"></path>
            </svg>
        ';
    }

    if ($tipo === "ojos") {
        return '
            <svg ' . $comun . '>
                <path d="M2.5 12s3.4-4.4 9.5-4.4 9.5 4.4 9.5 4.4-3.4 4.4-9.5 4.4S2.5 12 2.5 12z"></path>
                <circle cx="12" cy="12" r="2.2"></circle>
            </svg>
        ';
    }

    if ($tipo === "cejas") {
        return '
            <svg ' . $comun . '>
                <path d="M4 9c1.8-1.5 3.8-2.1 6-1.6"></path>
                <path d="M14 7.4c2.2-.5 4.2.1 6 1.6"></path>
                <path d="M6 13c1.2-.7 2.5-.7 3.8 0"></path>
                <path d="M14.2 13c1.3-.7 2.6-.7 3.8 0"></path>
            </svg>
        ';
    }

    if ($tipo === "boca") {
        return '
            <svg ' . $comun . '>
                <path d="M4 13c2.5-2 5-3 8-3s5.5 1 8 3"></path>
                <path d="M4 13c2.4 3 5.1 4.5 8 4.5s5.6-1.5 8-4.5"></path>
            </svg>
        ';
    }

    return "";
}


/* ============================================================
   INFORMACIÓN GENERAL DEL BEBÉ
   ============================================================ */

$stmt = $mysqli->prepare("
    SELECT
        COUNT(DISTINCT session_id) AS sesiones,
        COUNT(*) AS trials,
        SUM(
            CASE
                WHEN review_status IS NULL
                  OR review_status = 'pendiente'
                THEN 1 ELSE 0
            END
        ) AS pendientes,
        SUM(
            CASE WHEN review_status = 'valido'
            THEN 1 ELSE 0 END
        ) AS validos,
        SUM(
            CASE WHEN review_status = 'descartado'
            THEN 1 ELSE 0 END
        ) AS descartados,
        MAX(age_months) AS edad,
        MAX(sex_biological) AS sexo,
        MIN(created_at) AS primera,
        MAX(created_at) AS ultima
    FROM trials
    WHERE baby_id = ?
      AND COALESCE(hidden_no_video, 0) = 0
      $filtro_trial_especialista
");

$stmt->bind_param("s", $baby_id);
$stmt->execute();

$info = $stmt->get_result();
$data = $info->fetch_assoc();

$sesiones = (int)($data['sesiones'] ?? 0);
$total_trials = (int)($data['trials'] ?? 0);
$pendientes_count = (int)($data['pendientes'] ?? 0);
$validos_count = (int)($data['validos'] ?? 0);
$descartados_count = (int)($data['descartados'] ?? 0);
$evaluados_count = $validos_count + $descartados_count;

$edad = (
    $data['edad'] !== null
    ? round((float)$data['edad'], 1)
    : null
);

$sexo = strtoupper(trim((string)($data['sexo'] ?? "")));

$stmt->close();


/* ============================================================
   RESUMEN GRÁFICO DEL BEBÉ
   ============================================================ */

/*
 * 1. Valoración profesional:
 *    solo ensayos válidos revisados.
 *
 * 2. Clasificación IA:
 *    ensayos del bebé con salida computacional disponible.
 *
 * 3. Concordancia operacional:
 *    solo ensayos válidos con IA + valoración profesional.
 *    Se considera concordancia operacional:
 *      observable     <-> reacciono
 *      no concluyente <-> no_reacciono
 *
 *    "No evaluable" se conserva como categoría separada.
 *
 * 4. Seguimiento por sesión:
 *    valoración profesional por sesión en ensayos válidos.
 */


/* ---------- Seguimiento profesional por sesión ---------- */

$labels = array();
$serie_reacciono = array();
$serie_no_reacciono = array();
$serie_no_evaluable = array();

$stmt = $mysqli->prepare("
    SELECT
        session_id,

        COALESCE(
            SUM(
                CASE
                    WHEN COALESCE(
                        NULLIF(specialist_response_status, ''),
                        specialist_status
                    ) IN (
                        'reacciono',
                        'correcto',
                        'direccion_incorrecta'
                    )
                    THEN 1 ELSE 0
                END
            ),
            0
        ) AS reacciono,

        COALESCE(
            SUM(
                CASE
                    WHEN COALESCE(
                        NULLIF(specialist_response_status, ''),
                        specialist_status
                    ) IN (
                        'no_reacciono',
                        'sin_respuesta'
                    )
                    THEN 1 ELSE 0
                END
            ),
            0
        ) AS no_reacciono,

        COALESCE(
            SUM(
                CASE
                    WHEN COALESCE(
                        NULLIF(specialist_response_status, ''),
                        specialist_status
                    ) = 'no_evaluable'
                    THEN 1 ELSE 0
                END
            ),
            0
        ) AS no_evaluable,

        MIN(created_at) AS fecha_sesion

    FROM trials

    WHERE baby_id = ?
      AND COALESCE(hidden_no_video, 0) = 0
      $filtro_trial_especialista
      AND review_status = 'valido'
      AND COALESCE(
            NULLIF(specialist_response_status, ''),
            specialist_status
          ) IS NOT NULL
      AND COALESCE(
            NULLIF(specialist_response_status, ''),
            specialist_status
          ) <> ''

    GROUP BY session_id

    ORDER BY
        MIN(created_at),
        session_id
");

$stmt->bind_param("s", $baby_id);
$stmt->execute();

$q = $stmt->get_result();

$indice_sesion = 1;

while ($row = $q->fetch_assoc()) {

    $labels[] = "Sesión " . $indice_sesion;

    $serie_reacciono[] =
        (int)$row['reacciono'];

    $serie_no_reacciono[] =
        (int)$row['no_reacciono'];

    $serie_no_evaluable[] =
        (int)$row['no_evaluable'];

    $indice_sesion++;
}

$stmt->close();


/* ---------- Distribución profesional ---------- */

$stmt = $mysqli->prepare("
    SELECT

        COALESCE(
            SUM(
                CASE
                    WHEN COALESCE(
                        NULLIF(specialist_response_status, ''),
                        specialist_status
                    ) IN (
                        'reacciono',
                        'correcto',
                        'direccion_incorrecta'
                    )
                    THEN 1 ELSE 0
                END
            ),
            0
        ) AS reacciono,

        COALESCE(
            SUM(
                CASE
                    WHEN COALESCE(
                        NULLIF(specialist_response_status, ''),
                        specialist_status
                    ) IN (
                        'no_reacciono',
                        'sin_respuesta'
                    )
                    THEN 1 ELSE 0
                END
            ),
            0
        ) AS no_reacciono,

        COALESCE(
            SUM(
                CASE
                    WHEN COALESCE(
                        NULLIF(specialist_response_status, ''),
                        specialist_status
                    ) = 'no_evaluable'
                    THEN 1 ELSE 0
                END
            ),
            0
        ) AS no_evaluable

    FROM trials

    WHERE baby_id = ?
      AND COALESCE(hidden_no_video, 0) = 0
      $filtro_trial_especialista
      AND review_status = 'valido'
");

$stmt->bind_param("s", $baby_id);
$stmt->execute();

$dist = $stmt->get_result();
$d = $dist->fetch_assoc();

$n_reacciono =
    (int)($d['reacciono'] ?? 0);

$n_no_reacciono =
    (int)($d['no_reacciono'] ?? 0);

$n_no_evaluable =
    (int)($d['no_evaluable'] ?? 0);

$stmt->close();


/* ---------- Distribución de clasificación IA ---------- */

$stmt = $mysqli->prepare("
    SELECT

        COALESCE(
            SUM(
                CASE
                    WHEN
                        (
                            ai_response_status
                            = 'respuesta_conductual_observable'
                        )
                        OR
                        (
                            (
                                ai_response_status IS NULL
                                OR ai_response_status = ''
                            )
                            AND trial_status IN (
                                'correcto',
                                'direccion_incorrecta',
                                'reacciono'
                            )
                        )
                    THEN 1 ELSE 0
                END
            ),
            0
        ) AS observable,

        COALESCE(
            SUM(
                CASE
                    WHEN
                        (
                            ai_response_status
                            = 'respuesta_no_concluyente'
                        )
                        OR
                        (
                            (
                                ai_response_status IS NULL
                                OR ai_response_status = ''
                            )
                            AND trial_status IN (
                                'sin_respuesta',
                                'no_reacciono'
                            )
                        )
                    THEN 1 ELSE 0
                END
            ),
            0
        ) AS no_concluyente

    FROM trials

    WHERE baby_id = ?
      AND COALESCE(hidden_no_video, 0) = 0
      $filtro_trial_especialista
");

$stmt->bind_param("s", $baby_id);
$stmt->execute();

$ia_dist = $stmt->get_result();
$ia_d = $ia_dist->fetch_assoc();

$n_ia_observable =
    (int)($ia_d['observable'] ?? 0);

$n_ia_no_concluyente =
    (int)($ia_d['no_concluyente'] ?? 0);

$stmt->close();


/* ---------- Concordancia operacional IA vs especialista ---------- */

$stmt = $mysqli->prepare("
    SELECT

        COALESCE(
            SUM(
                CASE
                    WHEN
                        COALESCE(
                            NULLIF(specialist_response_status, ''),
                            specialist_status
                        ) = 'no_evaluable'
                    THEN 1 ELSE 0
                END
            ),
            0
        ) AS no_evaluable,

        COALESCE(
            SUM(
                CASE
                    WHEN
                        COALESCE(
                            NULLIF(specialist_response_status, ''),
                            specialist_status
                        ) IN (
                            'reacciono',
                            'correcto',
                            'direccion_incorrecta'
                        )
                        AND
                        (
                            ai_response_status
                            = 'respuesta_conductual_observable'
                            OR
                            (
                                (
                                    ai_response_status IS NULL
                                    OR ai_response_status = ''
                                )
                                AND trial_status IN (
                                    'correcto',
                                    'direccion_incorrecta',
                                    'reacciono'
                                )
                            )
                        )
                    THEN 1

                    WHEN
                        COALESCE(
                            NULLIF(specialist_response_status, ''),
                            specialist_status
                        ) IN (
                            'no_reacciono',
                            'sin_respuesta'
                        )
                        AND
                        (
                            ai_response_status
                            = 'respuesta_no_concluyente'
                            OR
                            (
                                (
                                    ai_response_status IS NULL
                                    OR ai_response_status = ''
                                )
                                AND trial_status IN (
                                    'sin_respuesta',
                                    'no_reacciono'
                                )
                            )
                        )
                    THEN 1

                    ELSE 0
                END
            ),
            0
        ) AS coincide,

        COALESCE(
            SUM(
                CASE
                    WHEN
                        COALESCE(
                            NULLIF(specialist_response_status, ''),
                            specialist_status
                        ) <> 'no_evaluable'
                        AND
                        COALESCE(
                            NULLIF(specialist_response_status, ''),
                            specialist_status
                        ) IS NOT NULL
                        AND
                        COALESCE(
                            NULLIF(specialist_response_status, ''),
                            specialist_status
                        ) <> ''
                        AND
                        (
                            ai_response_status IN (
                                'respuesta_conductual_observable',
                                'respuesta_no_concluyente'
                            )
                            OR
                            trial_status IN (
                                'correcto',
                                'direccion_incorrecta',
                                'sin_respuesta',
                                'reacciono',
                                'no_reacciono'
                            )
                        )
                        AND NOT (
                            (
                                COALESCE(
                                    NULLIF(specialist_response_status, ''),
                                    specialist_status
                                ) IN (
                                    'reacciono',
                                    'correcto',
                                    'direccion_incorrecta'
                                )
                                AND
                                (
                                    ai_response_status
                                    = 'respuesta_conductual_observable'
                                    OR
                                    (
                                        (
                                            ai_response_status IS NULL
                                            OR ai_response_status = ''
                                        )
                                        AND trial_status IN (
                                            'correcto',
                                            'direccion_incorrecta',
                                            'reacciono'
                                        )
                                    )
                                )
                            )
                            OR
                            (
                                COALESCE(
                                    NULLIF(specialist_response_status, ''),
                                    specialist_status
                                ) IN (
                                    'no_reacciono',
                                    'sin_respuesta'
                                )
                                AND
                                (
                                    ai_response_status
                                    = 'respuesta_no_concluyente'
                                    OR
                                    (
                                        (
                                            ai_response_status IS NULL
                                            OR ai_response_status = ''
                                        )
                                        AND trial_status IN (
                                            'sin_respuesta',
                                            'no_reacciono'
                                        )
                                    )
                                )
                            )
                        )
                    THEN 1 ELSE 0
                END
            ),
            0
        ) AS no_coincide

    FROM trials

    WHERE baby_id = ?
      AND COALESCE(hidden_no_video, 0) = 0
      $filtro_trial_especialista
      AND review_status = 'valido'
");

$stmt->bind_param("s", $baby_id);
$stmt->execute();

$concordancia =
    $stmt
    ->get_result()
    ->fetch_assoc();

$n_concuerda =
    (int)($concordancia['coincide'] ?? 0);

$n_no_concuerda =
    (int)($concordancia['no_coincide'] ?? 0);

$n_conc_no_evaluable =
    (int)($concordancia['no_evaluable'] ?? 0);

$stmt->close();


$graficas_total =
    $n_reacciono
    + $n_no_reacciono
    + $n_no_evaluable
    + $n_ia_observable
    + $n_ia_no_concluyente;

$hay_graficas =
    $graficas_total > 0;


/* ============================================================
   TRIALS PENDIENTES
   ============================================================ */


$stmt_pendientes = $mysqli->prepare("
    SELECT *
    FROM trials
    WHERE baby_id = ?
      AND COALESCE(hidden_no_video, 0) = 0
      $filtro_trial_especialista
      AND (
            review_status IS NULL
         OR review_status = 'pendiente'
      )
    ORDER BY created_at DESC
");

$stmt_pendientes->bind_param("s", $baby_id);
$stmt_pendientes->execute();

$trials_pendientes =
    $stmt_pendientes->get_result();


/* ============================================================
   TRIALS YA EVALUADOS
   ============================================================ */

$stmt_evaluados = $mysqli->prepare("
    SELECT *
    FROM trials
    WHERE baby_id = ?
      AND COALESCE(hidden_no_video, 0) = 0
      $filtro_trial_especialista
      AND review_status IN (
          'valido',
          'descartado'
      )
    ORDER BY
        reviewed_at DESC,
        created_at DESC
");

$stmt_evaluados->bind_param("s", $baby_id);
$stmt_evaluados->execute();

$trials_evaluados =
    $stmt_evaluados->get_result();

?>

<!DOCTYPE html>
<html lang="es">
<head>

<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Revisión del bebé <?php echo e($baby_id); ?></title>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>

:root {
    --bg: #f3f6fa;
    --surface: #ffffff;
    --surface-soft: #f8fafc;
    --primary: #163b63;
    --primary-light: #246196;
    --secondary: #60758a;
    --border: #dbe3ec;
    --text: #1f2933;
    --muted: #66788a;

    --success: #1f8f5f;
    --success-bg: #e8f7ef;

    --danger: #c0392b;
    --danger-bg: #fdecea;

    --warning: #b86c00;
    --warning-bg: #fff4df;

    --neutral: #52616f;
    --neutral-bg: #eef2f6;

    --info: #246196;
    --info-bg: #eaf3fb;

    --shadow: 0 14px 35px rgba(22, 59, 99, 0.08);

    --radius-xl: 24px;
    --radius-lg: 18px;
    --radius-md: 12px;
}

* {
    box-sizing: border-box;
}

body {
    margin: 0;
    font-family: "Segoe UI", Roboto, Arial, sans-serif;

    background:
        radial-gradient(
            circle at top left,
            rgba(36, 97, 150, 0.12),
            transparent 35%
        ),
        linear-gradient(
            180deg,
            #f7faff 0%,
            var(--bg) 45%,
            #eef3f8 100%
        );

    color: var(--text);
}

.page {
    width: min(1450px, calc(100% - 32px));
    margin: 0 auto;
    padding: 30px 0 42px;
}


/* ============================================================
   CABECERA
   ============================================================ */

.topbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 18px;
    margin-bottom: 24px;
}

.brand {
    display: flex;
    align-items: center;
    gap: 14px;
}

.brand-icon {
    width: 48px;
    height: 48px;
    border-radius: 16px;

    background:
        linear-gradient(
            135deg,
            var(--primary),
            var(--primary-light)
        );

    color: white;

    display: grid;
    place-items: center;

    font-weight: 800;
    letter-spacing: 0.5px;

    box-shadow:
        0 12px 24px
        rgba(22, 59, 99, 0.22);
}

.brand-text small {
    display: block;
    color: var(--muted);
    font-size: 13px;
    margin-bottom: 2px;
}

.brand-text strong {
    display: block;
    font-size: 20px;
    color: var(--primary);
}

.actions {
    display: flex;
    align-items: center;
    gap: 10px;
}

.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;

    padding: 11px 16px;
    border-radius: 999px;

    background: var(--primary);
    color: white;

    text-decoration: none;
    font-weight: 700;
    font-size: 14px;

    border: 1px solid transparent;

    transition: 0.2s ease;
}

.btn:hover {
    background: #0f2d4d;
    transform: translateY(-1px);
}

.btn-outline {
    background: white;
    color: var(--primary);
    border-color: var(--border);
}


/* ============================================================
   HERO
   ============================================================ */

.hero {
    position: relative;
    overflow: hidden;

    background:
        linear-gradient(
            135deg,
            rgba(22, 59, 99, 0.96),
            rgba(36, 97, 150, 0.94)
        );

    color: white;

    border-radius: var(--radius-xl);
    padding: 30px;

    box-shadow: var(--shadow);
    margin-bottom: 22px;
}

.hero::after {
    content: "";

    position: absolute;

    width: 260px;
    height: 260px;

    right: -80px;
    top: -110px;

    border-radius: 50%;

    background:
        rgba(255, 255, 255, 0.10);
}

.hero-content {
    position: relative;
    z-index: 1;

    display: grid;
    grid-template-columns: 1.4fr 0.8fr;

    gap: 24px;
    align-items: center;
}

.eyebrow {
    display: inline-flex;
    align-items: center;

    padding: 7px 12px;

    border-radius: 999px;

    background:
        rgba(255, 255, 255, 0.13);

    font-size: 13px;
    margin-bottom: 14px;
}

.hero h1 {
    margin: 0 0 10px;

    font-size:
        clamp(28px, 4vw, 42px);

    letter-spacing: -0.8px;
}

.hero p {
    margin: 0;
    max-width: 760px;

    line-height: 1.65;

    color:
        rgba(255, 255, 255, 0.88);
}

.review-summary {
    background:
        rgba(255, 255, 255, 0.12);

    border:
        1px solid
        rgba(255, 255, 255, 0.16);

    border-radius: 20px;

    padding: 18px;

    backdrop-filter: blur(12px);
}

.review-summary span {
    display: block;
    font-size: 13px;

    color:
        rgba(255, 255, 255, 0.78);

    margin-bottom: 6px;
}

.review-summary strong {
    display: block;
    font-size: 25px;
    margin-bottom: 8px;
}

.review-summary p {
    font-size: 13px;
    line-height: 1.45;

    color:
        rgba(255, 255, 255, 0.82);
}


/* ============================================================
   AVISO METODOLÓGICO
   ============================================================ */

.scope-note {
    margin-bottom: 22px;
    padding: 16px 18px;

    border-radius: 16px;

    background: var(--info-bg);
    border: 1px solid #cfe1f1;

    color: #244765;

    font-size: 13px;
    line-height: 1.55;
}

.scope-note strong {
    color: var(--primary);
}


/* ============================================================
   TARJETAS
   ============================================================ */

.cards {
    display: grid;
    grid-template-columns:
        repeat(6, minmax(145px, 1fr));

    gap: 16px;
    margin-bottom: 22px;
}

.card {
    background:
        rgba(255, 255, 255, 0.94);

    border:
        1px solid
        rgba(219, 227, 236, 0.9);

    border-radius: var(--radius-lg);

    padding: 18px;

    box-shadow: var(--shadow);
}

.card h3 {
    margin: 0 0 10px;

    color: var(--muted);

    font-size: 12px;
    font-weight: 700;

    text-transform: uppercase;
    letter-spacing: 0.06em;
}

.card p {
    margin: 0;

    font-size: 24px;

    color: var(--primary);

    font-weight: 800;
    letter-spacing: -0.5px;
}

.card small {
    display: block;
    margin-top: 7px;

    color: var(--muted);

    font-size: 12px;
}


/* ============================================================
   GRÁFICAS
   ============================================================ */

.layout {
    display: grid;
    grid-template-columns: 1.25fr 0.75fr;

    gap: 18px;
    margin-bottom: 22px;
}

.panel,
.empty-panel {
    background:
        rgba(255, 255, 255, 0.96);

    border:
        1px solid
        rgba(219, 227, 236, 0.9);

    border-radius: var(--radius-xl);

    padding: 22px;

    box-shadow: var(--shadow);
}

.panel h3,
.empty-panel h3 {
    margin: 0;

    color: var(--primary);

    font-size: 18px;
}

.panel p,
.empty-panel p {
    margin: 6px 0 0;

    color: var(--muted);

    font-size: 14px;
    line-height: 1.5;
}

.chart-box {
    position: relative;

    height: 310px;
    margin-top: 14px;
}

.chart-box.small {
    height: 295px;
}


/* ============================================================
   TABLAS
   ============================================================ */

.table-panel {
    background:
        rgba(255, 255, 255, 0.98);

    border:
        1px solid
        rgba(219, 227, 236, 0.95);

    border-radius: var(--radius-xl);

    box-shadow: var(--shadow);

    overflow: hidden;

    margin-bottom: 22px;
}

.table-title {
    display: flex;
    justify-content: space-between;
    align-items: center;

    gap: 14px;

    padding: 20px 22px;

    border-bottom:
        1px solid var(--border);
}

.table-title h3 {
    margin: 0;

    color: var(--primary);

    font-size: 18px;
}

.table-title p {
    margin: 4px 0 0;

    color: var(--muted);

    font-size: 14px;
}

.table-wrap {
    overflow-x: auto;
}

table {
    width: 100%;

    min-width: 1500px;

    border-collapse: collapse;
}

th,
td {
    padding: 14px 14px;

    border-bottom:
        1px solid #edf1f5;

    text-align: left;
    vertical-align: top;

    font-size: 13px;
}

th {
    background: #f7fafd;

    color: var(--secondary);

    font-size: 11px;

    text-transform: uppercase;
    letter-spacing: 0.055em;

    font-weight: 800;
}

tr:hover td {
    background: #fbfdff;
}

.mono {
    font-family:
        Consolas,
        "Courier New",
        monospace;

    color: var(--primary);
    font-weight: 700;
}


/* ============================================================
   BADGES
   ============================================================ */

.badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;

    padding: 7px 10px;

    border-radius: 999px;

    font-size: 12px;
    font-weight: 800;

    white-space: nowrap;
}

.badge-success {
    color: var(--success);
    background: var(--success-bg);
}

.badge-danger {
    color: var(--danger);
    background: var(--danger-bg);
}

.badge-warning {
    color: var(--warning);
    background: var(--warning-bg);
}

.badge-neutral {
    color: var(--neutral);
    background: var(--neutral-bg);
}

.badge-info {
    color: var(--info);
    background: var(--info-bg);
}


/* ============================================================
   RESULTADO IA / PROBABILIDADES
   ============================================================ */

.ai-box {
    min-width: 205px;
}

.ai-probs {
    margin-top: 8px;

    display: grid;
    gap: 4px;

    color: var(--muted);

    font-size: 11px;
}

.ai-probs strong {
    color: var(--text);
}


/* ============================================================
   EVIDENCIA
   ============================================================ */

.evidence-list {
    display: grid;
    gap: 6px;

    min-width: 155px;
}

.evidence-item {
    display: flex;
    align-items: center;
    gap: 6px;

    color: var(--text);
    font-size: 12px;
}

.evidence-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;

    background: #b8c2cc;
}

.evidence-dot.on {
    background: var(--success);
}


/* ============================================================
   VIDEO
   ============================================================ */

.video-frame {
    width: 225px;

    border-radius: 14px;

    border:
        1px solid var(--border);

    background: #0f172a;

    display: block;
}

.muted-text,
.no-video {
    color: var(--muted);
    font-size: 12px;
}


/* ============================================================
   DETALLES TÉCNICOS
   ============================================================ */

.technical-details {
    min-width: 310px;
}

.technical-details summary {
    cursor: pointer;

    display: inline-flex;
    align-items: center;
    gap: 6px;

    padding: 8px 11px;

    border-radius: 999px;

    background: var(--neutral-bg);

    color: var(--primary);

    font-weight: 800;
    font-size: 12px;

    list-style: none;
}

.technical-details summary::-webkit-details-marker {
    display: none;
}

.technical-details[open] summary {
    margin-bottom: 12px;

    background: var(--info-bg);
}

.technical-box {
    min-width: 500px;

    padding: 14px;

    border:
        1px solid var(--border);

    border-radius: 14px;

    background: var(--surface-soft);
}

.tech-section {
    margin-bottom: 14px;
}

.tech-section:last-child {
    margin-bottom: 0;
}

.tech-section h4 {
    margin: 0 0 8px;

    color: var(--primary);

    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.tech-grid {
    display: grid;
    grid-template-columns:
        repeat(2, minmax(190px, 1fr));

    gap: 7px 14px;
}

.tech-row {
    display: flex;
    justify-content: space-between;

    gap: 12px;

    padding-bottom: 5px;

    border-bottom:
        1px dashed #dce4ec;

    font-size: 11px;
}

.tech-row span {
    color: var(--muted);
}

.tech-row strong {
    color: var(--text);
    text-align: right;
}

.tech-note {
    margin-top: 10px;

    padding: 9px 10px;

    border-radius: 10px;

    background: #fff9eb;

    color: #745318;

    font-size: 10px;
    line-height: 1.45;
}


/* ============================================================
   FORMULARIO DE REVISIÓN
   ============================================================ */

.review-form {
    display: grid;
    gap: 8px;

    min-width: 255px;
}

.review-form label {
    font-size: 10px;
    font-weight: 800;

    color: var(--muted);

    text-transform: uppercase;
    letter-spacing: 0.04em;
}

.review-form select,
.review-form textarea {
    width: 100%;

    border:
        1px solid var(--border);

    border-radius: 10px;

    padding: 8px 10px;

    font-family: inherit;
    font-size: 12px;

    background: white;
    color: var(--text);
}

.review-form textarea {
    min-height: 72px;
    resize: vertical;
}

.review-form button {
    border: none;

    border-radius: 999px;

    padding: 10px 12px;

    background: var(--primary);
    color: white;

    font-weight: 800;

    cursor: pointer;
}

.review-form button:hover {
    background: #0f2d4d;
}

.review-help {
    margin-top: -2px;

    color: var(--muted);

    font-size: 10px;
    line-height: 1.35;
}


/* ============================================================
   VACÍO / PIE
   ============================================================ */

.empty-state {
    padding: 34px 22px;

    text-align: center;

    color: var(--muted);
}

.footer-note {
    color: var(--muted);

    text-align: center;

    font-size: 12px;

    margin-top: 18px;

    line-height: 1.5;
}


/* ============================================================
   RESPONSIVE
   ============================================================ */

@media (max-width: 1150px) {

    .cards {
        grid-template-columns:
            repeat(3, minmax(160px, 1fr));
    }

    .layout {
        grid-template-columns: 1fr;
    }

    .hero-content {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 640px) {

    .page {
        width: min(100% - 20px, 1180px);
        padding-top: 18px;
    }

    .topbar {
        flex-direction: column;
        align-items: stretch;
    }

    .actions {
        justify-content: space-between;
    }

    .hero {
        padding: 22px;
    }

    .cards {
        grid-template-columns: 1fr 1fr;
    }

    .table-title {
        flex-direction: column;
        align-items: flex-start;
    }

    .chart-box,
    .chart-box.small {
        height: 260px;
    }

    .tech-grid {
        grid-template-columns: 1fr;
    }
}


/* ============================================================
   V3 — TABLA COMPACTA Y VENTANAS FLOTANTES
   ============================================================ */

.compact-trials {
    width: 100%;
    min-width: 0 !important;
    table-layout: fixed;
}

.compact-trials th,
.compact-trials td {
    padding: 16px;
}

.compact-trials th:nth-child(1),
.compact-trials td:nth-child(1) {
    width: 21%;
}

.compact-trials th:nth-child(2),
.compact-trials td:nth-child(2) {
    width: 20%;
}

.compact-trials th:nth-child(3),
.compact-trials td:nth-child(3) {
    width: 22%;
}

.compact-trials th:nth-child(4),
.compact-trials td:nth-child(4) {
    width: 20%;
}

.compact-trials th:nth-child(5),
.compact-trials td:nth-child(5) {
    width: 17%;
}

.trace-box {
    display: grid;
    gap: 8px;
}

.trace-line {
    display: grid;
    grid-template-columns: 72px 1fr;
    gap: 8px;
    align-items: start;
}

.trace-line span {
    color: var(--muted);
    font-size: 11px;
    font-weight: 700;
}

.trace-line strong {
    color: var(--text);
    font-size: 12px;
    line-height: 1.35;
    overflow-wrap: anywhere;
}

.trace-trial {
    margin-top: 4px;
    padding-top: 7px;
    border-top: 1px dashed var(--border);
    color: var(--muted);
    font-size: 10px;
    overflow-wrap: anywhere;
}

.ai-compact {
    display: grid;
    gap: 9px;
}

.ai-confidence {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
    padding: 8px 10px;
    border-radius: 10px;
    background: var(--surface-soft);
    font-size: 11px;
    color: var(--muted);
}

.ai-confidence strong {
    color: var(--text);
}

.evidence-compact {
    display: grid;
    gap: 7px;
}

.evidence-summary {
    margin-top: 3px;
    padding-top: 7px;
    border-top: 1px dashed var(--border);
    color: var(--muted);
    font-size: 11px;
    line-height: 1.45;
}

.compact-video {
    width: 100%;
    max-width: 210px;
    border-radius: 14px;
    border: 1px solid var(--border);
    background: #0f172a;
    display: block;
}

.action-stack {
    display: grid;
    gap: 9px;
}

.action-btn {
    width: 100%;
    border: 1px solid transparent;
    border-radius: 12px;
    padding: 10px 12px;
    font-family: inherit;
    font-size: 12px;
    font-weight: 800;
    cursor: pointer;
    transition: 0.18s ease;
}

.action-btn:hover {
    transform: translateY(-1px);
}

.action-btn-primary {
    background: var(--primary);
    color: #fff;
}

.action-btn-primary:hover {
    background: #0f2d4d;
}

.action-btn-secondary {
    background: var(--info-bg);
    border-color: #cfe1f1;
    color: var(--primary);
}

.action-btn-secondary:hover {
    background: #dfeef9;
}

.action-btn-neutral {
    background: var(--neutral-bg);
    border-color: #d9e0e7;
    color: var(--neutral);
}

/* -------------------- MODALES -------------------- */

.modal-overlay {
    position: fixed;
    inset: 0;
    z-index: 5000;

    display: none;
    align-items: center;
    justify-content: center;

    padding: 20px;

    background: rgba(15, 23, 42, 0.62);
    backdrop-filter: blur(4px);
}

.modal-overlay.active {
    display: flex;
}

.modal-card {
    width: min(900px, 100%);
    max-height: 88vh;
    overflow-y: auto;

    background: #fff;
    border-radius: 22px;
    box-shadow: 0 30px 80px rgba(15, 23, 42, 0.28);
    border: 1px solid rgba(219, 227, 236, 0.95);
}

.modal-card.modal-review {
    width: min(620px, 100%);
}

.modal-header {
    position: sticky;
    top: 0;
    z-index: 3;

    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 18px;

    padding: 20px 22px;

    background: rgba(255, 255, 255, 0.97);
    border-bottom: 1px solid var(--border);
    backdrop-filter: blur(10px);
}

.modal-header h3 {
    margin: 0;
    color: var(--primary);
    font-size: 19px;
}

.modal-header p {
    margin: 5px 0 0;
    color: var(--muted);
    font-size: 12px;
    line-height: 1.45;
}

.modal-close {
    flex: 0 0 auto;

    width: 38px;
    height: 38px;

    border: none;
    border-radius: 50%;

    background: var(--neutral-bg);
    color: var(--primary);

    font-size: 23px;
    line-height: 1;

    cursor: pointer;
}

.modal-close:hover {
    background: #dfe6ed;
}

.modal-body {
    padding: 22px;
}

.modal-section {
    margin-bottom: 22px;
}

.modal-section:last-child {
    margin-bottom: 0;
}

.modal-section-title {
    margin: 0 0 12px;

    color: var(--primary);
    font-size: 13px;
    font-weight: 800;

    text-transform: uppercase;
    letter-spacing: 0.055em;
}

.variable-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 10px;
}

.variable-card {
    min-width: 0;

    padding: 12px 13px;

    border: 1px solid var(--border);
    border-radius: 13px;

    background: var(--surface-soft);
}

.variable-card span {
    display: block;
    margin-bottom: 5px;

    color: var(--muted);
    font-size: 10px;
    font-weight: 700;
}

.variable-card strong {
    display: block;

    color: var(--text);
    font-size: 14px;
    overflow-wrap: anywhere;
}

.variable-card code {
    display: block;
    margin-top: 4px;

    color: #6b7f91;
    font-size: 9px;
    overflow-wrap: anywhere;
}

.modal-model-box {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 10px;
}

.modal-review-form {
    display: grid;
    gap: 14px;
}

.modal-review-form label {
    color: var(--primary);
    font-size: 12px;
    font-weight: 800;
}

.modal-review-form select,
.modal-review-form textarea {
    width: 100%;

    border: 1px solid var(--border);
    border-radius: 12px;

    padding: 11px 12px;

    font: inherit;
    font-size: 13px;

    background: #fff;
    color: var(--text);
}

.modal-review-form textarea {
    min-height: 110px;
    resize: vertical;
}

.modal-review-help {
    padding: 11px 12px;

    border-radius: 12px;

    background: var(--info-bg);
    color: #375b77;

    font-size: 11px;
    line-height: 1.5;
}

.modal-review-actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;

    padding-top: 4px;
}

.review-readonly {
    display: grid;
    gap: 12px;
}

.review-readonly-row {
    display: grid;
    grid-template-columns: 160px 1fr;
    gap: 14px;

    padding-bottom: 10px;

    border-bottom: 1px dashed var(--border);
}

.review-readonly-row:last-child {
    border-bottom: none;
}

.review-readonly-row span {
    color: var(--muted);
    font-size: 11px;
    font-weight: 700;
}

.review-readonly-row strong,
.review-readonly-row div {
    color: var(--text);
    font-size: 13px;
    line-height: 1.5;
    overflow-wrap: anywhere;
}

body.modal-open {
    overflow: hidden;
}

@media (max-width: 1120px) {

    .compact-trials thead {
        display: none;
    }

    .compact-trials,
    .compact-trials tbody,
    .compact-trials tr,
    .compact-trials td {
        display: block;
        width: 100% !important;
    }

    .compact-trials tr {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0;

        margin: 14px;

        width: calc(100% - 28px) !important;

        border: 1px solid var(--border);
        border-radius: 18px;
        overflow: hidden;

        background: #fff;
    }

    .compact-trials td {
        border-bottom: 1px solid #edf1f5;
    }

    .compact-trials td::before {
        content: attr(data-label);
        display: block;

        margin-bottom: 8px;

        color: var(--primary);
        font-size: 10px;
        font-weight: 800;

        text-transform: uppercase;
        letter-spacing: 0.055em;
    }

    .compact-video {
        max-width: 260px;
    }
}

@media (max-width: 720px) {

    .compact-trials tr {
        grid-template-columns: 1fr;
    }

    .variable-grid,
    .modal-model-box {
        grid-template-columns: 1fr;
    }

    .review-readonly-row {
        grid-template-columns: 1fr;
        gap: 4px;
    }

    .modal-overlay {
        padding: 10px;
    }

    .modal-body,
    .modal-header {
        padding: 16px;
    }

    .modal-review-actions {
        flex-direction: column-reverse;
    }
}


.modal-video-section {
    margin-bottom: 22px;
}

.modal-video-wrap {
    display: inline-block;
    padding: 0;
    border: 1px solid var(--border);
    border-radius: 14px;
    background: #0f172a;
    overflow: hidden;
}

.modal-video {
    display: block;
    width: 210px;
    max-width: 100%;
    height: auto;
    border-radius: 14px;
    background: #000;
}

.modal-video-caption {
    max-width: 430px;
    margin-top: 8px;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.45;
}

.modal-video-empty {
    width: 210px;
    max-width: 100%;
    padding: 24px 14px;
    text-align: center;
    color: #cbd5e1;
    font-size: 12px;
}


/* ============================================================
   MODALES: VIDEO A LA IZQUIERDA + DATOS A LA DERECHA
   ============================================================ */

.modal-card {
    width: min(1050px, 100%);
}

.modal-card.modal-review {
    width: min(900px, 100%);
}

.modal-body {
    display: grid;
    grid-template-columns: 230px minmax(0, 1fr);
    column-gap: 24px;
    row-gap: 20px;
    align-items: start;
}

/* El video permanece en la columna izquierda durante todo el modal */
.modal-body > .modal-video-section {
    grid-column: 1;
    grid-row: 1 / span 20;
    margin-bottom: 0;
    position: sticky;
    top: 92px;
}

/* El resto de la información queda en la columna derecha */
.modal-body > .modal-section,
.modal-body > .modal-review-form,
.modal-body > .review-readonly {
    grid-column: 2;
    min-width: 0;
}

.modal-video {
    width: 210px;
    max-width: 100%;
}

.modal-video-caption {
    width: 210px;
    max-width: 100%;
}

/* En el modal de variables aprovechamos mejor el ancho derecho */
.modal-body .variable-grid {
    grid-template-columns: repeat(2, minmax(0, 1fr));
}

.modal-body .modal-model-box {
    grid-template-columns: repeat(2, minmax(0, 1fr));
}

/* En pantallas pequeñas vuelve a apilarse */
@media (max-width: 780px) {

    .modal-body {
        grid-template-columns: 1fr;
    }

    .modal-body > .modal-video-section {
        grid-column: 1;
        grid-row: auto;
        position: static;
    }

    .modal-body > .modal-section,
    .modal-body > .modal-review-form,
    .modal-body > .review-readonly {
        grid-column: 1;
    }

    .modal-video,
    .modal-video-caption {
        width: 210px;
    }

    .modal-body .variable-grid,
    .modal-body .modal-model-box {
        grid-template-columns: 1fr;
    }
}


/* ============================================================
   RESUMEN GRÁFICO COMPACTO
   ============================================================ */

.graph-summary {
    margin-bottom: 22px;

    border:
        1px solid
        rgba(219, 227, 236, 0.95);

    border-radius:
        var(--radius-xl);

    background:
        rgba(255, 255, 255, 0.97);

    box-shadow:
        var(--shadow);

    overflow: hidden;
}

.graph-summary-header {
    display: flex;

    align-items: center;

    justify-content:
        space-between;

    gap: 18px;

    padding:
        20px 22px;

    border-bottom:
        1px solid
        var(--border);
}

.graph-summary-header h2 {
    margin: 0;

    color:
        var(--primary);

    font-size:
        19px;
}

.graph-summary-header p {
    margin:
        5px 0 0;

    color:
        var(--muted);

    font-size:
        13px;

    line-height:
        1.5;
}

.graph-toggle {
    flex:
        0 0 auto;

    padding:
        9px 13px;

    border:
        1px solid
        var(--border);

    border-radius:
        999px;

    background:
        var(--surface-soft);

    color:
        var(--primary);

    font-family:
        inherit;

    font-size:
        11px;

    font-weight:
        800;

    cursor:
        pointer;
}

.graph-toggle:hover {
    background:
        var(--info-bg);
}

.graph-summary-body {
    padding: 18px;
}

.graph-summary-body.hidden {
    display: none;
}

.graph-grid-compact {
    display: grid;

    grid-template-columns:
        repeat(
            2,
            minmax(
                0,
                1fr
            )
        );

    gap: 16px;
}

.graph-card {
    min-width: 0;

    padding: 17px;

    border:
        1px solid
        var(--border);

    border-radius:
        18px;

    background:
        var(--surface);
}

.graph-card-head h3 {
    margin: 0;

    color:
        var(--primary);

    font-size:
        15px;
}

.graph-card-head p {
    margin:
        5px 0 0;

    color:
        var(--muted);

    font-size:
        11px;

    line-height:
        1.45;
}

.compact-chart {
    height:
        245px;

    margin-top:
        10px;
}

.graph-method-note {
    margin-top:
        9px;

    padding:
        8px 10px;

    border-radius:
        10px;

    background:
        #fff9eb;

    color:
        #745318;

    font-size:
        10px;

    line-height:
        1.45;
}

.graph-empty {
    padding:
        26px 16px;

    color:
        var(--muted);

    text-align:
        center;

    font-size:
        13px;
}

@media (max-width: 900px) {

    .graph-grid-compact {
        grid-template-columns:
            1fr;
    }
}

@media (max-width: 640px) {

    .graph-summary-header {
        flex-direction:
            column;

        align-items:
            stretch;
    }

    .graph-toggle {
        width:
            100%;
    }

    .compact-chart {
        height:
            225px;
    }
}



/* ============================================================
   AYUDA CONTEXTUAL AL PASAR EL CURSOR SOBRE LOS VALORES
   ============================================================ */

.value-tooltip {
    position: relative;
    display: inline-block;
    cursor: help;
    border-bottom: 1px dotted #7b8fa3;
    outline: none;
}

.value-tooltip::after {
    content: attr(data-tooltip);

    position: absolute;
    left: 50%;
    bottom: calc(100% + 10px);
    transform: translateX(-50%) translateY(4px);

    width: min(320px, 80vw);
    padding: 10px 12px;

    border-radius: 10px;

    background: #17212b;
    color: #ffffff;

    font-size: 11px;
    font-weight: 500;
    line-height: 1.45;
    text-align: left;

    box-shadow: 0 10px 26px rgba(0, 0, 0, 0.22);

    opacity: 0;
    visibility: hidden;
    pointer-events: none;

    transition:
        opacity 0.16s ease,
        transform 0.16s ease,
        visibility 0.16s ease;

    z-index: 9999;
}

.value-tooltip::before {
    content: "";

    position: absolute;
    left: 50%;
    bottom: calc(100% + 4px);
    transform: translateX(-50%);

    border: 6px solid transparent;
    border-top-color: #17212b;

    opacity: 0;
    visibility: hidden;

    transition:
        opacity 0.16s ease,
        visibility 0.16s ease;

    z-index: 10000;
}

.value-tooltip:hover::after,
.value-tooltip:hover::before,
.value-tooltip:focus::after,
.value-tooltip:focus::before {
    opacity: 1;
    visibility: visible;
}

.value-tooltip:hover::after,
.value-tooltip:focus::after {
    transform: translateX(-50%) translateY(0);
}

@media (max-width: 720px) {
    .value-tooltip::after {
        left: 0;
        transform: translateY(4px);
        width: min(280px, 76vw);
    }

    .value-tooltip:hover::after,
    .value-tooltip:focus::after {
        transform: translateY(0);
    }

    .value-tooltip::before {
        left: 18px;
        transform: none;
    }
}



/* ============================================================
   PASO 4 — ICONOS VISUALES DE REGIONES FACIALES
   ============================================================ */

.facial-variable-title {
    display: flex;
    align-items: center;
    gap: 8px;

    margin-bottom: 6px;

    color: var(--muted);
    font-size: 10px;
    font-weight: 700;
    line-height: 1.35;
}

.facial-variable-icon {
    flex: 0 0 auto;

    width: 28px;
    height: 28px;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    border-radius: 9px;

    background: var(--info-bg);
    color: var(--primary);
}

.facial-variable-icon svg {
    width: 19px;
    height: 19px;
}

.facial-variable-title span:last-child {
    min-width: 0;
}



/* ============================================================
   PASO 7 — LEGIBILIDAD DE TABLAS Y MODAL DE VARIABLES
   ============================================================ */

/* Más espacio útil en escritorio, sin dispersar el contenido */
.page {
    width: min(1460px, calc(100% - 28px));
}

/* ---------- Tabla principal de ensayos ---------- */

.table-wrap {
    overflow-x: auto;
}

.compact-trials {
    width: 100%;
    min-width: 1080px !important;
    table-layout: fixed;
}

.compact-trials th {
    padding: 15px 13px;

    background: #f5f8fb;

    color: var(--primary);

    font-size: 13px;
    font-weight: 800;

    line-height: 1.3;
}

.compact-trials td {
    padding: 15px 13px;

    color: var(--text);

    font-size: 14px;
    line-height: 1.5;

    vertical-align: top;
}

/* Reparto más equilibrado y menos sensación de columnas vacías */
.compact-trials th:nth-child(1),
.compact-trials td:nth-child(1) {
    width: 24%;
}

.compact-trials th:nth-child(2),
.compact-trials td:nth-child(2) {
    width: 18%;
}

.compact-trials th:nth-child(3),
.compact-trials td:nth-child(3) {
    width: 23%;
}

.compact-trials th:nth-child(4),
.compact-trials td:nth-child(4) {
    width: 21%;
}

.compact-trials th:nth-child(5),
.compact-trials td:nth-child(5) {
    width: 14%;
}

.trace-box {
    gap: 9px;
}

.trace-line {
    grid-template-columns: 78px minmax(0, 1fr);
    gap: 9px;
}

.trace-line span {
    font-size: 12px;
    line-height: 1.4;
}

.trace-line strong {
    font-size: 14px;
    line-height: 1.45;
}

.trace-trial {
    margin-top: 6px;
    padding-top: 8px;

    font-size: 11px;
    line-height: 1.45;
}

.ai-compact {
    gap: 11px;
}

.ai-compact .badge {
    display: inline-flex;
    align-items: center;

    width: fit-content;
    max-width: 100%;

    padding: 7px 10px;

    font-size: 12px;
    line-height: 1.35;

    white-space: normal;
}

.ai-confidence {
    padding: 10px 11px;

    font-size: 12px;
    line-height: 1.4;
}

.ai-confidence strong {
    font-size: 14px;
}

.evidence-compact {
    gap: 9px;
}

.evidence-item {
    font-size: 13px;
    line-height: 1.45;
}

.evidence-summary {
    padding-top: 9px;

    font-size: 12px;
    line-height: 1.6;
}

.evidence-summary strong {
    color: var(--text);
    font-size: 13px;
}

.compact-video {
    width: 100%;
    max-width: 240px;

    border-radius: 13px;
}

.action-stack {
    gap: 8px;
}

.action-btn {
    min-height: 42px;

    padding: 10px 11px;

    font-size: 13px;
    line-height: 1.3;
}


/* ---------- Modal de variables ---------- */

.modal-overlay {
    padding: 16px;
}

.modal-card {
    width: min(1280px, 96vw);
    max-height: 92vh;

    border-radius: 20px;
}

.modal-card.modal-review {
    width: min(1000px, 96vw);
}

.modal-header {
    padding: 22px 26px;
}

.modal-header h3 {
    font-size: 24px;
    line-height: 1.25;
}

.modal-header p {
    margin-top: 7px;

    font-size: 14px;
    line-height: 1.5;
}

.modal-close {
    width: 42px;
    height: 42px;

    font-size: 26px;
}

.modal-body {
    grid-template-columns: 310px minmax(0, 1fr);

    column-gap: 26px;
    row-gap: 18px;

    padding: 26px;
}

.modal-body > .modal-video-section {
    top: 100px;
}

.modal-video,
.modal-video-caption {
    width: 300px;
}

.modal-video {
    border-radius: 15px;
}

.modal-video-caption {
    margin-top: 10px;

    font-size: 13px;
    line-height: 1.55;
}

.modal-section:not(.modal-video-section) {
    margin-bottom: 0;

    padding: 18px;

    border: 1px solid var(--border);
    border-radius: 16px;

    background: #ffffff;
}

.modal-section-title {
    margin-bottom: 14px;

    font-size: 15px;
    line-height: 1.35;

    letter-spacing: .035em;
}

/* Dos columnas amplias: se prioriza lectura sobre cantidad */
.modal-body .variable-grid {
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 12px;
}

.modal-body .modal-model-box {
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 12px;
}

.variable-card {
    padding: 15px 16px;

    border-radius: 14px;
}

.variable-card > span {
    margin-bottom: 7px;

    font-size: 12px;
    line-height: 1.4;
}

.variable-card strong {
    font-size: 17px;
    line-height: 1.35;
}

.variable-card code {
    margin-top: 6px;

    font-size: 10px;
    line-height: 1.4;
}

.tech-note {
    margin-top: 13px;
    padding: 12px 14px;

    font-size: 13px;
    line-height: 1.6;
}

.facial-variable-title {
    gap: 9px;

    font-size: 12px;
    line-height: 1.4;
}

.facial-variable-icon {
    width: 32px;
    height: 32px;

    border-radius: 10px;
}

.facial-variable-icon svg {
    width: 21px;
    height: 21px;
}

/* Tooltips más fáciles de leer */
.value-tooltip::after {
    width: min(380px, 84vw);

    padding: 12px 14px;

    font-size: 13px;
    line-height: 1.55;
}


/* ---------- Responsive ---------- */

@media (max-width: 980px) {

    .modal-body {
        grid-template-columns: 260px minmax(0, 1fr);
    }

    .modal-video,
    .modal-video-caption {
        width: 250px;
    }
}

@media (max-width: 780px) {

    .compact-trials {
        min-width: 0 !important;
    }

    .compact-trials td {
        font-size: 14px;
    }

    .modal-card,
    .modal-card.modal-review {
        width: min(100%, 720px);
    }

    .modal-body {
        grid-template-columns: 1fr;

        padding: 18px;
    }

    .modal-body > .modal-video-section {
        position: static;
    }

    .modal-video,
    .modal-video-caption {
        width: 100%;
    }

    .modal-body .variable-grid,
    .modal-body .modal-model-box {
        grid-template-columns: 1fr;
    }

    .modal-section-title {
        font-size: 14px;
    }

    .variable-card strong {
        font-size: 16px;
    }
}

</style>

</head>

<body>

<div class="page">

    <header class="topbar">

        <div class="brand">

            <div class="brand-icon">
                BA
            </div>

            <div class="brand-text">

                <small>
                    Plataforma de revisión de respuestas conductuales
                </small>

                <strong>
                    Revisión del especialista
                </strong>

            </div>

        </div>

        <div class="actions">

            <a
                class="btn btn-outline"
                href="main.php"
            >
                &larr; Volver
            </a>

            <a
                class="btn"
                href="logout.php"
            >
                Cerrar sesión
            </a>

        </div>

    </header>


    <section class="hero">

        <div class="hero-content">

            <div>

                <div class="eyebrow">
                    Bebé · sesiones · ensayos · evidencia audiovisual
                </div>

                <h1>
                    Bebé <?php echo e($baby_id); ?>
                </h1>

                <p>
                    La plataforma organiza los ensayos registrados por el dispositivo,
                    presenta la clasificación preliminar generada por el Random Forest,
                    las probabilidades asociadas, la evidencia cefálica y facial,
                    las características cuantitativas y el video del ensayo.
                    La valoración final del comportamiento corresponde al especialista.
                </p>

            </div>

            <aside class="review-summary">

                <span>
                    Estado actual de revisión
                </span>

                <strong>
                    <?php echo e($pendientes_count); ?>
                    pendiente<?php echo $pendientes_count == 1 ? '' : 's'; ?>
                </strong>

                <p>
                    <?php echo e($validos_count); ?> válidos ·
                    <?php echo e($descartados_count); ?> descartados ·
                    <?php echo e($total_trials); ?> ensayos totales.
                </p>

            </aside>

        </div>

    </section>


    <section class="scope-note">

        <strong>Alcance de la información:</strong>

        la salida del Random Forest corresponde a una
        <strong>clasificación computacional preliminar</strong>
        entre respuesta conductual observable y respuesta no concluyente.
        La valoración profesional
        <strong>Reaccionó / No reaccionó / No evaluable</strong>
        se registra de forma independiente después de revisar el video
        y las condiciones del ensayo. La plataforma no emite un diagnóstico audiológico.

    </section>


    <section class="cards">

        <article class="card">

            <h3>Edad registrada</h3>

            <p>
                <?php
                echo $edad !== null
                    ? e($edad)
                    : "—";
                ?>
            </p>

            <small>
                Meses
            </small>

        </article>


        <article class="card">

            <h3>Sexo biológico</h3>

            <p>
                <?php
                if ($sexo === "M") {
                    echo "M";
                } elseif ($sexo === "F") {
                    echo "F";
                } else {
                    echo "—";
                }
                ?>
            </p>

            <small>
                Información de caracterización
            </small>

        </article>


        <article class="card">

            <h3>Sesiones</h3>

            <p>
                <?php echo e($sesiones); ?>
            </p>

            <small>
                Total registradas
            </small>

        </article>


        <article class="card">

            <h3>Pendientes</h3>

            <p>
                <?php echo e($pendientes_count); ?>
            </p>

            <small>
                Por revisar
            </small>

        </article>


        <article class="card">

            <h3>Válidos</h3>

            <p>
                <?php echo e($validos_count); ?>
            </p>

            <small>
                Revisados e incluidos
            </small>

        </article>


        <article class="card">

            <h3>Descartados</h3>

            <p>
                <?php echo e($descartados_count); ?>
            </p>

            <small>
                Conservados para trazabilidad
            </small>

        </article>

    </section>


    <section class="graph-summary">

        <div class="graph-summary-header">

            <div>

                <h2>
                    Resumen gráfico del bebé
                </h2>

                <p>
                    Visualización interactiva de la clasificación computacional,
                    la valoración profesional, su concordancia operacional
                    y el seguimiento entre sesiones.
                </p>

            </div>

            <button
                type="button"
                class="graph-toggle"
                id="graphToggle"
                onclick="toggleGraphSummary()"
            >
                Ocultar gráficas
            </button>

        </div>


        <div
            class="graph-summary-body"
            id="graphSummaryBody"
        >

        <?php if ($hay_graficas) { ?>

            <div class="graph-grid-compact">


                <article class="graph-card">

                    <div class="graph-card-head">

                        <h3>
                            Valoración profesional
                        </h3>

                        <p>
                            Ensayos válidos revisados por el especialista.
                        </p>

                    </div>

                    <div class="chart-box compact-chart">
                        <canvas id="chartProfesional"></canvas>
                    </div>

                </article>


                <article class="graph-card">

                    <div class="graph-card-head">

                        <h3>
                            Clasificación IA
                        </h3>

                        <p>
                            Distribución de la salida preliminar del Random Forest.
                        </p>

                    </div>

                    <div class="chart-box compact-chart">
                        <canvas id="chartIA"></canvas>
                    </div>

                </article>


                <article class="graph-card">

                    <div class="graph-card-head">

                        <h3>
                            Concordancia operacional
                        </h3>

                        <p>
                            Comparación entre la categoría computacional
                            y la valoración profesional.
                        </p>

                    </div>

                    <div class="chart-box compact-chart">
                        <canvas id="chartConcordancia"></canvas>
                    </div>

                    <div class="graph-method-note">
                        Concordancia operacional no equivale a exactitud
                        diagnóstica ni a validación clínica.
                    </div>

                </article>


                <article class="graph-card">

                    <div class="graph-card-head">

                        <h3>
                            Seguimiento por sesión
                        </h3>

                        <p>
                            Valoraciones profesionales registradas en cada sesión.
                        </p>

                    </div>

                    <div class="chart-box compact-chart">
                        <canvas id="chartSesiones"></canvas>
                    </div>

                </article>


            </div>

        <?php } else { ?>

            <div class="graph-empty">

                Las gráficas aparecerán cuando existan resultados
                computacionales o valoraciones profesionales disponibles
                para este bebé.

            </div>

        <?php } ?>

        </div>

    </section>



    <!-- ======================================================
         ENSAYOS PENDIENTES — VISTA COMPACTA
         ====================================================== -->

    <section class="table-panel">

        <div class="table-title">

            <div>
                <h3>Ensayos pendientes de revisión</h3>

                <p>
                    La tabla principal conserva únicamente la trazabilidad,
                    la clasificación preliminar, la evidencia observacional
                    y el video. Las variables y la valoración profesional
                    se consultan en ventanas flotantes.
                </p>
            </div>

            <span class="badge badge-warning">
                <?php echo e($pendientes_count); ?> pendientes
            </span>

        </div>

        <div class="table-wrap">

            <table class="compact-trials">

                <thead>
                    <tr>
                        <th>Trazabilidad</th>
                        <th>Clasificación IA</th>
                        <th>Evidencia observacional</th>
                        <th>Video</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>

                <?php
                if (
                    $trials_pendientes &&
                    $trials_pendientes->num_rows > 0
                ) {
                    while (
                        $row =
                        $trials_pendientes->fetch_assoc()
                    ) {

                        $trial_id_db = (int)($row['id'] ?? 0);
                        $modal_vars_id = "modal-vars-" . $trial_id_db;
                        $modal_review_id = "modal-review-" . $trial_id_db;
                ?>

                    <tr>

                        <!-- TRAZABILIDAD -->
                        <td data-label="Trazabilidad">

                            <div class="trace-box">

                                <div class="trace-line">
                                    <span>Sesión</span>
                                    <strong>
                                        <?php echo e($row['session_id'] ?? "—"); ?>
                                    </strong>
                                </div>

                                <div class="trace-line">
                                    <span>Fecha</span>
                                    <strong>
                                        <?php echo e(fecha_larga($row['created_at'] ?? null)); ?>
                                    </strong>
                                </div>

                                <div class="trace-line">
                                    <span>Estímulo</span>
                                    <strong>
                                        <?php echo e($row['stimulus_name'] ?? "—"); ?>
                                    </strong>
                                </div>

                                <div class="trace-line">
                                    <span>Emisión</span>
                                    <strong>
                                        <?php
                                        echo e(
                                            textoLado(
                                                $row['label_expected'] ?? ""
                                            )
                                        );
                                        ?>
                                    </strong>
                                </div>

                                <div class="trace-trial">
                                    Trial:
                                    <?php echo e($row['trial_id'] ?? "—"); ?>
                                </div>

                            </div>

                        </td>


                        <!-- CLASIFICACIÓN IA -->
                        <td data-label="Clasificación IA">

                            <div class="ai-compact">

                                <span
                                    class="badge
                                    <?php
                                    echo e(
                                        claseIA(
                                            $row['ai_response_status'] ?? "",
                                            $row['trial_status'] ?? ""
                                        )
                                    );
                                    ?>"
                                >
                                    <?php
                                    echo e(
                                        textoIA(
                                            $row['ai_response_status'] ?? "",
                                            $row['trial_status'] ?? ""
                                        )
                                    );
                                    ?>
                                </span>

                                <div class="ai-confidence">

                                    <span>
                                        Confianza del modelo
                                    </span>

                                    <strong>
                                        <?php
                                        echo e(
                                            porcentaje01(
                                                $row['ml_confidence'] ?? null
                                            )
                                        );
                                        ?>
                                    </strong>

                                </div>

                            </div>

                        </td>


                        <!-- EVIDENCIA -->
                        <td data-label="Evidencia observacional">

                            <div class="evidence-compact">

                                <div class="evidence-item">

                                    <span
                                        class="evidence-dot
                                        <?php
                                        echo (
                                            (int)(
                                                $row['head_response_detected']
                                                ?? 0
                                            ) === 1
                                        ) ? 'on' : '';
                                        ?>"
                                    ></span>

                                    Movimiento cefálico

                                </div>

                                <div class="evidence-item">

                                    <span
                                        class="evidence-dot
                                        <?php
                                        echo (
                                            (int)(
                                                $row['facial_response_detected']
                                                ?? 0
                                            ) === 1
                                        ) ? 'on' : '';
                                        ?>"
                                    ></span>

                                    Gesto facial

                                </div>

                                <div class="evidence-summary">

                                    <strong>
                                        <?php
                                        echo e(
                                            textoTipoRespuesta(
                                                $row['response_type'] ?? ""
                                            )
                                        );
                                        ?>
                                    </strong>

                                    <br>

                                    Gesto:
                                    <?php
                                    echo e(
                                        textoGestoFacial(
                                            $row['facial_gesture_type'] ?? ""
                                        )
                                    );
                                    ?>

                                    <br>

                                    Latencia:
                                    <?php
                                    echo formatearLatencia(
                                        $row['reaction_time_s'] ?? null
                                    );
                                    ?>

                                </div>

                            </div>

                        </td>


                        <!-- VIDEO -->
                        <td data-label="Video">

                            <?php
                            if (
                                !empty(
                                    $row['video_path']
                                )
                            ) {
                            ?>

                                <video
                                    class="compact-video"
                                    controls
                                    preload="metadata"
                                >
                                    <source
                                        src="<?php echo e($row['video_path']); ?>"
                                        type="video/mp4"
                                    >
                                    Tu navegador no soporta video.
                                </video>

                            <?php
                            } else {
                            ?>

                                <span class="no-video">
                                    Sin video disponible
                                </span>

                            <?php
                            }
                            ?>

                        </td>


                        <!-- ACCIONES -->
                        <td data-label="Acciones">

                            <div class="action-stack">

                                <button
                                    type="button"
                                    class="action-btn action-btn-secondary"
                                    onclick="openTrialModal('<?php echo e($modal_vars_id); ?>')"
                                >
                                    Ver variables
                                </button>

                                <button
                                    type="button"
                                    class="action-btn action-btn-primary"
                                    onclick="openTrialModal('<?php echo e($modal_review_id); ?>')"
                                >
                                    Valorar ensayo
                                </button>

                            </div>


                            <!-- =========================================
                                 MODAL VARIABLES
                                 ========================================= -->

                            <div
                                class="modal-overlay"
                                id="<?php echo e($modal_vars_id); ?>"
                                aria-hidden="true"
                            >

                                <div
                                    class="modal-card"
                                    role="dialog"
                                    aria-modal="true"
                                    aria-labelledby="<?php echo e($modal_vars_id); ?>-title"
                                >

                                    <div class="modal-header">

                                        <div>
                                            <h3 id="<?php echo e($modal_vars_id); ?>-title">
                                                Variables del ensayo
                                            </h3>

                                            <p>
                                                <?php echo e($row['session_id'] ?? "—"); ?>
                                                ·
                                                <?php echo e($row['trial_id'] ?? "—"); ?>
                                            </p>
                                        </div>

                                        <button
                                            type="button"
                                            class="modal-close"
                                            onclick="closeTrialModal('<?php echo e($modal_vars_id); ?>')"
                                            aria-label="Cerrar"
                                        >
                                            &times;
                                        </button>

                                    </div>


                                    <div class="modal-body">


                                        <section class="modal-video-section">

                                            <h4 class="modal-section-title">
                                                Evidencia audiovisual del ensayo
                                            </h4>

                                            <?php
                                            if (
                                                !empty(
                                                    $row['video_path']
                                                )
                                            ) {
                                            ?>

                                                <div class="modal-video-wrap">

                                                    <video
                                                        class="modal-video"
                                                        controls
                                                        preload="metadata"
                                                        src="<?php echo e($row['video_path']); ?>"
                                                    >
                                                        Tu navegador no soporta
                                                        la reproducción de video.
                                                    </video>

                                                </div>

                                                <div class="modal-video-caption">
                                                    Revise el comportamiento visible antes,
                                                    durante y después del estímulo. El video
                                                    constituye la evidencia principal para
                                                    contrastar la clasificación computacional
                                                    y la valoración profesional.
                                                </div>

                                            <?php
                                            } else {
                                            ?>

                                                <div class="modal-video-wrap">
                                                    <div class="modal-video-empty">
                                                        No hay video disponible para este ensayo.
                                                    </div>
                                                </div>

                                            <?php
                                            }
                                            ?>

                                        </section>



                                        <section class="modal-section">

                                            <h4 class="modal-section-title">
                                                Salida del Random Forest
                                            </h4>

                                            <div class="modal-model-box">

                                                <div class="variable-card">
                                                    <span>Clasificación preliminar</span>
                                                    <strong>
                                                        <?php
                                                        echo e(
                                                            textoIA(
                                                                $row['ai_response_status'] ?? "",
                                                                $row['trial_status'] ?? ""
                                                            )
                                                        );
                                                        ?>
                                                    </strong>
                                                    <code>ai_response_status</code>
                                                </div>

                                                <div class="variable-card">
                                                    <span>Confianza de la clase</span>
                                                    <strong>
                                                        <?php
                                                        echo e(
                                                            porcentaje01(
                                                                $row['ml_confidence'] ?? null
                                                            )
                                                        );
                                                        ?>
                                                    </strong>
                                                    <code>ml_confidence</code>
                                                </div>

                                                <div class="variable-card">
                                                    <span>Prob. observable</span>
                                                    <strong>
                                                        <?php
                                                        echo e(
                                                            porcentaje01(
                                                                $row[
                                                                    'prob_respuesta_conductual_observable'
                                                                ] ?? null
                                                            )
                                                        );
                                                        ?>
                                                    </strong>
                                                    <code>prob_respuesta_conductual_observable</code>
                                                </div>

                                                <div class="variable-card">
                                                    <span>Prob. no concluyente</span>
                                                    <strong>
                                                        <?php
                                                        echo e(
                                                            porcentaje01(
                                                                $row[
                                                                    'prob_respuesta_no_concluyente'
                                                                ] ?? null
                                                            )
                                                        );
                                                        ?>
                                                    </strong>
                                                    <code>prob_respuesta_no_concluyente</code>
                                                </div>

                                            </div>

                                        </section>


                                        <section class="modal-section">

                                            <h4 class="modal-section-title">
                                                Movimiento cefálico
                                            </h4>

                                            <div class="variable-grid">

                                                <?php
                                                $cef = array(
                                                    array("Línea base yaw", "baseline_yaw", 3, "°"),
                                                    array("Media Δyaw", "mean_delta_yaw", 3, "°"),
                                                    array("Mediana Δyaw", "median_delta_yaw", 3, "°"),
                                                    array("Variabilidad Δyaw", "std_delta_yaw", 3, "°"),
                                                    array("Mínimo Δyaw", "min_delta_yaw", 3, "°"),
                                                    array("Máximo Δyaw", "max_delta_yaw", 3, "°"),
                                                    array("Rango Δyaw", "range_delta_yaw", 3, "°")
                                                );

                                                foreach ($cef as $v) {
                                                ?>

                                                    <div class="variable-card">
                                                        <span><?php echo e($v[0]); ?></span>
                                                        <strong>
                                                            <?php
                                                            echo e(
                                                                numero(
                                                                    $row[$v[1]] ?? null,
                                                                    $v[2]
                                                                )
                                                            );
                                                            echo e($v[3]);
                                                            ?>
                                                        </strong>
                                                        <code><?php echo e($v[1]); ?></code>
                                                    </div>

                                                <?php } ?>

                                            </div>

                                        </section>


                                        <section class="modal-section">

                                            <h4 class="modal-section-title">
                                                Dinámica entre muestras
                                            </h4>

                                            <div class="variable-grid">

                                                <?php
                                                $din = array(
                                                    array("Cambio angular medio entre muestras", "mean_velocity"),
                                                    array("Variabilidad del cambio angular", "std_velocity"),
                                                    array("Mayor incremento angular entre muestras", "max_velocity"),
                                                    array("Mayor disminución angular entre muestras", "min_velocity")
                                                );

                                                foreach ($din as $v) {
                                                ?>

                                                    <div class="variable-card">
                                                        <span><?php echo e($v[0]); ?></span>
                                                        <strong>
                                                            <?php
                                                            echo e(
                                                                numero(
                                                                    $row[$v[1]] ?? null,
                                                                    4
                                                                )
                                                            );
                                                            echo " °/muestra";
                                                            ?>
                                                        </strong>
                                                        <code><?php echo e($v[1]); ?></code>
                                                    </div>

                                                <?php } ?>

                                            </div>

                                            <div class="tech-note">
                                                Estas variables comparan el ángulo yaw entre
                                                muestras válidas consecutivas. Por eso se muestran
                                                en <strong>grados por muestra (°/muestra)</strong>.
                                                No equivalen a velocidad angular en °/s, porque el
                                                intervalo temporal entre muestras válidas puede variar.
                                            </div>

                                        </section>


                                        <section class="modal-section">

                                            <h4 class="modal-section-title">
                                                Magnitud y distribución
                                            </h4>

                                            <div class="variable-grid">

                                                <div class="variable-card">
                                                    <span>Pico absoluto</span>
                                                    <strong>
                                                        <?php
                                                        echo e(
                                                            numero(
                                                                $row['peak_abs'] ?? null,
                                                                3
                                                            )
                                                        );
                                                        ?>°
                                                    </strong>
                                                    <code>peak_abs</code>
                                                </div>

                                                <div class="variable-card">
                                                    <span>Δyaw negativo</span>
                                                    <strong>
                                                        <?php
                                                        echo e(
                                                            porcentaje01(
                                                                $row['pct_negative'] ?? null
                                                            )
                                                        );
                                                        ?>
                                                    </strong>
                                                    <code>pct_negative</code>
                                                </div>

                                                <div class="variable-card">
                                                    <span>Δyaw positivo</span>
                                                    <strong>
                                                        <?php
                                                        echo e(
                                                            porcentaje01(
                                                                $row['pct_positive'] ?? null
                                                            )
                                                        );
                                                        ?>
                                                    </strong>
                                                    <code>pct_positive</code>
                                                </div>

                                                <div class="variable-card">
                                                    <span>|Media Δyaw|</span>
                                                    <strong>
                                                        <?php
                                                        echo e(
                                                            numero(
                                                                $row['abs_mean_yaw'] ?? null,
                                                                3
                                                            )
                                                        );
                                                        ?>°
                                                    </strong>
                                                    <code>abs_mean_yaw</code>
                                                </div>

                                                <div class="variable-card">
                                                    <span>|Mediana Δyaw|</span>
                                                    <strong>
                                                        <?php
                                                        echo e(
                                                            numero(
                                                                $row['abs_median_yaw'] ?? null,
                                                                3
                                                            )
                                                        );
                                                        ?>°
                                                    </strong>
                                                    <code>abs_median_yaw</code>
                                                </div>

                                                <div class="variable-card">
                                                    <span>Índice acumulado de movimiento</span>
                                                    <strong>
                                                        <?php
                                                        echo e(
                                                            numero(
                                                                $row['movement_energy'] ?? null,
                                                                3
                                                            )
                                                        );
                                                        ?>
                                                    </strong>
                                                    <code>movement_energy</code>
                                                </div>

                                                <div class="variable-card">
                                                    <span>Muestras válidas</span>
                                                    <strong>
                                                        <?php
                                                        echo e(
                                                            numero(
                                                                $row['samples'] ?? null,
                                                                0
                                                            )
                                                        );
                                                        ?>
                                                    </strong>
                                                    <code>samples</code>
                                                </div>

                                            </div>

                                        </section>


                                        <section class="modal-section">

                                            <h4 class="modal-section-title">
                                                Indicadores faciales
                                            </h4>

                                            <div class="variable-grid">

                                                <?php
                                                $fac = array(
                                                    array("Variación facial máxima detectada", "facial_score", "facial"),
                                                    array("Reducción máxima de apertura ocular (parpadeo)", "blink_score", "parpadeo"),
                                                    array("Aumento máximo de apertura ocular", "eye_score", "ojos"),
                                                    array("Elevación relativa máxima de cejas", "eyebrow_score", "cejas"),
                                                    array("Aumento máximo de apertura de boca", "mouth_score", "boca")
                                                );

                                                foreach ($fac as $v) {
                                                ?>

                                                    <div class="variable-card">

                                                        <div class="facial-variable-title">
                                                            <span class="facial-variable-icon">
                                                                <?php
                                                                echo iconoFacialSVG(
                                                                    $v[2] ?? ""
                                                                );
                                                                ?>
                                                            </span>

                                                            <span>
                                                                <?php echo e($v[0]); ?>
                                                            </span>
                                                        </div>

                                                        <strong>
                                                            <?php
                                                            echo e(
                                                                porcentajeFacial(
                                                                    $row[$v[1]] ?? null
                                                                )
                                                            );
                                                            ?>
                                                        </strong>
                                                        <code><?php echo e($v[1]); ?></code>
                                                    </div>

                                                <?php } ?>

                                            </div>

                                            <div class="tech-note">
                                                Los valores se muestran como porcentajes de variación relativa
                                                respecto a la línea base facial registrada antes del estímulo.
                                                El porcentaje facilita la lectura en la plataforma, pero no
                                                representa un porcentaje clínico de apertura o movimiento.
                                            </div>

                                        </section>


                                    </div>

                                </div>

                            </div>


                            <!-- =========================================
                                 MODAL VALORACIÓN PROFESIONAL
                                 ========================================= -->

                            <div
                                class="modal-overlay"
                                id="<?php echo e($modal_review_id); ?>"
                                aria-hidden="true"
                            >

                                <div
                                    class="modal-card modal-review"
                                    role="dialog"
                                    aria-modal="true"
                                    aria-labelledby="<?php echo e($modal_review_id); ?>-title"
                                >

                                    <div class="modal-header">

                                        <div>
                                            <h3 id="<?php echo e($modal_review_id); ?>-title">
                                                Valoración del especialista
                                            </h3>

                                            <p>
                                                <?php echo e($row['session_id'] ?? "—"); ?>
                                                ·
                                                <?php echo e($row['trial_id'] ?? "—"); ?>
                                            </p>
                                        </div>

                                        <button
                                            type="button"
                                            class="modal-close"
                                            onclick="closeTrialModal('<?php echo e($modal_review_id); ?>')"
                                            aria-label="Cerrar"
                                        >
                                            &times;
                                        </button>

                                    </div>


                                    <div class="modal-body">


                                        <section class="modal-video-section">

                                            <h4 class="modal-section-title">
                                                Evidencia audiovisual del ensayo
                                            </h4>

                                            <?php
                                            if (
                                                !empty(
                                                    $row['video_path']
                                                )
                                            ) {
                                            ?>

                                                <div class="modal-video-wrap">

                                                    <video
                                                        class="modal-video"
                                                        controls
                                                        preload="metadata"
                                                        src="<?php echo e($row['video_path']); ?>"
                                                    >
                                                        Tu navegador no soporta
                                                        la reproducción de video.
                                                    </video>

                                                </div>

                                                <div class="modal-video-caption">
                                                    Revise el comportamiento visible antes,
                                                    durante y después del estímulo. El video
                                                    constituye la evidencia principal para
                                                    contrastar la clasificación computacional
                                                    y la valoración profesional.
                                                </div>

                                            <?php
                                            } else {
                                            ?>

                                                <div class="modal-video-wrap">
                                                    <div class="modal-video-empty">
                                                        No hay video disponible para este ensayo.
                                                    </div>
                                                </div>

                                            <?php
                                            }
                                            ?>

                                        </section>


                                        <form
                                            action="actualizar_revision_trial.php"
                                            method="POST"
                                            class="modal-review-form"
                                        >

                                            <input
                                                type="hidden"
                                                name="trial_db_id"
                                                value="<?php echo e($row['id']); ?>"
                                            >

                                            <input
                                                type="hidden"
                                                name="baby_id"
                                                value="<?php echo e($baby_id); ?>"
                                            >


                                            <div class="modal-review-help">
                                                Revise el video y la calidad del registro.
                                                La valoración profesional es independiente
                                                de la clasificación preliminar generada
                                                por el Random Forest.
                                            </div>


                                            <div>
                                                <label for="quality-<?php echo e($trial_id_db); ?>">
                                                    Calidad del ensayo
                                                </label>

                                                <select
                                                    id="quality-<?php echo e($trial_id_db); ?>"
                                                    name="review_status"
                                                    required
                                                >
                                                    <option value="pendiente">
                                                        Pendiente
                                                    </option>

                                                    <option value="valido">
                                                        Válido
                                                    </option>

                                                    <option value="descartado">
                                                        Descartado
                                                    </option>
                                                </select>
                                            </div>


                                            <div>
                                                <label for="specialist-<?php echo e($trial_id_db); ?>">
                                                    Valoración conductual
                                                </label>

                                                <select
                                                    id="specialist-<?php echo e($trial_id_db); ?>"
                                                    name="specialist_response_status"
                                                >
                                                    <option value="">
                                                        Seleccione una opción
                                                    </option>

                                                    <option value="reacciono">
                                                        Reaccionó
                                                    </option>

                                                    <option value="no_reacciono">
                                                        No reaccionó
                                                    </option>

                                                    <option value="no_evaluable">
                                                        No evaluable
                                                    </option>
                                                </select>
                                            </div>


                                            <div>
                                                <label for="notes-<?php echo e($trial_id_db); ?>">
                                                    Observación
                                                </label>

                                                <textarea
                                                    id="notes-<?php echo e($trial_id_db); ?>"
                                                    name="review_notes"
                                                    placeholder="Ej.: rostro visible, movimiento espontáneo, llanto, reacción posterior al estímulo, interferencia durante el ensayo..."
                                                ></textarea>
                                            </div>


                                            <div class="modal-review-actions">

                                                <button
                                                    type="button"
                                                    class="action-btn action-btn-neutral"
                                                    onclick="closeTrialModal('<?php echo e($modal_review_id); ?>')"
                                                >
                                                    Cancelar
                                                </button>

                                                <button
                                                    type="submit"
                                                    class="action-btn action-btn-primary"
                                                >
                                                    Guardar valoración
                                                </button>

                                            </div>

                                        </form>

                                    </div>

                                </div>

                            </div>


                        </td>

                    </tr>

                <?php
                    }
                } else {
                ?>

                    <tr>
                        <td colspan="5" data-label="">
                            <div class="empty-state">
                                No hay ensayos pendientes para este bebé.
                            </div>
                        </td>
                    </tr>

                <?php } ?>

                </tbody>

            </table>

        </div>

    </section>



    <!-- ======================================================
         ENSAYOS EVALUADOS — VISTA COMPACTA
         ====================================================== -->

    <section class="table-panel">

        <div class="table-title">

            <div>
                <h3>Ensayos ya evaluados</h3>

                <p>
                    Se conserva la trazabilidad y el video.
                    La valoración completa y las variables técnicas
                    pueden consultarse sin recargar la tabla.
                </p>
            </div>

            <div style="display:flex;align-items:center;gap:10px;">
                <span class="badge badge-neutral">
                    <?php echo e($evaluados_count); ?> evaluados
                </span>

                <button
                    type="button"
                    class="graph-toggle"
                    id="evaluatedToggle"
                    onclick="toggleEvaluatedTrials()"
                >
                    Mostrar evaluados
                </button>
            </div>

        </div>


        <div
            class="table-wrap"
            id="evaluatedTrialsBody"
            style="display:none;"
        >

            <table class="compact-trials">

                <thead>
                    <tr>
                        <th>Trazabilidad</th>
                        <th>Clasificación IA</th>
                        <th>Valoración profesional</th>
                        <th>Video</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>

                <?php
                if (
                    $trials_evaluados &&
                    $trials_evaluados->num_rows > 0
                ) {

                    while (
                        $row =
                        $trials_evaluados->fetch_assoc()
                    ) {

                        $trial_id_db = (int)($row['id'] ?? 0);
                        $modal_vars_eval_id =
                            "modal-vars-eval-" . $trial_id_db;

                        $modal_review_eval_id =
                            "modal-review-eval-" . $trial_id_db;

                        $estado_profesional =
                            estadoEspecialistaFila($row);
                ?>

                    <tr>

                        <!-- TRAZABILIDAD -->
                        <td data-label="Trazabilidad">

                            <div class="trace-box">

                                <div class="trace-line">
                                    <span>Sesión</span>
                                    <strong>
                                        <?php echo e($row['session_id'] ?? "—"); ?>
                                    </strong>
                                </div>

                                <div class="trace-line">
                                    <span>Fecha</span>
                                    <strong>
                                        <?php echo e(fecha_larga($row['created_at'] ?? null)); ?>
                                    </strong>
                                </div>

                                <div class="trace-line">
                                    <span>Estímulo</span>
                                    <strong>
                                        <?php echo e($row['stimulus_name'] ?? "—"); ?>
                                    </strong>
                                </div>

                                <div class="trace-line">
                                    <span>Emisión</span>
                                    <strong>
                                        <?php
                                        echo e(
                                            textoLado(
                                                $row['label_expected'] ?? ""
                                            )
                                        );
                                        ?>
                                    </strong>
                                </div>

                                <div class="trace-trial">
                                    Trial:
                                    <?php echo e($row['trial_id'] ?? "—"); ?>
                                </div>

                            </div>

                        </td>


                        <!-- IA -->
                        <td data-label="Clasificación IA">

                            <div class="ai-compact">

                                <span
                                    class="badge
                                    <?php
                                    echo e(
                                        claseIA(
                                            $row['ai_response_status'] ?? "",
                                            $row['trial_status'] ?? ""
                                        )
                                    );
                                    ?>"
                                >
                                    <?php
                                    echo e(
                                        textoIA(
                                            $row['ai_response_status'] ?? "",
                                            $row['trial_status'] ?? ""
                                        )
                                    );
                                    ?>
                                </span>

                                <div class="ai-confidence">
                                    <span>Confianza</span>
                                    <strong>
                                        <?php
                                        echo e(
                                            porcentaje01(
                                                $row['ml_confidence'] ?? null
                                            )
                                        );
                                        ?>
                                    </strong>
                                </div>

                            </div>

                        </td>


                        <!-- VALORACIÓN PROFESIONAL RESUMIDA -->
                        <td data-label="Valoración profesional">

                            <div class="ai-compact">

                                <span
                                    class="badge
                                    <?php
                                    echo e(
                                        claseEspecialista(
                                            $estado_profesional
                                        )
                                    );
                                    ?>"
                                >
                                    <?php
                                    echo e(
                                        textoEspecialista(
                                            $estado_profesional
                                        )
                                    );
                                    ?>
                                </span>

                                <div>
                                    <?php
                                    echo badgeRevision(
                                        $row['review_status'] ?? 'pendiente'
                                    );
                                    ?>
                                </div>

                            </div>

                        </td>


                        <!-- VIDEO -->
                        <td data-label="Video">

                            <?php
                            if (
                                !empty(
                                    $row['video_path']
                                )
                            ) {
                            ?>

                                <video
                                    class="compact-video"
                                    controls
                                    preload="metadata"
                                >
                                    <source
                                        src="<?php echo e($row['video_path']); ?>"
                                        type="video/mp4"
                                    >
                                    Tu navegador no soporta video.
                                </video>

                            <?php
                            } else {
                            ?>

                                <span class="no-video">
                                    Sin video disponible
                                </span>

                            <?php
                            }
                            ?>

                        </td>


                        <!-- ACCIONES -->
                        <td data-label="Acciones">

                            <div class="action-stack">

                                <button
                                    type="button"
                                    class="action-btn action-btn-secondary"
                                    onclick="openTrialModal('<?php echo e($modal_vars_eval_id); ?>')"
                                >
                                    Ver variables
                                </button>

                                <button
                                    type="button"
                                    class="action-btn action-btn-neutral"
                                    onclick="openTrialModal('<?php echo e($modal_review_eval_id); ?>')"
                                >
                                    Ver valoración
                                </button>

                            </div>


                            <!-- MODAL VARIABLES EVALUADO -->
                            <div
                                class="modal-overlay"
                                id="<?php echo e($modal_vars_eval_id); ?>"
                                aria-hidden="true"
                            >

                                <div
                                    class="modal-card"
                                    role="dialog"
                                    aria-modal="true"
                                >

                                    <div class="modal-header">

                                        <div>
                                            <h3>Variables del ensayo</h3>

                                            <p>
                                                <?php echo e($row['session_id'] ?? "—"); ?>
                                                ·
                                                <?php echo e($row['trial_id'] ?? "—"); ?>
                                            </p>
                                        </div>

                                        <button
                                            type="button"
                                            class="modal-close"
                                            onclick="closeTrialModal('<?php echo e($modal_vars_eval_id); ?>')"
                                            aria-label="Cerrar"
                                        >
                                            &times;
                                        </button>

                                    </div>


                                    <div class="modal-body">


                                        <section class="modal-video-section">

                                            <h4 class="modal-section-title">
                                                Evidencia audiovisual del ensayo
                                            </h4>

                                            <?php
                                            if (
                                                !empty(
                                                    $row['video_path']
                                                )
                                            ) {
                                            ?>

                                                <div class="modal-video-wrap">

                                                    <video
                                                        class="modal-video"
                                                        controls
                                                        preload="metadata"
                                                        src="<?php echo e($row['video_path']); ?>"
                                                    >
                                                        Tu navegador no soporta
                                                        la reproducción de video.
                                                    </video>

                                                </div>

                                                <div class="modal-video-caption">
                                                    Revise el comportamiento visible antes,
                                                    durante y después del estímulo. El video
                                                    constituye la evidencia principal para
                                                    contrastar la clasificación computacional
                                                    y la valoración profesional.
                                                </div>

                                            <?php
                                            } else {
                                            ?>

                                                <div class="modal-video-wrap">
                                                    <div class="modal-video-empty">
                                                        No hay video disponible para este ensayo.
                                                    </div>
                                                </div>

                                            <?php
                                            }
                                            ?>

                                        </section>



                                        <section class="modal-section">

                                            <h4 class="modal-section-title">
                                                Salida del Random Forest
                                            </h4>

                                            <div class="modal-model-box">

                                                <div class="variable-card">
                                                    <span>Clasificación</span>
                                                    <strong>
                                                        <?php
                                                        echo e(
                                                            textoIA(
                                                                $row['ai_response_status'] ?? "",
                                                                $row['trial_status'] ?? ""
                                                            )
                                                        );
                                                        ?>
                                                    </strong>
                                                    <code>ai_response_status</code>
                                                </div>

                                                <div class="variable-card">
                                                    <span>Confianza</span>
                                                    <strong>
                                                        <?php
                                                        echo e(
                                                            porcentaje01(
                                                                $row['ml_confidence'] ?? null
                                                            )
                                                        );
                                                        ?>
                                                    </strong>
                                                    <code>ml_confidence</code>
                                                </div>

                                                <div class="variable-card">
                                                    <span>Prob. observable</span>
                                                    <strong>
                                                        <?php
                                                        echo e(
                                                            porcentaje01(
                                                                $row[
                                                                    'prob_respuesta_conductual_observable'
                                                                ] ?? null
                                                            )
                                                        );
                                                        ?>
                                                    </strong>
                                                    <code>prob_respuesta_conductual_observable</code>
                                                </div>

                                                <div class="variable-card">
                                                    <span>Prob. no concluyente</span>
                                                    <strong>
                                                        <?php
                                                        echo e(
                                                            porcentaje01(
                                                                $row[
                                                                    'prob_respuesta_no_concluyente'
                                                                ] ?? null
                                                            )
                                                        );
                                                        ?>
                                                    </strong>
                                                    <code>prob_respuesta_no_concluyente</code>
                                                </div>

                                            </div>

                                        </section>


                                        <section class="modal-section">

                                            <h4 class="modal-section-title">
                                                Movimiento cefálico
                                            </h4>

                                            <div class="variable-grid">

                                                <?php
                                                $cef_eval = array(
                                                    array("Línea base yaw", "baseline_yaw", 3, "°"),
                                                    array("Media Δyaw", "mean_delta_yaw", 3, "°"),
                                                    array("Mediana Δyaw", "median_delta_yaw", 3, "°"),
                                                    array("Variabilidad Δyaw", "std_delta_yaw", 3, "°"),
                                                    array("Mínimo Δyaw", "min_delta_yaw", 3, "°"),
                                                    array("Máximo Δyaw", "max_delta_yaw", 3, "°"),
                                                    array("Rango Δyaw", "range_delta_yaw", 3, "°")
                                                );

                                                foreach ($cef_eval as $v) {
                                                ?>

                                                    <div class="variable-card">
                                                        <span><?php echo e($v[0]); ?></span>
                                                        <strong>
                                                            <?php
                                                            echo e(
                                                                numero(
                                                                    $row[$v[1]] ?? null,
                                                                    $v[2]
                                                                )
                                                            );
                                                            echo e($v[3]);
                                                            ?>
                                                        </strong>
                                                        <code><?php echo e($v[1]); ?></code>
                                                    </div>

                                                <?php } ?>

                                            </div>

                                        </section>


                                        <section class="modal-section">

                                            <h4 class="modal-section-title">
                                                Dinámica entre muestras
                                            </h4>

                                            <div class="variable-grid">

                                                <?php
                                                $din_eval = array(
                                                    array("Cambio angular medio entre muestras", "mean_velocity"),
                                                    array("Variabilidad del cambio angular", "std_velocity"),
                                                    array("Mayor incremento angular entre muestras", "max_velocity"),
                                                    array("Mayor disminución angular entre muestras", "min_velocity")
                                                );

                                                foreach ($din_eval as $v) {
                                                ?>

                                                    <div class="variable-card">
                                                        <span><?php echo e($v[0]); ?></span>
                                                        <strong>
                                                            <?php
                                                            echo e(
                                                                numero(
                                                                    $row[$v[1]] ?? null,
                                                                    4
                                                                )
                                                            );
                                                            echo " °/muestra";
                                                            ?>
                                                        </strong>
                                                        <code><?php echo e($v[1]); ?></code>
                                                    </div>

                                                <?php } ?>

                                            </div>

                                            <div class="tech-note">
                                                Estas variables comparan el ángulo yaw entre
                                                muestras válidas consecutivas. Se muestran en
                                                <strong>grados por muestra (°/muestra)</strong>
                                                y no como velocidad angular en °/s.
                                            </div>

                                        </section>


                                        <section class="modal-section">

                                            <h4 class="modal-section-title">
                                                Magnitud y distribución
                                            </h4>

                                            <div class="variable-grid">

                                                <?php
                                                $mag_eval = array(
                                                    array("Pico absoluto", "peak_abs", 3, "°"),
                                                    array("|Media Δyaw|", "abs_mean_yaw", 3, "°"),
                                                    array("|Mediana Δyaw|", "abs_median_yaw", 3, "°"),
                                                    array("Índice acumulado", "movement_energy", 3, ""),
                                                    array("Muestras válidas", "samples", 0, "")
                                                );

                                                foreach ($mag_eval as $v) {
                                                ?>

                                                    <div class="variable-card">
                                                        <span><?php echo e($v[0]); ?></span>
                                                        <strong>
                                                            <?php
                                                            echo e(
                                                                numero(
                                                                    $row[$v[1]] ?? null,
                                                                    $v[2]
                                                                )
                                                            );
                                                            echo e($v[3]);
                                                            ?>
                                                        </strong>
                                                        <code><?php echo e($v[1]); ?></code>
                                                    </div>

                                                <?php } ?>

                                                <div class="variable-card">
                                                    <span>Δyaw negativo</span>
                                                    <strong>
                                                        <?php
                                                        echo e(
                                                            porcentaje01(
                                                                $row['pct_negative'] ?? null
                                                            )
                                                        );
                                                        ?>
                                                    </strong>
                                                    <code>pct_negative</code>
                                                </div>

                                                <div class="variable-card">
                                                    <span>Δyaw positivo</span>
                                                    <strong>
                                                        <?php
                                                        echo e(
                                                            porcentaje01(
                                                                $row['pct_positive'] ?? null
                                                            )
                                                        );
                                                        ?>
                                                    </strong>
                                                    <code>pct_positive</code>
                                                </div>

                                            </div>

                                        </section>


                                        <section class="modal-section">

                                            <h4 class="modal-section-title">
                                                Indicadores faciales
                                            </h4>

                                            <div class="variable-grid">

                                                <?php
                                                $fac_eval = array(
                                                    array("Variación facial máxima detectada", "facial_score", "facial"),
                                                    array("Reducción máxima de apertura ocular (parpadeo)", "blink_score", "parpadeo"),
                                                    array("Aumento máximo de apertura ocular", "eye_score", "ojos"),
                                                    array("Elevación relativa máxima de cejas", "eyebrow_score", "cejas"),
                                                    array("Aumento máximo de apertura de boca", "mouth_score", "boca")
                                                );

                                                foreach ($fac_eval as $v) {
                                                ?>

                                                    <div class="variable-card">

                                                        <div class="facial-variable-title">
                                                            <span class="facial-variable-icon">
                                                                <?php
                                                                echo iconoFacialSVG(
                                                                    $v[2] ?? ""
                                                                );
                                                                ?>
                                                            </span>

                                                            <span>
                                                                <?php echo e($v[0]); ?>
                                                            </span>
                                                        </div>

                                                        <strong>
                                                            <?php
                                                            echo e(
                                                                porcentajeFacial(
                                                                    $row[$v[1]] ?? null
                                                                )
                                                            );
                                                            ?>
                                                        </strong>
                                                        <code><?php echo e($v[1]); ?></code>
                                                    </div>

                                                <?php } ?>

                                            </div>

                                        </section>


                                    </div>

                                </div>

                            </div>


                            <!-- MODAL VALORACIÓN YA REALIZADA -->
                            <div
                                class="modal-overlay"
                                id="<?php echo e($modal_review_eval_id); ?>"
                                aria-hidden="true"
                            >

                                <div
                                    class="modal-card modal-review"
                                    role="dialog"
                                    aria-modal="true"
                                >

                                    <div class="modal-header">

                                        <div>
                                            <h3>Valoración profesional registrada</h3>

                                            <p>
                                                <?php echo e($row['session_id'] ?? "—"); ?>
                                                ·
                                                <?php echo e($row['trial_id'] ?? "—"); ?>
                                            </p>
                                        </div>

                                        <button
                                            type="button"
                                            class="modal-close"
                                            onclick="closeTrialModal('<?php echo e($modal_review_eval_id); ?>')"
                                            aria-label="Cerrar"
                                        >
                                            &times;
                                        </button>

                                    </div>


                                    <div class="modal-body">


                                        <section class="modal-video-section">

                                            <h4 class="modal-section-title">
                                                Evidencia audiovisual del ensayo
                                            </h4>

                                            <?php
                                            if (
                                                !empty(
                                                    $row['video_path']
                                                )
                                            ) {
                                            ?>

                                                <div class="modal-video-wrap">

                                                    <video
                                                        class="modal-video"
                                                        controls
                                                        preload="metadata"
                                                        src="<?php echo e($row['video_path']); ?>"
                                                    >
                                                        Tu navegador no soporta
                                                        la reproducción de video.
                                                    </video>

                                                </div>

                                                <div class="modal-video-caption">
                                                    Revise el comportamiento visible antes,
                                                    durante y después del estímulo. El video
                                                    constituye la evidencia principal para
                                                    contrastar la clasificación computacional
                                                    y la valoración profesional.
                                                </div>

                                            <?php
                                            } else {
                                            ?>

                                                <div class="modal-video-wrap">
                                                    <div class="modal-video-empty">
                                                        No hay video disponible para este ensayo.
                                                    </div>
                                                </div>

                                            <?php
                                            }
                                            ?>

                                        </section>


                                        <div class="review-readonly">

                                            <div class="review-readonly-row">
                                                <span>Calidad del ensayo</span>
                                                <div>
                                                    <?php
                                                    echo badgeRevision(
                                                        $row['review_status']
                                                        ?? 'pendiente'
                                                    );
                                                    ?>
                                                </div>
                                            </div>

                                            <div class="review-readonly-row">
                                                <span>Valoración conductual</span>
                                                <strong>
                                                    <?php
                                                    echo e(
                                                        textoEspecialista(
                                                            $estado_profesional
                                                        )
                                                    );
                                                    ?>
                                                </strong>
                                            </div>

                                            <div class="review-readonly-row">
                                                <span>Revisado por</span>
                                                <strong>
                                                    <?php
                                                    echo e(
                                                        $row['reviewed_by']
                                                        ?? "—"
                                                    );
                                                    ?>
                                                </strong>
                                            </div>

                                            <div class="review-readonly-row">
                                                <span>Fecha de revisión</span>
                                                <strong>
                                                    <?php
                                                    echo e(
                                                        fecha_larga(
                                                            $row['reviewed_at']
                                                            ?? null
                                                        )
                                                    );
                                                    ?>
                                                </strong>
                                            </div>

                                            <div class="review-readonly-row">
                                                <span>Observación</span>
                                                <div>
                                                    <?php
                                                    echo nl2br(
                                                        e(
                                                            $row['review_notes']
                                                            ?? "Sin observaciones"
                                                        )
                                                    );
                                                    ?>
                                                </div>
                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>


                        </td>

                    </tr>

                <?php
                    }
                } else {
                ?>

                    <tr>
                        <td colspan="5" data-label="">
                            <div class="empty-state">
                                Todavía no hay ensayos evaluados para este bebé.
                            </div>
                        </td>
                    </tr>

                <?php } ?>

                </tbody>

            </table>

        </div>

    </section>


    <p class="footer-note">

        Herramienta experimental de apoyo para la revisión de
        respuestas conductuales observables frente a estímulos sonoros.
        La clasificación computacional es preliminar y no sustituye
        la evaluación audiológica ni el criterio profesional.

    </p>

</div>


<?php if ($hay_graficas) { ?>

<script>

const professionalData = [
    <?php echo (int)$n_reacciono; ?>,
    <?php echo (int)$n_no_reacciono; ?>,
    <?php echo (int)$n_no_evaluable; ?>
];

const iaData = [
    <?php echo (int)$n_ia_observable; ?>,
    <?php echo (int)$n_ia_no_concluyente; ?>
];

const concordanceData = [
    <?php echo (int)$n_concuerda; ?>,
    <?php echo (int)$n_no_concuerda; ?>,
    <?php echo (int)$n_conc_no_evaluable; ?>
];

const sessionLabels =
    <?php
    echo json_encode(
        $labels,
        JSON_UNESCAPED_UNICODE
    );
    ?>;

const sessionReacted =
    <?php
    echo json_encode(
        $serie_reacciono,
        JSON_UNESCAPED_UNICODE
    );
    ?>;

const sessionNoReacted =
    <?php
    echo json_encode(
        $serie_no_reacciono,
        JSON_UNESCAPED_UNICODE
    );
    ?>;

const sessionNotEvaluable =
    <?php
    echo json_encode(
        $serie_no_evaluable,
        JSON_UNESCAPED_UNICODE
    );
    ?>;


/* ============================================================
   VALORACIÓN PROFESIONAL
   ============================================================ */

new Chart(
    document.getElementById(
        'chartProfesional'
    ),
    {

        type:
            'doughnut',

        data: {

            labels: [
                'Reaccionó',
                'No reaccionó',
                'No evaluable'
            ],

            datasets: [
                {
                    data:
                        professionalData,

                    backgroundColor: [
                        '#1f8f5f',
                        '#b86c00',
                        '#52616f'
                    ],

                    borderColor:
                        '#ffffff',

                    borderWidth:
                        4,

                    hoverOffset:
                        7
                }
            ]

        },

        options: {

            responsive:
                true,

            maintainAspectRatio:
                false,

            cutout:
                '68%',

            plugins: {

                legend: {
                    position:
                        'bottom',

                    labels: {
                        usePointStyle:
                            true,

                        boxWidth:
                            8,

                        font: {
                            size:
                                10
                        }
                    }
                }

            }

        }

    }
);


/* ============================================================
   CLASIFICACIÓN IA
   ============================================================ */

new Chart(
    document.getElementById(
        'chartIA'
    ),
    {

        type:
            'doughnut',

        data: {

            labels: [
                'Respuesta observable',
                'Respuesta no concluyente'
            ],

            datasets: [
                {
                    data:
                        iaData,

                    backgroundColor: [
                        '#246196',
                        '#b86c00'
                    ],

                    borderColor:
                        '#ffffff',

                    borderWidth:
                        4,

                    hoverOffset:
                        7
                }
            ]

        },

        options: {

            responsive:
                true,

            maintainAspectRatio:
                false,

            cutout:
                '68%',

            plugins: {

                legend: {
                    position:
                        'bottom',

                    labels: {
                        usePointStyle:
                            true,

                        boxWidth:
                            8,

                        font: {
                            size:
                                10
                        }
                    }
                }

            }

        }

    }
);


/* ============================================================
   CONCORDANCIA OPERACIONAL
   ============================================================ */

new Chart(
    document.getElementById(
        'chartConcordancia'
    ),
    {

        type:
            'bar',

        data: {

            labels: [
                'Coinciden',
                'No coinciden',
                'No evaluable'
            ],

            datasets: [
                {
                    label:
                        'Ensayos',

                    data:
                        concordanceData,

                    backgroundColor: [
                        '#1f8f5f',
                        '#c0392b',
                        '#52616f'
                    ],

                    borderRadius:
                        7
                }
            ]

        },

        options: {

            responsive:
                true,

            maintainAspectRatio:
                false,

            plugins: {

                legend: {
                    display:
                        false
                },

                tooltip: {

                    callbacks: {

                        label:
                            function(context) {

                                return (
                                    context.parsed.y
                                    + ' ensayo(s)'
                                );

                            }

                    }

                }

            },

            scales: {

                y: {

                    beginAtZero:
                        true,

                    ticks: {
                        precision:
                            0
                    }

                }

            }

        }

    }
);


/* ============================================================
   SEGUIMIENTO POR SESIÓN
   ============================================================ */

new Chart(
    document.getElementById(
        'chartSesiones'
    ),
    {

        type:
            'bar',

        data: {

            labels:
                sessionLabels,

            datasets: [

                {
                    label:
                        'Reaccionó',

                    data:
                        sessionReacted,

                    backgroundColor:
                        'rgba(31, 143, 95, 0.82)',

                    borderRadius:
                        6
                },

                {
                    label:
                        'No reaccionó',

                    data:
                        sessionNoReacted,

                    backgroundColor:
                        'rgba(184, 108, 0, 0.78)',

                    borderRadius:
                        6
                },

                {
                    label:
                        'No evaluable',

                    data:
                        sessionNotEvaluable,

                    backgroundColor:
                        'rgba(82, 97, 111, 0.70)',

                    borderRadius:
                        6
                }

            ]

        },

        options: {

            responsive:
                true,

            maintainAspectRatio:
                false,

            interaction: {
                mode:
                    'index',

                intersect:
                    false
            },

            plugins: {

                legend: {
                    position:
                        'bottom',

                    labels: {
                        usePointStyle:
                            true,

                        boxWidth:
                            8,

                        font: {
                            size:
                                10
                        }
                    }
                }

            },

            scales: {

                y: {

                    beginAtZero:
                        true,

                    ticks: {
                        precision:
                            0
                    }

                }

            }

        }

    }
);

</script>

<?php } ?>



<?php

$stmt_pendientes->close();
$stmt_evaluados->close();
$mysqli->close();

?>


<script>



function toggleEvaluatedTrials() {

    const body = document.getElementById('evaluatedTrialsBody');
    const button = document.getElementById('evaluatedToggle');

    if (!body || !button) {
        return;
    }

    const visible = body.style.display !== 'none';

    body.style.display = visible ? 'none' : 'block';
    button.textContent = visible
        ? 'Mostrar evaluados'
        : 'Ocultar evaluados';
}

function toggleGraphSummary() {

    const body =
        document.getElementById(
            'graphSummaryBody'
        );

    const button =
        document.getElementById(
            'graphToggle'
        );

    if (!body || !button) {
        return;
    }

    const hidden =
        body.classList.toggle(
            'hidden'
        );

    button.textContent =
        hidden
        ? 'Mostrar gráficas'
        : 'Ocultar gráficas';
}

function openTrialModal(id) {
    const modal = document.getElementById(id);

    if (!modal) {
        return;
    }

    modal.classList.add('active');
    modal.setAttribute('aria-hidden', 'false');
    document.body.classList.add('modal-open');
}

function closeTrialModal(id) {
    const modal = document.getElementById(id);

    if (!modal) {
        return;
    }

    modal.classList.remove('active');
    modal.setAttribute('aria-hidden', 'true');

    if (!document.querySelector('.modal-overlay.active')) {
        document.body.classList.remove('modal-open');
    }
}

document.addEventListener('click', function(event) {
    if (
        event.target.classList &&
        event.target.classList.contains('modal-overlay')
    ) {
        event.target.classList.remove('active');
        event.target.setAttribute('aria-hidden', 'true');

        if (!document.querySelector('.modal-overlay.active')) {
            document.body.classList.remove('modal-open');
        }
    }
});

document.addEventListener('keydown', function(event) {
    if (event.key !== 'Escape') {
        return;
    }

    document
        .querySelectorAll('.modal-overlay.active')
        .forEach(function(modal) {
            modal.classList.remove('active');
            modal.setAttribute('aria-hidden', 'true');
        });

    document.body.classList.remove('modal-open');
});
</script>



<script>
/* ============================================================
   EXPLICACIONES DE VARIABLES AL PASAR EL CURSOR
   ============================================================ */

document.addEventListener('DOMContentLoaded', function () {

    const variableHelp = {

        ai_response_status:
            'Categoría preliminar asignada por el Random Forest a partir del conjunto de variables del ensayo. No constituye un diagnóstico audiológico.',

        ml_confidence:
            'Grado con el que el modelo favorece la clase mostrada. No representa la probabilidad de que el bebé escuche ni una probabilidad diagnóstica.',

        prob_respuesta_conductual_observable:
            'Probabilidad computacional asignada por el modelo a la clase “Respuesta conductual observable”, cuando este valor fue almacenado para el ensayo.',

        prob_respuesta_no_concluyente:
            'Probabilidad computacional asignada por el modelo a la clase “Respuesta no concluyente”, cuando este valor fue almacenado para el ensayo.',

        baseline_yaw:
            'Orientación inicial de la cabeza utilizada como referencia para calcular los desplazamientos posteriores en el eje yaw.',

        mean_delta_yaw:
            'Desplazamiento promedio de la cabeza respecto de la posición inicial durante la ventana analizada.',

        median_delta_yaw:
            'Valor central de los desplazamientos respecto de la posición inicial. La mitad de las muestras queda por debajo y la otra mitad por encima.',

        std_delta_yaw:
            'Variabilidad de los desplazamientos de la cabeza respecto de la posición inicial. Un valor mayor indica mayor dispersión del movimiento.',

        min_delta_yaw:
            'Menor desplazamiento observado respecto de la posición inicial durante el ensayo.',

        max_delta_yaw:
            'Mayor desplazamiento observado respecto de la posición inicial durante el ensayo.',

        range_delta_yaw:
            'Diferencia entre el desplazamiento máximo y el mínimo. Resume la amplitud total del movimiento cefálico observado.',

        mean_velocity:
            'Promedio del cambio de Δyaw entre muestras válidas consecutivas. Se muestra en grados por muestra (°/muestra), no en grados por segundo.',

        std_velocity:
            'Indica cuánto varían los cambios de Δyaw entre una muestra válida y la siguiente. Se expresa en °/muestra y no corresponde a velocidad angular en °/s.',

        max_velocity:
            'Mayor aumento de Δyaw observado entre dos muestras válidas consecutivas. Se expresa en grados por muestra (°/muestra).',

        min_velocity:
            'Mayor disminución de Δyaw observada entre dos muestras válidas consecutivas. Se expresa en grados por muestra (°/muestra).',

        peak_abs:
            'Mayor magnitud absoluta del desplazamiento de la cabeza respecto de la posición inicial, independientemente del sentido.',

        pct_negative:
            'Proporción de muestras cuyo Δyaw fue negativo respecto de la posición inicial. El signo depende de la convención del eje usada por el sistema.',

        pct_positive:
            'Proporción de muestras cuyo Δyaw fue positivo respecto de la posición inicial. El signo no debe interpretarse automáticamente como oído derecho o izquierdo.',

        abs_mean_yaw:
            'Magnitud media del desplazamiento de la cabeza sin considerar el signo. Resume cuánto se alejó, en promedio, de la posición inicial.',

        abs_median_yaw:
            'Mediana de la magnitud del desplazamiento sin considerar el signo. Resume un valor central del alejamiento respecto de la posición inicial.',

        movement_energy:
            'Índice acumulado de movimiento calculado a partir de las magnitudes de desplazamiento durante el ensayo. No representa energía física ni se expresa en joules.',

        samples:
            'Número de muestras válidas utilizadas para calcular las características de movimiento del ensayo.',

        facial_score:
            'Porcentaje de la máxima variación facial relativa detectada respecto a la línea base del ensayo. Facilita la lectura del valor computacional; no constituye una escala clínica.',

        blink_score:
            'Porcentaje de la mayor reducción relativa de apertura ocular respecto a la línea base, asociada al parpadeo. No equivale a un porcentaje clínico de cierre del ojo.',

        eye_score:
            'Porcentaje del mayor aumento relativo de apertura ocular respecto a la línea base registrada antes del estímulo. No representa un porcentaje anatómico de apertura del ojo.',

        eyebrow_score:
            'Porcentaje de la mayor elevación relativa de las cejas respecto a la línea base facial del ensayo. Se utiliza como variable computacional y no como medida clínica.',

        mouth_score:
            'Porcentaje del mayor aumento relativo de apertura de boca respecto a la línea base facial registrada antes del estímulo. No significa que la boca esté abierta ese porcentaje.'
    };

    document
        .querySelectorAll('.variable-card')
        .forEach(function(card) {

            const code = card.querySelector('code');
            const value = card.querySelector('strong');

            if (!code || !value) {
                return;
            }

            const key = code.textContent.trim();
            const help = variableHelp[key];

            if (!help) {
                return;
            }

            value.classList.add('value-tooltip');
            value.setAttribute('data-tooltip', help);
            value.setAttribute('title', help);
            value.setAttribute('tabindex', '0');
            value.setAttribute(
                'aria-label',
                value.textContent.trim() + '. ' + help
            );
        });
});
</script>

</body>
</html>