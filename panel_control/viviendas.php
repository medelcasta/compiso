<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Información de la Vivienda</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <?php
    error_reporting(E_ALL);
    ini_set("display_errors", 1);

    require('../utiles/conexion.php');

    session_start();
    if (!isset($_SESSION["usuario"])) {
        echo "No has iniciado sesión.";
        exit;
    }
    ?>
    <script> window.chtlConfig = { chatbotId: "2783453492" } </script>
    <script async data-id="2783453492" id="chatling-embed-script" type="text/javascript" src="https://chatling.ai/js/embed.js"></script>
</head>
<body>
    <div class="container mt-5">
        <?php
        if (isset($_GET["id_vivienda"])) {
            $id_vivienda = $_GET["id_vivienda"];
            
            // Consultar la información de la vivienda
            $sql = $_conexion->prepare("SELECT * FROM Vivienda WHERE id_vivienda = ?");
            $sql->bind_param("s", $id_vivienda);
            $sql->execute();
            $resultado = $sql->get_result();

            if ($resultado->num_rows > 0) {
                $fila = $resultado->fetch_assoc();
                echo "<h1>Detalles de la Vivienda</h1>";
                echo "<p><strong>ID:</strong> " . $fila["id_vivienda"] . "</p>";
                echo "<p><strong>Dirección:</strong> " . $fila["direccion"] . "</p>";
                echo "<p><strong>Ciudad:</strong> " . $fila["ciudad"] . "</p>";
                echo "<p><strong>Descripción:</strong> " . $fila["descripcion"] . "</p>";
                echo "<p><strong>Precio:</strong> " . $fila["precio"] . " €</p>";
                echo "<p><strong>Habitaciones:</strong> " . $fila["habitaciones"] . "</p>";
                echo "<p><strong>Baños:</strong> " . $fila["banos"] . "</p>";
                echo "<p><strong>Metros Cuadrados:</strong> " . $fila["metros_cuadrados"] . " m²</p>";
                echo "<p><strong>Disponibilidad:</strong> " . ($fila["disponibilidad"] ? "Disponible" : "No disponible") . "</p>";
                echo "<p><strong>ID del Propietario:</strong> " . $fila["id_propietario"] . "</p>";
                echo '<p><strong>Imágenes:</strong></p>';
                echo '<img src="' . $fila["imagenes"] . '" alt="Imagen de la vivienda" class="img-fluid">';
            } else {
                echo "<p>No se encontró información para la vivienda seleccionada.</p>";
            }
        } else {
            echo "<p>El ID de la vivienda no fue proporcionado.</p>";
        }

        // Cerrar conexión
        $_conexion->close();
        ?>
          <a class="btn btn-secondary" href="./buscar_vivienda.php">Volver</a>
    </div>
  
</body>
</html>
