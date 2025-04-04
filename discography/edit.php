<?php
session_start();
if (!isset($_SESSION["userwtf"])) {
    header("Location: ../login/");
    exit; // Add exit after header redirect
} else {
    require "../assets/variables/sql.php";
    // Basic input validation for $_GET['id']
    $releaseId = isset($_GET["id"]) ? filter_var($_GET["id"], FILTER_VALIDATE_INT) : false;
    if ($releaseId === false) {
        echo '<script>alert("Invalid Release ID!"); window.location.href=".";</script>';
        exit;
    }

    $user = getUser($_SESSION["userwtf"]);
    $release = getRelease($_SESSION["userwtf"], 0, $releaseId);

    // Check if release exists for the user
    if (!$release) {
        echo '<script>alert("Release not found or access denied!"); window.location.href=".";</script>';
        exit;
    }

    if ($release->status == 3) {
        echo '<script>alert("Your release ' .
            htmlspecialchars($release->name, ENT_QUOTES, 'UTF-8') . // Use htmlspecialchars for output
            ' is currently being processed!");
        window.location.href=".";
        </script>';
        exit; // Add exit
    }
    $trackList = getTrackList($_SESSION["userwtf"]); // Assuming this gets tracks *not* yet in an album?
}

// --- Handle New/Delete ---
// Consider moving these actions to separate scripts or API endpoints for better structure.
if (isset($_GET["new"]) && isset($_SESSION["userwtf"])) {
    // Assuming these functions handle security appropriately (e.g., prepared statements)
    resetinc("album");
    query("insert into album (userID) values (" . $_SESSION["userwtf"] . ");");
    $newid = creNew($_SESSION["userwtf"]);
    // Basic validation for newid
    if ($newid) {
        echo "<script>window.location.href='edit.php?id=" . $newid . "';</script>";
    } else {
        echo "<script>alert('Failed to create new release.'); window.location.href='.';</script>";
    }
    exit; // Add exit
} elseif (
    isset($_GET["delete"]) &&
    isset($releaseId) && // Use the validated $releaseId
    isset($_SESSION["userwtf"])
) {
    // Ensure $release was fetched successfully before attempting delete
    if ($release) {
        foreach ($release->file as &$trackDel) {
            // Use NULL instead of "" if the column allows NULLs
            // Ensure update function uses prepared statements
            update("albumID", null, "track", "id=" . intval($trackDel));
        }
        // Ensure query function uses prepared statements
        query("delete from album where albumID=" . $releaseId . " AND userID=" . $_SESSION['userwtf'] . ";"); // Add userID check for security
        echo "<script>alert('Release deleted.'); window.location.href='.';</script>";
    } else {
        echo "<script>alert('Cannot delete - release not found.'); window.location.href='.';</script>";
    }
    exit; // Add exit
}

// --- Prepare data for the form ---
// --- Prepare data for the form ---
$mergedArtistnames = ""; // For album-level display (potentially)
$albumTracks = [];     // CORRECTED: Array to hold tracks belonging to THIS album
$allArtistNames = [];  // Helper array for unique album artists

// Ensure $release object exists and we have a valid numeric $releaseId
// Use filter_var again for safety, though it should be set earlier
$currentAlbumId = filter_var($releaseId, FILTER_VALIDATE_INT);
$currentUserId = filter_var($_SESSION["userwtf"], FILTER_VALIDATE_INT); // Sanitize user ID too

if ($release && $currentAlbumId > 0 && $currentUserId > 0) {

    // <<< --- CORRECTED TRACK FETCHING LOGIC --- >>>
    // Query the track table directly for tracks linked to this album ID AND user ID
    // (Sanitizing IDs before embedding - less secure than prepared statements)
    $sql_get_album_tracks = "SELECT id FROM track WHERE albumID = " . $currentAlbumId . " AND userID = " . $currentUserId;

    // Use the query function from sql.php
    $tracksResult = query($sql_get_album_tracks);

    if ($tracksResult) {
        if ($tracksResult->num_rows > 0) {
            while ($trackRow = $tracksResult->fetch_assoc()) {
                $trackId = $trackRow['id'];
                // Use existing getTrack function to get full track details by its ID
                $t = getTrack($trackId);

                // Check if getTrack returned a valid object for this ID
                if ($t && isset($t->id) && $t->id == $trackId) {
                    $albumTracks[] = $t; // Add the full track object to our display array

                    // Aggregate artist names for the album display block (optional)
                    // $t->artistname should be populated by getTrack()
                    if (!empty($t->artistname)) {
                         $currentTrackArtists = explode(', ', $t->artistname);
                         $allArtistNames = array_merge($allArtistNames, $currentTrackArtists);
                    }
                } else {
                     error_log("getTrack($trackId) failed to return expected object for album $currentAlbumId");
                }
            }
        }
        $tracksResult->free(); // Free result set
    } else {
        // Log error if the query failed
        error_log("SQL Error fetching album tracks for album $currentAlbumId: " . $GLOBALS["conn"]->error);
    }
    // <<< --- END OF CORRECTED LOGIC --- >>>

    // Consolidate unique artist names for the album display block (if needed)
    $uniqueArtistNamesArray = array_unique(array_filter($allArtistNames));
    $mergedArtistnames = implode(', ', $uniqueArtistNamesArray);
    // Escape the final merged list for display
    $mergedArtistnames = htmlspecialchars($mergedArtistnames, ENT_QUOTES, 'UTF-8');


}

