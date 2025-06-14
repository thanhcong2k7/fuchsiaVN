<?php
require_once '../../assets/variables/sql.php';
session_start();

header('Content-Type: application/json');

try {
    // Authentication check
    if (!isset($_SESSION["userwtf"])) {
        throw new Exception('Authentication required');
    }

    // Validate input
    $releaseId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    if (!$releaseId) {
        throw new Exception('Invalid release ID');
    }

    // Get release data
    $release = getRelease($_SESSION["userwtf"], 0, $releaseId);
    echo $_SESSION['userwtf']."\n";
    echo $release->id."\n";
    if (!$release) {
        throw new Exception('Release not found');
    }

    // Get tracks and artists
    $tracks = [];
    $f = getFile($_SESSION["userwtf"]);
    foreach ($release->file as $trackObject) {
        // Extract ID from trackType object
        $trackId = $trackObject->id;
        $track = getTrack($trackId);
        if ($track) {
            $artists = getArtist($track->id);
            $tracks[] = [
                'id' => $track->id,
                'name' => $track->name,
                'position' => $track->position,
                'duration' => formatDuration($track->duration),
                'artists' => array_column($artists, 'name')
            ];
        }
    }

    // Check if rejection_reason column exists
    $columnExists = false;
    $checkColumnQuery = "SHOW COLUMNS FROM album LIKE 'rejection_reason'";
    $columnResult = $GLOBALS["conn"]->query($checkColumnQuery);
    if ($columnResult && $columnResult->num_rows > 0) {
        $columnExists = true;
    }
    
    // Get rejection reason if status is ERROR and column exists
    $rejectionReason = null;
    if ($release->status == 2 && $columnExists) {
        $sql = "SELECT rejection_reason FROM album WHERE albumID = ?";
        $stmt = $GLOBALS["conn"]->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("i", $releaseId);
            $stmt->execute();
            $stmt->bind_result($rejectionReason);
            $stmt->fetch();
            $stmt->close();
        }
    }
    
    // Prepare response
    $response = [
        'status' => 'success',
        'data' => [
            'id' => $release->id,
            'name' => $release->name,
            'upc' => $release->upc,
            'artwork' => $release->artp,
            'status' => $release->status,
            'release_date' => $release->relDate,
            'original_release_date' => $release->orgReldate,
            'rejection_reason' => $rejectionReason,
            'artists' => array_values(array_unique(array_merge(...array_column($tracks, 'artists')))),
            'tracks' => $tracks
        ]
    ];

    echo json_encode($response);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}

function formatDuration($seconds)
{
    $minutes = floor($seconds / 60);
    $seconds = $seconds % 60;
    return sprintf('%d:%02d', $minutes, $seconds);
}