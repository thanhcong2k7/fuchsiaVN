<?php
// api/check-status.php
require_once '../../assets/plugins/vendor/autoload.php';
use PayOS\PayOS;

// 1) Initialize PayOS client
$clientId = 'fc13dcc9-dbbd-472b-9232-2049449847aa';
$apiKey = '7ec3d2f3-157d-43db-afc3-61dc72a8e186';
$checksumKey = '6706daaad10480a167971f24a2d5cd132ba6baf34af78befd20eaf1b3321516a';
$payOS = new PayOS($clientId, $apiKey, $checksumKey);

// 2) Read the paymentLinkId from the query
if (!isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing payment link ID']);
    exit;
}
$paymentLinkId = $_GET['id'];

try {
    $info = $payOS->getPaymentLinkInformation($paymentLinkId);

    header('Content-Type: application/json');
    echo json_encode([
        'status' => json_decode(json_encode($info))->status
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Unable to fetch status',
        'message' => $e->getMessage()
    ]);
}
?>