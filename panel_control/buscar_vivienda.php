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
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        mint: '#74C69D',
                        tealCustom: '#40916c'
                    }
                }
            }
        }
    </script>
    <script> window.chtlConfig = { chatbotId: "2783453492" } </script>
    <script async data-id="2783453492" id="chatling-embed-script" type="text/javascript"
        src="https://chatling.ai/js/embed.js"></script>
</head>

<body class="bg-gray-100 font-sans">
    <div class="max-w-3xl mx-auto mt-12 bg-white p-8 rounded-2xl shadow">
        <h1 class="text-3xl font-bold text-center text-gray-800 mb-8">Búsqueda de Vivienda</h1>

        <form method="POST" action="" class="space-y-6">
            <div>
                <label for="criterio" class="block text-sm font-semibold text-gray-700">Buscar por Dirección o
                    Ciudad:</label>
                <input type="text" id="criterio" name="criterio" placeholder="Introduce dirección o ciudad"
                    class="mt-2 w-full px-5 py-3 border border-gray-300 rounded-full shadow-sm focus:outline-none focus:ring-2 focus:ring-mint">
            </div>
            <div class="flex flex-wrap gap-4">
                <button type="submit"
                    class="flex-1 bg-mint hover:bg-tealCustom text-white font-semibold py-2 px-4 rounded-full transition-transform transform hover:-translate-y-1">
                    Buscar
                </button>
                <button type="submit" name="mostrar_todos" value="1"
                    class="flex-1 bg-mint hover:bg-tealCustom text-white font-semibold py-2 px-4 rounded-full transition-transform transform hover:-translate-y-1">
                    Mostrar todos
                </button>
                <a href="<?php echo obtenerEnlaceVolver(); ?>"
                    class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-full text-center transition-transform transform hover:-translate-y-1">
                    Volver
                </a>
            </div>
        </form>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST" && (isset($_POST["criterio"]) || isset($_POST["mostrar_todos"]))) {
            $criterio = $_POST["criterio"] ?? '';
            if (isset($_POST["mostrar_todos"])) {
                $sql = $_conexion->prepare("SELECT * FROM Vivienda");
            } else {
                $sql = $_conexion->prepare("SELECT * FROM Vivienda WHERE direccion LIKE ? OR ciudad LIKE ?");
            }

            if ($sql) {
                if (!isset($_POST["mostrar_todos"])) {
                    $param = "%$criterio%";
                    $sql->bind_param("ss", $param, $param);
                }
                $sql->execute();
                $resultado = $sql->get_result();

                if ($resultado->num_rows > 0) {
                    while ($row = $resultado->fetch_assoc()) {
                        echo '<div class="col-md-4 mb-4">'; // Reduciendo el tamaño de las tarjetas
                        echo '<div class="card" style="max-width: 300px;">'; // Limitar el tamaño máximo
        
                        $imagen = !empty($row["imagenes"]) ? $row["imagenes"] : 'default.jpg';
                        $ruta_web = "https://compiso.infy.uk/panel_control/uploads/" . $imagen;

                        $ruta_local = $_SERVER['DOCUMENT_ROOT'] . "/panel_control/uploads/" . $imagen;

                        if (!file_exists($ruta_local)) {
                            $ruta_web = "https://compiso.infy.uk/panel_control/uploads/default.jpg";
                        }

                        echo '<img src="' . htmlspecialchars($ruta_web) . '" class="card-img-top" alt="Imagen de la vivienda" style="width: 100%; height: 180px; object-fit: cover;">'; // Ajustando la imagen
                        echo '<div class="bg-white rounded-2xl shadow-md p-6">';
                        echo '<h2 class="text-xl font-semibold text-mint mb-2">' . htmlspecialchars($row["direccion"] ?? '') . ' - ' . htmlspecialchars($fila["ciudad"] ?? '') . '</h2>';
                        echo '<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">';
                        echo '<div>';
                        echo $row["disponibilidad"] ? '<img src="../images/disponible.png" width="30px">' : '<img src="../images/ocupado.png" width="30px">';
                        echo '</p>';
                        echo '</div>';
                        echo '</div>';
                        echo '<p class="mt-4 text-gray-600"><strong>Descripción:</strong> ' . htmlspecialchars($row["descripcion"] ?? '') . '</p>';
                        echo '</div>';
                        echo '<div class="mt-4 text-right">';
                        echo '<a href="../panel_control/detalles_piso.php?id_vivienda=' . urlencode($row["id_vivienda"]) . '" class="inline-block bg-tealCustom text-white px-4 py-2 rounded-full hover:bg-mint transition-transform transform hover:-translate-y-1">';
                        echo 'Ver más';
                        echo '</a>';
                        echo '</div>';
                    }
                    echo '</div>';
                }
            } else {
                echo "<p class='text-center'>No se encontraron viviendas.</p>";
            }

            $sql->close();
        } else {
            // echo "<p class='text-red-600 mt-6'>Error al preparar la consulta.</p>";
        }

        $_conexion->close();
        ?>
    </div>
</body>

</html>