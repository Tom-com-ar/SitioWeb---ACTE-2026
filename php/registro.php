<?php
require_once "conexion.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nombre = trim($_POST["nombre"]);
    $apellido = trim($_POST["apellido"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    try {

        $stmt = $conexion->prepare("
            INSERT INTO usuarios (nombre, apellido, email, password)
            VALUES (?, ?, ?, ?)
        ");

        $stmt->execute([
            $nombre,
            $apellido,
            $email,
            $passwordHash
        ]);

        header("Location: ../pages/login.html?registro=ok");
        exit;

    } catch(PDOException $e) {

        header("Location: ../pages/registro.html?error=email");
        exit;
    }
}