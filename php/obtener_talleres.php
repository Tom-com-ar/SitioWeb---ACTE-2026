<?php
require_once "conexion.php";

$stmt = $conexion->query("SELECT * FROM talleres");
$talleres = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($talleres);