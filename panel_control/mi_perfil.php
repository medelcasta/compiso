<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Mi Perfil - Compiso</title>
    <link rel="stylesheet" href="../css/estilos.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script> window.chtlConfig = { chatbotId: "2783453492" } </script>
    <script async data-id="2783453492" id="chatling-embed-script" type="text/javascript"
        src="https://chatling.ai/js/embed.js"></script>
</head>

<body class="bg-light">

    <?php
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
    session_start();

    require('../utiles/conexion.php');
    require('../utiles/depurar.php');

    if (!isset($_SESSION["usuario"])) {
        header("Location: ../usuario/iniciar_sesion.php");
        exit;
    }

    $nombre_sesion = $_SESSION["usuario"];

    $sql = $_conexion->prepare("SELECT * FROM Usuario WHERE nombre = ?");
    $sql->bind_param("s", $nombre_sesion);
    $sql->execute();
    $resultado = $sql->get_result();

    if ($fila = $resultado->fetch_assoc()) {
        $nombre = $fila["nombre"];
        $apellidos = $fila["apellidos"];
        $email = $fila["email"];
        $telefono = $fila["telefono"];
        $tipo_usuario = $fila["tipo_usuario"];
        $fecha_nacimiento = $fila["fecha_nacimiento"];
        $sexo = $fila["sexo"];
        $descripcion = $fila["descripcion"];
        // $foto = $fila["foto"];  // puedes usarlo si decides mostrar la imagen
    } else {
        echo "<div class='alert alert-danger'>No se encontró información del usuario.</div>";
        exit;
    }
    ?>

    <div class="container py-5">
        <div class="text-center mb-4">
            <h2 class="text-primary">Bienvenid@ <?php echo htmlspecialchars($nombre_sesion); ?></h2>
            <h1 class="display-5">Mi Perfil</h1>
        </div>

        <div class="card mx-auto shadow-lg" style="max-width: 600px;">
            <div class="card-body">
                <h4 class="card-title mb-4"><?php echo htmlspecialchars($nombre . ' ' . $apellidos); ?></h4>

                <!-- Si decides mostrar la foto -->
                <!-- <img src="../images/<?php echo htmlspecialchars($foto); ?>" class="img-thumbnail mb-3" alt="Foto de perfil" width="100"> -->

                <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
                <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($telefono); ?></p>
                <p><strong>Tipo de usuario:</strong> <?php echo $tipo_usuario == 1 ? "Inquilino" : "Propietario"; ?></p>
                <p><strong>Fecha de nacimiento:</strong> <?php echo htmlspecialchars($fecha_nacimiento); ?></p>
                <p><strong>Sexo:</strong> <?php echo htmlspecialchars($sexo); ?></p>
                <p><strong>Descripción:</strong> <?php echo nl2br(htmlspecialchars($descripcion)); ?></p>

                <div class="mt-4 text-end">
                    <a href="../usuario/cambiar_credenciales.php" class="btn btn-primary">Cambiar credenciales</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>