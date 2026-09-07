<?php
mysqli_report(MYSQLI_REPORT_OFF);

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json; charset=UTF-8');

/* ============================================================
   CONEXIÓN
   ============================================================ */

$config = require __DIR__ . "/config.php";

$conn = new mysqli(
    $config["host"],
    $config["user"],
    $config["pw"],
    $config["db"]
);

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(array(
        "ok" => false,
        "step" => "conexion",
        "error" => $conn->connect_error
    ), JSON_UNESCAPED_UNICODE);
    exit;
}

$conn->set_charset("utf8mb4");


/* ============================================================
   FUNCIONES AUXILIARES
   ============================================================ */

function get_string($data, $key, $default = "") {
    if (!array_key_exists($key, $data) || $data[$key] === null) {
        return $default;
    }

    return trim((string)$data[$key]);
}

function get_int($data, $key, $default = 0) {
    if (!array_key_exists($key, $data) || $data[$key] === null || $data[$key] === "") {
        return $default;
    }

    return is_numeric($data[$key]) ? (int)$data[$key] : $default;
}

function get_float($data, $key, $default = 0.0) {
    if (!array_key_exists($key, $data) || $data[$key] === null || $data[$key] === "") {
        return $default;
    }

    return is_numeric($data[$key]) ? (float)$data[$key] : $default;
}

/*
 * Para variables técnicas conviene conservar NULL cuando no llegaron.
 * No es metodológicamente correcto reemplazar automáticamente un dato
 * faltante por 0, porque 0 sí puede ser un valor real.
 */
function get_nullable_float($data, $key, $default = null) {
    if (!array_key_exists($key, $data) || $data[$key] === null || $data[$key] === "") {
        return $default;
    }

    return is_numeric($data[$key]) ? (float)$data[$key] : $default;
}

function get_nullable_int($data, $key, $default = null) {
    if (!array_key_exists($key, $data) || $data[$key] === null || $data[$key] === "") {
        return $default;
    }

    return is_numeric($data[$key]) ? (int)$data[$key] : $default;
}

function table_exists($conn, $table) {
    $table = $conn->real_escape_string($table);
    $res = $conn->query("SHOW TABLES LIKE '{$table}'");
    return $res && $res->num_rows > 0;
}

function normalize_ai_status($value) {
    $value = mb_strtolower(trim((string)$value), 'UTF-8');

    $map_reacciono = array(
        "reacciono",
        "reaccionó",
        "respuesta_conductual_observable",
        "correcto",
        "direccion_incorrecta",
        "dirección_incorrecta"
    );

    $map_no = array(
        "no_reacciono",
        "no reacciono",
        "no_reaccionó",
        "no reaccionó",
        "respuesta_no_concluyente",
        "sin_respuesta",
        "sin respuesta"
    );

    if (in_array($value, $map_reacciono, true)) {
        return "reacciono";
    }

    if (in_array($value, $map_no, true)) {
        return "no_reacciono";
    }

    if ($value === "no_evaluable" || $value === "no evaluable") {
        return "no_evaluable";
    }

    return "";
}


/* ============================================================
   LEER JSON
   ============================================================ */

$raw = file_get_contents("php://input");
$data = json_decode($raw, true);

if (!$data || !is_array($data)) {
    http_response_code(400);
    echo json_encode(array(
        "ok" => false,
        "step" => "json_decode",
        "error" => "No se recibió un JSON válido"
    ), JSON_UNESCAPED_UNICODE);
    exit;
}


/* ============================================================
   IDENTIFICACIÓN Y CONTEXTO
   ============================================================ */

$baby_id = get_string($data, "baby_id");
$baby_name = get_string($data, "baby_name", $baby_id);

$session_id = get_string($data, "session_id");
$trial_id = get_string($data, "trial_id");

$age_months = get_nullable_float($data, "age_months", null);
$sex_biological = get_string($data, "sex_biological", "");


/* ============================================================
   INFORMACIÓN DEL ESTÍMULO
   ============================================================ */

$stimulus_name = get_string($data, "stimulus_name");
$stimulus_type = get_string($data, "stimulus_type", "");

$stimulus_frequency_hz = get_nullable_float(
    $data,
    "stimulus_frequency_hz",
    null
);

$stimulus_duration_s = get_nullable_float(
    $data,
    "stimulus_duration_s",
    null
);

