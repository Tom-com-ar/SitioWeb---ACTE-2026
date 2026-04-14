<?php
session_start();
require_once "conexion.php";

if (!isset($_SESSION["usuario_id"])) {
    header("Location: ../pages/login.html");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST["inscripcion_id"])) {
    $inscripcion_id = (int) $_POST["inscripcion_id"];
    $usuario_id = $_SESSION["usuario_id"];

    $stmtGet = $conexion->prepare("SELECT taller_id FROM inscripciones WHERE id = ? AND usuario_id = ?");
    $stmtGet->execute([$inscripcion_id, $usuario_id]);
    $inscripcion = $stmtGet->fetch(PDO::FETCH_ASSOC);

    if ($inscripcion) {
        $taller_id = $inscripcion["taller_id"];
        
        $stmtDelActividades = $conexion->prepare(
            "DELETE FROM actividad_inscripciones WHERE usuario_id = ? AND actividad_id IN (
                SELECT id FROM actividades WHERE taller_id = ?
            )"
        );
        $stmtDelActividades->execute([$usuario_id, $taller_id]);
        
        $stmt = $conexion->prepare(
            "DELETE FROM inscripciones WHERE id = ? AND usuario_id = ?"
        );
        $stmt->execute([$inscripcion_id, $usuario_id]);
    }
}

header("Location: ../pages/dashboard.php");
exit;
