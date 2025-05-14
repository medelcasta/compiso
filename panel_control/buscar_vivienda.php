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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script> window.chtlConfig = { chatbotId: "2783453492" } </script>
    <script async data-id="2783453492" id="chatling-embed-script" type="text/javascript" src="https://chatling.ai/js/embed.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        .form-label {
            font-weight: bold;
            color: #555;
        }

        .form-control {
            border-radius: 25px;
            padding: 10px 15px;
            border: 1px solid #ccc;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .form-control:focus {
            border-color: #4CAF50;
            box-shadow: 0 0 5px rgba(76, 175, 80, 0.5);
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .card-header {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
            font-weight: bold;
        }

        .card-body {
            display: flex;
            flex-wrap: wrap;
            padding: 15px;
            background-color: #f9f9f9;
        }

        .left-section, .right-section {
            flex: 1 1 50%;
            padding: 10px;
        }

        .left-section {
            border-right: 1px solid #ddd;
        }

        .card-footer {
            background-color: #e8f5e9;
            padding: 15px;
            border-bottom-left-radius: 15px;
            border-bottom-right-radius: 15px;
        }

        .card-text img {
            margin-right: 8px;
        }

        .btn-primary, .btn-secondary {
            width: 100%;
            max-width: 200px;
            display: inline-block;
            text-align: center;
        }

        .btn-primary {
            background-color: #4CAF50;
            border: none;
            border-radius: 25px;
            padding: 10px 20px;
            font-size: 16px;
            font-weight: bold;
            color: #fff;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .btn-primary:hover {
            background-color: #45a049;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background-color: #6c757d;
            border: none;
            border-radius: 25px;
            padding: 10px 20px;
            font-size: 16px;
            font-weight: bold;
            color: #fff;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
            transform: translateY(-2px);
        }

        .button-container {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 20px;
        }
    </style>
</head>

<body>
<div class="container mt-5">
    <h1 class="mb-4">Búsqueda de Vivienda</h1>

    <form method="POST" action="">
        <div class="mb-3">
            <label for="criterio" class="form-label">Buscar por Dirección o Ciudad:</label>
            <input type="text" class="form-control" id="criterio" name="criterio" placeholder="Introduce dirección o ciudad">
        </div>
        <div class="button-container">
            <button type="submit" class="btn btn-primary">Buscar</button>
            <a class="btn btn-secondary" href="<?php echo obtenerEnlaceVolver(); ?>">Volver</a>
            <br>
        </div>
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["criterio"]) && trim($_POST["criterio"]) !== "") {
        $criterio = $_POST["criterio"];

        $sql = $_conexion->prepare("SELECT id_vivienda, direccion, ciudad, descripcion, precio, habitaciones, banos, disponibilidad FROM Vivienda WHERE direccion LIKE ? OR ciudad LIKE ?");

        if ($sql) {
            $param = "%$criterio%";
            $sql->bind_param("ss", $param, $param);
            $sql->execute();
            $resultado = $sql->get_result();

            if ($resultado->num_rows > 0) {
                while ($fila = $resultado->fetch_assoc()) {
                    echo '<div class="card shadow-lg">';
                    echo '<div class="card-header">' . htmlspecialchars($fila["direccion"] ?? '') . ' - ' . htmlspecialchars($fila["ciudad"] ?? '') . '</div>';
                    echo '<div class="card-body">';
                    echo '<div class="left-section">';
                    echo '<p class="card-text"><img src="../images/precio.png" width="30px"> ' . htmlspecialchars($fila["precio"] ?? '') . '</p>';
                    echo '<p class="card-text"><strong>Disponibilidad:</strong> ';
                    echo $fila["disponibilidad"] ? '<img src="../images/disponible.png" width="30px">' : '<img src="../images/ocupado.png" width="30px">';
                    echo '</p>';
                    echo '</div>';
                    echo '<div class="right-section">';
                    echo '<p class="card-text"><img src="../images/habitaciones.png" width="30px"> ' . htmlspecialchars($fila["habitaciones"] ?? '') . '</p>';
                    echo '<p class="card-text"><img src="../images/banos.png" width="30px"> ' . htmlspecialchars($fila["banos"] ?? '') . '</p>';
                    echo '</div>';
                    echo '</div>';
                    echo '<div class="card-footer">';
                    echo '<p class="card-text"><strong>Descripción:</strong> ' . htmlspecialchars($fila["descripcion"] ?? '') . '</p>';
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
