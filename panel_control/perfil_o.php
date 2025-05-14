<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Información del Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <?php
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
    require('../utiles/conexion.php');
    require("../utiles/volver.php");
    ?>
    <script> window.chtlConfig = { chatbotId: "2783453492" } </script>
    <script async data-id="2783453492" id="chatling-embed-script" type="text/javascript" src="https://chatling.ai/js/embed.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        .container {
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            max-width: 800px;
            margin: 50px auto;
        }

        h1 {
            text-align: center;
            color: #343a40;
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .profile-container {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .profile-container img {
            flex-shrink: 0;
            width: 150px;
            height: auto;
        }

        .profile-details {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .profile-description {
            margin-top: 20px;
            font-size: 16px;
            color: #495057;
            text-align: justify;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }

        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #545b62;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <?php
        if (isset($_GET["usuario_id"])) {
            $id_usuario = $_GET["usuario_id"];

            $sql = $_conexion->prepare("SELECT nombre, apellidos, email, telefono, tipo_usuario, descripcion, imagen FROM Usuario WHERE id_usuario = ?");
            $sql->bind_param("s", $id_usuario);
            $sql->execute();
            $resultado = $sql->get_result();

            if ($resultado->num_rows > 0) {
                $usuario = $resultado->fetch_assoc();

                echo "<h1>Usuario ";
                if ($usuario["tipo_usuario"] == 1) {
                    echo '<img src="../images/inquilino.png" width="50px">';
                } elseif ($usuario["tipo_usuario"] == 2) {
                    echo '<img src="../images/propietario.png" width="50px">';
                } else {
                    echo '<img src="../images/administrador.png" width="50px">';
                }
                echo "</h1>";

                echo "<h4 class='card-title'>" . htmlspecialchars($usuario["nombre"] . ' ' . $usuario["apellidos"]) . "</h4>";

                echo "<div class='profile-container mt-4'>";
                if (!empty($usuario["imagen"])) {
                    echo '<img src="../uploads/' . htmlspecialchars($usuario["imagen"]) . '" class="img-thumbnail mb-3" alt="Foto de perfil">';
                } else {
                    echo '<img src="../images/mujer.jpg" class="img-thumbnail mb-3" width="150px" alt="Foto de perfil predeterminada">';
                }

                echo "<div class='profile-details'>";
                echo "<p><img src='../images/email.png' width='30px'> " . htmlspecialchars($usuario["email"]) . "</p>";
                echo "<p><img src='../images/movil.png' width='30px'> " . htmlspecialchars($usuario["telefono"]) . "</p>";
                echo "</div></div>";

                echo "<p class='profile-description'><strong>Descripción:</strong> " . (!empty($usuario["descripcion"]) ? htmlspecialchars($usuario["descripcion"]) : "Sin descripción") . "</p>";

                echo '<form action="../mensajes/chat.php" method="post">
                        <input type="hidden" name="usuario_id" value="' . htmlspecialchars($id_usuario) . '">
                        <div class="mt-4 text-end">
                            <button type="submit" class="btn btn-primary">Enviar mensaje</button>
                            <a class="btn btn-secondary" href="' . obtenerEnlaceVolver() . '">Volver</a>
                        </div>
                      </form>';
            } else {
                echo "<p>No se encontró información para el usuario seleccionado.</p>";
            }

            $sql->close();
        } else {
            echo "<p>Usuario no encontrado.</p>";
        }

        $_conexion->close();
        ?>
    </div>
</body>

</html>
