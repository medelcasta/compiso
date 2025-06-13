<?php
require('../utiles/conexion.php');
require("../utiles/volver.php");

session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: ../login.php");
    exit;
}

// Mostrar errores en desarrollo
error_reporting(E_ALL);
ini_set("display_errors", 1);

// Obtener nombre del usuario desde el array de sesión
$datos_usuario = $_SESSION["usuario"];
$nombre_usuario = $datos_usuario["nombre"];  // Asegúrate de que en el array se guarda con la clave "nombre"

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

$sql_propietario = "SELECT id_propietario FROM Propietario WHERE id_usuario = ?";
$stmt_propietario = $_conexion->prepare($sql_propietario);
$stmt_propietario->bind_param("s", $id_usuario);
$stmt_propietario->execute();
$resultado_propietario = $stmt_propietario->get_result();

if ($resultado_propietario->num_rows == 0) {
    echo "<p class='text-center'>No se encontraron propiedades asociadas a este usuario.</p>";
    exit;
}

$propietario = $resultado_propietario->fetch_assoc();
$id_propietario = $propietario["id_propietario"];
$stmt_propietario->close();

$sql = "SELECT * FROM Vivienda WHERE id_propietario = ?";
$stmt_vivienda = $_conexion->prepare($sql);
$stmt_vivienda->bind_param("s", $id_propietario);
$stmt_vivienda->execute();
$result = $stmt_vivienda->get_result();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compiso</title>
    <link rel="icon" type="image/jpg" href="/images/logo_compiso.ico" />
    <link rel="stylesheet" href="./css/estilos.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
            max-width: 1200px;
            margin: 50px auto;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }

        .card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
            margin-bottom: 30px;
            overflow: hidden;
            width: 100%;
            min-height: 500px;
        }

        .card-img-top {
            height: 250px;
            object-fit: cover;
        }

        .card-header {
            background-color: #2e86de;
            color: white;
            padding: 20px;
            font-size: 18px;
            font-weight: bold;
            text-align: center;
        }

        .card-body {
            display: flex;
            background-color: #ffffff;
            padding: 20px;
            flex-direction: row;
            justify-content: space-between;
            gap: 20px;
        }

        .left-section,
        .right-section {
            width: 48%;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .left-section {
            border-right: 1px solid #ddd;
            padding-right: 15px;
        }

        .right-section {
            padding-left: 15px;
        }

        .card-footer {
            background-color: #dff9fb;
            padding: 20px;
            font-style: italic;
            font-size: 15px;
            color: #333;
            border-top: 1px solid #ccc;
        }

        .edit-button {
            margin-top: 15px;
            align-self: center;
        }

        .btn-edit {
            background-color: #ffc107;
            color: white;
            border: none;
            border-radius: 25px;
            padding: 10px 20px;
            font-weight: bold;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .btn-edit:hover {
            background-color: #e0a800;
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
    </style>
</head>

<body>
    <div class="container mt-4">
        <h1 class="header text-center">Mis Anuncios</h1>
        <div class="row">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="col-md-6 mb-4">';
                    echo '<div class="card">';

                    $imagen = !empty($row["imagenes"]) ? $row["imagenes"] : 'default.jpg';
                    $ruta_web = "https://compiso.infy.uk/panel_control/uploads/" . $imagen;
                    $ruta_local = $_SERVER['DOCUMENT_ROOT'] . "/panel_control/uploads/" . $imagen;

                    if (!file_exists($ruta_local)) {
                        $ruta_web = "https://compiso.infy.uk/panel_control/uploads/default.jpg";
                    }

                    echo '<img src="' . htmlspecialchars($ruta_web) . '" class="card-img-top" alt="Imagen de la vivienda">';
                    echo '<div class="card-header">' . htmlspecialchars($row["direccion"]) . ', ' . htmlspecialchars($row["ciudad"]) . '</div>';
                    echo '<div class="card-body">';
                    echo '<div class="left-section">';
                    echo '<p> <img src="../images/habitaciones.png" width="30px"> ' . $row["habitaciones"] . '</p>';
                    echo '<p> <img src="../images/banos.png" width="30px">' . $row["banos"] . '</p>';
                    echo '<p><strong>Metros cuadrados:</strong> ' . $row["metros_cuadrados"] . ' m²</p>';
                    echo '</div>';
                    echo '<div class="right-section">';
                    echo '<p><img src="../images/precio.png" width="30px">' . $row["precio"] . ' €</p>';
                    echo '<p><strong>Disponibilidad:</strong> ';
                    echo $row["disponibilidad"] ? '<img src="../images/disponible.png" width="30px">' : '<img src="../images/ocupado.png" width="30px">';
                    echo '</p>';
                    echo '<div class="edit-button">';
                    echo '<a href="../panel_control/cambiar_piso.php?id=' . $row["id_vivienda"] . '" class="btn btn-edit">Editar</a>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                    echo '<div class="card-footer">';
                    echo '<strong>Descripción:</strong> ' . htmlspecialchars($row["descripcion"]);
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