$stimulus_output_level = get_nullable_float(
    $data,
    "stimulus_output_level",
    null
);

/*
 * "label_expected" se conserva solo por compatibilidad.
 * En el enfoque actual representa el lado de emisión del estímulo,
 * NO el oído evaluado y NO una respuesta esperada.
 */
$label_expected = get_string(
    $data,
    "label",
    get_string($data, "label_expected", "")
);

$detected_direction = get_string(
    $data,
    "detected_direction",
    "no_aplica"
);


/* ============================================================
   CAMPOS LEGACY / COMPATIBILIDAD
   ============================================================ */

$trial_status = get_string($data, "trial_status", "");
$response_class = get_int($data, "response_class", 0);


/* ============================================================
   INFORMACIÓN TEMPORAL
   ============================================================ */

$reaction_time_s = get_nullable_float(
    $data,
    "reaction_time_s",
    null
);

/*
 * En el código de adquisición, -1 significa que no se obtuvo una
 * latencia inicial confiable. Para la base de datos se conserva
 * como NULL y la web mostrará "No determinada".
 */
if ($reaction_time_s !== null && $reaction_time_s < 0) {
    $reaction_time_s = null;
}


/* ============================================================
   23 VARIABLES DEL RANDOM FOREST FINAL
   ============================================================ */

/* ---------- Movimiento cefálico ---------- */

$baseline_yaw = get_nullable_float(
    $data,
    "baseline_yaw",
    null
);

$mean_delta_yaw = get_nullable_float(
    $data,
    "mean_delta_yaw",
    null
);

$median_delta_yaw = get_nullable_float(
    $data,
    "median_delta_yaw",
    null
);

$std_delta_yaw = get_nullable_float(
    $data,
    "std_delta_yaw",
    null
);

$min_delta_yaw = get_nullable_float(
    $data,
    "min_delta_yaw",
    null
);

$max_delta_yaw = get_nullable_float(
    $data,
    "max_delta_yaw",
    null
);

$range_delta_yaw = get_nullable_float(
    $data,
    "range_delta_yaw",
    null
);


/* ---------- Dinámica / variación entre muestras ---------- */

$mean_velocity = get_nullable_float(
    $data,
    "mean_velocity",
    null
);

$std_velocity = get_nullable_float(
    $data,
    "std_velocity",
    null
);

$max_velocity = get_nullable_float(
    $data,
    "max_velocity",
    null
);

$min_velocity = get_nullable_float(
    $data,
    "min_velocity",
    null
);


/* ---------- Magnitud y distribución ---------- */

$peak_abs = get_nullable_float(
    $data,
    "peak_abs",
    null
);

$pct_negative = get_nullable_float(
    $data,
    "pct_negative",
    null
);

$pct_positive = get_nullable_float(
    $data,
    "pct_positive",
    null
);

$abs_mean_yaw = get_nullable_float(
    $data,
    "abs_mean_yaw",
    null
);

$abs_median_yaw = get_nullable_float(
    $data,
    "abs_median_yaw",
    null
);

$movement_energy = get_nullable_float(
    $data,
    "movement_energy",
    null
);

$samples = get_nullable_int(
    $data,
    "samples",
    null
);


/* ---------- Respuesta facial ---------- */

$facial_score = get_nullable_float(
    $data,
    "facial_score",
    null
);

$blink_score = get_nullable_float(
    $data,
    "blink_score",
    null
);

$eye_score = get_nullable_float(
    $data,
    "eye_score",
    null
);

$eyebrow_score = get_nullable_float(
    $data,
    "eyebrow_score",
    null
);

$mouth_score = get_nullable_float(
    $data,
    "mouth_score",
    null
);


/* ============================================================
   EVIDENCIA OBSERVACIONAL
   ============================================================ */

$response_observed = get_int(
    $data,
    "response_observed",
    0
);

$head_response_detected = get_int(
    $data,
    "head_response_detected",
    0
);

$facial_response_detected = get_int(
    $data,
    "facial_response_detected",
    0
);

$multi_response_detected = get_int(
    $data,
    "multi_response_detected",
    (
        $head_response_detected === 1 &&
        $facial_response_detected === 1
    ) ? 1 : 0
);

$facial_features_available = get_int(
    $data,
    "facial_features_available",
    (
        $facial_score !== null ||
        $blink_score !== null ||
        $eye_score !== null ||
        $eyebrow_score !== null ||
        $mouth_score !== null
    ) ? 1 : 0
);

