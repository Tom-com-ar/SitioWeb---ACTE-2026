<?php
session_start();
require_once "conexion.php";

$data = json_decode(file_get_contents("php://input"), true);

$nombre = $data["nombre"];
$email = $data["email"];
$telefono = $data["telefono"];
$talleres = $data["talleres"];

$usuario_id = $_SESSION["usuario_id"] ?? null;

foreach ($talleres as $taller_id) {

    $stmt = $conexion->prepare("
        INSERT INTO inscripciones (usuario_id, taller_id, nombre, email, telefono)
        VALUES (?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $usuario_id,
        $taller_id,
        $nombre,
        $email,
        $telefono
    ]);
}

echo "ok";