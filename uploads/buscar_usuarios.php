<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
require('../utiles/conexion.php');
require_once("../utiles/volver.php");

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
    <title>Búsqueda de Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
       
        h1 {
            text-align: center;
            color: #333; /* Color del texto */
            margin-bottom: 20px;
        }

        .form-label {
            font-weight: bold;
            color: #555; /* Color del texto del label */
        }

        .form-control {
            border-radius: 25px; /* Bordes redondeados */
            padding: 10px 15px;
            border: 1px solid #ccc; /* Borde gris claro */
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1); /* Sombra interna */
        }

        .form-control:focus {
            border-color: #4CAF50; /* Color verde al enfocar */
            box-shadow: 0 0 5px rgba(76, 175, 80, 0.5); /* Sombra verde */
        }

        .btn-primary {
            background-color: #4CAF50; /* Color verde atractivo */
            border: none;
            border-radius: 25px; /* Bordes redondeados */
            padding: 10px 20px;
            font-size: 16px;
            font-weight: bold;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .btn-primary:hover {
            background-color: #45a049; /* Color más oscuro al pasar el cursor */
            transform: translateY(-2px); /* Efecto de elevación */
        }

        .btn-primary:active {
            background-color: #3e8e41; /* Color más oscuro al hacer clic */
            transform: translateY(0); /* Sin elevación */
        }

        .mt-5 {
            margin-top: 50px !important;
        }
    </style>
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

    // Verifica que la conexión esté definida
    if (!isset($conexion)) {
        die("Error: No se pudo establecer la conexión a la base de datos.");
    }

    $sql = $conexion->prepare("SELECT * FROM Usuario WHERE nombre LIKE ? OR email LIKE ?");
    if ($sql) {
        $param = "%$criterio%";
        $sql->bind_param("ss", $param, $param);
        $sql->execute();
        $resultado = $sql->get_result();

        if ($resultado->num_rows > 0) {
            echo "<h2 class='mt-4'>Resultados de la búsqueda:</h2>";
            echo '<div class="row mt-3">'; // Contenedor para las tarjetas
            while ($fila = $resultado->fetch_assoc()) {
                echo '<div class="col-md-6 mb-4">'; // Cada tarjeta ocupa la mitad del ancho en pantallas medianas
                echo '<div class="card shadow-lg" style="border-radius: 15px;">';
                echo '<div class="card-body">';
                echo '<h5 class="card-title text-primary">' . htmlspecialchars($fila["nombre"] ?? '') . ' ' . htmlspecialchars($fila["apellidos"] ?? '') . '</h5>';
                echo '<p class="card-text"><strong>Email:</strong> ' . htmlspecialchars($fila["email"] ?? '') . '</p>';
                echo '<p class="card-text"><strong>Teléfono:</strong> ' . htmlspecialchars($fila["telefono"] ?? '') . '</p>';
                echo '<p class="card-text"><strong>Tipo de Usuario:</strong> ' . htmlspecialchars($fila["tipo_usuario"] ?? '') . '</p>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
            echo '</div>'; // Cierra el contenedor de las tarjetas
        } else {
            echo "<p class='mt-3 text-danger'>No se encontraron usuarios que coincidan con el criterio de búsqueda.</p>";
        }

        $sql->close();
    } else {
        echo "<p class='mt-3 text-danger'>Error al preparar la consulta.</p>";
    }

    $conexion->close();
}
?>
<a class="btn btn-secondary mt-3" href="<?php echo obtenerEnlaceVolver(); ?>">Volver</a>  
   </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous">
    </script>
</body>

</html>