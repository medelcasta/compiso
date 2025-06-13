<?php
session_start();
error_reporting(E_ALL);
ini_set("display_errors", 1);

require("../utiles/conexion.php");
require("../utiles/volver.php");

if (!isset($_GET["id_vivienda"])) {
    echo "ID de vivienda no proporcionado.";
    exit;
}

$id_vivienda = $_GET["id_vivienda"];

// Obtener los detalles del piso junto al propietario
$sql = "
    SELECT 
        v.direccion, v.ciudad, v.descripcion, v.precio, v.habitaciones, 
        v.banos, v.metros_cuadrados, v.imagenes AS imagen_piso,
        u.id_usuario, u.nombre AS nombre_propietario, u.imagen AS imagen_usuario
    FROM Vivienda v
    LEFT JOIN Usuario u ON v.id_propietario = u.id_usuario
    WHERE v.id_vivienda = ?
";

$stmt = $_conexion->prepare($sql);
$stmt->bind_param("i", $id_vivienda);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    echo "Vivienda no encontrada.";
    exit;
}

$piso = $resultado->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalles del piso</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; font-family: Arial, sans-serif; }
        .container { margin-top: 30px; max-width: 800px; }
        .card { padding: 20px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        .card img { max-width: 100%; height: auto; border-radius: 8px; }
        .propietario { display: flex; align-items: center; gap: 15px; margin-top: 20px; }
        .propietario img { width: 60px; height: 60px; border-radius: 50%; object-fit: cover; }
    </style>
</head>
<body>
<div class="container">
    <h1 class="text-center mb-4">Detalles de la vivienda</h1>

    <div class="card">
        <?php if (!empty($piso["imagen_piso"])): ?>
            <img src="./uploads/<?php echo htmlspecialchars($piso["imagen_piso"] ?? ''); ?>" alt="Imagen del piso">
        <?php endif; ?>

        <h3 class="mt-3">
            <?php echo htmlspecialchars($piso["direccion"] ?? ''); ?>, 
            <?php echo htmlspecialchars($piso["ciudad"] ?? ''); ?>
        </h3>
        <p><strong>Descripción:</strong> <?php echo htmlspecialchars($piso["descripcion"] ?? ''); ?></p>
        <p><strong>Precio:</strong> <?php echo htmlspecialchars($piso["precio"] ?? ''); ?> €</p>
        <p><strong>Habitaciones:</strong> <?php echo htmlspecialchars($piso["habitaciones"] ?? ''); ?></p>
        <p><strong>Baños:</strong> <?php echo htmlspecialchars($piso["banos"] ?? ''); ?></p>
        <p><strong>Metros cuadrados:</strong> <?php echo htmlspecialchars($piso["metros_cuadrados"] ?? ''); ?> m²</p>

        <div class="propietario">
            <?php if (!empty($piso["imagen_usuario"])): ?>
                <img src="../usuario/uploads<?php echo htmlspecialchars($piso["imagen_usuario"] ?? ''); ?>" alt="Foto del propietario">
            <?php endif; ?>
            <span><strong>Propietario:</strong> <?php echo htmlspecialchars($piso["nombre_propietario"] ?? ''); ?></span>
        </div>

        <div class="text-center mt-3">
            <a href="../usuario/perfil_usuario.php?id=<?php echo urlencode($piso['id_usuario']); ?>" class="btn btn-outline-primary">
                Ver perfil del propietario
            </a>
        </div>

        <div class="text-center mt-4">
            <a href="<?php echo obtenerEnlaceVolver(); ?>" class="btn btn-secondary">Volver</a>
        </div>
    </div>
</div>
</body>
</html>
