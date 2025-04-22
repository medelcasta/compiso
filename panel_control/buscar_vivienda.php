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

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Búsqueda de Vivienda</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script> window.chtlConfig = { chatbotId: "2783453492" } </script>
    <script async data-id="2783453492" id="chatling-embed-script" type="text/javascript"
        src="https://chatling.ai/js/embed.js"></script>
</head>

<body>
    <div class="container mt-5">
        <h1 class="mb-4">Búsqueda de Vivienda</h1>

        <form method="POST" action="">
            <div class="mb-3">
                <label for="criterio" class="form-label">Buscar por Dirección o Ciudad:</label>
                <input type="text" class="form-control" id="criterio" name="criterio"
                    placeholder="Introduce dirección o ciudad">
            </div>
            <button type="submit" class="btn btn-primary">Buscar</button>
        </form>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["criterio"]) && trim($_POST["criterio"]) !== "") {
            $criterio = $_POST["criterio"];

            $sql = $_conexion->prepare("SELECT id_vivienda, direccion, ciudad, descripcion, precio, habitaciones, banos, disponibilidad 
                                    FROM Vivienda 
                                    WHERE direccion LIKE ? OR ciudad LIKE ?");

            if ($sql) {
                $param = "%$criterio%";
                $sql->bind_param("ss", $param, $param);
                $sql->execute();
                $resultado = $sql->get_result();

                if ($resultado->num_rows > 0) {
                    echo "<h2 class='mt-4'>Resultados de la búsqueda:</h2>";
                    echo '<table class="table table-striped table-hover mt-3">';
                    echo '<thead class="table-dark">
                        <tr>
                            <th>Dirección</th>
                            <th>Ciudad</th>
                            <th>Descripción</th>
                            <th>Precio</th>
                            <th>Habitaciones</th>
                            <th>Baños</th>
                            <th>Disponibilidad</th>
                            <th>Más Información</th>
                        </tr>
                      </thead>';
                    echo '<tbody>';
                    while ($fila = $resultado->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($fila["direccion"]) . "</td>";
                        echo "<td>" . htmlspecialchars($fila["ciudad"]) . "</td>";
                        echo "<td>" . htmlspecialchars($fila["descripcion"]) . "</td>";
                        echo "<td>" . htmlspecialchars($fila["precio"]) . " €</td>";
                        echo "<td>" . htmlspecialchars($fila["habitaciones"]) . "</td>";
                        echo "<td>" . htmlspecialchars($fila["banos"]) . "</td>";
                        echo "<td>" . ($fila["disponibilidad"] ? "Disponible" : "No disponible") . "</td>";
                        echo '<td><a href="viviendas.php?id_vivienda=' . urlencode($fila["id_vivienda"]) . '" 
                              class="btn btn-info" title="Ver más sobre la vivienda">Ver más</a></td>';
                        echo "</tr>";
                    }
                    echo '</tbody>';
                    echo '</table>';
                } else {
                    echo "<p class='mt-3 text-danger'>No se encontraron viviendas que coincidan con el criterio de búsqueda.</p>";
                }

                $sql->close();
            } else {
                echo "<p class='text-danger'>Error al preparar la consulta.</p>";
            }
        }
        $_conexion->close();
        ?>
        <a class="btn btn-secondary" href="../inicio.php">Volver</a>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>