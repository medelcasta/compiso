<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require('../utiles/conexion.php');
session_start();

if (!isset($_SESSION["usuario"]) || !is_array($_SESSION["usuario"])) {
    echo "Sesión inválida o usuario no autenticado.";
    exit;
}

$emisor_id = $_SESSION["usuario"]["id_usuario"];
$receptor_id = isset($_GET["usuario_id"]) ? $_GET["usuario_id"] : '';

if (!$receptor_id || $receptor_id === $emisor_id) {
    echo "ID de receptor inválido.";
    exit;
}

$stmt = $_conexion->prepare("SELECT * FROM Mensaje WHERE 
    (emisor_id = ? AND receptor_id = ?) OR 
    (emisor_id = ? AND receptor_id = ?) 
    ORDER BY fecha ASC");

$stmt->bind_param("ssss", $emisor_id, $receptor_id, $receptor_id, $emisor_id);
$stmt->execute();
$result = $stmt->get_result();

while ($fila = $result->fetch_assoc()) {
    $clase = $fila["emisor_id"] == $emisor_id ? "emisor" : "receptor";
    echo "<div class='mensaje $clase'>";
    echo htmlspecialchars($fila["contenido"]) . "<br>";
    echo "<small class='text-muted'>" . date("d/m/Y H:i", strtotime($fila["fecha"])) . "</small>";
    echo "</div>";
}

$stmt->close();
$_conexion->close();
?>
