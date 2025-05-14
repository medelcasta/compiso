<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    echo "No has iniciado sesión.";
    exit;
}

if ($_SESSION["tipo_usuario"] != '2') {
    echo "No tienes permisos para subir viviendas.";
    exit;
}

error_reporting(E_ALL);
ini_set("display_errors", 1);

require("../utiles/conexion.php");
require("../utiles/volver.php");

// Obtener el id_usuario del usuario actual
$nombre_usuario = $_SESSION["usuario"];
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
$stmt_propietario->bind_param("i", $id_usuario);
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
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Subir Vivienda</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Subir información de la vivienda</h2>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="direccion" class="form-label">Dirección</label>
                <input type="text" class="form-control" name="direccion" required>
            </div>
            <div class="mb-3">
                <label for="ciudad" class="form-label">Ciudad</label>
                <input type="text" class="form-control" name="ciudad" required>
            </div>
            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea class="form-control" name="descripcion"></textarea>
            </div>
            <div class="mb-3">
                <label for="precio" class="form-label">Precio</label>
                <input type="number" class="form-control" name="precio" step="0.01" required>
            </div>
            <div class="mb-3">
                <label for="habitaciones" class="form-label">Habitaciones</label>
                <input type="number" class="form-control" name="habitaciones">
            </div>
            <div class="mb-3">
                <label for="banos" class="form-label">Baños</label>
                <input type="number" class="form-control" name="banos">
            </div>
            <div class="mb-3">
                <label for="metros_cuadrados" class="form-label">Metros cuadrados</label>
                <input type="number" class="form-control" name="metros_cuadrados">
            </div>
            <div class="mb-3">
                <label for="disponibilidad" class="form-label">Disponibilidad (1: Sí, 0: No)</label>
                <input type="number" class="form-control" name="disponibilidad" required>
            </div>
            <div class="mb-3">
                <label for="imagenes" class="form-label">Imagen de la vivienda</label>
                <input type="file" class="form-control" name="imagenes" accept="image/*" required>
            </div>
            <button type="submit" class="btn btn-primary">Subir Vivienda</button>
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

        // Validación de imagen
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($imagen["type"], $allowed_types)) {
            echo "<div class='alert alert-danger text-center mt-3'>Formato de imagen no válido.</div>";
            exit;
        }

        // Procesar imagen
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $file_name = uniqid() . "-" . basename($imagen["name"]);
        $target_file = $target_dir . $file_name;

        // Verificar si la imagen se mueve correctamente al servidor
        if (!move_uploaded_file($imagen["tmp_name"], $target_file)) {
            echo "<div class='alert alert-danger text-center mt-3'>Error al mover la imagen al servidor.</div>";
            echo "<pre>";
            print_r(error_get_last());
            echo "</pre>";
            exit;
        } else {
            echo "<div class='alert alert-success text-center mt-3'>Imagen subida correctamente: " . htmlspecialchars($file_name) . "</div>";
        }
        


        // Guardar solo el nombre del archivo en la base de datos
        $stmt = $_conexion->prepare("INSERT INTO Vivienda (direccion, ciudad, descripcion, precio, habitaciones, banos, metros_cuadrados, disponibilidad, imagenes, id_propietario) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssiiisss", $direccion, $ciudad, $descripcion, $precio, $habitaciones, $banos, $metros_cuadrados, $disponibilidad, $file_name, $id_propietario);

        if ($stmt->execute()) {
            echo "<div class='alert alert-success text-center mt-3'>Vivienda subida correctamente.</div>";
        } else {
            echo "<div class='alert alert-danger mt-3'>Error: " . $stmt->error . "</div>";
        }

        $stmt->close();
        $_conexion->close();
    }
    ?>
     <a class="btn btn-secondary mt-3" href="<?php echo obtenerEnlaceVolver(); ?>">Volver</a>
</body>
</html>

