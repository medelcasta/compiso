<?php
// Credenciales de tu aplicación de PayPal
define("CLIENT_ID", "ASOwJpaJ8asf6_aGDUJmPgvUyThE7GqLwcJgLNQ6sfIWDUac2Fvzc5TyCeuZPvKKx1Aeyfsu2mm40555");
define("CLIENT_SECRET", "EMQRW_2Sq-bPs3KyYBe8z-dxqLeA-g96s-pINNmfrDv0Jnx9XPuqctUdUgQxPNI1oNO1250CymmmtIcy");

// URL base de la API de PayPal
// Usa la URL de sandbox para pruebas y la URL de producción para el entorno real
define("PAYPAL_API_URL", "https://api-m.sandbox.paypal.com"); // Para pruebas
// define("PAYPAL_API_URL", "https://api-m.paypal.com"); // Para producción


// Obtener el token de acceso desde PayPal
$ch = curl_init(PAYPAL_URL . "/v1/oauth2/token");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_USERPWD, CLIENT_ID . ":" . CLIENT_SECRET);
curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Accept: application/json",
    "Accept-Language: es_ES"
]);
$respuesta = curl_exec($ch);
$auth_response = json_decode($respuesta, true);
curl_close($ch);

if (!isset($auth_response['access_token'])) {
    die("Error: No se pudo obtener el token de PayPal.");
}

$access_token = $auth_response['access_token'];
?>
