
<?php //conexion con tabla match 
?>



<?php
// Conexión a la base de datos
$db_host = 'tu_host';
$db_user = 'tu_usuario';
$db_pass = 'tu_contraseña';
$db_name = 'tu_base_de_datos';

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Obtener vectores de la tabla match
$sql = "SELECT id_usuario, vector_embedding FROM match";
$result = $conn->query($sql);

$usuarios = [];
while ($row = $result->fetch_assoc()) {
    $usuarios[] = [
        "id" => $row["id_usuario"],
        "vector" => json_decode($row["vector_embedding"])
    ];
}

$conn->close();

// Continuar con la lógica de comparación
$matches = [];
foreach ($usuarios as $u) {
    $sim = similitud_coseno($vector_usuario, $u["vector"]);
    $matches[] = [
        "id" => $u["id"],
        "similitud" => $sim
    ];
}

usort($matches, function($a, $b) {
    return $b["similitud"] <=> $a["similitud"];
});

// Mostrar los mejores resultados
echo "Top matches:\n";
foreach ($matches as $m) {
    echo "Usuario ID " . $m["id"] . " - Similitud: " . round($m["similitud"], 4) . "\n";
}


?>
