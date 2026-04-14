<?php
session_start();
require_once "conexion.php";

if (!isset($_SESSION["usuario_id"])) {
    http_response_code(403);
    echo "Debe iniciar sesión para inscribirse";
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

$nombre = $data["nombre"];
$email = $data["email"];
$telefono = $data["telefono"];
$talleres = $data["talleres"] ?? [];
$actividades = $data["actividades"] ?? [];

$usuario_id = $_SESSION["usuario_id"];

if (!empty($actividades)) {
    $stmt = $conexion->prepare("SELECT taller_id FROM actividades WHERE id = ?");
    foreach ($actividades as $actividad_id) {
        $stmt->execute([(int) $actividad_id]);
        $actividad = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$actividad || !in_array((string) $actividad["taller_id"], array_map('strval', $talleres), true)) {
            http_response_code(403);
            echo "Actividad inválida para el taller seleccionado";
            exit;
        }
    }
}

foreach ($talleres as $taller_id) {
    $stmt = $conexion->prepare(
        "INSERT INTO inscripciones (usuario_id, taller_id, nombre, email, telefono)
        VALUES (?, ?, ?, ?, ?)"
    );
    $stmt->execute([
        $usuario_id,
        (int) $taller_id,
        $nombre,
        $email,
        $telefono
    ]);
}

foreach ($actividades as $actividad_id) {
    $stmt = $conexion->prepare(
        "INSERT INTO actividad_inscripciones (usuario_id, actividad_id, nombre, email, telefono)
        VALUES (?, ?, ?, ?, ?)"
    );
    $stmt->execute([
        $usuario_id,
        (int) $actividad_id,
        $nombre,
        $email,
        $telefono
    ]);
}

echo "ok";