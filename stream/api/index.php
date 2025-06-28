<?php
// Set content type to JSON
header('Content-Type: application/json');

// Database connection (replace with your credentials)
$host = '127.0.0.1';
$db = 'wtjmdnac_fuchsia';
$user = 'wtjmdnac_fuchsia';
$pass = 'nguyenthanhcong';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
    exit;
}

// Get album name from query string
$albumName = $_GET['associated'] ?? '';

if (empty($albumName)) {
    http_response_code(400);
    echo json_encode(['error' => 'Album name not provided']);
    exit;
}

// Fetch album from database using associated column
$stmt = $pdo->prepare('SELECT id, album_name AS albumName, release_date AS releaseDate, 
                       album_image AS albumImage, artist 
                       FROM albums_stream 
                       WHERE associated = :associated');
$stmt->execute([':associated' => $albumName]);
$album = $stmt->fetch();

if (!$album) {
    http_response_code(404);
    echo json_encode(['error' => 'Album not found']);
    exit;
}

// Fetch DSP URLs for the album
$stmt = $pdo->prepare('SELECT name, url, `order` FROM dsp_urls WHERE album_id = :album_id');
$stmt->execute([':album_id' => $album['id']]);
$dspURLs = $stmt->fetchAll();

// Build the response
$response = [
    'albumName' => $album['albumName'],
    'id' => $album['id'],
    'releaseDate' => $album['releaseDate'],
    'albumImage' => $album['albumImage'],
    'artist' => $album['artist'],
    'dspURLs' => $dspURLs
];

// Output the JSON response
echo json_encode($response, JSON_PRETTY_PRINT);
?>