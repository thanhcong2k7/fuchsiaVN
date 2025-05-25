<?php
// Include necessary files
include 'assets/variables/sql.php';
// Start session
session_start();

header('Content-Type: application/json');

$response = array('success' => false, 'message' => '');

// Check if release_id is provided and user is logged in
if (isset($_POST['release_id']) && isset($_SESSION['userwtf'])) {
    $releaseId = intval($_POST['release_id']);
    $staffId = intval($_SESSION['userwtf']);

    // Validate releaseId and staffId (optional but recommended)
    if ($releaseId > 0 && $staffId > 0) {
        // Update the staffID in the album table
        // Ensure the release is currently unclaimed (staffID IS NULL or 0)
        $sql = "UPDATE album SET staffID = ? WHERE albumID = ? AND (staffID IS NULL OR staffID = 0)";
        $stmt = $GLOBALS["conn"]->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("ii", $staffId, $releaseId);

            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0) {
                    $response['success'] = true;
                    $response['message'] = 'Release claimed successfully.';
                } else {
                    $response['message'] = 'Release already claimed or not found.';
                }
            } else {
                $response['message'] = 'Database execution error: ' . $stmt->error;
                 error_log("Database execution error in claim_release.php: " . $stmt->error);
            }
            $stmt->close();
        } else {
            $response['message'] = 'Database prepare error: ' . $GLOBALS["conn"]->error;
            error_log("Database prepare error in claim_release.php: " . $GLOBALS["conn"]->error);
        }
    } else {
        $response['message'] = 'Invalid release ID or staff ID.';
    }
} else {
    $response['message'] = 'Release ID or user not provided.';
}

echo json_encode($response);
?>