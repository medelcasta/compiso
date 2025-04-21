<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compiso</title>
    <link rel="icon" type="image/jpg" href="/images/logo_compiso.ico" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <?php
    error_reporting(E_ALL);
    ini_set("display_errors", 1);

    if (!isset($_SESSION["usuario"])) {
        echo "No has iniciado sesión.";
        exit;
    }

    /*session_start();

    if (!isset($_SESSION['usuario_id'])) {
        header("Location: ./usuario/iniciar_sesion.php");
        exit();
    }*/

    ?>
    <script> window.chtlConfig = { chatbotId: "2783453492" } </script>
    <script async data-id="2783453492" id="chatling-embed-script" type="text/javascript"
        src="https://chatling.ai/js/embed.js"></script>
</head>

<body>
    <div class="container text-center mt-5">
        <h1>Bienvenidos</h1>

        <div class="mt-4">
            <a href="./panel_control/completa_perfil.php" class="btn btn-primary mb-2">Completa tu perfil</a>
            <a href="./panel_control/mi_perfil.php" class="btn btn-primary mb-2">Mi perfil</a>
            <a href="./panel_control/pisos.php" class="btn btn-info mb-2">Pisos</a>
            <a href="./panel_control/compis.php" class="btn btn-success mb-2">Compañeros</a>
            <a href="./panel_control/subir_vivienda.php" class="btn btn-info mb-2">Subir Vivienda</a>
            <a href="./panel_control/buscar_vivienda.php" class="btn btn-success mb-2">Buscar Vivienda</a>
            <a href="./panel_control/buscar_usuarios.php" class="btn btn-success mb-2">Buscar Usuario</a>
        </div>

        <div class="mt-4">
            <a href="./usuario/cerrar_session.php" class="btn btn-danger">Cerrar Sesión</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>