$track=array();
// Ensure $release is valid before proceeding
if ($release && isset($release->file) && is_array($release->file)) {
    echo '<script>console.log("Found Tracks in this album.")</script>';
    $f = getFile($_SESSION["userwtf"]); // Assuming getFile gets tracks associated with the album ID
    if ($f) { // Check if getFile returned data
        foreach ($release->file as $trackIdInRelease) {
            // Find the corresponding track details from $f
            $foundTrack = null;
            foreach ($f as $fileData) {
                if ($fileData->id == $trackIdInRelease->file) {
                    $foundTrack = $fileData;
                    break;
                }
            }

            if ($foundTrack->id) {
                $t = getTrack(strval($foundTrack->id));
                //echo "<script>console.log('foundtrack id = ".json_encode($t)."');</script>";
                // Returning in console: select * from track where id=;
                if ($t->name != NULL) { // Check if getTrack returned data
                    $track[] = $t;
                    echo "<script>console.log('huh ".json_encode($t)."');</script>";
                    $artists = getArtist(strval($t->id)); // NO IT'S NOT WORKING
                    // Assuming getArtist returns artists for a track ID
                    if ($artists) { // Check if getArtist returned data
                        $currentTrackArtists = [];
                        foreach ($artists as $artist) {
                            $currentTrackArtists[] = htmlspecialchars($artist->name, ENT_QUOTES, 'UTF-8');
                        }
                        // Add unique artists to the merged list (more complex logic needed if distinct album artists desired)
                        if (!empty($currentTrackArtists)) {
                            $mergedArtistnames .= ($mergedArtistnames != "" ? ", " : "") . implode(", ", $currentTrackArtists);
                            // To get unique names across all tracks:
                            // $allArtistNames = array_merge($allArtistNames, $currentTrackArtists);
                            // $mergedArtistnames = implode(", ", array_unique($allArtistNames)); // Process after loop
                        }

                        // Store artist names directly with the track object if needed later
                        $foundTrack->artistname = implode(", ", $currentTrackArtists);

                    } else {
                        $foundTrack->artistname = "(No artists found)";
                    }
                }
            }
        }
        // If you need unique artist names for the whole album display:
        // $allArtistNamesArray = explode(', ', $mergedArtistnames);
        // $uniqueArtistNamesArray = array_unique(array_filter($allArtistNamesArray));
        // $mergedArtistnames = implode(', ', $uniqueArtistNamesArray);

    }
}
$collllll = array_column($track, 'id');
array_multisort($track, SORT_ASC, $collllll);

// Format dates safely
$relDateFormatted = '';
if (!empty($release->relDate) && $release->relDate !== '0000-00-00') { // Check for valid date
    try {
        $relDateFormatted = date_format(date_create($release->relDate), "m/d/Y");
    } catch (Exception $e) {
        $relDateFormatted = '';
    } // Handle invalid date format
}
$orgRelDateFormatted = '';
if (!empty($release->orgReldate) && $release->orgReldate !== '0000-00-00') { // Check for valid date
    try {
        $orgRelDateFormatted = date_format(date_create($release->orgReldate), "m/d/Y");
    } catch (Exception $e) {
        $orgRelDateFormatted = '';
    } // Handle invalid date format
}

