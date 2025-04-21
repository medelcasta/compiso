<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
require('../utiles/conexion.php');

if (!isset($_SESSION["usuario"])) {
    echo "No has iniciado sesión.";
    exit;
}
/*
session_start();
if (isset($_SESSION["usuario"])) {
    echo "<h2>Bienvenid@ " . $_SESSION["usuario"] . "</h2>";
} else {
    header("location: usuario/iniciar_sesion.php");
    exit;
}
*/
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Búsqueda de Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script> window.chtlConfig = { chatbotId: "2783453492" } </script>
    <script async data-id="2783453492" id="chatling-embed-script" type="text/javascript"
        src="https://chatling.ai/js/embed.js"></script>
</head>

<body>
    <div class="container mt-5">
        <!--<a class="btn btn-warning mb-3" href="usuario/cerrar_sesion.php">Cerrar sesión</a>-->
        <h1 class="mb-4">Búsqueda de Usuario</h1>

        <form method="POST" action="">
            <div class="mb-3">
                <label for="criterio" class="form-label">Buscar por Nombre o Email:</label>
                <input type="text" class="form-control" id="criterio" name="criterio"
                    placeholder="Introduce nombre o email">
            </div>
            <button type="submit" class="btn btn-primary">Buscar</button>
        </form>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["criterio"]) && $_POST["criterio"] !== "") {
            $criterio = $_POST["criterio"];
            $sql = $_conexion->prepare("SELECT * FROM Usuario WHERE nombre LIKE ? OR email LIKE ?");
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
                        echo "<td>" . htmlspecialchars($fila["nombre"]) . "</td>";
                        echo "<td>" . htmlspecialchars($fila["apellidos"]) . "</td>";
                        echo "<td>" . htmlspecialchars($fila["email"]) . "</td>";
                        echo "<td>" . htmlspecialchars($fila["telefono"]) . "</td>";
                        echo "<td>" . htmlspecialchars($fila["tipo_usuario"]) . "</td>";
                        echo "</tr>";
                    }
                    echo '</tbody>';
                    echo '</table>';
                } else {
                    echo "<p class='mt-3 text-danger'>No se encontraron usuarios que coincidan con el criterio de búsqueda.</p>";
                }

                $sql->close();
            } else {
                echo "<p class='mt-3 text-danger'>Error en la consulta preparada.</p>";
            }
        }
        $_conexion->close();
        ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>