<?php
$clientId = 'ASOwJpaJ8asf6_aGDUJmPgvUyThE7GqLwcJgLNQ6sfIWDUac2Fvzc5TyCeuZPvKKx1Aeyfsu2mm40555';
$secret = 'EMQRW_2Sq-bPs3KyYBe8z-dxqLeA-g96s-pINNmfrDv0Jnx9XPuqctUdUgQxPNI1oNO1250CymmmtIcy';

function getAccessToken($clientId, $secret) {
    $ch = curl_init("https://api-m.sandbox.paypal.com/v1/oauth2/token");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, "$clientId:$secret");
    curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Accept: application/json",
        "Content-Type: application/x-www-form-urlencoded"
    ]);
    $res = curl_exec($ch);
    curl_close($ch);
    return json_decode($res, true)['access_token'];
}

function crearProducto($accessToken) {
    $ch = curl_init("https://api-m.sandbox.paypal.com/v1/catalogs/products");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
        "name" => "Suscripción Premium",
        "description" => "Acceso mensual",
        "type" => "SERVICE",
        "category" => "SOFTWARE"
    ]));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json",
        "Authorization: Bearer $accessToken"
    ]);
    $res = curl_exec($ch);
    curl_close($ch);
    return json_decode($res, true)['id'];
}

function crearPlan($accessToken, $productId) {
    $ch = curl_init("https://api-m.sandbox.paypal.com/v1/billing/plans");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
        "product_id" => $productId,
        "name" => "Plan Mensual",
        "description" => "Cobro mensual recurrente",
        "billing_cycles" => [[
            "frequency" => ["interval_unit" => "MONTH", "interval_count" => 1],
            "tenure_type" => "REGULAR",
            "sequence" => 1,
            "total_cycles" => 0,
            "pricing_scheme" => [
                "fixed_price" => ["value" => "10.00", "currency_code" => "USD"]
            ]
        ]],
        "payment_preferences" => [
            "auto_bill_outstanding" => true,
            "setup_fee_failure_action" => "CONTINUE",
            "payment_failure_threshold" => 1
        ]
    ]));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json",
        "Authorization: Bearer $accessToken"
    ]);
    $res = curl_exec($ch);
    curl_close($ch);
    return json_decode($res, true)['id'];
}

$accessToken = getAccessToken($clientId, $secret);
$productId = crearProducto($accessToken);
$planId = crearPlan($accessToken, $productId);

if(isset($planId)) {
    header("location: iniciar_suscripcion.php?plan_id=$planId");
}
?>