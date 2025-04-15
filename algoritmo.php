<?php
// Conexión
$pdo = new PDO('mysql:host=localhost;dbname=pisos', 'root', '');

// Elegimos un usuario de referencia (ej: Carlos con ID 1)
$idUsuario = 1;

// Obtenemos su perfil
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->execute([$idUsuario]);
$usuarioBase = $stmt->fetch(PDO::FETCH_ASSOC);

// Obtenemos los demás usuarios
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id != ?");
$stmt->execute([$idUsuario]);
$otrosUsuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Función de cálculo de match
function calcularMatch($a, $b) {
    $score = 0;
    $total = 0;

    if ($a['fumador'] == $b['fumador']) $score++;
    $total++;

    if ($a['mascotas'] == $b['mascotas']) $score++;
    $total++;

    $score += 1 - abs($a['limpieza'] - $b['limpieza']) / 4;
    $total++;

    $score += 1 - abs($a['sociabilidad'] - $b['sociabilidad']) / 4;
    $total++;

    if ($b['edad'] >= $a['preferencia_edad_min'] && $b['edad'] <= $a['preferencia_edad_max']) $score++;
    $total++;

    if ($a['preferencia_sexo'] == 'indiferente' || $a['preferencia_sexo'] == $b['sexo']) $score++;
    $total++;

    return round(($score / $total) * 100, 2); // Porcentaje
}

// Mostramos resultados
foreach ($otrosUsuarios as $usuario) {
    $porcentaje = calcularMatch($usuarioBase, $usuario);
    echo "{$usuario['nombre']} tiene un match del {$porcentaje}% con {$usuarioBase['nombre']}<br>";
}
?>


<!--00000000000000000000000 bidirecional 000000000000000000000000000000 -->


<?php
// Conexión a la base de datos
$pdo = new PDO('mysql:host=localhost;dbname=pisos', 'root', '');

// Usuario base
$idUsuario = 1;

// Obtener datos del usuario base
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->execute([$idUsuario]);
$usuarioBase = $stmt->fetch(PDO::FETCH_ASSOC);

// Obtener los demás usuarios
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id != ?");
$stmt->execute([$idUsuario]);
$otrosUsuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Función para calcular compatibilidad A -> B
function calcularMatch($a, $b) {
    $score = 0;
    $total = 0;

    if ($a['fumador'] == $b['fumador']) $score++;
    $total++;

    if ($a['mascotas'] == $b['mascotas']) $score++;
    $total++;

    $score += 1 - abs($a['limpieza'] - $b['limpieza']) / 4;
    $total++;

    $score += 1 - abs($a['sociabilidad'] - $b['sociabilidad']) / 4;
    $total++;

    if ($b['edad'] >= $a['preferencia_edad_min'] && $b['edad'] <= $a['preferencia_edad_max']) $score++;
    $total++;

    if ($a['preferencia_sexo'] == 'indiferente' || $a['preferencia_sexo'] == $b['sexo']) $score++;
    $total++;

    return round(($score / $total) * 100, 2); // Devuelve porcentaje
}

// Mostrar resultados bidireccionales
foreach ($otrosUsuarios as $usuario) {
    $matchAB = calcularMatch($usuarioBase, $usuario); // ¿el otro encaja en mí?
    $matchBA = calcularMatch($usuario, $usuarioBase); // ¿yo encajo en el otro?

    $matchFinal = round(($matchAB + $matchBA) / 2, 2);

    echo "{$usuario['nombre']} tiene un match bidireccional del {$matchFinal}% con {$usuarioBase['nombre']}<br>";
}
?>