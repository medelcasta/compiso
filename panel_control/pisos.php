<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compiso</title>
    <link rel="icon" type="image/jpg" href="/images/logo_compiso.ico"/>
    <link rel="stylesheet" href="./css/estilos.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script> window.chtlConfig = { chatbotId: "2783453492" } </script>
<script async data-id="2783453492" id="chatling-embed-script" type="text/javascript" src="https://chatling.ai/js/embed.js"></script>
</head>
<body>
    <div class="container mt-4">
        <h1 class="header text-center">Compiso</h1>
        <div class="row">
            <?php
                require('../utiles/conexion.php');

                session_start();
                if (!isset($_SESSION["usuario"])) {
                    echo "No has iniciado sesión.";
                    exit;
                }

                $sql = "SELECT * FROM Vivienda"; 
                $result = $_conexion->query($sql);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo '<div class="col-md-4 mb-4">';
                        echo '<div class="card h-100">';
                        $imagen = !empty($row["imagenes"]) ? $row["imagenes"] : 'foto1.jpg';
                        echo '<img src="./images/' . htmlspecialchars($imagen) . '" class="card-img-top" alt="Imagen de la vivienda">';
                        echo '<div class="card-body">';
                        echo '<h5 class="card-title">' . htmlspecialchars($row["direccion"]) . ', ' . htmlspecialchars($row["ciudad"]) . '</h5>';
                        echo '<p class="card-text"><strong>Descripción:</strong> ' . htmlspecialchars($row["descripcion"]) . '</p>';
                        echo '<p class="card-text"><strong>Precio:</strong> ' . $row["precio"] . ' €</p>';
                        echo '<p class="card-text"><strong>Habitaciones:</strong> ' . $row["habitaciones"] . '</p>';
                        echo '<p class="card-text"><strong>Baños:</strong> ' . $row["baños"] . '</p>';
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
        <a class="btn btn-secondary" href="../inicio.php">Volver</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
