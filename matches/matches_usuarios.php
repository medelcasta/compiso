<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Mostrar Matches</title>
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

    $nombre_usuario_en_sesion = $_SESSION["usuario"]["nombre"];
    $id_usuario_en_sesion = $_SESSION["usuario"]["id_usuario"];
    $query_suscripcion = "SELECT suscripcion FROM Usuario WHERE id_usuario = '$id_usuario_en_sesion'";
    $res_suscripcion = $_conexion->query($query_suscripcion);
    $suscripcion_usuario = "";
    if ($res_suscripcion && $row_s = $res_suscripcion->fetch_assoc()) {
        $suscripcion_usuario = $row_s["suscripcion"];
    }


    ?>
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
</head>

<body class="bg-gray-100 font-sans">

    <header class="w-full bg-mint shadow-md py-4 px-6">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="/">
                    <img src="../images/logo_compiso.png" alt="Logo" class="rounded-lg w-12 h-12">
                </a>
                <h1 class="text-white text-2xl font-bold">Compiso</h1>
            </div>
            <div class="flex items-center gap-2">
                <a href="<?php echo obtenerEnlaceVolver(); ?>"
                    class="border border-white text-white px-4 py-2 rounded-lg hover:bg-white hover:text-mint transition">Volver</a>
            </div>
        </div>
    </header>
    <div class="bg-[#40916c] text-white py-6 shadow">
        <h1 class="text-3xl font-bold text-center">
            Matches para <?php echo htmlspecialchars($nombre_usuario_en_sesion); ?>
        </h1>
    </div>
    <div class="container mx-auto px-4 py-10 relative">
        <img src="../images/info.png" alt="Ayuda" class="w-8 h-8 cursor-pointer absolute top-5 right-5"
            onclick="document.getElementById('modalAyuda').classList.remove('hidden')" />
        <div id="modalAyuda" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
            <div class="bg-white rounded-xl shadow-lg w-11/12 max-w-md p-6 relative">
                <h2 class="text-xl font-bold mb-4 text-center text-gray-800">Instrucciones</h2>
                <p class="text-gray-700 text-sm mb-4">
                    Haz clic en la imagen verde para ver tu similitud.<br>
                    Cuanto más alto sea el porcentaje, más compatibles son los perfiles.<br><br>
                    <b>¡A POR TU COMPISO!</b>
                </p>
                <div class="text-center">
                    <button class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 transition"
                        onclick="document.getElementById('modalAyuda').classList.add('hidden')">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
        <?php
        $cohere_api_key = "W1BsiighNdMkjVNl52ZuYKGwTou5esNsIShOIbdi";
        $sql = "SELECT id_usuario, nombre, descripcion FROM Usuario WHERE descripcion IS NOT NULL AND descripcion != ''";
        $result = $_conexion->query($sql);
        if ($result->num_rows < 2) {
            echo "Se necesitan al menos dos usuarios con descripción para hacer comparaciones.";
            exit;
        }
        $usuarios = [];
        while ($row = $result->fetch_assoc()) {
            $vector = obtener_embedding($row["descripcion"], $cohere_api_key);
            if ($vector) {
                $usuarios[] = [
                    "id" => $row["id_usuario"],
                    "nombre" => $row["nombre"],
                    "vector" => $vector
                ];
            }
        }
        $usuario_actual = null;
        foreach ($usuarios as $u) {
            if ($u["nombre"] === $nombre_usuario_en_sesion) {
                $usuario_actual = $u;
                break;
            }
        }
        if (!$usuario_actual) {
            echo "No se encontró al usuario logueado con descripción válida.";
            exit;
        }
        $matches = [];
        foreach ($usuarios as $otro) {
            if ($otro["nombre"] !== $usuario_actual["nombre"]) {
                $sim = similitud_coseno($usuario_actual["vector"], $otro["vector"]);
                if ($sim > 0.1) {
                    $matches[] = [
                        "id2" => $otro["id"],
                        "nombre1" => $usuario_actual["nombre"],
                        "nombre2" => $otro["nombre"],
                        "similitud" => $sim * 100
                    ];
                }
            }
        }
        usort($matches, function ($a, $b) {
            return $b["similitud"] <=> $a["similitud"];
        });
        function renderDestacados($matches)
        {
            foreach (array_slice($matches, 0, 3) as $m) {
                echo '
                <div class="w-full sm:w-1/2 md:w-1/3 p-4">
                    <div class="bg-white rounded-2xl shadow-lg p-6 text-center hover:shadow-2xl transition relative">
                        <span class="absolute top-2 right-2 text-yellow-400 text-xl">&#11088;</span>
                        <div class="relative group cursor-pointer mx-auto w-32 h-32">
                            <img src="../images/verde.png" alt="Match" class="w-full h-full object-contain transition-opacity group-hover:opacity-0" />
                            <div class="absolute inset-0 flex items-center justify-center text-xl font-bold text-black hidden group-hover:flex">
                                ' . round($m["similitud"], 2) . '%
                            </div>
                        </div>
                        <a href="../panel_control/perfil.php?id_usuario=' . $m["id2"] . '" class="block mt-4 text-lg font-semibold text-green-600 hover:underline">
                            ' . htmlspecialchars($m["nombre2"]) . '
                        </a>
                    </div>
                </div>';
            }
        }
        if (empty($matches)) {
            echo "<p class='text-center text-gray-600'>No se encontraron matches con similitud significativa.</p>";
        } else {
            if ($suscripcion_usuario === 'premium') {
                echo '<h2 class="text-xl sm:text-2xl font-semibold text-white bg-[#74C69D] rounded-md px-4 py-2 text-center my-6">Perfiles Destacados</h2>';
                echo '<div class="flex flex-wrap justify-center">';
                renderDestacados($matches);
                echo '</div>';
            }
            echo '<h2 class="text-xl sm:text-2xl font-semibold text-white bg-[#74C69D] rounded-md px-4 py-2 text-center mt-12 mb-6">Todos los Matches</h2>';
            echo '<div id="matches-carousel" class="flex justify-center">';
            foreach ($matches as $index => $m) {
                echo '
                <div class="match-card w-full sm:w-2/3 md:w-1/3 p-4 hidden" data-index="' . $index . '">
                    <div class="bg-white rounded-2xl shadow-lg p-6 text-center hover:shadow-2xl transition relative">
                        <div class="relative group cursor-pointer mx-auto w-32 h-32">
                            <img src="../images/verde.png" alt="Match" class="w-full h-full object-contain transition-opacity group-hover:opacity-0" />
                            <div class="absolute inset-0 flex items-center justify-center text-xl font-bold text-black hidden group-hover:flex">
                                ' . round($m["similitud"], 2) . '%
                            </div>
                        </div>
                        <a href="../panel_control/perfil.php?id_usuario=' . $m["id2"] . '" class="block mt-4 text-lg font-semibold text-green-600 hover:underline">
                            ' . htmlspecialchars($m["nombre2"]) . '
                        </a>
                    </div>
                </div>';
            }
            echo '</div>';
            echo '<div class="flex justify-center gap-4 mt-6">
                <button id="prev-btn" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">&larr; Anterior</button>
                <button id="next-btn" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">Siguiente &rarr;</button>
            </div>';
        }
        $_conexion->close();
        ?>
    </div>
    <script>
        const cards = document.querySelectorAll('.match-card');
        let currentIndex = 0;
        function updateCarousel() {
            cards.forEach(card => card.classList.add('hidden'));
            if (cards[currentIndex]) {
                cards[currentIndex].classList.remove('hidden');
            }
        }
        document.getElementById('prev-btn').addEventListener('click', () => {
            currentIndex = (currentIndex - 1 + cards.length) % cards.length;
            updateCarousel();
        });
        document.getElementById('next-btn').addEventListener('click', () => {
            currentIndex = (currentIndex + 1) % cards.length;
            updateCarousel();
        });
        updateCarousel();
    </script>

