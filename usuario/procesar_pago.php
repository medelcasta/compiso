<?php
session_start();
require 'paypal-config.php';
require('../utiles/conexion.php');

if (!isset($_POST["precio"]) || !filter_var($_POST["precio"], FILTER_VALIDATE_FLOAT)) {
    die("Error: Precio no válido.");
}

$precio = $_POST["precio"];
$email = $_POST["email"];
$metodo_pago = $_POST["metodo_pago"];

if ($metodo_pago === "paypal") {
    // Crear la orden en PayPal
    $pedido = [
        "intent" => "CAPTURE",
        "purchase_units" => [[
            "amount" => [
                "currency_code" => "EUR",
                "value" => $precio
            ],
            "description" => "Suscripción Premium"
        ]]
    ];

    // Inicializar cURL para crear la orden
    $ch = curl_init(PAYPAL_URL . "/v2/checkout/orders");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($pedido));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $access_token",
        "Content-Type: application/json"
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $respuesta = curl_exec($ch);
    $paypal_response = json_decode($respuesta, true);
    curl_close($ch);

    // Verificar si se creó la orden
    if (isset($paypal_response['status']) && $paypal_response['status'] == "CREATED") {
        foreach ($paypal_response['links'] as $link) {
            if ($link['rel'] === 'approve') {
                header("Location: " . $link['href']); // Redirigir al pago
                exit;
            }
        }
    } else {
        die("Error: No se pudo crear la orden de PayPal.");
    }
} else {
    die("Error: Método de pago no válido.");
}
?>
