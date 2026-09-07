<?php
header('Content-Type: application/json; charset=UTF-8');

$uploadDir = __DIR__ . '/../interfaces/videos/';

if (!is_dir($uploadDir)) {
    if (!mkdir($uploadDir, 0775, true)) {
        echo json_encode([
            "ok" => false,
            "message" => "No se pudo crear la carpeta de videos"
        ]);
        exit;
    }
}

if (!isset($_FILES['video'])) {
    echo json_encode([
        "ok" => false,
        "message" => "No se recibió ningún archivo de video"
    ]);
    exit;
}

$file = $_FILES['video'];

if ($file['error'] !== UPLOAD_ERR_OK) {
    echo json_encode([
        "ok" => false,
        "message" => "Error al subir archivo",
        "error_code" => $file['error']
    ]);
    exit;
}

$originalName = basename($file['name']);
$extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

$allowed = ['avi', 'mp4', 'mov', 'mkv'];

if (!in_array($extension, $allowed)) {
    echo json_encode([
        "ok" => false,
        "message" => "Formato de video no permitido",
        "extension" => $extension
    ]);
    exit;
}

$babyId = isset($_POST['baby_id']) ? preg_replace('/[^A-Za-z0-9_-]/', '', $_POST['baby_id']) : 'SIN_BABY';
$trialId = isset($_POST['trial_id']) ? preg_replace('/[^A-Za-z0-9_-]/', '', $_POST['trial_id']) : pathinfo($originalName, PATHINFO_FILENAME);

$finalName = $babyId . '_' . $trialId . '_' . date('Ymd_His') . '.' . $extension;
$destPath = $uploadDir . $finalName;

if (!move_uploaded_file($file['tmp_name'], $destPath)) {
    echo json_encode([
        "ok" => false,
        "message" => "No se pudo mover el video al destino final"
    ]);
    exit;
}

$baseUrl = "http://100.79.171.96/tesis/sprint2/interfaces/videos/";
$videoUrl = $baseUrl . $finalName;

echo json_encode([
    "ok" => true,
    "message" => "Video subido correctamente",
    "filename" => $finalName,
    "video_url" => $videoUrl,
    "video_path" => "interfaces/videos/" . $finalName
]);