$facial_gesture_type = get_string(
    $data,
    "facial_gesture_type",
    ""
);


/*
 * MUY IMPORTANTE:
 * response_observed NO se sobrescribe usando la salida del Random Forest.
 * Esto permite detectar y mostrar desacuerdos entre:
 * - evidencia observacional
 * - clasificación IA
 * - valoración del especialista
 */


/* ============================================================
   TIPO DE RESPUESTA OBSERVACIONAL
   ============================================================ */

$response_type = get_string(
    $data,
    "response_type",
    ""
);

if ($response_type === "") {
    if (
        $head_response_detected === 1 &&
        $facial_response_detected === 1
    ) {
        $response_type = "cabeza_y_gesto_facial";

    } elseif ($head_response_detected === 1) {
        $response_type = "cabeza";

    } elseif ($facial_response_detected === 1) {
        $response_type = "gesto_facial";

    } else {
        $response_type = "ninguno";
    }
}


/* ============================================================
   SALIDA DEL RANDOM FOREST FINAL
   ============================================================ */

$ml_prediction = get_int(
    $data,
    "ml_prediction",
    0
);

$ml_confidence = get_nullable_float(
    $data,
    "ml_confidence",
    null
);

/*
 * Se aceptan ambos nombres para mantener compatibilidad con
 * distintas versiones del predictor/send_trial.
 */
$prob_respuesta_no_concluyente = get_nullable_float(
    $data,
    "prob_respuesta_no_concluyente",
    get_nullable_float($data, "prob_no_reacciono", null)
);

$prob_respuesta_conductual_observable = get_nullable_float(
    $data,
    "prob_respuesta_conductual_observable",
    get_nullable_float($data, "prob_reacciono", null)
);

$model_used = get_string(
    $data,
    "model_used",
    "random_forest_respuesta_conductual"
);

$ai_response_status = normalize_ai_status(
    get_string($data, "ai_response_status", "")
);


/* ============================================================
   COMPLETAR SALIDA IA SI FALTA
   ============================================================ */

if ($ai_response_status === "") {
    if ($ml_prediction === 1) {
        $ai_response_status = "reacciono";
    } elseif ($ml_prediction === 0) {
        $ai_response_status = "no_reacciono";
    } else {
        $ai_response_status = normalize_ai_status($trial_status);
    }
}

if ($ai_response_status === "") {
    $ai_response_status = "no_evaluable";
}

/*
 * Si llegaron las dos probabilidades, normalizarlas para evitar
 * inconsistencias pequeñas de redondeo.
 */
if (
    $prob_respuesta_no_concluyente !== null &&
    $prob_respuesta_conductual_observable !== null
) {
    $total_prob =
        $prob_respuesta_no_concluyente +
        $prob_respuesta_conductual_observable;

    if ($total_prob > 0) {
        $prob_respuesta_no_concluyente =
            $prob_respuesta_no_concluyente / $total_prob;

        $prob_respuesta_conductual_observable =
            $prob_respuesta_conductual_observable / $total_prob;
    }
}

/*
 * Si no llegó ml_confidence pero sí las probabilidades,
 * calcularla como la mayor de las dos.
 */
if (
    $ml_confidence === null &&
    $prob_respuesta_no_concluyente !== null &&
    $prob_respuesta_conductual_observable !== null
) {
    $ml_confidence = max(
        $prob_respuesta_no_concluyente,
        $prob_respuesta_conductual_observable
    );
}


/* ============================================================
   ARCHIVO AUDIOVISUAL Y ESTADO DE REVISIÓN
   ============================================================ */

$video_path = get_string(
    $data,
    "video_path",
    ""
);

/*
 * Todo trial nuevo queda pendiente hasta que sea revisado
 * por el especialista desde la plataforma.
 */
$review_status = "pendiente";

$hidden_no_video = (
    $video_path === ""
    ? 1
    : 0
);


/* ============================================================
   VALIDACIONES BÁSICAS
   ============================================================ */

if (
    $baby_id === "" ||
    $session_id === "" ||
    $trial_id === ""
) {
    http_response_code(400);

    echo json_encode(array(
        "ok" => false,
        "step" => "required_fields",
        "error" => (
            "Faltan campos obligatorios: "
            . "baby_id, session_id o trial_id"
        )
    ), JSON_UNESCAPED_UNICODE);

    exit;
}

