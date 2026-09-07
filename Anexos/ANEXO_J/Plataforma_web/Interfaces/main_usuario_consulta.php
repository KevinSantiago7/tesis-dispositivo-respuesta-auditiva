<?php
header('Content-Type: text/html; charset=UTF-8');
session_start();

if (
    empty($_SESSION['autenticado'])
    || empty($_SESSION['rol'])
    || $_SESSION['rol'] !== 'padre'
) {
    header("Location: index.php");
    exit;
}

if (empty($_SESSION['baby_id'])) {
    die("No hay bebé asociado a esta cuenta.");
}

include("conexion.php");

$mysqli = new mysqli(
    $host,
    $user,
    $pw,
    $db
);

if ($mysqli->connect_error) {
    die("Error de conexión: " . $mysqli->connect_error);
}

$mysqli->set_charset("utf8mb4");

$baby_id = $_SESSION['baby_id'];

$baby_name =
    $_SESSION['baby_name']
    ?? $_SESSION['usuario']
    ?? 'Bebé';


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

function fecha_corta($fecha) {
    if (!$fecha) {
        return "Sin registro";
    }

    return date(
        "d/m/Y",
        strtotime($fecha)
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

function texto_lado($lado) {
    $lado = strtolower(
        trim((string)$lado)
    );

    if ($lado === "izquierda") {
        return "Izquierda";
    }

    if ($lado === "derecha") {
        return "Derecha";
    }

    if (
        $lado === "centro"
        || $lado === "center"
    ) {
        return "Centro";
    }

    return $lado !== ""
        ? ucfirst($lado)
        : "—";
}

function normalizar_estado_profesional($estado) {
    $estado = mb_strtolower(
        trim((string)$estado),
        'UTF-8'
    );

    if (
        $estado === "reacciono"
        || $estado === "reaccionó"
        || $estado === "correcto"
        || $estado === "direccion_incorrecta"
        || $estado === "dirección_incorrecta"
    ) {
        return "reacciono";
    }

    if (
        $estado === "no_reacciono"
        || $estado === "no reacciono"
        || $estado === "no_reaccionó"
        || $estado === "no reaccionó"
        || $estado === "sin_respuesta"
        || $estado === "sin respuesta"
    ) {
        return "no_reacciono";
    }

    if (
        $estado === "no_evaluable"
        || $estado === "no evaluable"
    ) {
        return "no_evaluable";
    }

    return "";
}

function texto_estado_profesional($estado) {
    $estado =
        normalizar_estado_profesional(
            $estado
        );

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

function badge_estado_profesional($estado) {
    $estado =
        normalizar_estado_profesional(
            $estado
        );

    if ($estado === "reacciono") {
        return (
            "<span class='badge badge-success'>"
            . "Reaccionó"
            . "</span>"
        );
    }

    if ($estado === "no_reacciono") {
        return (
            "<span class='badge badge-warning'>"
            . "No reaccionó"
            . "</span>"
        );
    }

    if ($estado === "no_evaluable") {
        return (
            "<span class='badge badge-neutral'>"
            . "No evaluable"
            . "</span>"
        );
    }

    return (
        "<span class='badge badge-neutral'>"
        . "Sin valoración"
        . "</span>"
    );
}

function formatear_latencia($valor) {
    if (
        $valor === null
        || $valor === ''
        || !is_numeric($valor)
        || (float)$valor < 0
    ) {
        return "No determinada";
    }

    return (
        number_format(
            (float)$valor,
            2,
            ',',
            '.'
        )
        . " s"
    );
}


/* ============================================================
   COMPATIBILIDAD CON LA COLUMNA NUEVA DEL ESPECIALISTA
   ============================================================ */

$columna_nueva =
    $mysqli->query(
        "SHOW COLUMNS FROM trials "
        . "LIKE 'specialist_response_status'"
    );

$tiene_estado_nuevo =
    $columna_nueva
    && $columna_nueva->num_rows > 0;

/*
 * Nunca se construye con datos del usuario.
 * Solo elegimos entre dos expresiones conocidas.
 */
$estado_expr = $tiene_estado_nuevo
    ? "COALESCE(NULLIF(specialist_response_status, ''), specialist_status)"
    : "specialist_status";


/* ============================================================
   RESUMEN GENERAL
   SOLO ENSAYOS VÁLIDOS Y REVISADOS
   ============================================================ */

$sql_resumen = "
    SELECT
        COUNT(DISTINCT session_id) AS sesiones,
        COUNT(*) AS trials,
        MAX(age_months) AS edad,
        MIN(created_at) AS primera,
        MAX(created_at) AS ultima

    FROM trials

    WHERE baby_id = ?
      AND review_status = 'valido'
      AND {$estado_expr} IS NOT NULL
      AND {$estado_expr} <> ''
";

$stmt = $mysqli->prepare(
    $sql_resumen
);

$stmt->bind_param(
    "s",
    $baby_id
);

$stmt->execute();

$resumen =
    $stmt
    ->get_result()
    ->fetch_assoc();

$stmt->close();

$sesiones =
    (int)($resumen['sesiones'] ?? 0);

$total_trials =
    (int)($resumen['trials'] ?? 0);

$edad =
    $resumen['edad'] !== null
    ? round(
        (float)$resumen['edad'],
        1
    )
    : null;

$primera =
    $resumen['primera']
    ?? null;

$ultima =
    $resumen['ultima']
    ?? null;


/* ============================================================
   DISTRIBUCIÓN GLOBAL DE LA VALORACIÓN PROFESIONAL
   ============================================================ */

$sql_distribucion = "
    SELECT

        COALESCE(
            SUM(
                CASE
                    WHEN {$estado_expr} IN (
                        'reacciono',
                        'correcto',
                        'direccion_incorrecta'
                    )
                    THEN 1
                    ELSE 0
                END
            ),
            0
        ) AS reacciono,

        COALESCE(
            SUM(
                CASE
                    WHEN {$estado_expr} IN (
                        'no_reacciono',
                        'sin_respuesta'
                    )
                    THEN 1
                    ELSE 0
                END
            ),
            0
        ) AS no_reacciono,

        COALESCE(
            SUM(
                CASE
                    WHEN {$estado_expr} = 'no_evaluable'
                    THEN 1
                    ELSE 0
                END
            ),
            0
        ) AS no_evaluable

    FROM trials

    WHERE baby_id = ?
      AND review_status = 'valido'
      AND {$estado_expr} IS NOT NULL
      AND {$estado_expr} <> ''
";

$stmt = $mysqli->prepare(
    $sql_distribucion
);

$stmt->bind_param(
    "s",
    $baby_id
);

$stmt->execute();

$distribucion =
    $stmt
    ->get_result()
    ->fetch_assoc();

$stmt->close();

$n_reacciono =
    (int)(
        $distribucion['reacciono']
        ?? 0
    );

$n_no_reacciono =
    (int)(
        $distribucion['no_reacciono']
        ?? 0
    );

$n_no_evaluable =
    (int)(
        $distribucion['no_evaluable']
        ?? 0
    );


/* ============================================================
   SEGUIMIENTO POR SESIÓN
   ============================================================ */

$labels = array();
$serie_reacciono = array();
$serie_no_reacciono = array();
$serie_no_evaluable = array();

$sql_sesiones = "
    SELECT
        session_id,
        MIN(created_at) AS fecha_sesion,

        COALESCE(
            SUM(
                CASE
                    WHEN {$estado_expr} IN (
                        'reacciono',
                        'correcto',
                        'direccion_incorrecta'
                    )
                    THEN 1
                    ELSE 0
                END
            ),
            0
        ) AS reacciono,

        COALESCE(
            SUM(
                CASE
                    WHEN {$estado_expr} IN (
                        'no_reacciono',
                        'sin_respuesta'
                    )
                    THEN 1
                    ELSE 0
                END
            ),
            0
        ) AS no_reacciono,

        COALESCE(
            SUM(
                CASE
                    WHEN {$estado_expr} = 'no_evaluable'
                    THEN 1
                    ELSE 0
                END
            ),
            0
        ) AS no_evaluable

    FROM trials

    WHERE baby_id = ?
      AND review_status = 'valido'
      AND {$estado_expr} IS NOT NULL
      AND {$estado_expr} <> ''

    GROUP BY session_id

    ORDER BY
        MIN(created_at),
        session_id
";

$stmt = $mysqli->prepare(
    $sql_sesiones
);

$stmt->bind_param(
    "s",
    $baby_id
);

$stmt->execute();

$result =
    $stmt->get_result();

$indice_sesion = 1;

while (
    $row =
    $result->fetch_assoc()
) {
    $labels[] =
        "Sesión "
        . $indice_sesion;

    $serie_reacciono[] =
        (int)$row['reacciono'];

    $serie_no_reacciono[] =
        (int)$row['no_reacciono'];

    $serie_no_evaluable[] =
        (int)$row['no_evaluable'];

    $indice_sesion++;
}

$stmt->close();

$hay_graficas =
    (
        $n_reacciono
        + $n_no_reacciono
        + $n_no_evaluable
    ) > 0;


/* ============================================================
   LISTA DE REGISTROS REVISADOS
   ============================================================ */

$sql_trials = "
    SELECT
        session_id,
        trial_id,
        stimulus_name,
        label_expected,
        reaction_time_s,
        created_at,
        {$estado_expr} AS specialist_response_status

    FROM trials

    WHERE baby_id = ?
      AND review_status = 'valido'
      AND {$estado_expr} IS NOT NULL
      AND {$estado_expr} <> ''

    ORDER BY created_at DESC

    LIMIT 30
";

$stmt_trials = $mysqli->prepare(
    $sql_trials
);

$stmt_trials->bind_param(
    "s",
    $baby_id
);

$stmt_trials->execute();

$trials =
    $stmt_trials->get_result();

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
    Seguimiento de
    <?php echo e($baby_name); ?>
</title>

<?php if ($hay_graficas) { ?>

<script
    src="https://cdn.jsdelivr.net/npm/chart.js"
></script>

<?php } ?>

<style>

:root {
    --bg: #f3f6fa;
    --surface: #ffffff;
    --surface-soft: #f8fafc;

    --primary: #163b63;
    --primary-light: #246196;

    --text: #1f2933;
    --muted: #66788a;
    --border: #dbe3ec;

    --success: #1f8f5f;
    --success-bg: #e8f7ef;

    --warning: #b86c00;
    --warning-bg: #fff4df;

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
            var(--bg) 55%,
            #eef3f8 100%
        );

    color: var(--text);
}

.page {
    width:
        min(
            1120px,
            calc(100% - 32px)
        );

    margin: 0 auto;

    padding:
        30px 0 42px;
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

    box-shadow:
        0 12px 24px
        rgba(22, 59, 99, 0.22);
}

.brand small {
    display: block;

    color: var(--muted);

    font-size: 13px;
}

.brand strong {
    display: block;

    color: var(--primary);

    font-size: 20px;
}

.btn {
    display: inline-flex;

    align-items: center;

    justify-content: center;

    padding:
        11px 16px;

    border-radius: 999px;

    background:
        var(--primary);

    color: white;

    text-decoration: none;

    font-weight: 700;

    font-size: 14px;
}

.btn:hover {
    background: #0f2d4d;
}


/* ============================================================
   HERO
   ============================================================ */

.hero {
    position: relative;

    overflow: hidden;

    display: grid;

    grid-template-columns:
        1.2fr 0.8fr;

    gap: 24px;

    align-items: center;

    padding: 30px;

    margin-bottom: 22px;

    border-radius:
        var(--radius-xl);

    background:
        linear-gradient(
            135deg,
            rgba(22, 59, 99, 0.96),
            rgba(36, 97, 150, 0.94)
        );

    color: white;

    box-shadow:
        var(--shadow);
}

.hero::after {
    content: "";

    position: absolute;

    width: 250px;
    height: 250px;

    right: -80px;
    top: -120px;

    border-radius: 50%;

    background:
        rgba(255, 255, 255, 0.10);
}

.hero-main,
.follow-summary {
    position: relative;

    z-index: 1;
}

.hero h1 {
    margin:
        0 0 10px;

    font-size:
        clamp(
            28px,
            4vw,
            42px
        );
}

.hero p {
    margin: 0;

    color:
        rgba(
            255,
            255,
            255,
            0.86
        );

    line-height: 1.6;
}

.follow-summary {
    padding: 18px;

    border:
        1px solid
        rgba(
            255,
            255,
            255,
            0.16
        );

    border-radius: 20px;

    background:
        rgba(
            255,
            255,
            255,
            0.12
        );
}

.follow-summary span {
    display: block;

    margin-bottom: 7px;

    color:
        rgba(
            255,
            255,
            255,
            0.78
        );

    font-size: 13px;
}

.follow-summary strong {
    display: block;

    margin-bottom: 8px;

    font-size: 25px;
}

.follow-summary p {
    font-size: 13px;
}


/* ============================================================
   TARJETAS
   ============================================================ */

.cards {
    display: grid;

    grid-template-columns:
        repeat(
            4,
            minmax(
                160px,
                1fr
            )
        );

    gap: 16px;

    margin-bottom: 22px;
}

.card,
.panel,
.scope-note,
.table-panel,
.empty-panel {
    background:
        rgba(
            255,
            255,
            255,
            0.96
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
        0 0 10px;

    color:
        var(--muted);

    font-size: 12px;

    text-transform:
        uppercase;

    letter-spacing:
        0.06em;
}

.card p {
    margin: 0;

    color:
        var(--primary);

    font-size: 26px;

    font-weight: 800;
}

.card small {
    display: block;

    margin-top: 6px;

    color:
        var(--muted);

    font-size: 12px;
}


/* ============================================================
   NOTA DE ALCANCE
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

    font-size: 13px;

    line-height: 1.55;
}

.scope-note strong {
    color:
        var(--primary);
}


/* ============================================================
   GRÁFICAS
   ============================================================ */

.layout {
    display: grid;

    grid-template-columns:
        1.15fr 0.85fr;

    gap: 18px;

    margin-bottom: 22px;
}

.panel,
.empty-panel {
    padding: 22px;

    border-radius:
        var(--radius-xl);
}

.panel h3,
.empty-panel h3 {
    margin: 0;

    color:
        var(--primary);

    font-size: 18px;
}

.panel > p,
.empty-panel > p {
    margin:
        6px 0 0;

    color:
        var(--muted);

    font-size: 14px;

    line-height: 1.5;
}

.chart-box {
    position: relative;

    height: 310px;

    margin-top: 16px;
}


/* ============================================================
   TABLA
   ============================================================ */

.table-panel {
    overflow: hidden;

    border-radius:
        var(--radius-xl);
}

.table-title {
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
}

.table-title p {
    margin:
        5px 0 0;

    color:
        var(--muted);

    font-size: 14px;
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
        14px 16px;

    border-bottom:
        1px solid
        #edf1f5;

    text-align: left;

    vertical-align:
        middle;

    font-size: 13px;
}

th {
    background:
        #f7fafd;

    color:
        var(--muted);

    text-transform:
        uppercase;

    letter-spacing:
        0.055em;

    font-size: 11px;
}

tr:hover td {
    background:
        #fbfdff;
}

.badge {
    display: inline-flex;

    align-items: center;

    justify-content: center;

    padding:
        7px 10px;

    border-radius:
        999px;

    font-size: 12px;

    font-weight: 800;

    white-space: nowrap;
}

.badge-success {
    background:
        var(--success-bg);

    color:
        var(--success);
}

.badge-warning {
    background:
        var(--warning-bg);

    color:
        var(--warning);
}

.badge-neutral {
    background:
        var(--neutral-bg);

    color:
        var(--neutral);
}

.footer-note {
    margin-top: 18px;

    color:
        var(--muted);

    text-align: center;

    font-size: 12px;

    line-height: 1.5;
}


/* ============================================================
   RESPONSIVE
   ============================================================ */

@media (max-width: 900px) {

    .hero,
    .layout {
        grid-template-columns:
            1fr;
    }

    .cards {
        grid-template-columns:
            repeat(
                2,
                minmax(
                    160px,
                    1fr
                )
            );
    }
}

@media (max-width: 720px) {

    .page {
        width:
            min(
                100% - 20px,
                1120px
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
            1fr;
    }

    table,
    tbody,
    tr,
    td {
        display: block;

        width: 100%;
    }

    thead {
        display: none;
    }

    tr {
        margin: 12px;

        width:
            calc(
                100% - 24px
            );

        border:
            1px solid
            var(--border);

        border-radius:
            15px;

        overflow: hidden;
    }

    td::before {
        display: block;

        margin-bottom: 5px;

        color:
            var(--primary);

        font-size: 10px;

        font-weight: 800;

        text-transform:
            uppercase;

        letter-spacing:
            0.05em;
    }

    td:nth-child(1)::before {
        content: "Sesión";
    }

    td:nth-child(2)::before {
        content: "Fecha";
    }

    td:nth-child(3)::before {
        content: "Estímulo";
    }

    td:nth-child(4)::before {
        content: "Lado de emisión";
    }

    td:nth-child(5)::before {
        content: "Valoración profesional";
    }

    td:nth-child(6)::before {
        content: "Latencia";
    }
}

</style>

</head>

<body>

<div class="page">


    <header class="topbar">

        <div class="brand">

            <div class="brand-icon">
                VA
            </div>

            <div>

                <small>
                    Seguimiento para padres y tutores
                </small>

                <strong>
                    Registros del bebé
                </strong>

            </div>

        </div>

        <a
            class="btn"
            href="logout.php"
        >
            Cerrar sesión
        </a>

    </header>


    <section class="hero">

        <div class="hero-main">

            <h1>
                <?php
                echo e(
                    $baby_name
                );
                ?>
            </h1>

            <p>
                Este panel presenta los ensayos del dispositivo que
                ya fueron revisados y validados por el especialista.
                Permite consultar el seguimiento por sesiones y los
                registros individuales asociados al bebé.
            </p>

        </div>


        <aside class="follow-summary">

            <span>
                Seguimiento disponible
            </span>

            <strong>
                <?php
                echo e(
                    $total_trials
                );
                ?>
                ensayo<?php echo $total_trials === 1 ? '' : 's'; ?>
                revisado<?php echo $total_trials === 1 ? '' : 's'; ?>
            </strong>

            <p>
                Última sesión registrada:
                <?php
                echo e(
                    fecha_larga(
                        $ultima
                    )
                );
                ?>
            </p>

        </aside>

    </section>


    <section class="cards">

        <article class="card">

            <h3>
                Edad registrada
            </h3>

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

            <h3>
                Sesiones
            </h3>

            <p>
                <?php
                echo e(
                    $sesiones
                );
                ?>
            </p>

            <small>
                Con ensayos revisados
            </small>

        </article>


        <article class="card">

            <h3>
                Ensayos revisados
            </h3>

            <p>
                <?php
                echo e(
                    $total_trials
                );
                ?>
            </p>

            <small>
                Registros válidos
            </small>

        </article>


        <article class="card">

            <h3>
                Última sesión
            </h3>

            <p style="font-size:22px;">
                <?php
                echo e(
                    fecha_corta(
                        $ultima
                    )
                );
                ?>
            </p>

            <small>
                Registro más reciente
            </small>

        </article>

    </section>


    <section class="scope-note">

        <strong>
            Información para seguimiento:
        </strong>

        esta vista muestra únicamente registros que fueron
        revisados por el especialista. Las categorías
        <strong>
            Reaccionó, No reaccionó y No evaluable
        </strong>
        describen la valoración conductual del ensayo.
        La información apoya el seguimiento y la comunicación
        con el profesional; no constituye por sí sola un
        diagnóstico audiológico.

    </section>


    <?php if ($hay_graficas) { ?>

        <section class="layout">


            <article class="panel">

                <h3>
                    Seguimiento por sesión
                </h3>

                <p>
                    Comparación del número de ensayos valorados por
                    el especialista en cada sesión registrada.
                </p>

                <div class="chart-box">

                    <canvas
                        id="seguimiento"
                    ></canvas>

                </div>

            </article>


            <article class="panel">

                <h3>
                    Distribución de valoraciones
                </h3>

                <p>
                    Resumen de las valoraciones profesionales de los
                    ensayos válidos disponibles.
                </p>

                <div class="chart-box">

                    <canvas
                        id="distribucion"
                    ></canvas>

                </div>

            </article>


        </section>

    <?php } else { ?>

        <section class="empty-panel">

            <h3>
                Seguimiento aún no disponible
            </h3>

            <p>
                Las gráficas aparecerán cuando el especialista haya
                revisado y validado al menos un ensayo.
            </p>

        </section>

    <?php } ?>


    <section class="table-panel">

        <div class="table-title">

            <h3>
                Registros revisados
            </h3>

            <p>
                Lista de los ensayos válidos revisados por el especialista,
                ordenados desde el registro más reciente.
            </p>

        </div>


        <div class="table-wrap">

            <table>

                <thead>

                    <tr>

                        <th>
                            Sesión
                        </th>

                        <th>
                            Fecha
                        </th>

                        <th>
                            Estímulo
                        </th>

                        <th>
                            Lado de emisión
                        </th>

                        <th>
                            Valoración profesional
                        </th>

                        <th>
                            Latencia
                        </th>

                    </tr>

                </thead>


                <tbody>

                <?php
                if (
                    $trials
                    && $trials->num_rows > 0
                ) {
                ?>

                    <?php
                    while (
                        $row =
                        $trials->fetch_assoc()
                    ) {
                    ?>

                        <tr>

                            <td>
                                <?php
                                echo e(
                                    $row['session_id']
                                    ?? "—"
                                );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo e(
                                    fecha_larga(
                                        $row['created_at']
                                        ?? null
                                    )
                                );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo e(
                                    $row['stimulus_name']
                                    ?? "—"
                                );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo e(
                                    texto_lado(
                                        $row['label_expected']
                                        ?? ""
                                    )
                                );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo badge_estado_profesional(
                                    $row[
                                        'specialist_response_status'
                                    ]
                                    ?? ""
                                );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo e(
                                    formatear_latencia(
                                        $row[
                                            'reaction_time_s'
                                        ]
                                        ?? null
                                    )
                                );
                                ?>
                            </td>

                        </tr>

                    <?php
                    }
                    ?>

                <?php
                } else {
                ?>

                    <tr>

                        <td colspan="6">
                            No hay registros revisados disponibles
                            para este bebé.
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

        Plataforma de apoyo para el seguimiento de respuestas
        conductuales observables frente a estímulos sonoros.
        La interpretación clínica corresponde al profesional
        encargado.

    </p>


</div>


<?php if ($hay_graficas) { ?>

<script>

const labels =
    <?php
    echo json_encode(
        $labels,
        JSON_UNESCAPED_UNICODE
    );
    ?>;

const reacciono =
    <?php
    echo json_encode(
        $serie_reacciono,
        JSON_UNESCAPED_UNICODE
    );
    ?>;

const noReacciono =
    <?php
    echo json_encode(
        $serie_no_reacciono,
        JSON_UNESCAPED_UNICODE
    );
    ?>;

const noEvaluable =
    <?php
    echo json_encode(
        $serie_no_evaluable,
        JSON_UNESCAPED_UNICODE
    );
    ?>;


/* ============================================================
   GRÁFICA 1 — SEGUIMIENTO POR SESIÓN
   ============================================================ */

new Chart(
    document.getElementById(
        'seguimiento'
    ),
    {

        type: 'bar',

        data: {

            labels: labels,

            datasets: [

                {
                    label:
                        'Reaccionó',

                    data:
                        reacciono,

                    backgroundColor:
                        'rgba(31, 143, 95, 0.82)',

                    borderRadius:
                        6
                },

                {
                    label:
                        'No reaccionó',

                    data:
                        noReacciono,

                    backgroundColor:
                        'rgba(184, 108, 0, 0.78)',

                    borderRadius:
                        6
                },

                {
                    label:
                        'No evaluable',

                    data:
                        noEvaluable,

                    backgroundColor:
                        'rgba(82, 97, 111, 0.70)',

                    borderRadius:
                        6
                }

            ]

        },

        options: {

            responsive: true,

            maintainAspectRatio:
                false,

            interaction: {
                mode: 'index',
                intersect: false
            },

            plugins: {

                legend: {
                    position:
                        'bottom'
                },

                tooltip: {

                    callbacks: {

                        label:
                            function(context) {

                                return (
                                    context.dataset.label
                                    + ': '
                                    + context.parsed.y
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
   GRÁFICA 2 — DISTRIBUCIÓN GLOBAL
   ============================================================ */

const distData = [

    <?php
    echo (int)$n_reacciono;
    ?>,

    <?php
    echo (int)$n_no_reacciono;
    ?>,

    <?php
    echo (int)$n_no_evaluable;
    ?>

];

new Chart(
    document.getElementById(
        'distribucion'
    ),
    {

        type: 'doughnut',

        data: {

            labels: [
                'Reaccionó',
                'No reaccionó',
                'No evaluable'
            ],

            datasets: [

                {

                    data:
                        distData,

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
                                    distData.reduce(
                                        (a, b) =>
                                            a + b,
                                        0
                                    );

                                const value =
                                    context.parsed;

                                const pct =
                                    total > 0
                                    ? (
                                        (
                                            value
                                            / total
                                        )
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

</script>

<?php } ?>


<?php

$stmt_trials->close();
$mysqli->close();

?>

</body>
</html>