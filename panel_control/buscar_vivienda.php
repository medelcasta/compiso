<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
require('../utiles/conexion.php');
require("../utiles/volver.php");

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
        <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9; /* Fondo claro */
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            background: #fff; /* Fondo blanco para el contenedor */
            padding: 20px;
            border-radius: 10px; /* Bordes redondeados */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Sombra suave */
        }

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
        .card {
            border: none;
            border-radius: 15px; /* Bordes redondeados */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Sombra suave */
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px); /* Efecto de elevación */
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2); /* Sombra más intensa */
        }

        .card-title {
            font-size: 1.5rem;
            font-weight: bold;
            color: #4CAF50; /* Color verde atractivo */
        }

        .card-text {
            font-size: 1rem;
            color: #555; /* Color gris para el texto */
        }
        .btn-primary,
    .btn-secondary {
        width: 100%; /* Ambos botones tendrán el mismo ancho */
        max-width: 200px; /* Ancho máximo para evitar que sean demasiado grandes */
        display: inline-block; /* Asegura que se comporten como elementos en línea */
        text-align: center; /* Centra el texto dentro del botón */
    }

    .btn-primary {
        background-color: #4CAF50; /* Color verde atractivo */
        border: none;
        border-radius: 25px; /* Bordes redondeados */
        padding: 10px 20px;
        font-size: 16px;
        font-weight: bold;
        color: #fff; /* Texto blanco */
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

    .btn-secondary {
        background-color: #6c757d; /* Color gris */
        border: none;
        border-radius: 25px; /* Bordes redondeados */
        padding: 10px 20px;
        font-size: 16px;
        font-weight: bold;
        color: #fff; /* Texto blanco */
        transition: background-color 0.3s ease, transform 0.2s ease;
    }

    .btn-secondary:hover {
        background-color: #5a6268; /* Color más oscuro al pasar el cursor */
        transform: translateY(-2px); /* Efecto de elevación */
    }

    .btn-secondary:active {
        background-color: #4e555b; /* Color más oscuro al hacer clic */
        transform: translateY(0); /* Sin elevación */
    }

    .button-container {
        display: flex; /* Alinea los botones en fila */
        justify-content: center; /* Centra los botones horizontalmente */
        gap: 15px; /* Espacio entre los botones */
        margin-top: 20px; /* Espaciado superior */
    }
</style>
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
            <a class="btn btn-secondary" href="<?php echo obtenerEnlaceVolver(); ?>">Volver</a>
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
                    echo '<div class="row mt-3">';
                    while ($fila = $resultado->fetch_assoc()) {
                        echo '<div class="col-md-12 mb-12">'; // Cada tarjeta ocupa la mitad del ancho en pantallas medianas
                                echo '<div class="card shadow-lg" style="border-radius: 15px;">';
                                echo '<div class="card-body">';
                                echo '<h5 class="card-title text-primary">' . htmlspecialchars($fila["direccion"] ?? '') . ' ' . htmlspecialchars($fila["apellidos"] ?? '') . '</h5>';
                                echo '<p class="card-text"><strong>Ciudad:</strong> ' . htmlspecialchars($fila["ciudad"] ?? '') . '</p>';
                                echo '<p class="card-text"><strong>Descripcion:</strong> ' . htmlspecialchars($fila["descripcion"] ?? '') . '</p>';
                                echo '<p class="card-text"><strong>Precio:</strong> ' . htmlspecialchars($fila["precio"] ?? '') . '</p>';
                                echo '<p class="card-text"><strong>Habitaciones:</strong> ' . htmlspecialchars($fila["habitaciones"] ?? '') . '</p>';
                                echo '<p class="card-text"><strong>Baños:</strong> ' . htmlspecialchars($fila["banos"] ?? '') . '</p>';
                                echo '<p class="card-text"><strong>Disponibilidad:</strong> ' . ($fila["disponibilidad"] ? "Disponible" : "No disponible") . '</p>';
                                echo '</div>';
                                echo '</div>';
                                echo '</div>';
                    }
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
 </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>