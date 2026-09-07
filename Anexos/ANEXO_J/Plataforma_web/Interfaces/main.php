<?php
header('Content-Type: text/html; charset=UTF-8');
session_start();

if (empty($_SESSION['autenticado'])) {
    header("Location: index.php");
    exit;
}

/*
 * Si por alguna razón un padre llega a main.php,
 * lo enviamos a su vista específica.
 */
if (
    isset($_SESSION['rol'])
    && $_SESSION['rol'] === 'padre'
) {
    header("Location: main_usuario_consulta.php");
    exit;
}

include("conexion.php");

$mysqli = new mysqli(
    $host,
    $user,
    $pw,
    $db
);

if ($mysqli->connect_error) {
    die(
        "Error de conexión: "
        . $mysqli->connect_error
    );
}

$mysqli->set_charset("utf8mb4");


/* ============================================================
   VISIBILIDAD DE BEBÉS Y TRIALS PARA EL ESPECIALISTA
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


$filtro_bebes_especialista = "

NOT EXISTS (
    SELECT 1
    FROM specialist_baby_visibility sbv
    WHERE sbv.baby_id = trials.baby_id
      AND sbv.visible_especialista = 0
)

AND

NOT EXISTS (
    SELECT 1
    FROM specialist_trial_visibility stv
    WHERE stv.trial_id = trials.id
      AND stv.visible_especialista = 0
)

";


/* ============================================================
   FUNCIONES
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

    return date(
        "d/m/Y H:i",
        strtotime($fecha)
    );
}

function fecha_corta($fecha) {
    if (!$fecha) {
        return "—";
    }

    return date(
        "d/m/Y",
        strtotime($fecha)
    );
}

function numero(
    $valor,
    $decimales = 1,
    $vacio = "—"
) {
    if (
        $valor === null
        || $valor === ''
        || !is_numeric($valor)
    ) {
        return $vacio;
    }

    return number_format(
        (float)$valor,
        $decimales,
        ',',
        '.'
    );
}

function porcentaje01(
    $valor,
    $decimales = 1,
    $vacio = "—"
) {
    if (
        $valor === null
        || $valor === ''
        || !is_numeric($valor)
    ) {
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

function texto_sexo($valor) {
    $valor = strtoupper(
        trim((string)$valor)
    );

    if ($valor === "M") {
        return "Masculino";
    }

    if ($valor === "F") {
        return "Femenino";
    }

    return "—";
}

function table_column_exists(
    $conn,
    $table,
    $column
) {
    $table_esc =
        $conn->real_escape_string(
            $table
        );

    $column_esc =
        $conn->real_escape_string(
            $column
        );

    $res =
        $conn->query(
            "SHOW COLUMNS FROM `{$table_esc}` "
            . "LIKE '{$column_esc}'"
        );

    return (
        $res
        && $res->num_rows > 0
    );
}


/* ============================================================
   COMPATIBILIDAD DE ESQUEMA
   ============================================================ */

$tiene_specialist_response_status =
    table_column_exists(
        $mysqli,
        "trials",
        "specialist_response_status"
    );

$tiene_sex_biological =
    table_column_exists(
        $mysqli,
        "trials",
        "sex_biological"
    );

$tiene_model_used =
    table_column_exists(
        $mysqli,
        "trials",
        "model_used"
    );

$estado_prof_expr =
    $tiene_specialist_response_status
    ? (
        "COALESCE("
        . "NULLIF(specialist_response_status, ''), "
        . "specialist_status"
        . ")"
    )
    : "specialist_status";

$sexo_expr =
    $tiene_sex_biological
    ? "MAX(sex_biological)"
    : "NULL";

$model_filter =
    $tiene_model_used
    ? (
        "(ai_response_status IS NOT NULL "
        . "OR model_used IS NOT NULL)"
    )
    : "ai_response_status IS NOT NULL";


/*
 * Expresión compatible para clasificación IA.
 * Los estados antiguos se muestran solo como compatibilidad
 * y NO como evaluación de dirección.
 */
