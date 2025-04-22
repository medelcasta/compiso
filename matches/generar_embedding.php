
<?php
function obtener_embedding($texto, $api_key) {
    $url = "https://api.cohere.ai/v1/embed";
    $data = array(
        "texts" => [$texto],
        "model" => "embed-english-v3.0",
        "input_type" => "search_document"
    );

    $options = array(
        "http" => array(
            "header" => "Content-type: application/json\r\nAuthorization: Bearer $api_key",
            "method" => "POST",
            "content" => json_encode($data)
        )
    );

    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    if ($result === FALSE) return null;

    $response = json_decode($result, true);
    return $response["embeddings"][0];
}
?>
