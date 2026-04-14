<?php
require_once "conexion.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../index.php#contacto");
    exit;
}

$nombre = trim($_POST["nombre"] ?? "");
$email = trim($_POST["email"] ?? "");
$asunto = trim($_POST["asunto"] ?? "");
$mensaje = trim($_POST["mensaje"] ?? "");

if ($nombre === "" || $email === "" || $mensaje === "" || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: ../index.php?contacto=error#contacto");
    exit;
}

try {
    $stmt = $conexion->prepare("
        INSERT INTO contactos (nombre, email, asunto, mensaje)
        VALUES (?, ?, ?, ?)
    ");

    $stmt->execute([
        $nombre,
        $email,
        $asunto !== "" ? $asunto : null,
        $mensaje
    ]);

    header("Location: ../index.php?contacto=ok#contacto");
    exit;
} catch (PDOException $e) {
    header("Location: ../index.php?contacto=error#contacto");
    exit;
}