$currentYear = date("Y");

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Release Editor -
        "<?php echo $release->name ? htmlspecialchars($release->name, ENT_QUOTES, 'UTF-8') : "(untitled)"; ?>"
    </title>
    <link rel="icon" href="/assets/images/favicon.ico" type="image/x-icon">

    <link href="/assets/css/pace.min.css" rel="stylesheet" />
    <link href="/assets/plugins/vectormap/jquery-jvectormap-2.0.2.css" rel="stylesheet" />
    <link href="/assets/plugins/simplebar/css/simplebar.css" rel="stylesheet" />
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="/assets/css/animate.css" rel="stylesheet" type="text/css" />
    <link href="/assets/css/icons.css" rel="stylesheet" type="text/css" />
    <link href="/assets/css/sidebar-menu.css" rel="stylesheet" />
    <link href="/assets/css/app-style.css" rel="stylesheet" />
    <link href="/assets/css/scroll-bar.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="/assets/css/select2_custom.css">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.css" />

    <script src="/assets/js/pace.min.js"></script>
    <style>
        /* --- Embedded Styles (Consider moving to CSS files) --- */

        /* DnD Artwork */
        .dnd {
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
        }

        #drop-area {
            width: 300px;
            height: 300px;
            padding: 15px;
            text-align: center;
            border-radius: 7px;
            border: 2px dashed #ffffff;
            cursor: pointer;
        }

        #img-view {
            width: 100%;
            height: 100%;
            border-radius: 7px;
            border: 1px dashed #AFFFFFFF;
            background: #BF000000;
            background-position: center;
            background-size: cover;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
        }

        #img-view img {
            width: 100px;
            /* Adjust icon size */
            margin-bottom: 15px;
        }

        /* Style the icon */
        #img-view span {
            display: block;
            font-size: 12px;
            color: #eeeeee;
            line-height: 1.4;
        }

        /* Loader */
        .loader {
            width: 30px;
            aspect-ratio: 1;
            --c: no-repeat linear-gradient(#fff 0 0);
            background: var(--c) 0% 50%, var(--c) 50% 50%, var(--c) 100% 50%;
            background-size: 20% 100%;
            animation: l1 1s infinite linear;
        }

        @keyframes l1 {

            0%,
            100% {
                background-size: 20% 100%, 20% 100%, 20% 100%
            }

            33% {
                background-size: 20% 10%, 20% 100%, 20% 100%
            }

            50% {
                background-size: 20% 100%, 20% 10%, 20% 100%
            }

            66% {
                background-size: 20% 100%, 20% 100%, 20% 10%
            }
        }

        /* Modal */
        .modal-content {
            background-color: rgba(0, 0, 0, 0.9) !important;
            color: #fff;
            font-size: 11px;
        }

        .modal-header,
        .modal-footer {
            border-color: #333 !important;
        }

        .modal-header .close {
            color: #fff !important;
            opacity: 1;
        }

        /* Alert */
        .alert.callout {
            padding: 15px;
            border-color: #6DD134;
            border-radius: 5px;
            border-width: 2px;
            color: white;
        }

        /* Select2 Dark Theme Adjustments */
        .select2-container--default .select2-selection--multiple {
            background-color: #3A3F51;
            border: 1px solid #545A75;
            color: #fff;
        }

        /* Adjust background and border */
        .select2-container--default .select2-selection--multiple .select2-selection__rendered {
            color: #fff !important;
        }

        /* List color */
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #545A75;
            border: 1px solid #6C757D;
            color: #fff;
        }

        /* Choice background */
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: #ced4da;
        }

        /* Choice remove icon */
        .select2-container--default .select2-search--inline .select2-search__field {
            color: #fff !important;
        }

        /* Search input text */
        /* Dropdown Styles */
        .select2-dropdown {
            background-color: #3A3F51;
            border: 1px solid #545A75;
            color: #fff;
        }

        .select2-container--default .select2-results__option {
            color: #fff !important;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #545A75 !important;
            color: #fff !important;
        }

        .select2-container--default .select2-results__option[aria-selected=true] {
            background-color: #6C757D;
        }

        /* Selected item in dropdown */
        .select2-results__option--selectable {
            color: #fff;
        }
    </style>
</head>

