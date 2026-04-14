<?php
session_start();
require_once "conexion.php";

if (!isset($_SESSION["usuario_id"])) {
    header("Location: ../pages/login.html");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST["actividad_inscripcion_id"])) {
    $actividad_inscripcion_id = (int) $_POST["actividad_inscripcion_id"];
    $usuario_id = $_SESSION["usuario_id"];

    $stmt = $conexion->prepare(
        "DELETE FROM actividad_inscripciones WHERE id = ? AND usuario_id = ?"
    );
    $stmt->execute([$actividad_inscripcion_id, $usuario_id]);
}

header("Location: ../pages/dashboard.php");
exit;
