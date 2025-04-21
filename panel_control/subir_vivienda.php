<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subir Vivienda</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script> window.chtlConfig = { chatbotId: "2783453492" } </script>
    <script async data-id="2783453492" id="chatling-embed-script" type="text/javascript"
        src="https://chatling.ai/js/embed.js"></script>

    <?php 
        if (!isset($_SESSION["usuario"])) {
            echo "No has iniciado sesión.";
            exit;
        }
    ?>
</head>

<body>
    <div class="container mt-5">
        <h2>Subir información de la vivienda</h2>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="direccion" class="form-label">Dirección</label>
                <input type="text" class="form-control" id="direccion" name="direccion" required>
            </div>
            <div class="mb-3">
                <label for="ciudad" class="form-label">Ciudad</label>
                <input type="text" class="form-control" id="ciudad" name="ciudad" required>
            </div>
            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea class="form-control" id="descripcion" name="descripcion"></textarea>
            </div>
            <div class="mb-3">
                <label for="precio" class="form-label">Precio</label>
                <input type="number" class="form-control" id="precio" name="precio" step="0.01" required>
            </div>
            <div class="mb-3">
                <label for="habitaciones" class="form-label">Habitaciones</label>
                <input type="number" class="form-control" id="habitaciones" name="habitaciones">
            </div>
            <div class="mb-3">
                <label for="baños" class="form-label">Baños</label>
                <input type="number" class="form-control" id="baños" name="baños">
            </div>
            <div class="mb-3">
                <label for="metros_cuadrados" class="form-label">Metros Cuadrados</label>
                <input type="number" class="form-control" id="metros_cuadrados" name="metros_cuadrados">
            </div>
            <div class="mb-3">
                <label for="disponibilidad" class="form-label">Disponibilidad (1: Disponible, 0: No Disponible)</label>
                <input type="number" class="form-control" id="disponibilidad" name="disponibilidad" required>
            </div>
            <div class="mb-3">
                <label for="imagenes" class="form-label">Imagen de la vivienda</label>
                <input type="file" class="form-control" id="imagenes" name="imagenes" accept="image/*" required>
            </div>
            <button type="submit" class="btn btn-primary">Subir Vivienda</button>
        </form>
    </div>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id_vivienda = $_POST['id_vivienda'];
        $direccion = $_POST['direccion'];
        $ciudad = $_POST['ciudad'];
        $descripcion = $_POST['descripcion'];
        $precio = $_POST['precio'];
        $habitaciones = $_POST['habitaciones'];
        $baños = $_POST['baños'];
        $metros_cuadrados = $_POST['metros_cuadrados'];
        $disponibilidad = $_POST['disponibilidad'];
        $id_propietario = $_POST['id_propietario'];
        $imagen = $_FILES['imagenes'];

        // Verificar y mover la imagen subida
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($imagen["name"]);
        if (move_uploaded_file($imagen["tmp_name"], $target_file)) {
            echo "La imagen se subió correctamente.";
        } else {
            echo "Hubo un error al subir la imagen.";
        }

        // Conexión a la base de datos
        require('./utiles/conexion.php');

        // Insertar los datos en la base de datos
        $sql = "INSERT INTO Vivienda (id_vivienda, direccion, ciudad, descripcion, precio, habitaciones, baños, metros_cuadrados, disponibilidad, imagenes, id_propietario) 
            VALUES ('$id_vivienda', '$direccion', '$ciudad', '$descripcion', '$precio', '$habitaciones', '$baños', '$metros_cuadrados', '$disponibilidad', '$target_file', '$id_propietario')";

        if ($_conexion->query($sql)) {
            echo "La vivienda se ha subido correctamente.";
        } else {
            echo "Error: " . $sql . "<br>" . $_conexion->error;
        }

        // Cerrar la conexión
        $_conexion->close();
    }
    ?>

</body>

</html>