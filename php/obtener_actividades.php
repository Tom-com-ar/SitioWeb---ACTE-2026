<?php
require_once "conexion.php";

$stmt = $conexion->query("SELECT a.id, a.taller_id, a.nombre, a.descripcion, a.imagen, t.nombre AS taller_nombre FROM actividades a JOIN talleres t ON a.taller_id = t.id");
$actividades = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($actividades);