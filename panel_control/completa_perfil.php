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

    require("../utiles/conexion.php");
    require("../utiles/volver.php");

    // Obtener información del usuario actual
    $usuario_en_sesion = $_SESSION["usuario"];
    $sql = "SELECT id_usuario, descripcion FROM Usuario WHERE nombre = '$usuario_en_sesion'";
    $result = $_conexion->query($sql);

    if ($result->num_rows == 0) {
        echo "No se encontró al usuario.";
        exit;
    }

    $usuario = $result->fetch_assoc();
    $id_usuario = $usuario["id_usuario"];
    $descripcion_actual = $usuario["descripcion"];
    ?>
    <script> window.chtlConfig = { chatbotId: "2783453492" } </script>
    <script async data-id="2783453492" id="chatling-embed-script" type="text/javascript"
        src="https://chatling.ai/js/embed.js"></script>
<style>
    /* Estilos generales */
    body {
        font-family: Arial, sans-serif;
        color: white;
        margin: 0;
        padding: 0;
        background-color: #f4f4f4; /* Fondo general claro */
    }

    /* Contenedor principal */
    .container {
        max-width: 600px;
        background-color:rgb(86, 174, 130); /* Fondo blanco con opacidad */
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.3); /* Efecto de luz y profundidad */
        margin: 50px auto;
        text-align: center;
    }

    /* Título */
    h2 {
        font-size: 24px;
        color: #333; /* Color oscuro para el texto */
        margin-bottom: 20px;
    }

    /* Etiquetas del formulario */
    .form-label {
        font-size: 16px;
        font-weight: bold;
        color: #555; /* Color gris oscuro */
    }

    /* Campos de entrada */
    input[type="text"] {
        width: calc(100% - 20px); /* Ajusta el ancho para que no exceda el contenedor */
        max-width: 100%; /* Asegura que no exceda el contenedor */
        padding: 10px;
        border: 2px solid #ccc; /* Borde gris claro */
        border-radius: 5px;
        margin-bottom: 15px;
        box-sizing: border-box; /* Incluye el padding y el borde en el ancho total */
    }

    /* Botón principal */
    .btn-primary {
        background-color: #74C69D;
        border: none;
        padding: 10px 15px;
        color: white;
        font-size: 16px;
        border-radius: 5px;
        cursor: pointer;
        transition: background 0.3s;
    }

    .btn-primary:hover {
        background-color: #198754;
    }

    /* Botón de volver */
    .btn-secondary {
        background-color: #0D6EFD;
        border: none;
        padding: 10px 15px;
        color: white;
        font-size: 16px;
        border-radius: 5px;
        cursor: pointer;
        display: block;
        margin: 20px auto;
        width: fit-content;
    }

    .btn-secondary:hover {
        background-color: #0056b3;
    }
</style>
    

  
</head>

<body>
    <div class="container mt-5">
        <h2>Completa tu perfil</h2>
        <form action="completa_perfil.php" method="POST">
            <input type="hidden" name="id_usuario" value="<?php echo $id_usuario; ?>">

            <div class="mb-3">
                <label class="form-label">¿Cuál es tu hobby favorito?</label>
                <input type="text" class="form-control" name="hobby">
            </div>

            <div class="mb-3">
                <label class="form-label">¿Qué tipo de música te gusta?</label>
                <input type="text" class="form-control" name="musica">
            </div>

            <div class="mb-3">
                <label class="form-label">¿Cómo describirías tu personalidad en pocas palabras?</label>
                <input type="text" class="form-control" name="personalidad">
            </div>

            <div class="mb-3">
                <label class="form-label">¿Cuál es tu destino de viaje soñado?</label>
                <input type="text" class="form-control" name="viaje">
            </div>

            <button type="submit" class="btn btn-primary">Actualizar perfil</button>
            <a class="btn btn-secondary" href="<?php echo obtenerEnlaceVolver(); ?>">Volver</a>
        </form>

        
    </div>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id_usuario = $_POST["id_usuario"];
        $hobby = $_POST["hobby"] ?? "";
        $musica = $_POST["musica"] ?? "";
        $personalidad = $_POST["personalidad"] ?? "";
        $viaje = $_POST["viaje"] ?? "";

        // Obtener la descripción actual del usuario
        $sql = "SELECT descripcion FROM Usuario WHERE id_usuario = '$id_usuario'";
        $result = $_conexion->query($sql);
        if ($result->num_rows == 0) {
            echo "Error: Usuario no encontrado.";
            exit;
        }

        $row = $result->fetch_assoc();
        $descripcion_actual = $row["descripcion"] ?? "";

        // Construir nueva descripción
        $nueva_descripcion = trim($descripcion_actual . " Hobby: $hobby. Música: $musica. Personalidad: $personalidad. Viaje soñado: $viaje.");

        // Actualizar la descripción en la base de datos
        $sql_update = "UPDATE Usuario SET descripcion = '$nueva_descripcion' WHERE id_usuario = '$id_usuario'";
     
        if ($_conexion->query($sql_update)) {
            echo "<div style='text-align:center; margin-top:20px;'><h2>✅ Perfil actualizado exitosamente.</h2></div>";
        } else {
            echo "<div style='text-align:center; margin-top:20px;'><h2>❌ Error al actualizar el perfil: " . $_conexion->error . "</h2></div>";
        }

        $_conexion->close();
    }
    ?>
</body>

</html>
