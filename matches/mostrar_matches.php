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
    require("../utiles/volver.php");

    if (!isset($_SESSION["usuario"])) {
        echo "No has iniciado sesión.";
        exit;
    }

    $usuario_en_sesion = $_SESSION["usuario"];
    ?>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }

        h1, h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #343a40;
        }

        .card {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            overflow: hidden;
            background-color: white;
            text-align: center;
            padding: 20px;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 12px rgba(0, 0, 0, 0.2);
        }

        .corazon-container {
            position: relative;
            width: 150px;
            height: 150px;
            margin: 0 auto 20px auto;
            background-image: url('../images/corazon.png');
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
        }

        .similitud-centro {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-weight: bold;
            color: #dc3545;
            font-size: 1.5rem;
            background-color: rgba(255, 255, 255, 0.8);
            padding: 5px 10px;
            border-radius: 10px;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }

        .col-md-4 {
            flex: 0 0 calc(33.333% - 20px);
            max-width: calc(33.333% - 20px);
        }

        @media (max-width: 768px) {
            .col-md-4 {
                flex: 0 0 100%;
                max-width: 100%;
            }
        }

        .btn-container {
            text-align: center;
            margin-top: 20px;
        }

        .btn-container .btn {
            margin: 5px;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h1>Matches para <?php echo htmlspecialchars($usuario_en_sesion); ?></h1>

        <?php
        $cohere_api_key = "Ez2MpXNBBfFe4LxthbeFj5Ne9npfWNq0PLRpWoOU";

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
            if ($u["nombre"] === $usuario_en_sesion) {
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

        if (empty($matches)) {
            echo "No se encontraron matches con similitud significativa.";
        } else {
            echo '<h2>Perfiles Destacados</h2>';
            echo '<div class="row">';
            $top_matches = array_slice($matches, 0, 3);
            foreach ($top_matches as $m) {
                echo '<div class="col-md-4 mb-3">
                        <div class="card">
                            <div class="corazon-container">
                                <div class="similitud-centro">' . round($m["similitud"], 2) . '%</div>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">Match con ' . $m['nombre2'] . '</h5>
                                <form action="../panel_control/perfil.php" method="GET">
                                    <input type="hidden" name="id_usuario" value="' . $m['id2'] . '">
                                    <button type="submit" class="btn btn-primary">Ver perfil</button>
                                </form>
                            </div>
                        </div>
                    </div>';
            }
            echo '</div>';

            echo '<h2>Todos los Matches</h2>';
            echo '<div class="row">';
            foreach ($matches as $m) {
                echo '<div class="col-md-4 mb-3">
                        <div class="card">
                            <div class="corazon-container">
                                <div class="similitud-centro">' . round($m["similitud"], 2) . '%</div>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">Match con ' . $m['nombre2'] . '</h5>
                                <form action="../panel_control/perfil.php" method="GET">
                                    <input type="hidden" name="id_usuario" value="' . $m['id2'] . '">
                                    <button type="submit" class="btn btn-primary">Ver perfil</button>
                                </form>
                            </div>
                        </div>
                    </div>';
            }
            echo '</div>';
        }

        $_conexion->close();
        ?>
        <div class="btn-container">
            <a class="btn btn-secondary" href="<?php echo obtenerEnlaceVolver(); ?>">Volver</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
