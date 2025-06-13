<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comentarios y Calificación</title>
    <script> window.chtlConfig = { chatbotId: "2783453492" } </script>
    <script async data-id="2783453492" id="chatling-embed-script" type="text/javascript" src="https://chatling.ai/js/embed.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        mint: '#74C69D',
                        tealCustom: '#4C9F8D',
                        baseBg: '#F8FAF9',
                        darkText: '#2F3E46'
                    }
                }
            }
        }
    </script>
</head>
<body class="min-h-screen flex flex-col bg-white text-gray-800 font-sans">


<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
session_start();

require('../utiles/conexion.php');
require('../utiles/depurar.php');
require("../utiles/volver.php");

if (!isset($_SESSION["usuario"])) {
    header("Location: ../usuario/iniciar_sesion.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_usuario = $_SESSION['usuario']['id_usuario'];
    $comentario = trim($_POST['comentario']);
    $num_estrellas = $_POST['num_estrellas'];

    if (!empty($num_estrellas)) {
        $sql_insert = "INSERT INTO Comentarios (id_usuario, comentario, fecha, num_estrellas) VALUES (?, ?, NOW(), ?)";
        $stmt = $_conexion->prepare($sql_insert);
        $stmt->bind_param("iss", $id_usuario, $comentario, $num_estrellas);

        if (!$stmt->execute()) {
            echo "<p class='text-red-500 font-bold'>❌ Error al guardar el comentario: " . $_conexion->error . "</p>";
        } else {
            echo "<p class='text-green-500 font-bold'>✅ Comentario guardado correctamente.</p>";
        }

        $stmt->close();
    } else {
        echo "<p class='text-red-500 font-bold'>❗ Debes seleccionar una calificación.</p>";
    }
}



?>
<header class="w-full bg-mint shadow-md py-4 px-6">
  <div class="max-w-7xl mx-auto flex items-center justify-between">
    <div class="flex items-center gap-4">
      <a href="/">
        <img src="../images/logo_compiso.png" alt="Logo" class="rounded-lg w-12 h-12">
      </a>
      <h1 class="text-white text-2xl font-bold">Compiso</h1>
    </div>
    <div class="flex items-center gap-2">
        <a href="<?php echo obtenerEnlaceVolver(); ?>" class="border border-white text-white px-4 py-2 rounded-lg hover:bg-white hover:text-mint transition">Volver</a>
    </div>
  </div>
</header>

<div class="relative w-full max-w-6xl mx-auto px-4 m-5">
  <div class="bg-teal-600 text-white text-4xl lg:text-5xl font-bold py-6 px-8 rounded-3xl shadow-lg text-center relative overflow-hidden">
    <div class="absolute inset-0 bg-teal-100 opacity-10 rounded-3xl pointer-events-none"></div>
    <h2 class="text-3xl font-bold">Califica y deja un comentario</h2>
  </div>
</div>
<!-- Formulario -->
 
<div id="mensaje" class="hidden mt-4 px-4 py-3 rounded border border-green-500 bg-green-100 text-green-800 flex items-center space-x-2 shadow transition-opacity duration-500">
  <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
  </svg>
  <span>Formulario enviado correctamente.</span>
</div>
<form action="comentarios.php" method="POST" class="w-full max-w-5xl mx-auto bg-gray-100 p-6 rounded-lg shadow-md px-4" id="miFormulario" onsubmit="return confirm('¿Quieres subir esta reseña?')">
    <label class="block text-lg font-semibold mb-2">Puntuación:</label>
    <div class="flex justify-center space-x-1 mb-4 text-3xl">
        <?php for ($i = 1; $i <= 5; $i++): ?>
            <input type="radio" id="estrella<?= $i ?>" name="num_estrellas" value="<?= $i ?>" class="hidden" onclick="resaltarEstrellas(<?= $i ?>)">
            <label for="estrella<?= $i ?>" class="cursor-pointer text-gray-400" id="label<?= $i ?>">★</label>
        <?php endfor; ?>
    </div>

    <textarea name="comentario" placeholder="Escribe tu comentario..." class="w-full h-28 p-3 border border-teal-400 rounded mb-4 resize-none focus:outline-none focus:ring-2 focus:ring-teal-300"></textarea>

    <input type="hidden" name="id_usuario" value="<?php echo htmlspecialchars($_SESSION['usuario']['id_usuario']); ?>">

    <div class="flex items-center justify-between">
        <button type="submit" class="bg-teal-500 hover:bg-teal-600 text-white py-2 px-6 rounded transition">Enviar</button>
    </div>
</form>

<script>
    function resaltarEstrellas(valor) {
        for (let i = 1; i <= 5; i++) {
            document.getElementById(`label${i}`).classList.toggle('text-yellow-400', i <= valor);
        }
    }
</script>


<!--<script>
  document.getElementById('miFormulario').addEventListener('submit', function (e) {
    e.preventDefault(); // Evita el envío real

    const mensaje = document.getElementById('mensaje');

    // Mostrar el mensaje
    mensaje.classList.remove('hidden');

    // Ocultarlo luego de 3 segundos
    setTimeout(() => {
      mensaje.classList.add('hidden');
    }, 3000);
  });
</script>-->

<!-- Sección de otras valoraciones -->
<div class="relative w-full max-w-6xl mx-auto px-4 m-5">
  <div class="bg-teal-600 text-white text-4xl lg:text-5xl font-bold py-6 px-8 rounded-3xl shadow-lg text-center relative overflow-hidden">
    <div class="absolute inset-0 bg-teal-100 opacity-10 rounded-3xl pointer-events-none"></div>
    <h2 class="text-3xl font-bold">Otras Valoraciones</h2>
  </div>
</div>
<div class="max-w-4xl mx-auto space-y-4">
    <?php
    $sql_select = "SELECT c.comentario, c.num_estrellas, c.fecha, u.nombre 
                   FROM Comentarios c 
                   LEFT JOIN Usuario u ON c.id_usuario = u.id_usuario 
                   ORDER BY c.num_estrellas DESC, c.fecha DESC LIMIT 8";

    $result = $_conexion->query($sql_select);

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $iniciales = strtoupper(substr($row["nombre"] ?? "U", 0, 1));
            echo "
            <div class='bg-white rounded-lg shadow p-4 flex items-start gap-4'>
                <div class='w-12 h-12 rounded-full bg-teal-500 text-white flex items-center justify-center font-bold text-lg'>{$iniciales}</div>
                <div>
                    <div class='font-semibold text-lg'>{$row["nombre"]} <span class='text-yellow-500'>⭐ {$row["num_estrellas"]}/5</span></div>
                    <p class='text-gray-700 mt-1'>" . htmlspecialchars($row["comentario"]) . "</p>
                    <small class='text-gray-500'>{$row["fecha"]}</small>
                </div>
            </div>";
        }
    } else {
        echo "<p class='text-center text-gray-600'>No hay comentarios aún.</p>";
    }
    ?>
</div>
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
</body>
</html>