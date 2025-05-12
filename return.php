<?php
// return.php
require_once 'assets/plugins/vendor/autoload.php';
use PayOS\PayOS;

// Initialize PayOS SDK
$clientId = 'fc13dcc9-dbbd-472b-9232-2049449847aa';
$apiKey = '7ec3d2f3-157d-43db-afc3-61dc72a8e186';
$checksumKey = '6706daaad10480a167971f24a2d5cd132ba6baf34af78befd20eaf1b3321516a';
$payOS = new PayOS($clientId, $apiKey, $checksumKey);

// Parse redirect parameters
$id = $_GET['id'] ?? '';
$status = $_GET['status'] ?? '';
$cancel = ($_GET['cancel'] ?? 'false') === 'true';

// Determine final status
if ($cancel) {
    $finalStatus = 'CANCELLED';
} elseif (in_array($status, ['PAID', 'CANCELLED'], true)) {
    $finalStatus = $status;
} else {
    // Fetch live status from PayOS
    $info = $payOS->getPaymentLinkInformation($id);
    $finalStatus = $info->status;
}

// Render the page
?>
<!doctype html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <title>Trạng thái thanh toán</title>
    <script>
        // On load, either show finalStatus or start polling if still PENDING/PROCESSING
        document.addEventListener('DOMContentLoaded', () => {
            const statusEl = document.getElementById('currentStatus');
            let status = '<?= htmlspecialchars($finalStatus, ENT_QUOTES, 'UTF-8') ?>';
            statusEl.textContent = `Trạng thái: ${status}`;

            if (status === 'PENDING' || status === 'PROCESSING') {
                // pollReturnStatus defined in payment.js
                pollReturnStatus('<?= addslashes($id) ?>', statusEl);
            }
        });
    </script>
    <!-- include payment.js and Bootstrap here -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
</head>

<body class="p-4">
    <h1>Thanh toán của bạn</h1>
    <p id="currentStatus">Đang kiểm tra…</p>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="/components/payment.js"></script>
</body>

</html>