if (strlen($baby_id) > 20) {
    http_response_code(400);

    echo json_encode(array(
        "ok" => false,
        "step" => "baby_id_length",
        "error" => (
            "El baby_id supera los 20 caracteres "
            . "permitidos por la tabla padres."
        ),
        "baby_id" => $baby_id
    ), JSON_UNESCAPED_UNICODE);

    exit;
}


/* ============================================================
   ASEGURAR QUE EL BEBÉ EXISTA EN PADRES
   ============================================================ */

$ensure_parent = $conn->prepare("
    INSERT INTO padres (
        baby_id,
        nombre
    )
    VALUES (?, ?)

    ON DUPLICATE KEY UPDATE
        nombre = IF(
            nombre IS NULL OR nombre = '',
            VALUES(nombre),
            nombre
        )
");

if (!$ensure_parent) {
    http_response_code(500);

    echo json_encode(array(
        "ok" => false,
        "step" => "prepare_ensure_parent",
        "error" => $conn->error
    ), JSON_UNESCAPED_UNICODE);

    exit;
}

$ensure_parent->bind_param(
    "ss",
    $baby_id,
    $baby_name
);

if (!$ensure_parent->execute()) {
    http_response_code(500);

    echo json_encode(array(
        "ok" => false,
        "step" => "execute_ensure_parent",
        "error" => $ensure_parent->error
    ), JSON_UNESCAPED_UNICODE);

    exit;
}

$ensure_parent->close();


/* ============================================================
   SINCRONIZAR TABLA BEBES
   ============================================================ */

if (table_exists($conn, "bebes")) {

    $ensure_baby = $conn->prepare("
        INSERT INTO bebes (
            baby_id,
            baby_name,
            age_months
        )
        VALUES (?, ?, ?)

        ON DUPLICATE KEY UPDATE
            baby_name = IF(
                baby_name IS NULL OR baby_name = '',
                VALUES(baby_name),
                baby_name
            ),
            age_months = IF(
                age_months IS NULL OR age_months = 0,
                VALUES(age_months),
                age_months
            )
    ");

    if (!$ensure_baby) {
        http_response_code(500);

        echo json_encode(array(
            "ok" => false,
            "step" => "prepare_ensure_baby",
            "error" => $conn->error
        ), JSON_UNESCAPED_UNICODE);

        exit;
    }

    /*
     * Si age_months llega NULL, mysqli lo enviará como NULL.
     */
    $ensure_baby->bind_param(
        "ssd",
        $baby_id,
        $baby_name,
        $age_months
    );

    if (!$ensure_baby->execute()) {
        http_response_code(500);

        echo json_encode(array(
            "ok" => false,
            "step" => "execute_ensure_baby",
            "error" => $ensure_baby->error
        ), JSON_UNESCAPED_UNICODE);

        exit;
    }

    $ensure_baby->close();
}


/* ============================================================
   INSERTAR TRIAL
   ============================================================ */

$sql = "
    INSERT INTO trials (

        baby_id,
        baby_name,
        session_id,
        trial_id,

        age_months,
        sex_biological,

        stimulus_name,
        stimulus_type,
        stimulus_frequency_hz,
        stimulus_duration_s,
        stimulus_output_level,
        label_expected,
        detected_direction,

        trial_status,
        response_class,
        reaction_time_s,

        baseline_yaw,
        mean_delta_yaw,
        median_delta_yaw,
        std_delta_yaw,
        min_delta_yaw,
        max_delta_yaw,
        range_delta_yaw,

        mean_velocity,
        std_velocity,
        max_velocity,
        min_velocity,

        peak_abs,
        pct_negative,
        pct_positive,
        abs_mean_yaw,
        abs_median_yaw,
        movement_energy,
        samples,

        facial_score,
        blink_score,
        eye_score,
        eyebrow_score,
        mouth_score,

        response_observed,
        response_type,
        head_response_detected,
        facial_response_detected,
        multi_response_detected,
        facial_features_available,
        facial_gesture_type,

        ml_prediction,
        ml_confidence,
        prob_respuesta_no_concluyente,
        prob_respuesta_conductual_observable,
        ai_response_status,
        model_used,

        video_path,
        review_status,
        hidden_no_video

    )
    VALUES (

        ?, ?, ?, ?,

        ?, ?,

        ?, ?, ?, ?, ?, ?, ?,

        ?, ?, ?,

        ?, ?, ?, ?, ?, ?, ?,

        ?, ?, ?, ?,

        ?, ?, ?, ?, ?, ?, ?,

        ?, ?, ?, ?, ?,

        ?, ?, ?, ?, ?, ?, ?,

        ?, ?, ?, ?,
        ?, ?,

        ?, ?, ?

    )
";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    http_response_code(500);

    echo json_encode(array(
        "ok" => false,
        "step" => "prepare_insert_trial",
        "error" => $conn->error,
        "hint" => (
            "Verifica que todas las columnas nuevas "
            . "existan en la tabla trials."
        )
    ), JSON_UNESCAPED_UNICODE);

    exit;
}


/*
 * TIPOS:
 * s = string
 * i = integer
 * d = double
 *
 * 55 parámetros en total.
 */
$types =
    "ssss" .       // baby/session/trial
    "ds" .         // age, sex
    "ssdddss" .    // estímulo
    "sid" .        // legacy + reaction_time
    "ddddddd" .    // yaw
    "dddd" .       // dinámica
    "ddddddi" .    // magnitud + samples
    "ddddd" .      // facial scores
    "isiiiis" .    // evidencia observacional
    "idddss" .     // IA
    "ssi";         // video/review/hidden


$stmt->bind_param(
    $types,

    $baby_id,
    $baby_name,
    $session_id,
    $trial_id,

    $age_months,
    $sex_biological,

    $stimulus_name,
    $stimulus_type,
    $stimulus_frequency_hz,
    $stimulus_duration_s,
    $stimulus_output_level,
    $label_expected,
    $detected_direction,

    $trial_status,
    $response_class,
    $reaction_time_s,

    $baseline_yaw,
    $mean_delta_yaw,
    $median_delta_yaw,
    $std_delta_yaw,
    $min_delta_yaw,
    $max_delta_yaw,
    $range_delta_yaw,

    $mean_velocity,
    $std_velocity,
    $max_velocity,
    $min_velocity,

    $peak_abs,
    $pct_negative,
    $pct_positive,
    $abs_mean_yaw,
    $abs_median_yaw,
    $movement_energy,
    $samples,

    $facial_score,
    $blink_score,
    $eye_score,
    $eyebrow_score,
    $mouth_score,

    $response_observed,
    $response_type,
    $head_response_detected,
    $facial_response_detected,
    $multi_response_detected,
    $facial_features_available,
    $facial_gesture_type,

    $ml_prediction,
    $ml_confidence,
    $prob_respuesta_no_concluyente,
    $prob_respuesta_conductual_observable,
    $ai_response_status,
    $model_used,

    $video_path,
    $review_status,
    $hidden_no_video
);


if (!$stmt->execute()) {
    http_response_code(500);

    echo json_encode(array(
        "ok" => false,
        "step" => "execute_insert_trial",
        "error" => $stmt->error,
        "baby_id" => $baby_id,
        "session_id" => $session_id,
        "trial_id" => $trial_id
    ), JSON_UNESCAPED_UNICODE);

    exit;
}

$trial_db_id = $stmt->insert_id;

$stmt->close();
$conn->close();


/* ============================================================
   RESPUESTA OK
   ============================================================ */

echo json_encode(array(
    "ok" => true,
    "message" => "Trial guardado y pendiente de revisión profesional",

    "trial_db_id" => $trial_db_id,
    "baby_id" => $baby_id,
    "baby_name" => $baby_name,

    "review_status" => $review_status,

    "ai_response_status" => $ai_response_status,
    "ml_prediction" => $ml_prediction,
    "ml_confidence" => $ml_confidence,

    "prob_respuesta_no_concluyente" =>
        $prob_respuesta_no_concluyente,

    "prob_respuesta_conductual_observable" =>
        $prob_respuesta_conductual_observable,

    "response_observed" => $response_observed,
    "response_type" => $response_type,

    "head_response_detected" =>
        $head_response_detected,

    "facial_response_detected" =>
        $facial_response_detected,

    "facial_gesture_type" =>
        $facial_gesture_type,

    "model_used" =>
        $model_used

), JSON_UNESCAPED_UNICODE);

exit;
?>