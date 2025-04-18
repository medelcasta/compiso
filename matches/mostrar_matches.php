<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mostrar Matches</title>
    <?php
    error_reporting(E_ALL);
    ini_set("display_errors", 1);

    include "generar_embedding.php";
    include "similitud.php";
    include "../utiles/conexion.php";
    ?>
</head>

<body>
    <h1>Matches entre usuarios</h1>

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

    if (count($usuarios) < 2) {
        echo "No se pudieron generar suficientes vectores para comparar.";
        exit;
    }

    // Comparar todos contra todos (sin repetir)
    $matches = [];
    for ($i = 0; $i < count($usuarios); $i++) {
        for ($j = $i + 1; $j < count($usuarios); $j++) {
            $sim = similitud_coseno($usuarios[$i]["vector"], $usuarios[$j]["vector"]);
            if ($sim > 0.1) { // Puedes ajustar el umbral según tu criterio
                $matches[] = [
                    "nombre1" => $usuarios[$i]["nombre"],
                    "nombre2" => $usuarios[$j]["nombre"],
                    "similitud" => $sim * 100 // Convertimos a porcentaje
                ];
            }
        }
    }

    // Ordenar los matches por similitud de mayor a menor
    usort($matches, function ($a, $b) {
        return $b["similitud"] <=> $a["similitud"];
    });

    if (empty($matches)) {
        echo "No se encontraron matches con similitud significativa.";
    } else {
        echo "<table border='1' cellpadding='10'>
                <tr><th>Usuario 1</th><th>Usuario 2</th><th>Similitud (%)</th></tr>";
        foreach ($matches as $m) {
            echo "<tr>
                    <td>{$m['nombre1']}</td>
                    <td>{$m['nombre2']}</td>
                    <td>" . round($m["similitud"], 2) . "%</td>
                </tr>";
        }
        echo "</table>";
    }

    $_conexion->close();
    ?>
</body>

</html>
