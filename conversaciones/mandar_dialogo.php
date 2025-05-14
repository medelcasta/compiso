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
$receptor_id = isset($_POST["usuario_id"]) ? $_POST["usuario_id"] : '';
$contenido = trim($_POST["mensaje"] ?? '');

if (!empty($receptor_id) && $contenido !== '') {
    $id_mensaje = uniqid(); // Generar ID único como string

    $stmt = $_conexion->prepare("INSERT INTO Mensaje (id, emisor_id, receptor_id, contenido, fecha) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param("ssss", $id_mensaje, $emisor_id, $receptor_id, $contenido);
    $stmt->execute();
    $stmt->close();
}

$_conexion->close();
?>
