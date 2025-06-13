<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    echo "No has iniciado sesión.";
    exit;
}

if ($_SESSION["tipo_usuario"] != '2') {
    echo "No tienes permisos para modificar viviendas.";
    exit;
}

error_reporting(E_ALL);
ini_set("display_errors", 1);

require("../utiles/conexion.php");
require("../utiles/volver.php");

// Obtener el id_usuario del usuario actual
$datos_usuario = $_SESSION["usuario"];
$nombre_usuario = $datos_usuario["nombre"]; // <- ERROR ORIGINAL: se usaba $_SESSION["usuario"] como string, pero es un array

$sql = "SELECT id_usuario FROM Usuario WHERE nombre = ?";
$stmt = $_conexion->prepare($sql);
$stmt->bind_param("s", $nombre_usuario);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows == 0) {
    echo "<div class='alert alert-danger text-center mt-3'>No se encontró el usuario en la base de datos.</div>";
    exit;
}

$usuario = $resultado->fetch_assoc();
$id_usuario = $usuario["id_usuario"];
$stmt->close();

// Obtener el id_propietario del usuario actual
$sql_propietario = "SELECT id_propietario FROM Propietario WHERE id_usuario = ?";
$stmt_propietario = $_conexion->prepare($sql_propietario);
$stmt_propietario->bind_param("s", $id_usuario);
$stmt_propietario->execute();
$resultado_propietario = $stmt_propietario->get_result();

if ($resultado_propietario->num_rows == 0) {
    echo "<div class='alert alert-danger text-center mt-3'>No se encontró un propietario asociado con este usuario.</div>";
    exit;
}

$propietario = $resultado_propietario->fetch_assoc();
$id_propietario = $propietario["id_propietario"];
$_SESSION["id_propietario"] = $id_propietario;
$stmt_propietario->close();

// Obtener la vivienda existente del propietario
$sql_vivienda = "SELECT * FROM Vivienda WHERE id_propietario = ? LIMIT 1";
$stmt_vivienda = $_conexion->prepare($sql_vivienda);
$stmt_vivienda->bind_param("s", $id_propietario);
$stmt_vivienda->execute();
$resultado_vivienda = $stmt_vivienda->get_result();

if ($resultado_vivienda->num_rows == 0) {
    echo "<div class='alert alert-warning text-center mt-3'>No tienes viviendas registradas para actualizar.</div>";
    exit;
}

$vivienda = $resultado_vivienda->fetch_assoc();
$id_vivienda = $vivienda["id_vivienda"];
$stmt_vivienda->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Vivienda</title>
    <link rel="icon" type="image/jpg" href="/images/logo_compiso.ico" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Editar información de la vivienda</h2>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Dirección</label>
                <input type="text" class="form-control" name="direccion" value="<?= htmlspecialchars($vivienda['direccion']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Ciudad</label>
                <input type="text" class="form-control" name="ciudad" value="<?= htmlspecialchars($vivienda['ciudad']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Descripción</label>
                <textarea class="form-control" name="descripcion"><?= htmlspecialchars($vivienda['descripcion']) ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Precio</label>
                <input type="number" class="form-control" name="precio" step="0.01" value="<?= $vivienda['precio'] ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Habitaciones</label>
                <input type="number" class="form-control" name="habitaciones" value="<?= $vivienda['habitaciones'] ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Baños</label>
                <input type="number" class="form-control" name="banos" value="<?= $vivienda['banos'] ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Metros cuadrados</label>
                <input type="number" class="form-control" name="metros_cuadrados" value="<?= $vivienda['metros_cuadrados'] ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Disponibilidad (1: Sí, 0: No)</label>
                <input type="number" class="form-control" name="disponibilidad" value="<?= $vivienda['disponibilidad'] ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Imagen nueva (opcional)</label>
                <input type="file" class="form-control" name="imagenes" accept="image/*">
            </div>
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </form>
    </div>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $direccion = $_POST['direccion'];
        $ciudad = $_POST['ciudad'];
        $descripcion = $_POST['descripcion'];
        $precio = $_POST['precio'];
        $habitaciones = $_POST['habitaciones'];
        $banos = $_POST['banos'];
        $metros_cuadrados = $_POST['metros_cuadrados'];
        $disponibilidad = $_POST['disponibilidad'];

        $imagen = $_FILES['imagenes'];
        $nueva_imagen = $vivienda['imagenes'];

        if ($imagen['size'] > 0) {
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (!in_array($imagen["type"], $allowed_types)) {
                echo "<div class='alert alert-danger text-center mt-3'>Formato de imagen no válido.</div>";
                exit;
            }

            $target_dir = "uploads/";
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            $file_name = uniqid() . "-" . basename($imagen["name"]);
            $target_file = $target_dir . $file_name;

            if (!move_uploaded_file($imagen["tmp_name"], $target_file)) {
                echo "<div class='alert alert-danger text-center mt-3'>Error al subir la imagen al servidor.</div>";
                exit;
            }

            $nueva_imagen = $file_name;
        }

        $sql_update = "UPDATE Vivienda SET direccion=?, ciudad=?, descripcion=?, precio=?, habitaciones=?, banos=?, metros_cuadrados=?, disponibilidad=?, imagenes=? WHERE id_vivienda=?";
        $stmt_update = $_conexion->prepare($sql_update);
        $stmt_update->bind_param("sssiiisisi", $direccion, $ciudad, $descripcion, $precio, $habitaciones, $banos, $metros_cuadrados, $disponibilidad, $nueva_imagen, $id_vivienda);

        if ($stmt_update->execute()) {
            echo "<div class='alert alert-success text-center mt-3'>Vivienda actualizada correctamente.</div>";
        } else {
            echo "<div class='alert alert-danger text-center mt-3'>Error al actualizar: " . $stmt_update->error . "</div>";
        }

        $stmt_update->close();
        $_conexion->close();
    }
    ?>
    <a class="btn btn-secondary mt-3" href="<?php echo obtenerEnlaceVolver(); ?>">Volver</a>
</body>
</html>
