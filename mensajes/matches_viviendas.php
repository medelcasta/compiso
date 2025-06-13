<script>
    theme: {
  extend: {
    colors: {
      mint: '#74C69D', // o el valor exacto que estés usando
    },
  },
},

</script>
<?php
session_start();
error_reporting(E_ALL);
ini_set("display_errors", 1);

include "generar_embedding.php";
include "similitud.php";
include "../utiles/conexion.php";
require("../utiles/volver.php");

if (!isset($_SESSION["usuario"])) {
    echo "No has iniciado sesión.";
    exit;
}

$nombre_usuario = $_SESSION["usuario"]["nombre"];
$cohere_api_key = "jjk20HgHxNuESvPj07QobKOHc1CO7LZFnLeLW5EY";

$sql_usuario = "
    SELECT u.descripcion, i.preferencias_piso
    FROM Usuario u
    LEFT JOIN Inquilino i ON u.id_usuario = i.id_usuario
    WHERE u.nombre = ?
";
$stmt = $_conexion->prepare($sql_usuario);
$stmt->bind_param("s", $nombre_usuario);
$stmt->execute();
$result_usuario = $stmt->get_result();

if ($result_usuario->num_rows == 0) {
    echo "Usuario no encontrado o sin descripción.";
    exit;
}

$usuario_data = $result_usuario->fetch_assoc();
$descripcion_usuario = $usuario_data["descripcion"] ?? "";
$preferencias_usuario = $usuario_data["preferencias_piso"] ?? "";

$texto_usuario = trim($descripcion_usuario . " " . $preferencias_usuario);
$vector_usuario = obtener_embedding($texto_usuario, $cohere_api_key);

$sql_viviendas = "
    SELECT 
        id_vivienda, 
        descripcion, 
        direccion, 
        ciudad,
        precio, 
        habitaciones, 
        banos, 
        metros_cuadrados,
        imagenes
    FROM Vivienda 
    WHERE descripcion IS NOT NULL AND descripcion != ''
";
$result_viviendas = $_conexion->query($sql_viviendas);

$viviendas = [];
while ($row = $result_viviendas->fetch_assoc()) {
    $texto_vivienda = trim(
        $row["descripcion"] . " " .
        $row["direccion"] . " " .
        $row["ciudad"] . " " .
        $row["precio"] . " euros " .
        $row["habitaciones"] . " habitaciones " .
        $row["banos"] . " baños " .
        $row["metros_cuadrados"] . " metros cuadrados"
    );

    $vector = obtener_embedding($texto_vivienda, $cohere_api_key);
    if ($vector) {
        $similitud = similitud_coseno($vector_usuario, $vector);
        if ($similitud > 0.05) {
            $viviendas[] = [
                "id" => $row["id_vivienda"],
                "direccion" => $row["direccion"],
                "ciudad" => $row["ciudad"],
                "precio" => $row["precio"],
                "habitaciones" => $row["habitaciones"],
                "banos" => $row["banos"],
                "metros_cuadrados" => $row["metros_cuadrados"],
                "imagenes" => $row["imagenes"],
                "similitud" => $similitud * 100
            ];
        }
    }
}

