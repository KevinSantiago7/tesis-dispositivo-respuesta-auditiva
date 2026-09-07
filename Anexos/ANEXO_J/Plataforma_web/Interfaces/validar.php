<?php
session_start();
include "conexion.php";

$usuario  = isset($_POST['usuario']) ? trim($_POST['usuario']) : '';
$password = isset($_POST['password']) ? trim($_POST['password']) : '';

if ($usuario === '' || $password === '') {
    header("Location: index.php?mensaje=error");
    exit;
}

$usuario_normalizado = strtolower($usuario);

$mysqli = new mysqli($host, $user, $pw, $db);
if ($mysqli->connect_error) {
    header("Location: index.php?mensaje=error");
    exit;
}

$mysqli->set_charset("utf8mb4");

/* ==========================================================
   1. INTENTO DE INGRESO COMO ESPECIALISTA / USUARIO INTERNO
   Tabla: usuarios
   ========================================================== */

$sql_usuario = "
    SELECT id, nombre, password, rol
    FROM usuarios
    WHERE LOWER(login) = ? AND activo = 1
    LIMIT 1
";

$stmt = $mysqli->prepare($sql_usuario);

if ($stmt) {
    $stmt->bind_param("s", $usuario_normalizado);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $row = $result->fetch_assoc();

        if ($row['password'] === md5($password)) {
            $rol = strtolower(
                trim(
                    (string)$row['rol']
                )
            );

            $_SESSION['autenticado'] = true;
            $_SESSION['id_usuario'] = $row['id'];
            $_SESSION['usuario'] = $row['nombre'];
            $_SESSION['nombre_usuario'] = $row['nombre'];
            $_SESSION['rol'] = $rol;
            $_SESSION['tipo_usuario'] = ucfirst($rol);

            if ($rol === 'admin') {
                header("Location: admin.php");
                exit;
            }

            if ($rol === 'especialista') {
                header("Location: main.php");
                exit;
            }

            header("Location: main_usuario_consulta.php");
            exit;
        }
    }

    $stmt->close();
}

/* ==========================================================
   2. INTENTO DE INGRESO COMO PADRE
   Tabla: padres

   En esta lógica, el padre puede ingresar usando:
   - username del bebé
   - nombre del bebé

   Ejemplo:
   Usuario: LIAM
   Contraseña: LIAM
   ========================================================== */

$sql_padre = "
    SELECT id, baby_id, nombre, username, password
    FROM padres
    WHERE LOWER(username) = ? OR LOWER(nombre) = ?
    LIMIT 1
";

$stmt = $mysqli->prepare($sql_padre);

if ($stmt) {
    $stmt->bind_param("ss", $usuario_normalizado, $usuario_normalizado);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $row = $result->fetch_assoc();

        if ($row['password'] === md5($password)) {
            $_SESSION['autenticado'] = true;

            $_SESSION['id_padre'] = $row['id'];
            $_SESSION['id_usuario'] = "padre_" . $row['id'];

            $_SESSION['usuario'] = $row['nombre'];
            $_SESSION['nombre_usuario'] = $row['nombre'];

            $_SESSION['rol'] = "padre";
            $_SESSION['tipo_usuario'] = "Padre";

            $_SESSION['baby_id'] = $row['baby_id'];
            $_SESSION['baby_name'] = $row['nombre'];

            header("Location: main_usuario_consulta.php");
            exit;
        }
    }

    $stmt->close();
}

$mysqli->close();

header("Location: index.php?mensaje=error");
exit;