</body>
<footer class="bg-black text-white py-6 px-4 mt-auto">
    <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between space-y-4 sm:space-y-0">
        <p class="text-sm">&copy; <?php echo date("Y"); ?> Compiso. Todos los derechos reservados.</p>
        <a href="https://www.instagram.com/compiso_web" target="_blank" rel="noopener noreferrer"
            class="flex items-center space-x-2 hover:text-mint transition-colors duration-300">
            <svg class="w-5 h-5 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path
                    d="M7.75 2h8.5A5.75 5.75 0 0 1 22 7.75v8.5A5.75 5.75 0 0 1 16.25 22h-8.5A5.75 5.75 0 0 1 2 16.25v-8.5A5.75 5.75 0 0 1 7.75 2zm0 1.5A4.25 4.25 0 0 0 3.5 7.75v8.5A4.25 4.25 0 0 0 7.75 20.5h8.5a4.25 4.25 0 0 0 4.25-4.25v-8.5A4.25 4.25 0 0 0 16.25 3.5h-8.5zM12 7a5 5 0 1 1 0 10 5 5 0 0 1 0-10zm0 1.5a3.5 3.5 0 1 0 0 7 3.5 3.5 0 0 0 0-7zm5.25-.25a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
            </svg>
            <span class="text-sm">Instagram</span>
        </a>
    </div>
</footer>

</html>