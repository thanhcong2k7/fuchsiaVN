<?php
header("Content-Type: application/json");
header("Cross-Origin-Embedder-Policy: require-corp");
header("Cross-Origin-Resource-Policy: cross-origin");
header("Cross-Origin-Opener-Policy: same-origin");
session_start();

require '../assets/variables/sql.php';

if (!isset($_SESSION["userwtf"])) {
    http_response_code(401);
    die(json_encode(['error' => 'Unauthorized']));
}

if (!isset($_GET['trackID'])) {
    http_response_code(400);
    die(json_encode(['error' => 'Missing trackID parameter']));
}

$trackID = intval($_GET['trackID']);
$track = getTrack($trackID);

if (!$track || $track->user_id !== $_SESSION["userwtf"]) {
    http_response_code(404);
    die(json_encode(['error' => 'Track not found']));
}

// Get additional data if needed
$release = getRelease($_SESSION["userwtf"], 0, $trackID);
$artist = fetchArtist($_SESSION["userwtf"]);

echo json_encode([
    'track' => $track,
    'release' => $release,
    'artists' => $artist
]);
?>