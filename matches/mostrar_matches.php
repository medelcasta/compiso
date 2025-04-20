<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mostrar Matches</title>
    <?php
    session_start();
    error_reporting(E_ALL);
    ini_set("display_errors", 1);

    include "generar_embedding.php";
    include "similitud.php";
    include "../utiles/conexion.php";

    if (!isset($_SESSION["usuario"])) {
        echo "No has iniciado sesión.";
        exit;
    }

    $usuario_en_sesion = $_SESSION["usuario"];
    ?>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script> window.chtlConfig = { chatbotId: "2783453492" } </script>
    <script async data-id="2783453492" id="chatling-embed-script" type="text/javascript" src="https://chatling.ai/js/embed.js"></script>
</head>

<body>
    <div class="container mt-5">
        <h1>Matches para <?php echo htmlspecialchars($usuario_en_sesion); ?></h1>

        <?php
        // Clave de la API de Cohere
        $cohere_api_key = "eOXxc1z4XGq0jYEcaLZirJcJ62SMSoHOILrHhDhr";

        // Obtener todos los usuarios con descripción
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

        // Encontrar al usuario logueado
        $usuario_actual = null;
        foreach ($usuarios as $u) {
            if ($u["nombre"] === $usuario_en_sesion) {
                $usuario_actual = $u;
                break;
            }
        }

        if (!$usuario_actual) {
            echo "No se encontró al usuario logueado con descripción válida.";
            exit;
        }

        // Comparar solo contra los demás usuarios
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

        // Ordenar por similitud descendente
        usort($matches, function ($a, $b) {
            return $b["similitud"] <=> $a["similitud"];
        });

        if (empty($matches)) {
            echo "No se encontraron matches con similitud significativa.";
        } else {
            // Mostrar los matches como tarjetas
            echo '<div class="row">';
            foreach ($matches as $m) {
                echo '<div class="col-md-4 mb-3">
                        <div class="card" style="width: 100%;">
                            <div class="card-body">
                                <h5 class="card-title">Match con ' . $m['nombre2'] . '</h5>
                                <p class="card-text">Similitud: ' . round($m["similitud"], 2) . '%</p>
                                <form action="../panel_control/perfil.php" method="GET">
                                    <input type="hidden" name="id_usuario" value="' . $m['id2'] . '">
                                    <button type="submit" class="btn btn-primary">Ver perfil</button>
                                </form>
                            </div>
                        </div>
                    </div>';
            }
            echo '</div>'; // Cierra la fila de tarjetas
        }

        $_conexion->close();
        ?>
    </div>

    <!-- Bootstrap JS (opcional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-0evHe/X8g5zYkltHhLQk7Ex3cK1tyOjfvTY0lZWbQbcgOXpG9V8yTpHXzJ7pfa2S" crossorigin="anonymous"></script>
</body>

</html>
