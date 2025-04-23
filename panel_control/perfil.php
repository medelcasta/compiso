<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Información del Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <?php
    error_reporting(E_ALL);
    ini_set("display_errors", 1);

    require('../utiles/conexion.php');
    ?>
    <script> window.chtlConfig = { chatbotId: "2783453492" } </script>
    <script async data-id="2783453492" id="chatling-embed-script" type="text/javascript" src="https://chatling.ai/js/embed.js"></script>
</head>

<body>
    <div class="container mt-5">
        <?php
        if (isset($_GET["id_usuario"])) {
            $id_usuario = $_GET["id_usuario"];
            
            // Consultar la información del usuario
            $sql = $_conexion->prepare("SELECT nombre, apellidos, email, telefono, tipo_usuario, descripcion FROM Usuario WHERE id_usuario = ?");
            $sql->bind_param("s", $id_usuario);
            $sql->execute();
            $resultado = $sql->get_result();

            if ($resultado->num_rows > 0) {
                $usuario = $resultado->fetch_assoc();

                // Convertir tipo_usuario de número a texto
                $tipo_usuario_texto = "";
                if ($usuario["tipo_usuario"] == 1) {
                    $tipo_usuario_texto = "Inquilino";
                } elseif ($usuario["tipo_usuario"] == 2) {
                    $tipo_usuario_texto = "Propietario";
                } else {
                    $tipo_usuario_texto = "Desconocido";
                }

                echo "<h1>Detalles del Usuario</h1>";
                echo "<p><strong>Nombre:</strong> " . $usuario["nombre"] . "</p>";
                echo "<p><strong>Apellidos:</strong> " . $usuario["apellidos"] . "</p>";
                echo "<p><strong>Email:</strong> " . $usuario["email"] . "</p>";
                echo "<p><strong>Teléfono:</strong> " . $usuario["telefono"] . "</p>";
                echo "<p><strong>Tipo de Usuario:</strong> " . $tipo_usuario_texto . "</p>";
                echo "<p><strong>Descripción:</strong> " . (!empty($usuario["descripcion"]) ? $usuario["descripcion"] : "Sin descripción") . "</p>";

                // Botón de Enviar Mensaje (sin funcionalidad por el momento)
                echo '<form action="#" method="post">
                        <button type="submit" class="btn btn-primary">Enviar mensaje</button>
                      </form>';
            } else {
                echo "<p>No se encontró información para el usuario seleccionado.</p>";
            }
        } else {
            echo "<p>Usuario no encontrado.</p>";
        }

        // Cerrar conexión
        $_conexion->close();
        ?>
        <a class="btn btn-secondary" href="../inicio.php">Volver</a>
    </div>
</body>

</html>
