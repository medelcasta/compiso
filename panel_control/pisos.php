<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compiso</title>
    <link rel="icon" type="image/jpg" href="/images/logo_compiso.ico"/>
    <link rel="stylesheet" href="./css/estilos.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script> window.chtlConfig = { chatbotId: "2783453492" } </script>
    <script async data-id="2783453492" id="chatling-embed-script" type="text/javascript" src="https://chatling.ai/js/embed.js"></script>
</head>
<body>
    <div class="container mt-4">
        <h1 class="header text-center">Compiso</h1>
        <div class="row">
        <?php
    require('../utiles/conexion.php');
    require("../utiles/volver.php");

    session_start();
    if (!isset($_SESSION["usuario"])) {
        echo "No has iniciado sesión.";
        exit;
    }

    error_reporting(E_ALL);
    ini_set("display_errors", 1);

    // Obtener el id_usuario de la sesión
    $nombre_usuario = $_SESSION["usuario"];
    $sql_usuario = "SELECT id_usuario FROM Usuario WHERE nombre = ?";
    $stmt_usuario = $_conexion->prepare($sql_usuario);
    $stmt_usuario->bind_param("s", $nombre_usuario);
    $stmt_usuario->execute();
    $resultado_usuario = $stmt_usuario->get_result();

    if ($resultado_usuario->num_rows == 0) {
        echo "<p class='text-center'>No se encontró el usuario en la base de datos.</p>";
        exit;
    }

    $usuario = $resultado_usuario->fetch_assoc();
    $id_usuario = $usuario["id_usuario"];
    $stmt_usuario->close();

    // Obtener el id_propietario correspondiente al usuario
    $sql_propietario = "SELECT id_propietario FROM Propietario WHERE id_usuario = ?";
    $stmt_propietario = $_conexion->prepare($sql_propietario);
    $stmt_propietario->bind_param("i", $id_usuario);
    $stmt_propietario->execute();
    $resultado_propietario = $stmt_propietario->get_result();

    if ($resultado_propietario->num_rows == 0) {
        echo "<p class='text-center'>No se encontraron propiedades asociadas a este usuario.</p>";
        exit;
    }

    $propietario = $resultado_propietario->fetch_assoc();
    $id_propietario = $propietario["id_propietario"];
    $stmt_propietario->close();

    // Filtrar viviendas por id_propietario
    $sql = "SELECT * FROM Vivienda WHERE id_propietario = ?";
    $stmt_vivienda = $_conexion->prepare($sql);
    $stmt_vivienda->bind_param("i", $id_propietario);
    $stmt_vivienda->execute();
    $result = $stmt_vivienda->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<div class="col-md-4 mb-4">';
            echo '<div class="card h-100">';
            
            $imagen = !empty($row["imagenes"]) ? $row["imagenes"] : 'default.jpg';
            $ruta_web = "https://compiso.infy.uk/panel_control/uploads/" . $imagen;
            $ruta_local = $_SERVER['DOCUMENT_ROOT'] . "/panel_control/uploads/" . $imagen;
            
            if (!file_exists($ruta_local)) {
                $ruta_web = "https://compiso.infy.uk/panel_control/uploads/default.jpg";
            }
            
            echo '<img src="' . htmlspecialchars($ruta_web) . '" class="card-img-top" alt="Imagen de la vivienda">';
            echo '<div class="card-body">';
            echo '<h5 class="card-title">' . htmlspecialchars($row["direccion"]) . ', ' . htmlspecialchars($row["ciudad"]) . '</h5>';
            echo '<p class="card-text"><strong>Descripción:</strong> ' . htmlspecialchars($row["descripcion"]) . '</p>';
            echo '<p class="card-text"><strong>Precio:</strong> ' . $row["precio"] . ' €</p>';
            echo '<p class="card-text"><strong>Habitaciones:</strong> ' . $row["habitaciones"] . '</p>';
            echo '<p class="card-text"><strong>Baños:</strong> ' . $row["banos"] . '</p>';
            echo '<p class="card-text"><strong>Metros cuadrados:</strong> ' . $row["metros_cuadrados"] . ' m²</p>';
            echo '<p class="card-text"><strong>Disponibilidad:</strong> ' . ($row["disponibilidad"] ? 'Disponible' : 'No disponible') . '</p>';
            echo '<a href="./usuario/iniciar_sesion.php" class="btn btn-primary mt-2">Más Info</a>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
    } else {
        echo "<p class='text-center'>No se encontraron viviendas para este usuario.</p>";
    }

    $stmt_vivienda->close();
    $_conexion->close();
?>


        </div>
        <a class="btn btn-secondary mt-3" href="<?php echo obtenerEnlaceVolver(); ?>">Volver</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


