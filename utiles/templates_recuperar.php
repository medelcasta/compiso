<?php

// Función para enviar el correo de recuperación de contraseña
function enviarCorreoRecuperacion($nombre, $email, $enlaceRecuperacion) {
    // Parámetros para el correo
    $params = [
        'nombre' => $nombre,
        'enlaceRecuperacion' => $enlaceRecuperacion
    ];

    // Llamada a la función para enviar el correo
    $emailjs_response = sendEmailJS('service_jc4gtoj', 'template_jor91ch', $params);

    // Verificamos la respuesta de EmailJS
    if ($emailjs_response) {
        return 'Correo de recuperación enviado correctamente a ' . $email;
    } else {
        return 'Hubo un error al enviar el correo de recuperación. Revisa los logs.';
    }
}

// Función para enviar el correo a través de la API de EmailJS
function sendEmailJS($service_id, $template_id, $params) {
    // URL de la API de EmailJS
    $url = 'https://api.emailjs.com/api/v1.0/email/send';
    
    // Datos a enviar en el cuerpo de la solicitud
    $data = [
        'service_id' => $service_id,
        'template_id' => $template_id,
        'user_id' => '4ffStSi1GG4niqwgL', // Sustituye con tu user_id de EmailJS
        'template_params' => $params
    ];

    // Inicializamos cURL para enviar la solicitud POST a la API de EmailJS
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);

    // Ejecutamos la solicitud y capturamos la respuesta
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    // Verificamos la respuesta
    if ($http_code == 200) {
        curl_close($ch);
        return true;  // Correo enviado correctamente
    } else {
        // Imprimir la respuesta para depuración en caso de error
        echo "Error: " . $response; // Aquí verás más detalles sobre el error
        curl_close($ch);
        return false;  // Hubo un error al enviar el correo
    }
}

?>
