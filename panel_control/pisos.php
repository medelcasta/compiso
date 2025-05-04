<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compiso</title>
    <link rel="icon" type="image/jpg" href="/images/logo_compiso.ico"/>
    <link rel="stylesheet" href="./css/estilos.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script> window.chtlConfig = { chatbotId: "2783453492" } </script>
    <script async data-id="2783453492" id="chatling-embed-script" type="text/javascript" src="https://chatling.ai/js/embed.js"></script>
</head>
<body>
    <div class="container mt-4">
        <h1 class="header text-center">Compiso</h1>
        <div class="row">
            <?php
                require('../utiles/conexion.php');
                require("../utiles/volver.php");

                session_start();
                if (!isset($_SESSION["usuario"])) {
                    echo "No has iniciado sesión.";
                    exit;
                }

                error_reporting(E_ALL);
                ini_set("display_errors", 1);

                $sql = "SELECT * FROM Vivienda"; 
                $result = $_conexion->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<div class="col-md-4 mb-4">';
                        echo '<div class="card h-100">';

                        // Verifica si la imagen existe en la carpeta uploads, si no, usa una imagen por defecto
                        $imagen = !empty($row["imagenes"]) ? $row["imagenes"] : 'default.jpg';
                        $ruta_imagen = "uploads/" . $imagen;
                        
                        if (!file_exists($ruta_imagen)) {
                            $ruta_imagen = "uploads/default.jpg"; // Imagen de respaldo
                        }

                        echo '<img src="' . htmlspecialchars($ruta_imagen) . '" class="card-img-top" alt="Imagen de la vivienda">';
                        echo '<div class="card-body">';
                        echo '<h5 class="card-title">' . htmlspecialchars($row["direccion"]) . ', ' . htmlspecialchars($row["ciudad"]) . '</h5>';
                        echo '<p class="card-text"><strong>Descripción:</strong> ' . htmlspecialchars($row["descripcion"]) . '</p>';
                        echo '<p class="card-text"><strong>Precio:</strong> ' . $row["precio"] . ' €</p>';
                        echo '<p class="card-text"><strong>Habitaciones:</strong> ' . $row["habitaciones"] . '</p>';
                        echo '<p class="card-text"><strong>Baños:</strong> ' . $row["banos"] . '</p>';
                        echo '<p class="card-text"><strong>Metros cuadrados:</strong> ' . $row["metros_cuadrados"] . ' m²</p>';
                        echo '<p class="card-text"><strong>Disponibilidad:</strong> ' . ($row["disponibilidad"] ? 'Disponible' : 'No disponible') . '</p>';
                        echo '<a href="./usuario/iniciar_sesion.php" class="btn btn-primary mt-2">Más Info</a>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo "<p class='text-center'>No se encontraron viviendas.</p>";
                }

                $_conexion->close();
            ?>
        </div>
        <a class="btn btn-secondary mt-3" href="<?php echo obtenerEnlaceVolver(); ?>">Volver</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


