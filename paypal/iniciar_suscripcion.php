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

$planId = $_GET['plan_id'];
$accessToken = getAccessToken($clientId, $secret);

$ch = curl_init("https://api-m.sandbox.paypal.com/v1/billing/subscriptions");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    "plan_id" => $planId,
    "application_context" => [
        "brand_name" => "Mi Sitio",
        "return_url" => "https://compiso.infy.uk/paypal/sub_exitosa.php",
        "cancel_url" => "https://compiso.infy.uk/paypal/sub_cancelada.php"
    ]
]));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Authorization: Bearer $accessToken"
]);
$res = curl_exec($ch);
curl_close($ch);

$data = json_decode($res, true);
foreach ($data['links'] as $link) {
    if ($link['rel'] === 'approve') {
        header("Location: " . $link['href']);
        exit;
    }
}
