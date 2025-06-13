<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Mi Perfil - Compiso</title>
    <link rel="icon" type="image/jpg" href="/images/logo_compiso.ico" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background-image: url('https://www.transparenttextures.com/patterns/cartographer.png');
            background-color: #74C69D;
        }
    </style>
    <?php
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
    session_start();

    require('../utiles/conexion.php');
    require('../utiles/depurar.php');
    require("../utiles/volver.php");

    if (!isset($_SESSION["usuario"])) {
        echo "<div class='text-red-600 text-center mt-4'>No has iniciado sesión. Por favor, inicia sesión.</div>";
        exit;
    }

    $id_usuario = $_SESSION["usuario"]["id_usuario"];

    $sql = $_conexion->prepare("SELECT * FROM Usuario WHERE id_usuario = ?");
    $sql->bind_param("s", $id_usuario);
    $sql->execute();
    $resultado = $sql->get_result();


    if ($fila = $resultado->fetch_assoc()) {
        $nombre = $fila["nombre"];
        $apellidos = $fila["apellidos"];
        $email = $fila["email"];
        $telefono = $fila["telefono"];
        $tipo_usuario = $fila["tipo_usuario"];
        $fecha_nacimiento = $fila["fecha_nacimiento"];
        $sexo = $fila["sexo"];
        $descripcion = $fila["descripcion"];
        if (empty($descripcion)) {
            echo '<p class="text-gray-700">' . nl2br(htmlspecialchars($descripcion)) . '</p>';
        }

        $foto = trim(str_replace("../usuario/uploads/", "", $fila["imagen"]));
    } else {
        echo "<div class='text-red-600 text-center mt-4'>No se encontró información del usuario.</div>";
        exit;
    }
    ?>
</head>

