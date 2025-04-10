<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Búsqueda de Vivienda</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <?php
    error_reporting(E_ALL);
    ini_set("display_errors", 1);

    require('./util/conexion.php');
    ?>
</head>
<body>
    <div class="container">
        <h1>Búsqueda de Vivienda</h1>
        <!-- Formulario de búsqueda -->
        <form method="POST" action="">
            <div class="mb-3">
                <label for="criterio" class="form-label">Buscar por Dirección o Ciudad:</label>
                <input type="text" class="form-control" id="criterio" name="criterio" placeholder="Introduce dirección o ciudad">
            </div>
            <button type="submit" class="btn btn-primary">Buscar</button>
        </form>
        
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $criterio = $_POST["criterio"];
            
            // 1. Preparar la consulta
            $sql = $_conexion->prepare("SELECT id_vivienda, direccion, ciudad, descripcion, precio, habitaciones, baños, disponibilidad FROM Vivienda WHERE direccion LIKE ? OR ciudad LIKE ?");
            
            // 2. Bind de parámetros
            $param = "%$criterio%";
            $sql->bind_param("ss", $param, $param);

            // 3. Ejecutar
            $sql->execute();

            // 4. Obtener resultados
            $resultado = $sql->get_result();
            if ($resultado->num_rows > 0) {
                echo "<h2>Resultados de la búsqueda:</h2>";
                echo '<table class="table table-striped table-hover">';
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
                    echo "<td>" . $fila["direccion"] . "</td>";
                    echo "<td>" . $fila["ciudad"] . "</td>";
                    echo "<td>" . $fila["descripcion"] . "</td>";
                    echo "<td>" . $fila["precio"] . " €</td>";
                    echo "<td>" . $fila["habitaciones"] . "</td>";
                    echo "<td>" . $fila["baños"] . "</td>";
                    echo "<td>" . ($fila["disponibilidad"] ? "Disponible" : "No disponible") . "</td>";
                    // Botón que redirige a viviendas.php
                    echo '<td><a href="viviendas.php?id_vivienda=' . $fila["id_vivienda"] . '" class="btn btn-info">Ver más</a></td>';
                    echo "</tr>";
                }
                echo '</tbody>';
                echo '</table>';
            } else {
                echo "<p>No se encontraron viviendas que coincidan con el criterio de búsqueda.</p>";
            }
        }

        // Cerrar conexión
        $_conexion->close();
        ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>

