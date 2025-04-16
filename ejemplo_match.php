
<?php
include "generar_embedding.php";
include "similitud.php";

// Clave de Cohere
$cohere_api_key = "eOXxc1z4XGq0jYEcaLZirJcJ62SMSoHOILrHhDhr";

// Descripción del nuevo usuario
$descripcion_usuario = "Busco compañero de piso que no fume y sea ordenado.";

// Generamos su vector
$vector_usuario = obtener_embedding($descripcion_usuario, $cohere_api_key);

// Vectores de otros usuarios guardados en la base de datos (ejemplo estático aquí)
$usuarios = [
    ["id" => 1, "vector" => json_decode(file_get_contents("usuario1_vector.json"))],
    ["id" => 2, "vector" => json_decode(file_get_contents("usuario2_vector.json"))],
    ["id" => 3, "vector" => json_decode(file_get_contents("usuario3_vector.json"))]
];

// Comparar y mostrar los matches más cercanos
$matches = [];
foreach ($usuarios as $u) {
    $sim = similitud_coseno($vector_usuario, $u["vector"]);
    $matches[] = ["id" => $u["id"], "similitud" => $sim];
}

usort($matches, function($a, $b) {
    return $b["similitud"] <=> $a["similitud"];
});

echo "Top matches:\n";
foreach ($matches as $m) {
    echo "Usuario ID " . $m["id"] . " - Similitud: " . round($m["similitud"], 4) . "\n";
}
?>
