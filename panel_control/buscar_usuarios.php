<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Búsqueda de Usuario</title>
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
    
    <?php
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
    session_set_cookie_params(['path' => '/']);
    session_start();

    require('../utiles/conexion.php');
    require("../utiles/volver.php");

    if (!isset($_SESSION["usuario"]) || !is_array($_SESSION["usuario"])) {
        echo "<div class='text-center mt-10 text-red-500 font-semibold'>Sesión inválida o usuario no autenticado.</div>";
        exit;
    }
    ?>

    <div class="max-w-3xl mx-auto mt-12 bg-white p-8 rounded-2xl shadow">
        <h1 class="text-3xl font-bold text-center text-gray-800 mb-8">Búsqueda de Usuario</h1>

        <form method="POST" action="" class="space-y-6">
            <div>
                <label for="criterio" class="block text-sm font-semibold text-gray-700">Introduce Nombre o
                    Email:</label>
                <input type="text" id="criterio" name="criterio" placeholder="Introduce nombre o email"
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
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $criterio = trim($_POST["criterio"] ?? '');

            if (isset($_POST["mostrar_todos"])) {
                $sql = $_conexion->prepare("SELECT * FROM Usuario");
            } elseif ($criterio === '') {
                echo "<p class='mt-6 text-red-600'>Debes introducir un nombre o email para buscar.</p>";
                $_conexion->close();
                return;
            } else {
                $sql = $_conexion->prepare("SELECT * FROM Usuario WHERE nombre LIKE ? OR email LIKE ?");
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
                        $id = urlencode($row["id_usuario"]);
                        $foto = trim(str_replace("uploads/", "", $row["imagen"]));
                        $ruta_relativa = "../usuario/uploads/" . htmlspecialchars($foto);

                        echo '<div class="col-md-4 mb-4">';
                        echo '  <div class="card shadow-sm rounded-lg overflow-hidden transition transform hover:scale-105">';
                        echo '      <div class="relative">';
                        echo '          <img src="' . $ruta_relativa . '" class="card-img-top rounded-full mx-auto mt-4 border-2 border-gray-300" alt="Usuario" style="width: 100px; height: 100px; object-fit: cover;">';
                        echo '      </div>';
                        echo '      <div class="card-body text-center py-4">';
                        echo '          <h5 class="font-bold text-lg text-gray-800">' . htmlspecialchars($row["nombre"]) . '</h5>';
                        echo '      </div>';
                        echo '<a href="perfil_o.php?usuario_id=' . $id . '" class="group block text-center">Más Info</a>';
                        echo '  </div>';
                        echo '</div>';




                    }
                } else {
                    echo "<div class='alert alert-warning'>No se encontraron usuarios.</div>";
                }




                $sql->close();
            } else {
                echo "<p class='mt-6 text-red-600'>Error en la consulta preparada.</p>";
            }
        }

        $_conexion->close();
        ?>

    </div>
    
</body>

</html>