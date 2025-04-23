<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Completa tu perfil</title>
    <?php
    session_start();
    if (!isset($_SESSION["usuario"])) {
        echo "No has iniciado sesión.";
        exit;
    }
    ?>
    <script> window.chtlConfig = { chatbotId: "2783453492" } </script>
    <script async data-id="2783453492" id="chatling-embed-script" type="text/javascript"
        src="https://chatling.ai/js/embed.js"></script>
</head>

<body>
    <a class="btn btn-secondary" href="../inicio.php">Volver</a>
</body>

</html>