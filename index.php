<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compiso</title>
    <link rel="icon" type="image/jpg" href="/images/logo_compiso.ico" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        mint: '#74C69D'
                    }
                }
            }
        }
    </script>
    <?php
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
    ?>
</head>

<body class="bg-gray-50 text-gray-800">

    <!-- HEADER -->
    <header class="bg-mint p-4 shadow-md">
        <div class="container mx-auto px-6 flex justify-between items-center">
            <div class="flex items-center gap-4">
                <a href="./index.php"><img src="./images/logo_compiso.png" alt="Logo" class="rounded-lg w-12 h-12"></a>
                <h1 class="text-white text-2xl font-bold">Compiso</h1>
            </div>

            <div class="flex items-center gap-4 ml-auto">
                <button onclick="mostrarPopup()"
                    class="text-white font-semibold px-4 py-2 rounded-lg hover:underline transition">
                    Conócenos
                </button>
                <a href="./usuario/iniciar_sesion.php" class="text-white hover:underline">
                    <button
                        class="bg-transparent border border-white px-4 py-2 rounded-lg hover:bg-white hover:text-mint transition">
                        Iniciar Sesión
                    </button>
                </a>
                <a href="./usuario/registro.php" class="text-white hover:underline">
                    <button
                        class="bg-transparent border border-white px-4 py-2 rounded-lg hover:bg-white hover:text-mint transition">
                        Registro
                    </button>
                </a>
            </div>
        </div>
    </header>
    <section class="w-full h-[70vh] bg-cover bg-center bg-no-repeat flex items-center justify-center text-white"
        style="background-image: url('./images/foto-malagaaaaaaaaa.jpeg');">
        <div class="bg-black bg-opacity-50 p-8 rounded-xl text-center max-w-3xl mx-auto">
            <h2 class="text-4xl font-bold mb-4">Bienvenido a Compiso</h2>
            <p class="text-lg leading-relaxed">
                Encuentra compañeros de piso compatibles y el hogar perfecto en Málaga.<br>
                Una forma más humana, fácil y segura de compartir vivienda.
            </p>
        </div>
    </section>
    <br><br>
    <div class="flex justify-between">
        <!-- aside left -->
         <aside class="sticky top-0 w-1/6 bg-white p-4">
            <a href="https://www.amazon.es/stores/page/A3737CDD-8B63-4BD9-AEF3-50100B42A5D3/?aref=SUwTotsuvF&aaxitk=ecd0f1960c9291195940d665bba615af&ref=AAP_588218372139694464&tag=ss-us-20&_encoding=U[%E2%80%A6]TE&pd_rd_wg=ROg83&pd_rd_r=f232301c-40cf-4cd5-a49b-0bf823edad20"
                target="_blank">
                 <img src="./images/publi-lilo.PNG" alt="Publicidad Izquierda" class="w-full h-auto sticky top-0">
            </a>
        </aside>
        <!-- MAIN -->
         <main class="w-4/6 bg-white p-4">

            <?php
            require('./utiles/conexion.php');

            $sql_usuarios = "SELECT nombre, imagen FROM Usuario";
            $result_usuarios = $_conexion->query($sql_usuarios);

            $sql_viviendas = "SELECT * FROM Vivienda";
            $result_viviendas = $_conexion->query($sql_viviendas);
            ?>


            <!-- VIVIENDAS -->
            <div class="bg-mint text-white text-3xl font-bold rounded-xl px-6 py-4 mb-6 shadow-md text-center">
                PISOS DISPONIBLES
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <?php
                if ($result_viviendas->num_rows > 0) {
                    while ($row = $result_viviendas->fetch_assoc()) {
                        $imagen = !empty($row["imagenes"]) ? $row["imagenes"] : 'default.jpg';
                        $ruta_web = "https://compiso.infy.uk/panel_control/uploads/" . $imagen;
                        $ruta_local = $_SERVER['DOCUMENT_ROOT'] . "/panel_control/uploads/" . $imagen;
                        if (!file_exists($ruta_local)) {
                            $ruta_web = "https://compiso.infy.uk/panel_control/uploads/default.jpg";
                        }

                        $disponible = $row["disponibilidad"] ? "../images/disponible.png" : "../images/ocupado.png";

                        echo '<div class="relative bg-white rounded-2xl shadow-xl overflow-hidden transform hover:scale-[1.02] transition duration-300">';

                        // Disponibilidad en esquina superior derecha
                        echo '<div class="absolute top-4 right-4 z-10">';
                        echo '<img src="' . $disponible . '" class="w-12 h-12" alt="Estado">';
                        echo '</div>';


                        // Imagen de la vivienda
                        echo '<img src="' . htmlspecialchars($ruta_web) . '" alt="Vivienda" class="w-full h-72 object-cover">';

                        // Dirección y descripción
                        echo '<div class="p-6">';
                        echo '<h3 class="text-xl font-bold text-mint mb-2">' . htmlspecialchars($row["direccion"]) . ', ' . htmlspecialchars($row["ciudad"]) . '</h3>';
                        echo '<p class="text-gray-700 leading-relaxed">' . htmlspecialchars($row["descripcion"]) . '</p>';
                        echo '</div>';

                        echo '</div>';
                    }
                } else {
                    echo "<p class='text-center col-span-2'>No se encontraron viviendas.</p>";
                }
                ?>
            </div>


            <!-- USUARIOS -->
            <div class="bg-mint text-white text-3xl font-bold rounded-xl px-6 py-4 mt-16 mb-6 shadow-md text-center">
                USUARIOS REGISTRADOS
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-6 text-center">
                <?php
                if ($result_usuarios->num_rows > 0) {
                    while ($row = $result_usuarios->fetch_assoc()) {
                        $foto = trim(str_replace("uploads/", "", $row["imagen"]));
                        $ruta_relativa = "usuario/uploads/" . htmlspecialchars($foto);
                        echo '<img src="' . $ruta_relativa . '" alt="Usuario" class="rounded-full border-4 border-mint shadow w-36 h-36 object-cover mx-auto">';
                    }
                } else {
                    echo "<div class='col-span-full text-center text-gray-600'>No se encontraron usuarios.</div>";
                }
                ?>
            </div>

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
                            <img src="./images/aurora.jpg" alt="Aurora" class="rounded-xl w-40 h-40 object-cover">
                        </div>
                        <div class="border-4 border-mint rounded-2xl">
                            <img src="./images/paula.jpeg" alt="Paula" class="rounded-xl w-40 h-40 object-cover">
                        </div>
                        <div class="border-4 border-mint rounded-2xl">
                            <img src="./images/carloos.jpeg" alt="pq pollas no funciona" class="rounded-xl w-40 h-40 object-cover">
                        </div>
                        <div class="border-4 border-mint rounded-2xl">
                            <img src="./images/luis.jpeg" alt="Luis" class="rounded-xl w-40 h-40 object-cover">
                        </div>
                    </div>

                    <p class="mt-6 text-center max-w-3xl mx-auto text-gray-700 leading-relaxed tracking-wide ">
                        <span class="font-semibold text-mint">Compiso</span> lo formamos un grupo de jóvenes dedicados
                        <span class="font-semibold text-black">al diseño y desarrollo web.</span>
                        <br><br>
                        Motivados por solucionar los problemas relevantes a nuestra generación, nos decidimos por
                        adentrarnos en el mundo del alquiler en Málaga capital y presentar una opción innovadora para la
                        gente joven que intenta encontrar un hueco en esta ciudad llena de oportunidades.
                        <br><br>
                        En <span class="font-semibold text-mint">Compiso</span> ofrecemos la oportunidad de encontrar
                        gente
                        compatible de forma sencilla y directa, además de un piso que se adecue a tus necesidades.
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

            <!-- CONTACTO -->
            <div class="bg-white shadow-lg rounded-xl p-6 max-w-xl mx-auto mt-16">
                <h4 class="text-2xl font-semibold text-mint mb-4 text-center">Contacta con Nosotros</h4>
                <form action="" method="post" class="space-y-4">
                    <div>
                        <label class="block mb-1">Nombre</label>
                        <input type="text" name="nombre" required
                            class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-mint">
                    </div>
                    <div>
                        <label class="block mb-1">Email</label>
                        <input type="email" name="email" required
                            class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-mint">
                    </div>
                    <div>
                        <label class="block mb-1">Mensaje</label>
                        <textarea name="mensaje" required
                            class="w-full p-2 border border-gray-300 rounded-lg h-28 resize-none focus:outline-none focus:ring-2 focus:ring-mint"></textarea>
                    </div>
                    <button type="submit"
                        class="bg-mint text-white px-4 py-2 rounded-lg hover:bg-emerald-600">Enviar</button>
                </form>
            </div>

            <!-- COMENTARIOS -->
            <div class="bg-mint text-white text-3xl font-bold rounded-xl px-6 py-4 mt-16 mb-4 shadow-md text-center">
                VALORACIONES DE USUARIOS
            </div>

            <div class="grid gap-6 max-w-4xl mx-auto">
                <?php
                $sql_select = "SELECT c.comentario, c.num_estrellas, c.fecha, u.nombre FROM Comentarios c 
      LEFT JOIN Usuario u ON c.id_usuario = u.id_usuario 
      ORDER BY c.num_estrellas DESC, c.fecha DESC LIMIT 4";

                $result_comentarios = $_conexion->query($sql_select);

                if ($result_comentarios && $result_comentarios->num_rows > 0) {
                    while ($row = $result_comentarios->fetch_assoc()) {
                        $iniciales = strtoupper(substr($row["nombre"] ?? "U", 0, 1));
                        echo "<div class='bg-white shadow rounded-lg p-4 flex gap-4 items-start'>";
                        echo "<div class='bg-mint text-white font-bold w-12 h-12 rounded-full flex items-center justify-center'>" . $iniciales . "</div>";
                        echo "<div>";
                        echo "<strong>{$row["nombre"]}</strong> <span class='text-yellow-500'>⭐ {$row["num_estrellas"]}/5</span>";
                        echo "<p class='text-gray-700'>" . htmlspecialchars($row["comentario"]) . "</p>";
                        echo "<small class='text-gray-400'>" . $row["fecha"] . "</small>";
                        echo "</div></div>";
                    }
                } else {
                    echo "<p class='text-center text-gray-500'>No hay comentarios aún.</p>";
                }
                ?>
            </div>

        </main>
        <!-- aside right -->
        <aside class="sticky top-0 w-1/6 bg-white p-4">
            
            <a href="https://store.oppomobile.es/es/?utm_medium=display&utm_source=marca&utm_campaign=oppo-mediaplusalma-es_es-brand-teaser-football-cpm--20250524-20250531-awareness-reach"
                target="_blank">
                <img src="./images/publi-futbol.PNG" alt="Publicidad Derecha" class="w-full h-auto sticky top-0">

            </a>
        </aside>
    </div>
    <footer class="bg-black p-4 text-white text-center text-sm mt-auto">
        &copy; <?php echo date("Y"); ?> Compiso. Todos los derechos reservados.
    </footer>
</body>

</html>