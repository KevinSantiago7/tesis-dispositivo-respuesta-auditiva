<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

header("Content-Type: application/json");

$config = require __DIR__ . "/config.php";

$conn = new mysqli(
    $config["host"],
    $config["user"],
    $config["pw"],
    $config["db"]
);

if ($conn->connect_error) {
    header("HTTP/1.1 500 Internal Server Error");
    echo json_encode(array(
        "ok" => false,
        "step" => "db_connect",
        "error" => $conn->connect_error
    ));
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    header("HTTP/1.1 400 Bad Request");
    echo json_encode(array(
        "ok" => false,
        "step" => "json_decode",
        "error" => "JSON invalido o vacio"
    ));
    exit;
}

$stmt = $conn->prepare("
INSERT INTO sessions_summary (
    session_id,
    baby_id,
    total_trials,
    correct,
    no_response,
    incorrect,
    pct_correct,
    avg_latency,
    status
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
");

if (!$stmt) {
    header("HTTP/1.1 500 Internal Server Error");
    echo json_encode(array(
        "ok" => false,
        "step" => "prepare",
        "error" => $conn->error
    ));
    exit;
}

$stmt->bind_param(
    "ssiiiidds",
    $data["session_id"],
    $data["baby_id"],
    $data["total_trials"],
    $data["correct"],
    $data["no_response"],
    $data["incorrect"],
    $data["pct_correct"],
    $data["avg_latency"],
    $data["status"]
);

if (!$stmt->execute()) {
    header("HTTP/1.1 500 Internal Server Error");
    echo json_encode(array(
        "ok" => false,
        "step" => "execute",
        "error" => $stmt->error
    ));
    exit;
}

echo json_encode(array(
    "ok" => true,
    "message" => "Sesion guardada"
));

$stmt->close();
$conn->close();
?>