<body class="min-h-screen flex items-center justify-center p-6 text-gray-800">

    <div class="min-h-screen flex flex-col">
        <!-- HEADER -->
        <header class="bg-mint p-4 shadow-md">
            <div class="container mx-auto px-6 flex justify-between items-center">
                <div class="flex items-center gap-4">
                    <a href="../index.php"><img src="../images/logo_compiso.png" alt="Logo"
                            class="rounded-lg w-12 h-12"></a>
                    <h1 class="text-white text-2xl font-bold">Compiso</h1>
                </div>

                <div class="flex items-center gap-4 ml-auto">
                    <button onclick="mostrarPopup()"
                        class="text-white font-semibold px-4 py-2 rounded-lg hover:underline transition">
                        Conócenos
                    </button>
                    <a href="../inicio_inquilino.php" class="text-white hover:underline">
                        <button
                            class="bg-transparent border border-white px-4 py-2 rounded-lg hover:bg-white hover:text-mint transition">
                            Volver
                        </button>
                    </a>
                </div>
            </div>
        </header>

        <!-- EQUIPO -->
        <div id="popupConocenos"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden transition-opacity duration-300">
            <div id="popupContenido"
                class="bg-white p-6 rounded-2xl max-w-4xl w-full relative overflow-y-auto max-h-[90vh] transform scale-95 opacity-0 transition-all duration-300">

                <button onclick="cerrarPopup()"
                    class="absolute top-4 right-4 text-gray-500 hover:text-gray-800 text-2xl font-bold">
                    &times;
                </button>

                <h2 class="text-3xl font-bold mt-4 mb-4 text-mint text-center">Conócenos</h2>
                <div class="flex flex-wrap justify-center gap-6 items-center">
                    <div class="border-4 border-mint rounded-2xl">
                        <img src="../images/aurora.jpg" alt="Aurora" class="rounded-xl w-40 h-40 object-cover">
                    </div>
                    <div class="border-4 border-mint rounded-2xl">
                        <img src="../images/paula.jpeg" alt="Paula" class="rounded-xl w-40 h-40 object-cover">
                    </div>
                    <div class="border-4 border-mint rounded-2xl">
                        <img src="../images/carlos.jpeg" alt="Carlos" class="rounded-xl w-40 h-40 object-cover">
                    </div>
                    <div class="border-4 border-mint rounded-2xl">
                        <img src="../images/luis.jpeg" alt="Luis" class="rounded-xl w-40 h-40 object-cover">
                    </div>
                </div>

                <p class="mt-6 text-center max-w-3xl mx-auto text-gray-700 leading-relaxed tracking-wide ">
                    <span class="font-semibold text-mint">Compiso</span> lo formamos un grupo de jóvenes dedicados <span
                        class="font-semibold text-black">al diseño y desarrollo web.</span>
                    <br><br>
                    Motivados por solucionar los problemas relevantes a nuestra generación, nos decidimos por
                    adentrarnos en el
                    mundo del alquiler en Málaga capital y presentar una opción innovadora para la gente joven que
                    intenta
                    encontrar un hueco en esta ciudad llena de oportunidades.
                    <br><br>
                    En <span class="font-semibold text-mint">Compiso</span> ofrecemos la oportunidad de encontrar gente
                    compatible
                    de forma sencilla y directa, además de un piso que se adecue a tus necesidades.
                    <br><br>
                    <span class="italic text-gray-800">A través de nuestra plataforma, el piso ideal te elige a
                        ti.</span>
                </p>
            </div>
        </div>

        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const popup = document.getElementById("popupConocenos");
                const contenido = document.getElementById("popupContenido");
                const botonAbrir = document.querySelector('[onclick="mostrarPopup()"]');

                function mostrarPopup() {
                    popup.classList.remove("hidden");
                    setTimeout(() => {
                        contenido.classList.remove("opacity-0", "scale-95");
                        contenido.classList.add("opacity-100", "scale-100");
                    }, 10);
                }

                function cerrarPopup() {
                    contenido.classList.remove("opacity-100", "scale-100");
                    contenido.classList.add("opacity-0", "scale-95");
                    setTimeout(() => {
                        popup.classList.add("hidden");
                    }, 300); // Duración de la animación
                }

                // Mostrar
                botonAbrir.addEventListener("click", mostrarPopup);

                // Cerrar al hacer clic fuera
                popup.addEventListener("click", function (e) {
                    if (!contenido.contains(e.target)) {
                        cerrarPopup();
                    }
                });

                // Cerrar con Esc
                document.addEventListener("keydown", function (e) {
                    if (e.key === "Escape") {
                        cerrarPopup();
                    }
                });

                // Permitir uso con onclick="cerrarPopup()"
                window.cerrarPopup = cerrarPopup;
            });
        </script>


        <main class="flex-grow">
            <div class="w-full max-w-3xl bg-white rounded-xl shadow-xl overflow-hidden">
                <div class="bg-[#40916c] text-white text-center py-4 rounded-t-xl">
                    <h2 class="text-xl font-semibold">Bienvenid@ <?php echo htmlspecialchars($nombre); ?></h2>
                    <h1 class="text-3xl font-bold">Mi Perfil</h1>
                </div>

                <div class="flex flex-col md:flex-row p-6 gap-6 items-start">
                    <div class="flex-shrink-0">
                        <?php
                        $ruta_relativa = "../usuario/" . htmlspecialchars($foto);
                        $ruta_absoluta = $_SERVER['DOCUMENT_ROOT'] . "/usuario/uploads/" . htmlspecialchars($foto);

                       //echo "<p>🔍 Ruta relativa generada: $ruta_relativa</p>";
                        //echo "<p>🔍 Ruta absoluta generada: $ruta_absoluta</p>";

                        if (!empty($foto)) {
                            if (file_exists($ruta_relativa)) {
                                echo '<img src="' . $ruta_relativa . '" class="img-thumbnail mb-3" alt="Foto de perfil">';
                            } elseif (file_exists($ruta_absoluta)) {
                                echo '<img src="' . $ruta_absoluta . '" class="img-thumbnail mb-3" alt="Foto de perfil">';
                            } else {
                                echo "<p class='text-danger'>❌ Imagen no encontrada en 'uploads'. Verifica que el archivo existe.</p>";
                            }
                        } else {
                            echo "<p class='text-danger'>❌ La variable de imagen está vacía.</p>";
                        }

                        ?>
                    </div>

                    <div class="flex-1 space-y-3">
                        <h3 class="text-2xl font-bold flex items-center gap-3">
                            <?php echo htmlspecialchars($nombre . ' ' . $apellidos); ?>
                            <?php
                            if ($tipo_usuario == 1) {
                                echo '<img src="../images/inquilino.png" class="w-8 h-8">';
                            } elseif ($tipo_usuario == 2) {
                                echo '<img src="../images/propietario.png" class="w-8 h-8">';
                            } else {
                                echo '<img src="../images/administrador.png" class="w-8 h-8">';
                            }
                            ?>
                        </h3>
                        <p class="flex items-center gap-2"><img src="../images/email.png" class="w-6">
                            <?php echo htmlspecialchars($email); ?></p>
                        <p class="flex items-center gap-2"><img src="../images/movil.png" class="w-6">
                            <?php echo htmlspecialchars($telefono); ?></p>
                        <p class="flex items-center gap-2"><img src="../images/tarta.png" class="w-6">
                            <?php echo htmlspecialchars($fecha_nacimiento); ?></p>
                        <p class="flex items-center gap-2">
                            <?php
                            if ($sexo == 'Mujer') {
                                echo '<img src="../images/mujer.jpg" class="w-6 h-6 rounded-full">';
                            } else if ($sexo == 'Hombre') {
                                echo '<img src="../images/hombre.jpg" class="w-6 h-6 rounded-full">';
                            }
                            echo htmlspecialchars($sexo); ?>
                        </p>
                    </div>
                </div>

                <div class="px-6 pb-4">
                    <p class="text-lg font-semibold mb-1">Descripción:</p>
                    <p class="text-gray-700">
                        <?php echo nl2br(htmlspecialchars($descripcion !== null ? $descripcion : '')); ?></p>
                </div>

                <div class="bg-[#f0f0f0] px-6 py-4 flex justify-center gap-4 rounded-b-xl">
                    <a href="<?php echo obtenerEnlaceVolver(); ?>"
                        class="bg-gray-400 text-white px-4 py-2 rounded-full shadow hover:bg-gray-500 transition">Volver</a>
                    <a href="../usuario/cambiar_credenciales.php"
                        class="bg-[#74C69D] text-white px-4 py-2 rounded-full shadow hover:bg-[#5fb88a] transition">Cambiar
                        credenciales</a>
                </div>
            </div>
        </main>

        <footer class="bg-black p-4 text-white text-sm mt-auto">
            <div class="container mx-auto px-6 text-center">
                &copy; <?php echo date("Y"); ?> Compiso. Todos los derechos reservados.
            </div>
        </footer>
    </div>
</body>

</html>