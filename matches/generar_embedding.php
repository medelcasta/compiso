<?php
function obtener_embedding($texto, $api_key) {
    static $ya_mostro_limite = false;

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
            "content" => json_encode($data),
            "ignore_errors" => true
        )
    );

    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);

    $http_response_header_str = isset($http_response_header) ? implode("\n", $http_response_header) : '';

    if (strpos($http_response_header_str, "429 Too Many Requests") !== false) {
        if (!$ya_mostro_limite) {
            $retryAfter = null;
            foreach ($http_response_header as $header) {
                if (stripos($header, 'Retry-After:') === 0) {
                    $retryAfter = trim(substr($header, strlen('Retry-After:')));
                    break;
                }
            }

            if ($retryAfter) {
                echo "Has superado el límite de peticiones. Intenta de nuevo en $retryAfter segundos.";
            } else {
                echo "Has superado el límite de peticiones. Intenta más tarde.";
            }

            $ya_mostro_limite = true;
        }
        return null;
    }

    if ($result === FALSE) {
        echo "Error al conectarse a la API.";
        return null;
    }

    $response = json_decode($result, true);
    return $response["embeddings"][0] ?? null;
}
?>
