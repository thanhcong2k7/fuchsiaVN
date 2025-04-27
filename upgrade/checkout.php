<?php
require_once '../assets/plugins/vendor/autoload.php';
header("Content-Type: application/json");
use PayOS\PayOS;
$payOS = new PayOS('fc13dcc9-dbbd-472b-9232-2049449847aa', '7ec3d2f3-157d-43db-afc3-61dc72a8e186', '6706daaad10480a167971f24a2d5cd132ba6baf34af78befd20eaf1b3321516a');
$data = [
    "orderCode" => intval(substr(strval(microtime(true) * 10000), -6)),
    "amount" => 150000,
    "description" => "White Label",
    "items" => [
        0 => [
            'name' => 'fuchsia Media Group - PRO Plan',
            'price' => 150000,
            'quantity' => 1
        ]
    ],
    "returnUrl" => "https://localhost/upgrade/success.html",
    "cancelUrl" => "https://localhost/upgrade/cancel.html"
];
$response = $payOS->createPaymentLink($data);
echo json_encode($response);
?>