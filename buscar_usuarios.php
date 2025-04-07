<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Búsqueda de Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <?php
    error_reporting(E_ALL);
    ini_set("display_errors", 1);

    require('./utiles/conexion.php');
   /* session_start();
    if (isset($_SESSION["usuario"])) {
        echo "<h2>Bienvenid@ " . $_SESSION["usuario"] . "</h2>";
    } else {
        header("location: usuario/iniciar_sesion.php");
        exit;
    }*/
    ?>
</head>
<body>
    <div class="container">
         <!--<a class="btn btn-warning" href="usuario/cerrar_sesion.php">Cerrar sesión</a>-->
        <h1>Búsqueda de Usuario</h1>
        <!-- Formulario de búsqueda -->
        <form method="POST" action="">
            <div class="mb-3">
                <label for="criterio" class="form-label">Buscar por Nombre o Email:</label>
                <input type="text" class="form-control" id="criterio" name="criterio" placeholder="Introduce nombre o email">
            </div>
            <button type="submit" class="btn btn-primary">Buscar</button>
        </form>
        
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $criterio = $_POST["criterio"];
            
            // 1. Preparar la consulta
            $sql = $_conexion->prepare("SELECT * FROM Usuario WHERE nombre LIKE ? OR email LIKE ?");

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
                            <th>Nombre</th>
                            <th>Apellidos</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th>Tipo de Usuario</th>
                        </tr>
                      </thead>';
                echo '<tbody>';
                while ($fila = $resultado->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $fila["nombre"] . "</td>";
                    echo "<td>" . $fila["apellidos"] . "</td>";
                    echo "<td>" . $fila["email"] . "</td>";
                    echo "<td>" . $fila["telefono"] . "</td>";
                    echo "<td>" . $fila["tipo_usuario"] . "</td>";
                    echo "</tr>";
                }
                echo '</tbody>';
                echo '</table>';
            } else {
                echo "<p>No se encontraron usuarios que coincidan con el criterio de búsqueda.</p>";
            }
        }

        // Cerrar conexión
        $_conexion->close();
        ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
