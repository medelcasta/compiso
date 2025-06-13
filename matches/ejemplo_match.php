<?php
include "generar_embedding.php";
include "similitud.php";
include "../utiles/conexion.php";

// Clave de Cohere
$cohere_api_key = "jjk20HgHxNuESvPj07QobKOHc1CO7LZFnLeLW5EY";
// API NUEVA cLP5vlcg5D3qiS6LgpB9wCn2A8Wa0ihAjLdp2PMd
// API ANTIGUA eOXxc1z4XGq0jYEcaLZirJcJ62SMSoHOILrHhDhr
//jFrOFJxd1BxzLDQGckJhPp6aMxdD1bPEmiObMyUx
//Pq38StlpD4n7F4HxbHCI23G5eJjzhrVn5oUwkwSR

// Descripción del nuevo usuario
$descripcion_usuario = "SELECT descripcion FROM Usuario";

// Generamos su vector
$vector_usuario = obtener_embedding($descripcion_usuario, $cohere_api_key);

// Recuperar vectores de otros usuarios desde la base de datos
$sql = "SELECT id_usuario, descripcion FROM Match_Usuario";
$result = $conn->query($sql);

$usuarios = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $usuarios[] = ["id" => $row["id_usuario"], "vector" => json_decode($row["descripcion"])];
    }
} else {
    echo "No hay usuarios registrados.";
    exit;
}

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

// Cerrar conexión
$conn->close();
?>