usort($viviendas, function ($a, $b) {
    return $b["similitud"] <=> $a["similitud"];
});
?>
<?php
// Tu código PHP igual que antes (no lo repito para centrarme en el front)
?>
<?php
// Tu código PHP igual que antes (no lo repito para centrarme en el front)
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Viviendas compatibles</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        function ampliarImagen(src) {
            const modal = document.getElementById('modal');
            const img = document.getElementById('modal-img');
            img.src = src;
            modal.classList.remove('hidden');
        }
        function cerrarModal() {
            document.getElementById('modal').classList.add('hidden');
        }
    </script>
    <style>
        /* Oculta scroll bar en navegadores basados en WebKit */
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        /* Firefox */
        .scrollbar-hide {
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        /* Animación para el carrusel infinito */
        @keyframes slide-left {
            0% {
                transform: translateX(0);
            }
            100% {
                transform: translateX(-50%);
            }
        }

        /* Contenedor de tarjetas animado */
        .cinta-animada {
            display: flex;
            width: max-content;
            animation: slide-left 40s linear infinite;
        }

        /* Contenedor padre con overflow oculto */
        .cinta-wrapper {
            overflow: hidden;
            width: 100%;
        }
    </style>
</head>
<body class="bg-gray-100 font-sans">
<header class="w-full bg-[#74C69D] shadow-md py-4 px-6">
  <div class="max-w-7xl mx-auto flex items-center justify-between">
    <div class="flex items-center gap-4">
    <a href="/">
        <img src="../images/logo_compiso.png" alt="Logo" class="rounded-lg w-12 h-12">
    </a>
        <h1 class="text-white text-2xl font-bold">Compiso</h1>
    </div>
    <div class="flex items-center gap-2">
        <a href="<?php echo obtenerEnlaceVolver(); ?>" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded transition">Volver</a>
    </div>
  </div>
</header>

<!-- Banner con fondo decorativo reducido -->
<section class="relative w-full h-48 bg-[#40916c] rounded-b-3xl shadow-lg flex items-center justify-center overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-[#74C69D] via-[#74C69D]/80 to-[#74C69D]/60 opacity-80"></div>
    <h1 class="relative z-10 text-white text-3xl sm:text-4xl md:text-5xl font-extrabold text-center drop-shadow-lg px-4">
        Viviendas compatibles para <?php echo htmlspecialchars($nombre_usuario); ?>
    </h1>
</section>



    <div id="modal" class="fixed inset-0 bg-black bg-opacity-80 flex items-center justify-center hidden z-50">
        <img id="modal-img" class="max-h-[90vh] max-w-[90vw] rounded-lg shadow-2xl" />
        <button onclick="cerrarModal()" class="absolute top-4 right-4 text-white text-3xl font-bold">&times;</button>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-8">

        <?php if (empty($viviendas)): ?>
            <p class="text-center text-gray-600 text-xl">No se encontraron viviendas compatibles.</p>
        <?php else: ?>
            <div class="cinta-wrapper">
                <div class="cinta-animada">
                    <?php 
                    // Para que el bucle sea infinito y fluido, repetimos el listado dos veces
                    for ($i = 0; $i < 2; $i++): 
                        foreach ($viviendas as $v): ?>
                            <div class="bg-white rounded-3xl shadow-lg flex-shrink-0 w-80 hover:shadow-2xl transition-shadow duration-300 overflow-hidden cursor-pointer group mx-3">
                                <div onclick="ampliarImagen('<?php echo htmlspecialchars($v["imagenes"] ?: '../img/default_house.jpg'); ?>')">
                                    <img src="../panel_control/uploads/<?php echo htmlspecialchars($v["imagenes"] ?: '../img/default_house.jpg'); ?>"
                                         alt="Imagen vivienda"
                                         class="w-full h-48 object-cover rounded-t-3xl group-hover:scale-105 transition-transform duration-300" />
                                </div>
                                <div class="p-6 flex flex-col space-y-4 max-w-[90%] mx-auto">
                                    <h2 class="text-2xl font-bold text-gray-800">
                                        <?php echo htmlspecialchars($v["direccion"]); ?>, <?php echo htmlspecialchars($v["ciudad"]); ?>
                                    </h2>

                                    <p class="text-green-600 text-2xl font-bold">
                                        <?php echo number_format($v["precio"], 2); ?> €
                                    </p>

                                    <div class="relative inline-block group w-max">
                                        <img src="../images/verde.png" alt="Icono info" class="w-8 h-8 cursor-pointer" />
                                        <div class="absolute left-full top-1/2 -translate-y-1/2 ml-3 hidden group-hover:block bg-white text-gray-800 text-lg font-semibold px-3 py-1 rounded-xl shadow-xl whitespace-nowrap z-10">
                                            Compatibilidad: <?php echo round($v["similitud"], 2); ?>%
                                        </div>
                                    </div>

                                    <form action="../panel_control/detalles_piso.php" method="GET" class="mt-auto">
                                        <input type="hidden" name="id_vivienda" value="<?php echo htmlspecialchars($v["id"]); ?>" />
                                        <button type="submit"
                                                class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-lg text-lg font-semibold">
                                            Ver detalles
                                        </button>
                                    </form>
                                </div>
                            </div>
                    <?php endforeach; endfor; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>
<footer class="bg-black text-white py-6 px-4 mt-auto">
    <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between space-y-4 sm:space-y-0">
        <p class="text-sm">&copy; <?php echo date("Y"); ?> Compiso. Todos los derechos reservados.</p>
        <a href="https://www.instagram.com/compiso_web" target="_blank" rel="noopener noreferrer"
           class="flex items-center space-x-2 hover:text-mint transition-colors duration-300">
            <svg class="w-5 h-5 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path d="M7.75 2h8.5A5.75 5.75 0 0 1 22 7.75v8.5A5.75 5.75 0 0 1 16.25 22h-8.5A5.75 5.75 0 0 1 2 16.25v-8.5A5.75 5.75 0 0 1 7.75 2zm0 1.5A4.25 4.25 0 0 0 3.5 7.75v8.5A4.25 4.25 0 0 0 7.75 20.5h8.5a4.25 4.25 0 0 0 4.25-4.25v-8.5A4.25 4.25 0 0 0 16.25 3.5h-8.5zM12 7a5 5 0 1 1 0 10 5 5 0 0 1 0-10zm0 1.5a3.5 3.5 0 1 0 0 7 3.5 3.5 0 0 0 0-7zm5.25-.25a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
            </svg>
            <span class="text-sm">Instagram</span>
        </a>
    </div>
</footer>
</html>
