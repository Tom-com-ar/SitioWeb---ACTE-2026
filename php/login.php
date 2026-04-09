<?php
session_start();
require_once "conexion.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    $stmt = $conexion->prepare("
        SELECT * FROM usuarios WHERE email = ?
    ");

    $stmt->execute([$email]);

    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($password, $usuario["password"])) {

        $_SESSION["usuario_id"] = $usuario["id"];
        $_SESSION["usuario_nombre"] = $usuario["nombre"];
        $_SESSION["usuario_apellido"] = $usuario["apellido"];
        $_SESSION["usuario_email"] = $usuario["email"];

        header("Location: ../pages/dashboard.php");
        exit;

    } else {

        header("Location: ../pages/login.html?error=credenciales");
        exit;
    }
}