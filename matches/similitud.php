
<?php
function similitud_coseno($v1, $v2) {
    $dot = 0.0;
    $normA = 0.0;
    $normB = 0.0;
    for ($i = 0; $i < count($v1); $i++) {
        $dot += $v1[$i] * $v2[$i];
        $normA += $v1[$i] * $v1[$i];
        $normB += $v2[$i] * $v2[$i];
    }
    return $dot / (sqrt($normA) * sqrt($normB));
}
?>
