<?php
// Database connection (adjust credentials)
$mysqli = new mysqli('localhost', 'username', 'password', 'database');
if ($mysqli->connect_errno) {
    http_response_code(500);
    echo json_encode(['error' => $mysqli->connect_error]);
    exit;
}

// Fetch album
$albumId = intval($_GET['id'] ?? 0);
$stmt = $mysqli->prepare('SELECT album_name, release_date, album_image FROM albums WHERE id = ?');
$stmt->bind_param('i', $albumId);
$stmt->execute();
$stmt->bind_result($name, $releaseDate, $image);
if (!$stmt->fetch()) {
    http_response_code(404);
    echo json_encode(['error' => 'Album not found']);
    exit;
}
$stmt->close();

// Fetch DSP URLs
$stmt2 = $mysqli->prepare('
    SELECT dsp_name, dsp_url, dsp_order
    FROM dsp_urls
    WHERE album_id = ?
    ORDER BY dsp_order
');
$stmt2->bind_param('i', $albumId);
$stmt2->execute();
$stmt2->bind_result($dspName, $dspUrl, $dspOrder);

$dspURLs = [];
while ($stmt2->fetch()) {
    $dspURLs[] = [
        'name'  => $dspName,
        'url'   => $dspUrl,
        'order' => $dspOrder
    ];
}
$stmt2->close();
$mysqli->close();

// Build return structure
$returnData = [
    'albumName'   => $name,
    'id'          => $albumId,
    'releaseDate' => $releaseDate,
    'albumImage'  => $image,
    'dspURLs'     => $dspURLs
];

// Send JSON response
header('Content-Type: application/json');
echo json_encode($returnData, JSON_PRETTY_PRINT);
?>
