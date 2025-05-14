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
    <title>Búsqueda de Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script> window.chtlConfig = { chatbotId: "2783453492" } </script>
    <script async data-id="2783453492" id="chatling-embed-script" type="text/javascript"
        src="https://chatling.ai/js/embed.js"></script>
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
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        .card-header {
            background-color: #4CAF50;
            color: white;
            padding: 15px;
            font-size: 1.25rem;
            font-weight: bold;
            border-bottom: 1px solid #3e8e41;
        }

        .card-body {
            padding: 20px;
            background-color: #fff;
        }

        .card-text {
            font-size: 1rem;
            color: #555;
        }

        .card-footer {
            background-color: #f1f1f1;
            padding: 15px;
            text-align: center;
            border-top: 1px solid #ddd;
        }

        .btn-primary,
        .btn-secondary {
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

        .btn-primary:active {
            background-color: #3e8e41;
            transform: translateY(0);
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

        .btn-secondary:active {
            background-color: #4e555b;
            transform: translateY(0);
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h1 class="mb-4">Búsqueda de Usuario</h1>

        <form method="POST" action="">
            <div class="mb-3">
                <label for="criterio" class="form-label">Introduce Nombre o Email:</label>
                <input type="text" class="form-control" id="criterio" name="criterio"
                    placeholder="Introduce nombre o email">
            </div>
            <button type="submit" class="btn btn-primary">Buscar</button>
            <button type="submit" class="btn btn-primary" name="mostrar_todos" value="1">Mostrar todos</button>
            <a class="btn btn-secondary" href="<?php echo obtenerEnlaceVolver(); ?>">Volver</a>
        </form>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST" && (isset($_POST["criterio"]) || isset($_POST["mostrar_todos"]))) {
            $criterio = $_POST["criterio"] ?? '';
            if (isset($_POST["mostrar_todos"])) {
                $sql = $_conexion->prepare("SELECT * FROM Usuario");
            } else {
                $sql = $_conexion->prepare("SELECT * FROM Usuario WHERE nombre LIKE ? OR email LIKE ?");
            }

            if ($sql) {
                if (!isset($_POST["mostrar_todos"])) {
                    $param = "%$criterio%";
                    $sql->bind_param("ss", $param, $param);
                }
                $sql->execute();
                $resultado = $sql->get_result();

                if ($resultado->num_rows > 0) {
                    echo '<div class="row mt-3">';
                    while ($fila = $resultado->fetch_assoc()) {
                        echo '<div class="col-md-12 mb-4">';
                        echo '<div class="card shadow-lg">';
                        echo '<div class="card-header">';
                        if ($fila["tipo_usuario"] == 1) {
                            echo '<img src="../images/inquilino.png" width="50px">';
                        } elseif ($fila["tipo_usuario"] == 2) {
                            echo '<img src="../images/propietario.png" width="50px">';
                        } else {
                            echo '<img src="../images/administrador.png" width="50px">';
                        }
                        echo htmlspecialchars($fila["nombre"] ?? '') . ' ' . htmlspecialchars($fila["apellidos"] ?? '');
                        echo '</div>';
                        echo '<div class="card-body">';
                        echo '<p class="card-text"><img src="../images/email.png" width="30px"> ' . htmlspecialchars($fila["email"] ?? '') . '</p>';
                        echo '<p class="card-text"><img src="../images/movil.png" width="30px"> ' . htmlspecialchars($fila["telefono"] ?? '') . '</p>';
                        echo '</div>';
                        echo '<div class="card-footer">';

                        echo '<a href="../conversaciones/dialogo.php?usuario_id=' . urlencode($fila["id_usuario"]) . '" class="btn btn-primary">Enviar mensaje</a>';

                        echo '<a href="perfil_o.php?usuario_id=' . urlencode($fila["id_usuario"]) . '" class="btn btn-secondary">Ver perfil</a>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                    }
                    echo '</div>';
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