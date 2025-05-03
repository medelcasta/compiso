<?php
include('../utiles/conexion.php');
session_start();

if (!isset($_POST['nombre']) || !isset($_POST['message']) || !isset($_SESSION["usuario"])) {
    echo "Faltan datos.";
    exit;
}

$user = $_POST['nombre']; // emisor
$msg = $_POST['message'];
$receptor = 'admin'; // receptor fijo (ajustable)

$id_mensaje = uniqid();
$fecha = date("Y-m-d");
$hora = date("H:i:s");

$sql = "INSERT INTO Mensaje (id_mensaje, id_usuario1, id_usuario2, contenido, fecha, hora)
        VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("ssssss", $id_mensaje, $user, $receptor, $msg, $fecha, $hora);
$stmt->execute();

echo "Mensaje enviado";

$stmt->close();
$conexion->close();
?>