$ia_expr = "
CASE
    WHEN ai_response_status IN (
        'respuesta_conductual_observable',
        'reacciono'
    )
    THEN 'respuesta_conductual_observable'

    WHEN ai_response_status IN (
        'respuesta_no_concluyente',
        'no_reacciono'
    )
    THEN 'respuesta_no_concluyente'

    WHEN (
        ai_response_status IS NULL
        OR ai_response_status = ''
    )
    AND trial_status IN (
        'correcto',
        'direccion_incorrecta',
        'reacciono'
    )
    THEN 'respuesta_conductual_observable'

    WHEN (
        ai_response_status IS NULL
        OR ai_response_status = ''
    )
    AND trial_status IN (
        'sin_respuesta',
        'no_reacciono'
    )
    THEN 'respuesta_no_concluyente'

    ELSE NULL
END
";


/* ============================================================
   RESUMEN GLOBAL
   ============================================================ */

$sql_resumen = "
SELECT
    COUNT(DISTINCT baby_id) AS bebes,
    COUNT(DISTINCT session_id) AS sesiones,
    COUNT(*) AS ensayos,

    COALESCE(
        SUM(
            CASE
                WHEN review_status IS NULL
                  OR review_status = 'pendiente'
                THEN 1 ELSE 0
            END
        ),
        0
    ) AS pendientes,

    COALESCE(
        SUM(
            CASE
                WHEN review_status = 'valido'
                THEN 1 ELSE 0
            END
        ),
        0
    ) AS validos,

    COALESCE(
        SUM(
            CASE
                WHEN review_status = 'descartado'
                THEN 1 ELSE 0
            END
        ),
        0
    ) AS descartados,

    MAX(created_at) AS ultimo_registro

FROM trials
WHERE {$filtro_bebes_especialista}
";

$resumen_result =
    $mysqli->query(
        $sql_resumen
    );

if (!$resumen_result) {
    die(
        "Error consultando resumen global: "
        . $mysqli->error
    );
}

$resumen =
    $resumen_result->fetch_assoc();

$total_bebes =
    (int)($resumen['bebes'] ?? 0);

$total_sesiones =
    (int)($resumen['sesiones'] ?? 0);

$total_ensayos =
    (int)($resumen['ensayos'] ?? 0);

$total_pendientes =
    (int)($resumen['pendientes'] ?? 0);

$total_validos =
    (int)($resumen['validos'] ?? 0);

$total_descartados =
    (int)($resumen['descartados'] ?? 0);

$ultimo_registro =
    $resumen['ultimo_registro']
    ?? null;


/* ============================================================
   DISTRIBUCIÓN DE CLASIFICACIÓN IA
   ============================================================ */

$sql_ia = "
SELECT
    COALESCE(
        SUM(
            CASE
                WHEN ({$ia_expr})
                    = 'respuesta_conductual_observable'
                THEN 1 ELSE 0
            END
        ),
        0
    ) AS observable,

    COALESCE(
        SUM(
            CASE
                WHEN ({$ia_expr})
                    = 'respuesta_no_concluyente'
                THEN 1 ELSE 0
            END
        ),
        0
    ) AS no_concluyente

FROM trials
WHERE {$model_filter}
  AND {$filtro_bebes_especialista}
";

$ia_result =
    $mysqli->query(
        $sql_ia
    );

if (!$ia_result) {
    die(
        "Error consultando clasificación IA: "
        . $mysqli->error
    );
}

$ia_data =
    $ia_result->fetch_assoc();

$ia_observable =
    (int)(
        $ia_data['observable']
        ?? 0
    );

$ia_no_concluyente =
    (int)(
        $ia_data['no_concluyente']
        ?? 0
    );


/* ============================================================
   DISTRIBUCIÓN DE VALORACIÓN PROFESIONAL
   SOLO ENSAYOS VÁLIDOS
   ============================================================ */

