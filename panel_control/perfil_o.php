<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Información del Usuario</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <?php
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
    require('../utiles/conexion.php');
    require("../utiles/volver.php");
    ?>
    <style>
        body {
            background-image: url('https://www.transparenttextures.com/patterns/cartographer.png');
            background-color: #74c69d;
        }

        .enlarge-img {
            transition: transform 0.3s ease;
        }

        .enlarge-img:hover {
            transform: scale(1.05);
        }
    </style>
</head>

<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="max-w-4xl w-full bg-white rounded-xl shadow-lg overflow-hidden p-6">
            <?php
            if (isset($_GET["usuario_id"])) {
                $id_usuario = $_GET["usuario_id"];

                $sql = $_conexion->prepare("SELECT nombre, apellidos, email, telefono, tipo_usuario, descripcion, imagen, sexo 
                                            FROM Usuario WHERE id_usuario = ?");
                $sql->bind_param("s", $id_usuario);
                $sql->execute();
                $resultado = $sql->get_result();

                if ($resultado->num_rows > 0) {
                    $usuario = $resultado->fetch_assoc();

                    $tipo_icono = match ($usuario["tipo_usuario"]) {
                        "1" => "../images/inquilino.png",
                        "2" => "../images/propietario.png",
                        default => "../images/administrador.png"
                    };

                    $genero_icono = (strtolower($usuario["sexo"]) === "mujer") ? "💁‍♀️" : "💁‍♂️";
                    $ruta_imagen = '../usuario/' . trim($usuario["imagen"]);
                    $imagen_src = (!empty(trim($usuario["imagen"])) && file_exists($ruta_imagen)) ? $ruta_imagen : '../images/mujer.jpg';

                    echo '<div class="flex flex-col md:flex-row items-start gap-6">';
                    
                    // Foto a la izquierda
                    echo '<div class="flex-shrink-0">';
                    echo '<img src="' . $imagen_src . '" alt="Foto de perfil" class="w-40 h-40 rounded-full object-cover shadow-lg cursor-pointer hover:scale-105 transition duration-300" onclick="ampliarImagen(\'' . $imagen_src . '\')">';
                    echo '</div>';

                    // Información a la derecha
                    echo '<div class="flex-1">';
                    echo '<h1 class="text-2xl font-semibold text-gray-800 flex items-center gap-3 mb-4">';
                    echo htmlspecialchars($usuario["nombre"] . ' ' . $usuario["apellidos"]);
                    echo '<img src="' . $tipo_icono . '" alt="tipo" class="w-8 h-8">';
                    echo '</h1>';

                    echo '<div class="space-y-3 text-gray-700">';
                    echo '<p class="flex items-center gap-3"><img src="../images/email.png" class="w-6"> ' . htmlspecialchars($usuario["email"]) . '</p>';
                    echo '<p class="flex items-center gap-3"><img src="../images/movil.png" class="w-6"> ' . htmlspecialchars($usuario["telefono"]) . '</p>';
                    echo '<p class="flex items-center gap-3"> ' . $genero_icono . htmlspecialchars($usuario["sexo"]) . '</p>';
                    echo '</div>';
                    echo '</div>';

                    echo '</div>'; // Fin del flex principal

                    // Descripción y botones
                    echo '<div class="mt-6">';
                    echo '<p class="text-gray-800 text-sm font-semibold mb-1">Descripción:</p>';
                    echo '<p class="text-gray-700 text-justify mb-4">' . (!empty($usuario["descripcion"]) ? htmlspecialchars($usuario["descripcion"]) : 'Sin descripción') . '</p>';

                    echo '<div class="flex flex-col sm:flex-row justify-center items-center gap-4">';
                    echo '<form action="../conversaciones/dialogo.php" method="post">';
                    echo '<input type="hidden" name="usuario_id" value="' . htmlspecialchars($id_usuario) . '">';
                    echo '<button type="submit" class="bg-[#74C69D] text-white font-semibold px-4 py-2 rounded-full shadow hover:bg-[#5fb88a] transition">Enviar mensaje</button>';
                    echo '</form>';
                    echo '<a href="buscar_usuarios.php" class="bg-gray-300 text-gray-800 font-semibold px-4 py-2 rounded-full shadow hover:bg-gray-400 transition">Volver</a>';
                    echo '</div>';
                    echo '</div>';

                } else {
                    echo "<p class='p-4 text-center'>No se encontró información para el usuario seleccionado.</p>";
                }

                $sql->close();
            } else {
                echo "<p class='p-4 text-center'>Usuario no encontrado.</p>";
            }

            $_conexion->close();
            ?>
        </div>
    </div>

    <!-- Modal para imagen ampliada -->
    <div id="modal-imagen" class="fixed inset-0 bg-black bg-opacity-60 z-50 hidden items-center justify-center">
        <div class="bg-white p-4 rounded-lg shadow-lg max-w-lg w-full relative">
            <button onclick="cerrarModal()" class="absolute top-2 right-2 text-gray-700 hover:text-red-500 text-xl">&times;</button>
            <img id="imagen-ampliada" src="" alt="Imagen Ampliada" class="w-full h-auto rounded-lg">
        </div>
    </div>

    <script>
        function ampliarImagen(src) {
            document.getElementById('imagen-ampliada').src = src;
            document.getElementById('modal-imagen').classList.remove('hidden');
            document.getElementById('modal-imagen').classList.add('flex');
        }

        function cerrarModal() {
            document.getElementById('modal-imagen').classList.add('hidden');
            document.getElementById('modal-imagen').classList.remove('flex');
        }
    </script>
</body>

</html>