<body class="bg-theme bg-theme1">
    <div id="wrapper">

        <?php include "../components/sidebar.php"; ?>
        <?php include "../components/topbar.php"; ?>
        <div class="clearfix"></div>

        <div class="content-wrapper">
            <div class="container-fluid">

                <div class="row">
                    <div class="col">
                        <form action="save.php" method="POST" id="formdepchai" enctype='multipart/form-data'>
                            <input value="<?php echo $releaseId; ?>" name="albumid" type="hidden">

                            <div class="card">
                                <div class="card-header">
                                    <i class="zmdi zmdi-border-color"></i> Release ID:
                                    <?php echo "FMG" . $releaseId; ?>
                                </div>
                                <div class="card-body">
                                    <ul class="nav nav-tabs nav-tabs-primary top-icon nav-justified">
                                        <li class="nav-item">
                                            <a href="javascript:void();" data-target="#metadata" data-toggle="pill"
                                                class="nav-link active"><i class="zmdi zmdi-storage"></i><span
                                                    class="hidden-xs">1. Metadata</span></a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="javascript:void();" data-target="#tracks" data-toggle="pill"
                                                class="nav-link"><i class="zmdi zmdi-playlist-audio"></i><span
                                                    class="hidden-xs">2. Tracks</span></a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="javascript:void();" data-target="#dist" data-toggle="pill"
                                                class="nav-link"><i class="zmdi zmdi-arrow-split"></i><span
                                                    class="hidden-xs">3. Distribute</span></a>
                                        </li>
                                    </ul>
                                    <div class="table-responsive overflow-hidden tab-content p-3">
                                        <div class="tab-pane active mb-0" id="metadata">
                                            <div class="">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="dnd">
                                                            <label for="input-file" id="drop-area">
                                                                <input type="file" accept="image/*" id="input-file"
                                                                    name="artworkup" hidden>
                                                                <div id="img-view"
                                                                    style="<?php echo !empty($release->art) ? 'background-image: url(\'' . htmlspecialchars($release->art, ENT_QUOTES, 'UTF-8') . '\');' : ''; ?>">
                                                                    <?php if (empty($release->art)): ?>
                                                                        <i class="zmdi zmdi-file-plus"
                                                                            style="font-size: 50px; margin-bottom: 15px;"></i>
                                                                        <span id="texttt">Drag & Drop or Click to Upload
                                                                            Artwork<br>(Min. 1500x1500, Square)</span>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-8" style="padding-top: 20px;">
                                                        <h3><?php echo ($release->name ? htmlspecialchars($release->name, ENT_QUOTES, 'UTF-8') : "(untitled)") .
                                                            ($release->version ? " (" . htmlspecialchars($release->version, ENT_QUOTES, 'UTF-8') . ")" : ""); ?>
                                                        </h3>
                                                        <span><span style="font-weight: bold;">UPC</span>:
                                                            <?php echo $release->upc ? htmlspecialchars($release->upc, ENT_QUOTES, 'UTF-8') : "(not set)"; ?></span>
                                                        <br />
                                                        <span><span style="font-weight: bold;">Artists:
                                                            </span><?php echo $mergedArtistnames; // Already HTML-escaped during creation ?></span>
                                                        <br />
                                                        <span><span style="font-weight: bold;">Status:
                                                            </span><?php
                                                            // Simplified status display
                                                            $statusText = "UNKNOWN";
                                                            switch ($release->status) {
                                                                case 0:
                                                                    $statusText = "DRAFT";
                                                                    break;
                                                                case 1:
                                                                    $statusText = "DELIVERED";
                                                                    break;
                                                                case 2:
                                                                    $statusText = "ERROR";
                                                                    break;
                                                                case 3:
                                                                    $statusText = "CHECKING";
                                                                    break;
                                                            }
                                                            echo $statusText;
                                                            ?>
                                                        </span>
                                                    </div>
                                                </div>
                                                <br>
                                                <div class="row">
                                                    <div class="form-group col-md-6"> <label for="albumtitle">Album
                                                            Title</label>
                                                        <input type="text" class="form-control" name="albumtitle"
                                                            placeholder="Name of your release"
                                                            value="<?php echo htmlspecialchars($release->name ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                                    </div>
                                                    <div class="form-group col-md-6"> <label for="albumversion">Version
                                                            line (optional)</label>
                                                        <input type="text" class="form-control" name="albumversion"
                                                            placeholder="Leave blank if only 1 track. E.g., Remix, Instrumental"
                                                            value="<?php echo htmlspecialchars($release->version ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col-md-6" id="sandbox-container">
                                                        <label for="reldate">Release date (mm/dd/yyyy)</label>
                                                        <div class="input-group"> <input type="text"
                                                                class="form-control" id="reldate" name="reldate"
                                                                placeholder="Pick release date (mm/dd/yyyy)"
                                                                value="<?php echo $relDateFormatted; ?>">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text"><i
                                                                        class="zmdi zmdi-calendar"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-6" id="sandbox-container2">
                                                        <label for="orgreldate">Original release date (optional,
                                                            mm/dd/yyyy)</label>
                                                        <div class="input-group"> <input type="text"
                                                                class="form-control" id="orgreldate" name="orgreldate"
                                                                placeholder="If released before (mm/dd/yyyy)"
                                                                value="<?php echo $orgRelDateFormatted; ?>">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text"><i
                                                                        class="zmdi zmdi-calendar"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr class="mt-1 mb-3" />
                                                <div class="row">
                                                    <div class="form-group col-12"> <label for="upc">UPC
                                                            (optional)</label>
                                                        <input type="text" class="form-control" maxlength="12"
                                                            name="upc" pattern="\d{12}" title="Enter a 12-digit UPC"
                                                            placeholder="Valid 12-digit UPC. Leave blank to auto-assign."
                                                            value="<?php echo htmlspecialchars($release->upc ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col-md-4">
                                                        <label for="cyear">© Copyright Year</label>
                                                        <input type="number" class="form-control" name="cyear"
                                                            pattern="\d{4}" title="Enter a 4-digit year"
                                                            placeholder="<?php echo $currentYear; ?>"
                                                            value="<?php echo htmlspecialchars($release->cyear ?? $currentYear, ENT_QUOTES, 'UTF-8'); ?>"
                                                            required>
                                                    </div>
                                                    <div class="form-group col-md-8">
                                                        <label for="cline">© Copyright Line</label>
                                                        <input type="text" class="form-control" name="cline"
                                                            placeholder="Holder of the composition copyright. E.g., Your Label Name"
                                                            value="<?php echo htmlspecialchars($release->c ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                                            required>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col-md-4">
                                                        <label for="pyear">℗ Phonogram Year</label>
                                                        <input type="number" class="form-control" name="pyear"
                                                            pattern="\d{4}" title="Enter a 4-digit year"
                                                            placeholder="<?php echo $currentYear; ?>"
                                                            value="<?php echo htmlspecialchars($release->pyear ?? $currentYear, ENT_QUOTES, 'UTF-8'); ?>"
                                                            required>
                                                    </div>
                                                    <div class="form-group col-md-8">
                                                        <label for="pline">℗ Phonogram Line</label>
                                                        <input type="text" class="form-control" name="pline"
                                                            placeholder="Holder of the sound recording copyright. E.g., Your Label Name"
                                                            value="<?php echo htmlspecialchars($release->p ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                                            required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane card mb-0" id="tracks">
                                            <div class="card-header">
                                                <i class="zmdi zmdi-playlist-audio"></i> Tracks List
                                                <div class="card-action">
                                                    <div class="dropdown">
                                                        <a href="#" class="text dropdown-toggle dropdown-toggle-nocaret"
                                                            data-toggle="modal" data-target="#addTrackModal"> <i
                                                                class="zmdi zmdi-collection-plus"></i> Add Tracks
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body table-responsive">
                                                <table class="table align-items-center table-borderless table-sm">
                                                    <thead>
                                                        <tr>
                                                            <th>ID</th>
                                                            <th>Track Name</th>
                                                            <th>Artists</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php if (!empty($track)): ?>
                                                            <?php foreach ($track as $tr): ?>
                                                                <tr id="track<?php echo $tr->id; ?>">
                                                                    <td><?php echo $tr->id; ?></td>
                                                                    <td><?php echo htmlspecialchars($tr->name ?? 'N/A', ENT_QUOTES, 'UTF-8'); ?>
                                                                    </td>
                                                                    <td><?php echo $tr->artistname; // Already escaped ?></td>
                                                                    <td>
                                                                        <a href="#" class="text-warning delete-track"
                                                                            data-track-id="<?php echo $tr->id; ?>"
                                                                            data-album-id="<?php echo $releaseId; ?>">
                                                                            Delete
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                                <?php echo "<script>console.log(".$tr->id.");</script>"; ?>
                                                            <?php endforeach; ?>
                                                        <?php else: ?>
                                                            <tr>
                                                                <td colspan="4" class="text-center">No tracks added to this
                                                                    release yet.</td>
                                                            </tr>
                                                        <?php endif; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="tab-pane card mb-0" id="dist">
                                            <div class="card-header">
                                                <i class="zmdi zmdi-store"></i> Distribution Outlets & Options
                                            </div>
                                            <div class="card-body">
                                                <div class="row">

                                                    <div class="col-12">
                                                        <label for="stores">Stores/Services</label>
                                                        <div class="row align-items-center no-gutters">
                                                            <div class="col"> <select name="stores[]"
                                                                    class="form-control" id="stores" multiple
                                                                    data-placeholder="Choose stores/services to distribute to"
                                                                    style="width:100%;">
                                                                    <?php
                                                                    $availableStores = getStore(); // Assume returns array of store objects (id, name)
                                                                    $selectedStores = $release->stores ?? []; // Assume $release->stores is an array of selected store IDs
                                                                    if ($availableStores) {
                                                                        foreach ($availableStores as $s) {
                                                                            $isSelected = in_array($s->id, $selectedStores) ? 'selected' : '';
                                                                            echo '<option value="' . $s->id . '" ' . $isSelected . '>' . htmlspecialchars($s->name, ENT_QUOTES, 'UTF-8') . '</option>';
                                                                        }
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-auto pl-2">
                                                                <div class="form-check"> <input class="form-check-input"
                                                                        type="checkbox" id="toggleSelectAllStores"
                                                                        title="Select/Deselect All">
                                                                    <label class="form-check-label small"
                                                                        for="toggleSelectAllStores"> All
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <hr>
                                                <h5>Additional Delivery Options</h5>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="alert alert-warning callout mt-3" role="alert"> <i
                                                                class="zmdi zmdi-info-outline"></i>
                                                            <strong>Note:</strong> Enable YouTube Content ID only if
                                                            your track is 100% original and contains no third-party
                                                            samples/loops/sounds unless properly licensed for Content ID
                                                            usage. Misuse can lead to penalties.
                                                        </div>
                                                        <div class="card card-body mb-2">
                                                            <div class="icheck-material-white">
                                                                <input type="checkbox" id="ytcid" name="ytcid" value="1"
                                                                    <?php echo !empty($release->ytcid) ? "checked" : ""; ?> />
                                                                <label for="ytcid"> YouTube Content ID</label>
                                                            </div>
                                                        </div>
                                                        <div class="card card-body mb-2">
                                                            <div class="icheck-material-white">
                                                                <input type="checkbox" id="scloud" name="scloud"
                                                                    value="1" <?php echo !empty($release->sc) ? "checked" : ""; ?> />
                                                                <label for="scloud"
                                                                    style="overflow: hidden;white-space: initial;">
                                                                    SoundCloud Monetization & Content Protection</label>
                                                            </div>
                                                        </div>
                                                        <div class="card card-body mb-2">
                                                            <div class="icheck-material-white">
                                                                <input type="checkbox" id="soundx" name="soundx"
                                                                    value="1" <?php echo !empty($release->sx) ? "checked" : ""; ?> />
                                                                <label for="soundx"> SoundExchange Registration</label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="card card-body mb-2">
                                                            <div class="icheck-material-white">
                                                                <input type="checkbox" id="jdl" name="jdl" value="1"
                                                                    <?php echo !empty($release->jdl) ? "checked" : ""; ?> />
                                                                <label for="jdl"> Juno Download</label>
                                                            </div>
                                                        </div>
                                                        <div class="card card-body mb-2">
                                                            <div class="icheck-material-white">
                                                                <input type="checkbox" id="trl" name="trl" value="1"
                                                                    <?php echo !empty($release->trl) ? "checked" : ""; ?> />
                                                                <label for="trl"> Tracklib</label>
                                                            </div>
                                                        </div>
                                                        <div class="card card-body mb-2">
                                                            <div class="row align-items-center no-gutters">
                                                                <div class="col-auto pr-2">
                                                                    <div class="icheck-material-white">
                                                                        <input type="checkbox" id="bport"
                                                                            name="bport_enabled" value="1" <?php echo !empty($release->bp) ? "checked" : ""; ?> />
                                                                        <label for="bport"> Beatport</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col">
                                                                    <input type="text"
                                                                        class="form-control form-control-sm"
                                                                        name="bporturl"
                                                                        placeholder="Label Page URL (optional, e.g., beatport.com/label/your-label/xxxxx)"
                                                                        value="<?php echo htmlspecialchars($release->bp ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-center mt-4"> <input name="saveform" id="saveform" type="submit"
                                            class="btn btn-light btn-round px-5" value="Save Changes">
                                        <input name="distform" id="distform" type="submit"
                                            class="btn btn-warning btn-round px-5" value="Distribute Now">
                                    </div>

                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="overlay toggle-menu"></div>
            </div>
        </div>
        <div class="modal fade" id="addTrackModal" tabindex="-1" role="dialog" aria-labelledby="addTrackModalTitle"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addTrackModalTitle">
                            <i class="zmdi zmdi-playlist-audio"></i> Choose Tracks from Catalogue
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="catalogueTrackList">
                            <div class="text-center p-3">
                                <div class="loader"></div> Loading catalogue...
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary px-5" data-dismiss="modal">Close</button> <button
                            type="button" id="addSelectedTracksBtn" class="btn btn-primary px-5">Add Selected
                            Tracks</button>
                    </div>
                </div>
            </div>
        </div>
        <a href="javaScript:void();" class="back-to-top"><i class="fa fa-angle-double-up"></i> </a>
        <footer class="footer">
            <div class="container">
                <div class="text-center">
                    Copyright © <span id="cccccyear"><?php echo date("Y"); ?></span> fuchsia Media Group.
                </div>
            </div>
        </footer>
        <?php // Include color switcher if needed, or remove if not functional
        /*
        <div class="right-sidebar"> ... </div>
        */
        ?>
    </div>
    <script src="/assets/js/jquery.min.js"></script>
    <script src="/assets/js/popper.min.js"></script>
    <script src="/assets/js/bootstrap.min.js"></script>

    <script src="../assets/plugins/simplebar/js/simplebar.js"></script>
    <script src="../assets/js/sidebar-menu.js"></script>
    <script src="../assets/plugins/jquery-loading-indicator-master/dist/jquery.loading-indicator.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js"></script>

    <script src="../assets/js/app-script.js"></script>

    <script>
        $(document).ready(function () {
            // --- Datepicker Initialization ---
            var commonDatepickerOptions = {
                autoclose: true,
                todayHighlight: true,
                format: 'mm/dd/yyyy' // Ensure format matches display
            };

            $('#sandbox-container input').datepicker({
                ...commonDatepickerOptions,
                startDate: '-0d' // Allow today and future dates
            }).on('show', function (e) {
                // Sticky date logic (optional, keep if needed)
                $(this).data('stickyDate', e.date ? e.date : null);
            }).on('hide', function (e) {
                var stickyDate = $(this).data('stickyDate');
                if (!e.date && stickyDate) {
                    $(this).datepicker('setDate', stickyDate);
                    $(this).data('stickyDate', null);
                }
            });


            $('#sandbox-container2 input').datepicker({
                ...commonDatepickerOptions,
                endDate: '+0d' // Allow today and past dates
            }).on('show', function (e) {
                // Sticky date logic (optional, keep if needed)
                $(this).data('stickyDate', e.date ? e.date : null);
            }).on('hide', function (e) {
                var stickyDate = $(this).data('stickyDate');
                if (!e.date && stickyDate) {
                    $(this).datepicker('setDate', stickyDate);
                    $(this).data('stickyDate', null);
                }
            });


            // --- Select2 Initialization ---
            $('#stores').select2({
                width: "100%",
                placeholder: "Choose stores/services...",
                allowClear: true // Optional: allow clearing selection
                // language: { searching: function() { return ""; } } // Keep if you want to hide "Searching..."
            });

            // Select/Deselect All Stores
            $('#toggleSelectAllStores').change(function () {
                if (this.checked) {
                    $('#stores > option').prop("selected", "selected");
                } else {
                    $('#stores > option').prop("selected", false);
                }
                $('#stores').trigger('change'); // Trigger change for Select2 to update
            });


            // --- Artwork Upload ---
            const dropArea = document.getElementById("drop-area");
            const inputFile = document.getElementById("input-file");
            const imageView = document.getElementById("img-view");
            const textSpan = document.getElementById("texttt"); // Get the span

            if (inputFile) {
                inputFile.addEventListener("change", uploadImage);
            }
            if (dropArea) {
                dropArea.addEventListener("dragover", function (e) { e.preventDefault(); dropArea.style.borderColor = '#6DD134'; }); // Highlight on dragover
                dropArea.addEventListener("dragleave", function (e) { e.preventDefault(); dropArea.style.borderColor = '#ffffff'; }); // Remove highlight
                dropArea.addEventListener("drop", function (e) {
                    e.preventDefault();
                    dropArea.style.borderColor = '#ffffff'; // Remove highlight
                    inputFile.files = e.dataTransfer.files;
                    uploadImage();
                });
            }

            function uploadImage() {
                if (!inputFile.files || inputFile.files.length === 0) return; // No file selected

                const file = inputFile.files[0];
                if (!file.type.startsWith("image/")) { // Basic type check
                    alert("Please select an image file.");
                    return;
                }

                var reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onload = function (e) {
                    var image = new Image();
                    image.src = e.target.result;
                    image.onload = function () {
                        var height = this.height;
                        var width = this.width;
                        // Stricter check for square and size
                        if (height < 1500 || width < 1500 || Math.abs(height - width) > 2) { // Allow tiny pixel difference
                            alert("Artwork must be square and at least 1500x1500 pixels. Your image is " + width + "x" + height + ".");
                            inputFile.value = ""; // Clear the input
                            // Optionally reset preview:
                            // imageView.style.backgroundImage = '';
                            // if (textSpan) textSpan.style.display = 'block';
                            return;
                        }

                        // Use object URL for preview - more efficient
                        let imgLink = URL.createObjectURL(file);
                        imageView.style.backgroundImage = `url(${imgLink})`;
                        // Hide the placeholder text/icon if it exists
                        if (textSpan) textSpan.style.display = 'none';
                        // If you previously added an <img> icon, remove it
                        const existingIcon = imageView.querySelector('i, img');
                        if (existingIcon) existingIcon.style.display = 'none';

                        // Revoke object URL when not needed anymore (e.g., on modal close, or new upload)
                        // For simplicity, we might revoke later or rely on browser cleanup
                        // imageView.dataset.objectUrl = imgLink; // Store it if needed for revocation
                    };
                    image.onerror = function () {
                        alert("Could not load image metadata.");
                        inputFile.value = "";
                    };
                };
                reader.onerror = function () {
                    alert("Could not read file.");
                    inputFile.value = "";
                };
            }

            // --- Maxlength enforcement (Optional but good UX) ---
            document.querySelectorAll('[maxlength]').forEach(input => {
                input.addEventListener('input', e => {
                    let val = e.target.value;
                    let len = +e.target.getAttribute('maxlength');
                    if (val.length > len) {
                        e.target.value = val.slice(0, len);
                    }
                });
            });

            // --- Delete Track AJAX ---
            $('.delete-track').on('click', function (e) {
                e.preventDefault();
                var trackId = $(this).data('track-id');
                var albumId = $(this).data('album-id');
                var row = $('#track' + trackId);

                if (confirm('Are you sure you want to remove track ID ' + trackId + ' from this release?')) {
                    // Optional: Add loading indicator
                    $(this).html('Deleting...'); // Simple feedback

                    fetch('delete.php?albumid=' + albumId + "&trackid=" + trackId, {
                        method: 'GET', // Or POST if your delete script expects POST
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest' // Identify as AJAX
                        }
                        //credentials: 'same-origin' // Default for fetch
                    })
                        .then(response => {
                            if (!response.ok) { throw new Error('Network response was not ok: ' + response.statusText); }
                            return response.json();
                        })
                        .then(data => {
                            console.log(data); // Log response
                            if (data.status === 'success' || data.success) { // Check response format
                                row.fadeOut(300, function () { $(this).remove(); });
                                // Optionally show a success message
                                // alert('Track removed successfully.');
                            } else {
                                alert('Error removing track: ' + (data.message || 'Unknown error'));
                                $(this).html('Delete'); // Reset button text
                            }
                        })
                        .catch(error => {
                            console.error('Error deleting track:', error);
                            alert('An error occurred while trying to remove the track. Please check the console or try again.');
                            $(this).html('Delete'); // Reset button text
                        });
                }
            });

            // --- Load Tracks into Modal ---
            $('#addTrackModal').on('show.bs.modal', function () {
                var modalBody = $('#catalogueTrackList');
                modalBody.html('<div class="text-center p-3"><div class="loader"></div> Loading catalogue...</div>'); // Show loader

                // Get IDs of tracks already in the release (from the main table)
                let existingTrackIds = new Set();
                // Select rows within the specific table body in the #tracks tab
                $('#tracks .table tbody tr[id^="track"]').each(function () {
                    // Extract ID more safely
                    let id = this.id.replace('track', '');
                    if (id && /^\d+$/.test(id)) { // Check if it's a number after removing 'track'
                        existingTrackIds.add(id); // Add the track ID (as a string) to the Set
                    }
                });
                // console.log("Existing Track IDs:", existingTrackIds); // For debugging

                fetch('get_catalogue_tracks.php') // Fetch directly from your PHP script
                    .then(response => {
                        if (!response.ok) {
                            // Try to get error text if possible for better debugging
                            return response.text().then(text => {
                                throw new Error(`Network response was not ok: ${response.status} ${response.statusText}. Server response: ${text}`);
                            });
                        }
                        // Expecting a direct JSON array like [{"id":"0", ...}, {"id":"1", ...}]
                        return response.json();
                    })
                    .then(trackList => { // trackList is the JSON array
                        let trackHtml = '<form id="addTracksForm">'; // Start form inside modal body
                        if (Array.isArray(trackList) && trackList.length > 0) {
                            trackHtml += '<ul class="list-group list-group-flush">'; // Use list group for better styling
                            trackList.forEach(track => {
                                // Check if track is already in the current release using the Set
                                // Ensure comparison is safe (both as strings)
                                let isAlreadyAdded = existingTrackIds.has(String(track.id));

                                // Escape track details for safety before inserting into HTML
                                // Using jQuery's text() method on a dummy element is a robust way
                                const trackName = track.name ? $('<div>').text(track.name).html() : 'Untitled Track';
                                const artistName = track.artistname ? $('<div>').text(track.artistname).html() : 'Unknown Artist';
                                const trackId = String(track.id); // Ensure trackId is a string

                                trackHtml += `<li class="list-group-item bg-transparent" style="border-color: rgba(255, 255, 255, 0.1);"> <div class="icheck-material-white">
                                <input type="checkbox" name="trackids[]" value="${trackId}" id="modalTrack_${trackId}" ${isAlreadyAdded ? 'disabled' : ''}>
                                <label for="modalTrack_${trackId}" style="cursor: ${isAlreadyAdded ? 'not-allowed' : 'pointer'};"> ${trackId} - ${trackName} (${artistName}) ${isAlreadyAdded ? '<small class="text-muted font-italic">(Already in release)</small>' : ''}
                                </label>
                            </div>
                          </li>`;
                            });
                            trackHtml += '</ul>';
                        } else if (Array.isArray(trackList) && trackList.length === 0) {
                            trackHtml = '<p class="text-center my-3">No tracks found in your catalogue.</p>'; // Clearer message
                        } else {
                            // Handle cases where the response wasn't a valid array
                            console.error("Received non-array or unexpected response from get_catalogue_tracks.php:", trackList);
                            trackHtml = '<p class="text-center text-danger my-3">Error: Unexpected data format received from server.</p>';
                        }
                        trackHtml += '</form>'; // End form
                        modalBody.html(trackHtml); // Replace loader with the generated list
                    })
                    .catch(error => {
                        console.error('Error loading catalogue tracks:', error);
                        modalBody.html(`<p class="text-center text-danger my-3">Failed to load tracks. ${error.message || ''}</p>`); // Display error in modal
                    });
            });

            // --- Add Selected Tracks Button ---
            // This handler should work as previously defined, provided add_tracks_to_album.php
            // exists and correctly processes the POSTed JSON data.
            $('#addSelectedTracksBtn').on('click', function () {
                var selectedTracks = [];
                // Find checkboxes within the specific form in the modal
                $('#addTrackModal #addTracksForm input[name="trackids[]"]:checked').each(function () {
                    selectedTracks.push($(this).val());
                });

                if (selectedTracks.length === 0) {
                    alert('Please select at least one track to add.');
                    return;
                }

                var albumId = $('input[name="albumid"]').val(); // Get album ID from the hidden input in the main form
                var button = $(this);
                button.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Adding...');

                fetch('add_tracks_to_album.php', { // MAKE SURE THIS SCRIPT EXISTS AND WORKS
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json', // We are sending JSON
                        'X-Requested-With': 'XMLHttpRequest' // Standard header for AJAX
                    },
                    body: JSON.stringify({ // Convert JS object to JSON string
                        albumid: albumId,
                        trackids: selectedTracks
                    })
                })
                    .then(response => {
                        // Check if the response is OK (status 200-299)
                        if (!response.ok) {
                            // If not OK, try to parse as JSON for an error message, otherwise throw generic error
                            return response.json().catch(() => {
                                throw new Error(`Server responded with status ${response.status}`);
                            }).then(errData => {
                                throw new Error(errData.message || `Server responded with status ${response.status}`);
                            });
                        }
                        // Assuming your PHP script returns JSON like {"success": true/false, "message": "..."}
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) { // Check the success flag from your JSON response
                            alert('Tracks added successfully! The page will now reload to reflect the changes.');
                            window.location.reload(); // Reload the page
                        } else {
                            alert('Error adding tracks: ' + (data.message || 'Unknown server error. Please check the console.'));
                            console.error("Server error response:", data); // Log detailed error from server
                            button.prop('disabled', false).html('Add Selected Tracks'); // Re-enable button
                        }
                    })
                    .catch(error => {
                        console.error('Fetch Error adding tracks:', error);
                        alert('An error occurred while adding tracks: ' + error.message + '. Please check the console or try again.');
                        button.prop('disabled', false).html('Add Selected Tracks'); // Re-enable button
                    });

            });

            // ... (rest of your $(document).ready code like datepicker, select2, artwork upload etc.)
            // --- Add Selected Tracks Button ---
            $('#addSelectedTracksBtn').on('click', function () {
                var selectedTracks = [];
                $('#addTracksForm input[name="trackids[]"]:checked').each(function () {
                    selectedTracks.push($(this).val());
                });

                if (selectedTracks.length === 0) {
                    alert('Please select at least one track to add.');
                    return;
                }

                var albumId = $('input[name="albumid"]').val();
                var button = $(this);
                button.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Adding...');

                fetch('add_tracks_to_album.php', { // Replace with your endpoint
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        albumid: albumId,
                        trackids: selectedTracks
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Tracks added successfully! Reloading...');
                            window.location.reload(); // Reload the page to show changes
                        } else {
                            alert('Error adding tracks: ' + (data.message || 'Unknown error'));
                            button.prop('disabled', false).html('Add Selected Tracks');
                        }
                    })
                    .catch(error => {
                        console.error('Error adding tracks:', error);
                        alert('An error occurred while adding tracks.');
                        button.prop('disabled', false).html('Add Selected Tracks');
                    });

            });


            // --- Form Submission Logic (Example for Save) ---
            $('#formdepchai').on('submit', function (e) {
                // Add validation here if needed before submission
                console.log('Form submitted');
                // Show loading indicator maybe
                $('body').loadingIndicator(); // Example using the loading indicator plugin
            });

            // --- Footer Year ---
            // Already handled by PHP echo, no JS needed unless you prefer it
            // document.getElementById("cccccyear").innerHTML = new Date().getFullYear();

        }); // End $(document).ready
    </script>

</body>

</html>