$sql_prof = "
SELECT

    COALESCE(
        SUM(
            CASE
                WHEN {$estado_prof_expr} IN (
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
                WHEN {$estado_prof_expr} IN (
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
                WHEN {$estado_prof_expr}
                    = 'no_evaluable'
                THEN 1 ELSE 0
            END
        ),
        0
    ) AS no_evaluable

FROM trials

WHERE review_status = 'valido'
  AND {$filtro_bebes_especialista}
  AND {$estado_prof_expr} IS NOT NULL
  AND {$estado_prof_expr} <> ''
";

$prof_result =
    $mysqli->query(
        $sql_prof
    );

if (!$prof_result) {
    die(
        "Error consultando valoración profesional: "
        . $mysqli->error
    );
}

$prof =
    $prof_result->fetch_assoc();

$prof_reacciono =
    (int)($prof['reacciono'] ?? 0);

$prof_no_reacciono =
    (int)($prof['no_reacciono'] ?? 0);

$prof_no_evaluable =
    (int)($prof['no_evaluable'] ?? 0);


/* ============================================================
   LISTA POR BEBÉ
   ============================================================ */

$sql_bebes = "
SELECT
    baby_id,

    MAX(
        NULLIF(
            baby_name,
            ''
        )
    ) AS baby_name,

    MAX(age_months) AS age_months,

    {$sexo_expr} AS sex_biological,

    COUNT(
        DISTINCT session_id
    ) AS sesiones,

    COUNT(*) AS ensayos,

    COALESCE(
        SUM(
            CASE
                WHEN review_status IS NULL
                  OR review_status = 'pendiente'
                THEN 1 ELSE 0
            END
        ),
        0
    ) AS pendientes,

    COALESCE(
        SUM(
            CASE
                WHEN review_status = 'valido'
                THEN 1 ELSE 0
            END
        ),
        0
    ) AS validos,

    COALESCE(
        SUM(
            CASE
                WHEN review_status = 'descartado'
                THEN 1 ELSE 0
            END
        ),
        0
    ) AS descartados,

    MAX(created_at) AS ultima_fecha

FROM trials

WHERE {$filtro_bebes_especialista}

GROUP BY baby_id

ORDER BY
    pendientes DESC,
    ultima_fecha DESC,
    baby_id ASC
";

$bebes_result =
    $mysqli->query(
        $sql_bebes
    );

if (!$bebes_result) {
    die(
        "Error consultando bebés: "
        . $mysqli->error
    );
}


/* ============================================================
   DATOS PARA GRÁFICA DE SESIONES RECIENTES
   ============================================================ */

$chart_session_labels = array();
$chart_session_total = array();
$chart_session_pending = array();

$sql_chart_sessions = "
SELECT
    session_id,
    COUNT(*) AS ensayos,

    COALESCE(
        SUM(
            CASE
                WHEN review_status IS NULL
                  OR review_status = 'pendiente'
                THEN 1 ELSE 0
            END
        ),
        0
    ) AS pendientes,

    MAX(created_at) AS ultima_fecha

FROM trials

WHERE {$filtro_bebes_especialista}

GROUP BY session_id

ORDER BY
    MAX(created_at) DESC

LIMIT 8
";

$chart_sessions_result =
    $mysqli->query(
        $sql_chart_sessions
    );

if ($chart_sessions_result) {

    $temp_sessions = array();

    while (
        $row =
        $chart_sessions_result->fetch_assoc()
    ) {
        $temp_sessions[] = $row;
    }

    /*
     * Se invierte para que la gráfica
     * se lea cronológicamente de izquierda a derecha.
     */
    $temp_sessions =
        array_reverse(
            $temp_sessions
        );

    foreach (
        $temp_sessions
        as $row
    ) {
        $chart_session_labels[] =
            $row['session_id'];

        $chart_session_total[] =
            (int)$row['ensayos'];

        $chart_session_pending[] =
            (int)$row['pendientes'];
    }
}

$hay_datos_ia =
    (
        $ia_observable
        + $ia_no_concluyente
    ) > 0;

$hay_datos_revision =
    (
        $total_pendientes
        + $total_validos
        + $total_descartados
    ) > 0;

$hay_sesiones_chart =
    count(
        $chart_session_labels
    ) > 0;

?>
<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">

<meta
    http-equiv="Content-Type"
    content="text/html; charset=UTF-8"
>

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<title>
    Panel de revisión del especialista
</title>

<script
    src="https://cdn.jsdelivr.net/npm/chart.js"
></script>

<style>

:root {
    --bg: #f3f6fa;
    --surface: #ffffff;
    --surface-soft: #f8fafc;

    --primary: #163b63;
    --primary-light: #246196;
    --secondary: #60758a;

    --text: #1f2933;
    --muted: #66788a;
    --border: #dbe3ec;

    --success: #1f8f5f;
    --success-bg: #e8f7ef;

    --warning: #b86c00;
    --warning-bg: #fff4df;

    --danger: #c0392b;
    --danger-bg: #fdecea;

    --neutral: #52616f;
    --neutral-bg: #eef2f6;

    --info-bg: #eaf3fb;

    --shadow:
        0 14px 35px
        rgba(22, 59, 99, 0.08);

    --radius-xl: 24px;
    --radius-lg: 18px;
}

* {
    box-sizing: border-box;
}

body {
    margin: 0;

    font-family:
        "Segoe UI",
        Roboto,
        Arial,
        sans-serif;

    background:
        radial-gradient(
            circle at top left,
            rgba(36, 97, 150, 0.12),
            transparent 35%
        ),
        linear-gradient(
            180deg,
            #f7faff 0%,
            var(--bg) 50%,
            #eef3f8 100%
        );

    color: var(--text);
}

.page {
    width:
        min(
            1400px,
            calc(100% - 32px)
        );

    margin:
        0 auto;

    padding:
        30px 0 44px;
}


/* ============================================================
   CABECERA
   ============================================================ */

.topbar {
    display: flex;

    justify-content:
        space-between;

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
    width: 50px;
    height: 50px;

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

    box-shadow:
        0 12px 24px
        rgba(22, 59, 99, 0.22);
}

.brand small {
    display: block;

    color:
        var(--muted);

    font-size:
        13px;
}

.brand strong {
    display: block;

    color:
        var(--primary);

    font-size:
        20px;
}

.btn {
    display:
        inline-flex;

    align-items:
        center;

    justify-content:
        center;

    padding:
        11px 16px;

    border-radius:
        999px;

    background:
        var(--primary);

    color:
        white;

    text-decoration:
        none;

    font-weight:
        700;

    font-size:
        14px;
}

.btn:hover {
    background:
        #0f2d4d;
}


/* ============================================================
   HERO
   ============================================================ */

.hero {
    position: relative;

    overflow: hidden;

    display: grid;

    grid-template-columns:
        1.35fr 0.65fr;

    gap: 24px;

    align-items: center;

    margin-bottom: 22px;

    padding: 30px;

    border-radius:
        var(--radius-xl);

    background:
        linear-gradient(
            135deg,
            rgba(22, 59, 99, 0.97),
            rgba(36, 97, 150, 0.94)
        );

    color: white;

    box-shadow:
        var(--shadow);
}

.hero::after {
    content: "";

    position: absolute;

    width: 280px;
    height: 280px;

    right: -80px;
    top: -130px;

    border-radius: 50%;

    background:
        rgba(
            255,
            255,
            255,
            0.10
        );
}

.hero-main,
.hero-review {
    position: relative;

    z-index: 1;
}

.eyebrow {
    display:
        inline-flex;

    padding:
        7px 12px;

    margin-bottom:
        14px;

    border-radius:
        999px;

    background:
        rgba(
            255,
            255,
            255,
            0.13
        );

    font-size:
        13px;
}

.hero h1 {
    margin:
        0 0 10px;

    font-size:
        clamp(
            30px,
            4vw,
            44px
        );

    letter-spacing:
        -0.7px;
}

.hero p {
    margin: 0;

    max-width:
        780px;

    line-height:
        1.65;

    color:
        rgba(
            255,
            255,
            255,
            0.87
        );
}

.hero-review {
    padding: 18px;

    border:
        1px solid
        rgba(
            255,
            255,
            255,
            0.16
        );

    border-radius:
        20px;

    background:
        rgba(
            255,
            255,
            255,
            0.12
        );
}

.hero-review span {
    display: block;

    margin-bottom:
        6px;

    color:
        rgba(
            255,
            255,
            255,
            0.78
        );

    font-size:
        13px;
}

.hero-review strong {
    display: block;

    margin-bottom:
        8px;

    font-size:
        28px;
}


/* ============================================================
   TARJETAS
   ============================================================ */

.cards {
    display: grid;

    grid-template-columns:
        repeat(
            6,
            minmax(
                140px,
                1fr
            )
        );

    gap: 15px;

    margin-bottom:
        22px;
}

.card,
.panel,
.table-panel,
.scope-note {
    background:
        rgba(
            255,
            255,
            255,
            0.97
        );

    border:
        1px solid
        var(--border);

    box-shadow:
        var(--shadow);
}

.card {
    padding: 18px;

    border-radius:
        var(--radius-lg);
}

.card h3 {
    margin:
        0 0 9px;

    color:
        var(--muted);

    font-size:
        11px;

    font-weight:
        800;

    text-transform:
        uppercase;

    letter-spacing:
        0.06em;
}

.card p {
    margin: 0;

    color:
        var(--primary);

    font-size:
        26px;

    font-weight:
        800;
}

.card small {
    display: block;

    margin-top:
        7px;

    color:
        var(--muted);

    font-size:
        11px;
}


/* ============================================================
   ALCANCE
   ============================================================ */

.scope-note {
    margin-bottom: 22px;

    padding:
        16px 18px;

    border-radius:
        var(--radius-lg);

    background:
        var(--info-bg);

    color:
        #315776;

    font-size:
        13px;

    line-height:
        1.55;
}

.scope-note strong {
    color:
        var(--primary);
}


/* ============================================================
   GRÁFICAS
   ============================================================ */

.graph-grid {
    display: grid;

    grid-template-columns:
        repeat(
            3,
            minmax(
                0,
                1fr
            )
        );

    gap: 18px;

    margin-bottom:
        22px;
}

.panel {
    padding: 20px;

    border-radius:
        var(--radius-xl);
}

.panel h3 {
    margin: 0;

    color:
        var(--primary);

    font-size:
        17px;
}

.panel p {
    margin:
        6px 0 0;

    color:
        var(--muted);

    font-size:
        12px;

    line-height:
        1.5;
}

.chart-box {
    position: relative;

    height: 285px;

    margin-top:
        12px;
}


/* ============================================================
   TABLAS
   ============================================================ */

.table-panel {
    overflow: hidden;

    margin-bottom:
        22px;

    border-radius:
        var(--radius-xl);
}

.table-title {
    display: flex;

    justify-content:
        space-between;

    align-items:
        center;

    gap: 15px;

    padding:
        20px 22px;

    border-bottom:
        1px solid
        var(--border);
}

.table-title h3 {
    margin: 0;

    color:
        var(--primary);

    font-size:
        18px;
}

.table-title p {
    margin:
        5px 0 0;

    color:
        var(--muted);

    font-size:
        13px;
}

.search-box {
    width: 260px;

    max-width: 100%;

    padding:
        10px 12px;

    border:
        1px solid
        var(--border);

    border-radius:
        12px;

    background:
        white;

    font: inherit;

    color:
        var(--text);
}

.table-wrap {
    overflow-x: auto;
}

table {
    width: 100%;

    border-collapse:
        collapse;
}

th,
td {
    padding:
        13px 14px;

    border-bottom:
        1px solid
        #edf1f5;

    text-align: left;

    vertical-align:
        middle;

    font-size:
        12px;
}

th {
    background:
        #f7fafd;

    color:
        var(--muted);

    font-size:
        10px;

    font-weight:
        800;

    text-transform:
        uppercase;

    letter-spacing:
        0.055em;
}

tbody tr:hover td {
    background:
        #fbfdff;
}

.badge {
    display:
        inline-flex;

    align-items:
        center;

    justify-content:
        center;

    padding:
        6px 9px;

    border-radius:
        999px;

    font-size:
        11px;

    font-weight:
        800;

    white-space:
        nowrap;
}

.badge-success {
    color:
        var(--success);

    background:
        var(--success-bg);
}

.badge-warning {
    color:
        var(--warning);

    background:
        var(--warning-bg);
}

.badge-danger {
    color:
        var(--danger);

    background:
        var(--danger-bg);
}

.badge-neutral {
    color:
        var(--neutral);

    background:
        var(--neutral-bg);
}

.open-btn {
    display:
        inline-flex;

    align-items:
        center;

    justify-content:
        center;

    min-width:
        82px;

    padding:
        8px 11px;

    border-radius:
        999px;

    background:
        var(--primary);

    color:
        white;

    text-decoration:
        none;

    font-size:
        11px;

    font-weight:
        800;
}

.open-btn:hover {
    background:
        #0f2d4d;
}

.muted {
    color:
        var(--muted);

    font-size:
        11px;
}

.baby-main {
    color:
        var(--primary);

    font-weight:
        800;
}

.baby-id {
    margin-top:
        3px;

    color:
        var(--muted);

    font-family:
        Consolas,
        "Courier New",
        monospace;

    font-size:
        10px;
}

.progress-review {
    width:
        100px;

    height:
        7px;

    overflow:
        hidden;

    margin-top:
        5px;

    border-radius:
        999px;

    background:
        #e8edf2;
}

.progress-review > span {
    display:
        block;

    height:
        100%;

    border-radius:
        999px;

    background:
        var(--primary-light);
}

.footer-note {
    margin-top:
        18px;

    color:
        var(--muted);

    text-align:
        center;

    font-size:
        12px;

    line-height:
        1.5;
}


/* ============================================================
   RESPONSIVE
   ============================================================ */

@media (max-width: 1150px) {

    .cards {
        grid-template-columns:
            repeat(
                3,
                minmax(
                    150px,
                    1fr
                )
            );
    }

    .graph-grid {
        grid-template-columns:
            1fr;
    }

    .hero {
        grid-template-columns:
            1fr;
    }
}

@media (max-width: 760px) {

    .page {
        width:
            min(
                100% - 20px,
                1400px
            );

        padding-top:
            18px;
    }

    .topbar {
        flex-direction:
            column;

        align-items:
            stretch;
    }

    .cards {
        grid-template-columns:
            repeat(
                2,
                minmax(
                    0,
                    1fr
                )
            );
    }

    .table-title {
        flex-direction:
            column;

        align-items:
            stretch;
    }

    .search-box {
        width:
            100%;
    }
}

@media (max-width: 520px) {

    .cards {
        grid-template-columns:
            1fr;
    }

    .hero {
        padding:
            22px;
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

            <div>

                <small>
                    Plataforma de respuestas conductuales
                </small>

                <strong>
                    Panel de revisión del especialista
                </strong>

            </div>

        </div>

        <div style="display:flex; gap:9px; flex-wrap:wrap;">

            <?php
            if (
                strtolower(
                    trim(
                        (string)(
                            $_SESSION['rol']
                            ?? ''
                        )
                    )
                ) === 'admin'
            ) {
            ?>

                <a
                    class="btn"
                    href="admin.php"
                    style="background:#ffffff; color:var(--primary); border:1px solid var(--border);"
                >
                    Administración
                </a>

            <?php } ?>

            <a
                class="btn"
                href="logout.php"
            >
                Cerrar sesión
            </a>

        </div>

    </header>


    <section class="hero">

        <div class="hero-main">

            <div class="eyebrow">
                Bebés · sesiones · ensayos · seguimiento
            </div>

            <h1>
                Panel de revisión del especialista
            </h1>

            <p>
                Esta vista consolida los datos recibidos desde el dispositivo,
                organiza los registros por bebé y sesión, permite identificar
                ensayos pendientes de revisión y facilita el acceso al análisis
                detallado de la clasificación computacional, la evidencia
                observacional y el registro audiovisual.
            </p>

        </div>


        <aside class="hero-review">

            <span>
                Trabajo pendiente
            </span>

            <strong>
                <?php
                echo e(
                    $total_pendientes
                );
                ?>
                ensayo<?php
                echo (
                    $total_pendientes === 1
                    ? ''
                    : 's'
                );
                ?>
            </strong>

            <p>
                Último registro:
                <?php
                echo e(
                    fecha_larga(
                        $ultimo_registro
                    )
                );
                ?>
            </p>

        </aside>

    </section>


    <section class="cards">

        <article class="card">
            <h3>Bebés registrados</h3>
            <p><?php echo e($total_bebes); ?></p>
            <small>Participantes con ensayos</small>
        </article>

        <article class="card">
            <h3>Sesiones</h3>
            <p><?php echo e($total_sesiones); ?></p>
            <small>Jornadas registradas</small>
        </article>

        <article class="card">
            <h3>Ensayos</h3>
            <p><?php echo e($total_ensayos); ?></p>
            <small>Registros almacenados</small>
        </article>

        <article class="card">
            <h3>Pendientes</h3>
            <p><?php echo e($total_pendientes); ?></p>
            <small>Requieren revisión</small>
        </article>

        <article class="card">
            <h3>Válidos</h3>
            <p><?php echo e($total_validos); ?></p>
            <small>Revisados e incluidos</small>
        </article>

        <article class="card">
            <h3>Descartados</h3>
            <p><?php echo e($total_descartados); ?></p>
            <small>Conservados para trazabilidad</small>
        </article>

    </section>


    <section class="scope-note">

        <strong>Alcance:</strong>

        la plataforma organiza información recibida del dispositivo
        para su consulta, seguimiento y análisis posterior.
        La clasificación del Random Forest corresponde a una
        <strong>salida computacional preliminar</strong>.
        La valoración profesional se registra de forma independiente
        y la plataforma no emite por sí sola un diagnóstico audiológico.

    </section>


    <section class="graph-grid">


        <article class="panel">

            <h3>
                Clasificación preliminar del Random Forest
            </h3>

            <p>
                Distribución de las categorías computacionales
                registradas en los ensayos.
            </p>

            <div class="chart-box">
                <canvas id="chartIA"></canvas>
            </div>

        </article>


        <article class="panel">

            <h3>
                Estado de revisión
            </h3>

            <p>
                Distribución de ensayos pendientes,
                válidos y descartados.
            </p>

            <div class="chart-box">
                <canvas id="chartRevision"></canvas>
            </div>

        </article>


        <article class="panel">

            <h3>
                Actividad por sesión
            </h3>

            <p>
                Ensayos registrados y pendientes en las
                sesiones más recientes.
            </p>

            <div class="chart-box">
                <canvas id="chartSessions"></canvas>
            </div>

        </article>


    </section>


    <!-- ======================================================
         LISTA POR BEBÉ
         ====================================================== -->

    <section class="table-panel">

        <div class="table-title">

            <div>

                <h3>
                    Bebés registrados
                </h3>

                <p>
                    Resumen individual para acceder al historial
                    completo de sesiones y ensayos.
                </p>

            </div>

            <input
                type="search"
                id="searchBabies"
                class="search-box"
                placeholder="Buscar bebé o código..."
                autocomplete="off"
            >

        </div>


        <div class="table-wrap">

            <table id="babiesTable">

                <thead>

                    <tr>
                        <th>Bebé</th>
                        <th>Edad</th>
                        <th>Sexo</th>
                        <th>Último registro</th>
                        <th>Detalle</th>
                    </tr>

                </thead>


                <tbody>

                <?php
                if (
                    $bebes_result
                    && $bebes_result->num_rows > 0
                ) {
                ?>

                    <?php
                    while (
                        $row =
                        $bebes_result->fetch_assoc()
                    ) {

                        $b_ensayos =
                            (int)$row['ensayos'];

                        $b_validos =
                            (int)$row['validos'];

                        $b_descartados =
                            (int)$row['descartados'];

                        $b_revisados =
                            $b_validos
                            + $b_descartados;

                        $pct_revision =
                            $b_ensayos > 0
                            ? round(
                                (
                                    $b_revisados
                                    / $b_ensayos
                                )
                                * 100,
                                1
                            )
                            : 0;

                        $nombre =
                            trim(
                                (string)(
                                    $row['baby_name']
                                    ?? ''
                                )
                            );

                        if ($nombre === '') {
                            $nombre =
                                $row['baby_id'];
                        }
                    ?>

                        <tr>

                            <td>

                                <div class="baby-main">
                                    <?php
                                    echo e(
                                        $nombre
                                    );
                                    ?>
                                </div>

                                <div class="baby-id">
                                    <?php
                                    echo e(
                                        $row['baby_id']
                                    );
                                    ?>
                                </div>

                            </td>

                            <td>
                                <?php
                                echo e(
                                    numero(
                                        $row['age_months']
                                        ?? null,
                                        1
                                    )
                                );
                                ?>
                                <?php
                                echo (
                                    $row['age_months'] !== null
                                    ? " meses"
                                    : ""
                                );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo e(
                                    texto_sexo(
                                        $row[
                                            'sex_biological'
                                        ]
                                        ?? null
                                    )
                                );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo e(
                                    fecha_larga(
                                        $row['ultima_fecha']
                                        ?? null
                                    )
                                );
                                ?>
                            </td>

                            <td>
                                <a
                                    class="open-btn"
                                    href="ver_bebe.php?baby_id=<?php
                                    echo urlencode(
                                        $row['baby_id']
                                    );
                                    ?>"
                                >
                                    Revisar
                                </a>
                            </td>

                        </tr>

                    <?php
                    }
                    ?>

                <?php
                } else {
                ?>

                    <tr>
                        <td colspan="5">
                            No hay bebés con ensayos registrados.
                        </td>
                    </tr>

                <?php
                }
                ?>

                </tbody>

            </table>

        </div>

    </section>


    <p class="footer-note">

        Plataforma web para recepción, almacenamiento,
        visualización y análisis de los datos generados por
        el dispositivo. La clasificación computacional es
        preliminar y la interpretación profesional se conserva
        como un proceso independiente.

    </p>


</div>


<script>

/* ============================================================
   FILTROS DE TABLAS
   ============================================================ */

function attachTableFilter(
    inputId,
    tableId
) {
    const input =
        document.getElementById(
            inputId
        );

    const table =
        document.getElementById(
            tableId
        );

    if (!input || !table) {
        return;
    }

    input.addEventListener(
        'input',
        function() {

            const query =
                input.value
                .toLowerCase()
                .trim();

            const rows =
                table
                .querySelectorAll(
                    'tbody tr'
                );

            rows.forEach(
                function(row) {

                    const text =
                        row.innerText
                        .toLowerCase();

                    row.style.display =
                        text.includes(query)
                        ? ''
                        : 'none';

                }
            );

        }
    );
}

attachTableFilter(
    'searchBabies',
    'babiesTable'
);


/* ============================================================
   GRÁFICA 1 — RANDOM FOREST
   ============================================================ */

const iaData = [
    <?php echo (int)$ia_observable; ?>,
    <?php echo (int)$ia_no_concluyente; ?>
];

new Chart(
    document.getElementById(
        'chartIA'
    ),
    {

        type:
            'doughnut',

        data: {

            labels: [
                'Respuesta conductual observable',
                'Respuesta no concluyente'
            ],

            datasets: [
                {
                    data:
                        iaData,

                    backgroundColor: [
                        '#1f8f5f',
                        '#b86c00'
                    ],

                    borderColor:
                        '#ffffff',

                    borderWidth:
                        4,

                    hoverOffset:
                        8
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
                        'bottom'
                },

                tooltip: {

                    callbacks: {

                        label:
                            function(context) {

                                const total =
                                    iaData.reduce(
                                        (a, b) =>
                                            a + b,
                                        0
                                    );

                                const value =
                                    context.parsed;

                                const pct =
                                    total > 0
                                    ? (
                                        value
                                        / total
                                        * 100
                                    ).toFixed(1)
                                    : 0;

                                return (
                                    context.label
                                    + ': '
                                    + value
                                    + ' ('
                                    + pct
                                    + '%)'
                                );

                            }

                    }

                }

            }

        }

    }
);


/* ============================================================
   GRÁFICA 2 — ESTADO DE REVISIÓN
   ============================================================ */

const reviewData = [
    <?php echo (int)$total_pendientes; ?>,
    <?php echo (int)$total_validos; ?>,
    <?php echo (int)$total_descartados; ?>
];

new Chart(
    document.getElementById(
        'chartRevision'
    ),
    {

        type:
            'doughnut',

        data: {

            labels: [
                'Pendientes',
                'Válidos',
                'Descartados'
            ],

            datasets: [
                {
                    data:
                        reviewData,

                    backgroundColor: [
                        '#b86c00',
                        '#1f8f5f',
                        '#c0392b'
                    ],

                    borderColor:
                        '#ffffff',

                    borderWidth:
                        4,

                    hoverOffset:
                        8
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
                        'bottom'
                }

            }

        }

    }
);


/* ============================================================
   GRÁFICA 3 — SESIONES RECIENTES
   ============================================================ */

const sessionLabels =
    <?php
    echo json_encode(
        $chart_session_labels,
        JSON_UNESCAPED_UNICODE
    );
    ?>;

const sessionTotal =
    <?php
    echo json_encode(
        $chart_session_total,
        JSON_UNESCAPED_UNICODE
    );
    ?>;

const sessionPending =
    <?php
    echo json_encode(
        $chart_session_pending,
        JSON_UNESCAPED_UNICODE
    );
    ?>;

new Chart(
    document.getElementById(
        'chartSessions'
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
                        'Ensayos registrados',

                    data:
                        sessionTotal,

                    backgroundColor:
                        'rgba(36, 97, 150, 0.80)',

                    borderRadius:
                        6
                },

                {
                    label:
                        'Pendientes de revisión',

                    data:
                        sessionPending,

                    backgroundColor:
                        'rgba(184, 108, 0, 0.78)',

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
                        'bottom'
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


<?php
$mysqli->close();
?>

</body>
</html>