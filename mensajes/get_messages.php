<?php
include('../utiles/conexion.php');
session_start();

if (!isset($_SESSION["usuario"])) {
    echo json_encode([]);
    exit;
}

$usuario = $_SESSION["usuario"];
$receptor = 'admin'; // Puedes cambiarlo según tu lógica

$sql = "SELECT id_usuario1 AS nombre, contenido AS message, CONCAT(fecha, ' ', hora) AS created_at
        FROM Mensaje
        WHERE (id_usuario1 = ? AND id_usuario2 = ?) OR (id_usuario1 = ? AND id_usuario2 = ?)
        ORDER BY fecha DESC, hora DESC
        LIMIT 50";
        
$stmt = $conexion->prepare($sql);
$stmt->bind_param("ssss", $usuario, $receptor, $receptor, $usuario);
$stmt->execute();
$result = $stmt->get_result();

$messages = array();
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}

echo json_encode($messages);
$stmt->close();
$conexion->close();
?>
