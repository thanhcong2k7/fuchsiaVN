<?php
// 1. Start Session & Set Content Type
session_start();
header('Content-Type: application/json'); // Set response type to JSON

// --- Helper function for sending JSON errors ---
function send_json_error($message, $http_code = 400)
{
    http_response_code($http_code); // Set appropriate HTTP status code
    echo json_encode(['success' => false, 'message' => $message]);
    exit; // Stop script execution
}

// 2. Check Authentication
if (!isset($_SESSION["userwtf"])) {
    send_json_error("Authentication required.", 401);
}
// **Sanitize User ID** (Ensure it's an integer)
$user_id = filter_var($_SESSION["userwtf"], FILTER_VALIDATE_INT);
if ($user_id === false || $user_id <= 0) {
    // This should ideally not happen if the session variable is always numeric
    error_log("Invalid user ID in session: " . $_SESSION["userwtf"]);
    send_json_error("Invalid session.", 400);
}


// 3. Check Request Method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    send_json_error("Invalid request method. Only POST is allowed.", 405);
}

// 4. Read and Decode JSON Input
$json_data = file_get_contents('php://input');
$data = json_decode($json_data, true);

if (json_last_error() !== JSON_ERROR_NONE || $data === null) {
    send_json_error("Invalid JSON data received.", 400);
}

// 5. Validate and **Sanitize** Input Data
$album_id_raw = isset($data['albumid']) ? $data['albumid'] : null;
$track_ids_raw = isset($data['trackids']) && is_array($data['trackids']) ? $data['trackids'] : null;

// **Sanitize Album ID**
$album_id = filter_var($album_id_raw, FILTER_VALIDATE_INT);
if ($album_id === false || $album_id <= 0) {
    send_json_error("Invalid or missing 'albumid'. Must be a positive integer.", 400);
}

if ($track_ids_raw === null || empty($track_ids_raw)) {
    send_json_error("Invalid or missing 'trackids' array.", 400);
}

// **Sanitize Track IDs** (Ensure they are positive integers)
$sanitized_track_ids = [];
foreach ($track_ids_raw as $tid) {
    $validated_id = filter_var($tid, FILTER_VALIDATE_INT);
    if ($validated_id !== false && $validated_id >= 0) {
        // Add the *sanitized integer* value to the array
        $sanitized_track_ids[] = $validated_id;
    } else {
        // Log or ignore invalid IDs, but don't let them proceed
        error_log("Invalid track ID skipped: " . $tid . " for user " . $user_id);
    }
}

// Remove duplicate IDs just in case
$sanitized_track_ids = array_unique($sanitized_track_ids);

if (empty($sanitized_track_ids)) {
    send_json_error("No valid track IDs provided.", 400);
}

// --- Database Operations using sql.php (Less Secure) ---
// Note: No try...catch specific to DB errors as sql.php suppresses warnings/errors

// 6. Include Database Functions
// Be careful with path if add_tracks_to_album.php is not in the same directory as edit.php
require '../assets/variables/sql.php'; // Make sure path is correct
// Check if $GLOBALS["conn"] was established (basic check)
if (!isset($GLOBALS["conn"]) || !$GLOBALS["conn"]) {
    error_log("Database connection failed in add_tracks_to_album.php");
    send_json_error("Internal server error (DB Connection).", 500);
}


// 7. Authorization Check: Verify Album Ownership
// **Build SQL String Carefully using Sanitized Integers**
$sql_check_album = "SELECT albumID FROM album WHERE albumID = " . $album_id . " AND userID = " . $user_id;
$result_check_album = query($sql_check_album); // Use the vulnerable query function

if (!$result_check_album) {
    // Query failed (could be SQL error or connection issue)
    error_log("SQL Error (Album Check): " . $GLOBALS["conn"]->error . " | SQL: " . $sql_check_album);
    send_json_error("Database error during permission check.", 500);
}

if ($result_check_album->num_rows === 0) {
    // Album not found OR does not belong to the user
    send_json_error("Album not found or you do not have permission to modify it.", 403); // 403 Forbidden
}
$result_check_album->free(); // Free result set


// 8. Prepare and Execute Update
// Build the IN clause string safely from *sanitized integers*
$safe_in_clause = implode(',', $sanitized_track_ids); // Safe because we ensured they are integers

// **Build SQL String Carefully using Sanitized Integers**
$sql_update_tracks = "UPDATE track SET albumID = " . $album_id
    . " WHERE id IN (" . $safe_in_clause . ")"
    . " AND userID = " . $user_id; // Essential security check

$update_result = query($sql_update_tracks); // Use the vulnerable query function

// 9. Prepare and Send Response
if ($update_result) {
    // Note: mysqli->query returns TRUE on success for UPDATE,
    // but doesn't guarantee rows were actually affected if WHERE clause didn't match.
    // For better feedback, you might check $GLOBALS["conn"]->affected_rows if needed.
    echo json_encode(['success' => true, 'message' => 'Tracks successfully linked to the album.']);
} else {
    // Database error occurred during update
    error_log("SQL Error (Track Update): " . $GLOBALS["conn"]->error . " | SQL: " . $sql_update_tracks);
    send_json_error("Database error occurred while adding tracks.", 500);
}

exit; // Ensure script terminates cleanly
?>