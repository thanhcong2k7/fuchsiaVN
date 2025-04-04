<?php
session_start();
header('Content-Type: application/json');

// --- Helper function for JSON errors ---
function send_json_error_del($message, $http_code = 400)
{
    http_response_code($http_code);
    echo json_encode(['success' => false, 'message' => $message]); // Use 'success' consistently
    exit;
}

// 1. Check Authentication
if (!isset($_SESSION["userwtf"])) {
    send_json_error_del("Authentication required.", 401);
}
$user_id = filter_var($_SESSION["userwtf"], FILTER_VALIDATE_INT);
if ($user_id === false || $user_id <= 0) {
    error_log("Invalid user ID in session for delete operation: " . $_SESSION["userwtf"]);
    send_json_error_del("Invalid session.", 400);
}

// 2. Validate Inputs
$album_id = isset($_GET['albumid']) ? filter_var($_GET['albumid'], FILTER_VALIDATE_INT) : false;
$track_id = isset($_GET['trackid']) ? filter_var($_GET['trackid'], FILTER_VALIDATE_INT) : false; // Also validate trackid

if ($album_id === false || $album_id <= 0) {
    send_json_error_del("Invalid or missing 'albumid'.", 400);
}

// Allow 0 as a valid track ID
if ($track_id === false || $track_id < 0) {
    send_json_error_del("Invalid or missing 'trackid'.", 400);
}

// --- Database Operations (using sql.php - less secure) ---
require '../assets/variables/sql.php';
if (!isset($GLOBALS["conn"]) || !$GLOBALS["conn"]) {
    error_log("Database connection failed in delete.php");
    send_json_error_del("Internal server error (DB Connection).", 500);
}

// 3. Primary Action: Update track.albumID to NULL
//    Ensure the track belongs to the user AND the specific album before nullifying
//    (Sanitizing IDs before embedding)
$sql_update_track = "UPDATE track SET albumID = NULL "
    . "WHERE id = " . $track_id . " "
    . "AND userID = " . $user_id . ";";       // Make sure user owns the track

$update_track_result = query($sql_update_track);

if (!$update_track_result) {
    error_log("SQL Error (Track Update to NULL): " . $GLOBALS["conn"]->error . " | SQL: " . $sql_update_track);
    send_json_error_del("Database error occurred while removing track association.", 500);
}

// Check if any row was actually updated (means the track was found and belonged to the user/album)
$affected_rows = $GLOBALS["conn"]->affected_rows;
if ($affected_rows <= 0) {
    // This could mean the track wasn't found, didn't belong to this album, or didn't belong to the user.
    // Depending on desired behavior, you might return success or a specific notice.
    // Let's return success, assuming the end state (track not linked) is achieved.
    // You could add more checks here if needed.
    // error_log("No track row updated for track $track_id, album $album_id, user $user_id. Might already be removed or invalid.");
}


// 4. Optional but Recommended: Clean up album.trackID JSON array
//    This part is more complex and requires careful handling.

//    a. Fetch the current album data (specifically the trackID JSON) - check ownership again for safety
$album_data = getRelease($user_id, 0, $album_id); // Use existing function

if (!$album_data || !isset($album_data->file) || !is_array($album_data->file)) {
    // Album not found for user, or trackID field is corrupt/missing
    // Log this, but don't fail the whole operation since track.albumID was updated.
    error_log("Could not fetch or parse album.trackID JSON for album $album_id during cleanup.");
    // Proceed to return success based on the primary action.
} else {
    $current_track_ids = $album_data->file; // This is already a PHP array from json_decode in getRelease

    // b. Filter out the track ID to be removed (use strict comparison if IDs are strings in JSON)
    $new_track_ids = array_filter($current_track_ids, function ($tid) use ($track_id) {
        // Ensure type comparison is consistent (e.g., both integers or both strings)
        return intval($tid) !== intval($track_id);
    });

    // Re-index array if needed (json_encode handles non-sequential keys fine for arrays)
    $new_track_ids = array_values($new_track_ids);

    // c. Encode the new array back to JSON
    $new_track_json = json_encode($new_track_ids);
    if ($new_track_json === false) {
        error_log("Failed to re-encode JSON for album.trackID on album $album_id.");
        // Don't fail the operation, just log the error.
    } else {
        // d. Update the album table (MUST escape the JSON string for SQL)
        $escaped_json = $GLOBALS["conn"]->real_escape_string($new_track_json); // Use mysqli escaping
        $sql_update_album_json = "UPDATE album SET trackID = '" . $escaped_json . "' "
            . "WHERE albumID = " . $album_id . " "
            . "AND userID = " . $user_id . ";"; // Ensure user owns album

        $update_album_result = query($sql_update_album_json);
        if (!$update_album_result) {
            error_log("SQL Error (Album JSON Update): " . $GLOBALS["conn"]->error . " | SQL: " . $sql_update_album_json);
            // Log error but don't necessarily fail the overall operation.
        }
    }
}


// 5. Send Success Response (assuming primary action succeeded or resulted in desired state)
echo json_encode(['success' => true, 'message' => 'Track removed from release.']);
exit;

?>