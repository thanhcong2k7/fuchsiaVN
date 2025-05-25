<?php
session_start();
require 'assets/variables/sql.php'; // Contains getUser(), getRelease(), getTrack(), getStatusText(), getStatusBadgeClass()

if (!isset($_SESSION["userwtf"])) {
    error_log("Admin area (view_release): Session 'userwtf' not set. Redirecting to login.");
    header("Location: login.php");
    exit;
}

$loggedInUser = getUser($_SESSION["userwtf"]);

if ($loggedInUser === null) {
    error_log("Admin area (view_release): User ID " . $_SESSION["userwtf"] . " not found or DB error. Clearing session and redirecting to login.");
    unset($_SESSION["userwtf"]);
    header("Location: login.php");
    exit;
}

error_log("Admin area (view_release): Checking user type. User ID: " . $loggedInUser->id . ", Type from DB: '" . $loggedInUser->type . "', PHP type: " . gettype($loggedInUser->type));
if ($loggedInUser->type !== 0) { // Admin type is 0 (integer)
    error_log("Admin area (view_release): User ID " . $_SESSION["userwtf"] . " (type: " . $loggedInUser->type . ") is not an admin. Redirecting to index.");
    header("Location: index.php");
    exit;
}
$user = $loggedInUser; // Use $user consistent with other pages

$release_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$release = null;
$tracks = [];

if ($release_id) {
    // Use getRelease function, assuming it can fetch a single release by ID
    // We might need to adapt getRelease or use a specific part of getAllReleases logic
    // For now, let's assume getRelease($userID, $num=0, $id) can be used by passing null for userID if admin
    // Or better, adapt getAllReleases to filter by ID if an ID is provided.
    // Let's use a simplified approach for now, assuming getRelease can work with just an ID for admin
    
    // A better approach would be a dedicated function like getReleaseById($release_id) in sql.php
    // For now, let's try to use the existing getRelease by passing the admin's ID and the release ID.
    // This might not be ideal if getRelease is strictly for a user's own releases.
    // $release = getRelease($user->id, 0, $release_id);

    // Let's assume we add a getReleaseById function or modify getAllReleases
    // For now, to make it work, let's fetch all and filter. This is inefficient but for demonstration.
    // Direct database query with explicit error handling and debugging
    try {
        // First, let's dump the table structure to see what fields are available
        $tableInfoQuery = "SHOW COLUMNS FROM album";
        $tableInfoResult = $GLOBALS["conn"]->query($tableInfoQuery);
        $tableColumns = [];
        if ($tableInfoResult) {
            while ($column = $tableInfoResult->fetch_assoc()) {
                $tableColumns[] = $column['Field'];
            }
        }
        
        // Now query the release with all fields
        $sql = "SELECT album.*, user.labelName AS submitterName
                FROM album
                JOIN user ON album.userID = user.userID
                WHERE album.albumID = ?";
        
        $stmt = $GLOBALS["conn"]->prepare($sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $GLOBALS["conn"]->error);
        }
        
        $stmt->bind_param("i", $release_id);
        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }
        
        $result = $stmt->get_result();
        if (!$result) {
            throw new Exception("Get result failed: " . $stmt->error);
        }
        
        if ($row = $result->fetch_assoc()) {
            // Create a new albumType object and populate it with all fields
            $release = new albumType();
            $release->id = $row["albumID"];
            $release->upc = $row["UPCNum"];
            $release->name = $row["albumName"];
            $release->status = $row["status"];
            $release->art = $row["artID"];
            $release->store = json_decode($row["storeID"]);
            $release->c = $row["compLine"];
            $release->p = $row["publishLine"];
            $release->orgReldate = $row["orgReldate"];
            $release->createdDate = $row["createdDate"];
            $release->relDate = $row["relDate"];
            $release->version = $row["versionLine"];
            $release->ytcid = $row["ytcid"];
            $release->jdl = $row["juno"];
            $release->sx = $row["soundx"];
            $release->sc = $row["scloud"];
            $release->bp = $row["beatport"];
            $release->artp = $row["artPrev"];
            $release->submitterName = $row["submitterName"];
            $release->staffID = isset($row["staffID"]) ? $row["staffID"] : null;
            
            // Store raw data for debugging
            $rawData = $row;
        } else {
            throw new Exception("No release found with ID: " . $release_id);
        }
        $stmt->close();
    } catch (Exception $e) {
        // Log the error
        error_log("Error in view_release.php: " . $e->getMessage());
        
        // Fallback to the original method
        $allReleases = getAllReleases();
        foreach ($allReleases as $r) {
            if ($r->id == $release_id) {
                $release = $r;
                break;
            }
        }
        
        // Store error for debugging
        $error = $e->getMessage();
    }

    if ($release) {
        $tracks = getTrackListOfAlbum($release_id); // Existing function
    }
}

if (!$release) {
    // Optionally, redirect to releases list with an error message
    // For now, just show an error on this page.
    // header("Location: releases.php?error=notfound");
    // exit;
}

// Helper function to get store names from IDs
function getStoreNames($storeIds, $allStores) {
    if (empty($storeIds) || empty($allStores)) return 'N/A';
    $names = [];
    foreach ($storeIds as $id) {
        foreach ($allStores as $store) {
            if ($store->id == $id) {
                $names[] = htmlspecialchars($store->name);
                break;
            }
        }
    }
    return !empty($names) ? implode(', ', $names) : 'N/A';
}
$availableStores = getStore(); // Fetch all available stores

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>View Release - Fuchsia Admin</title>
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <style>
        .release-art-preview { max-width: 200px; max-height: 200px; margin-bottom: 15px; }
        .detail-label { font-weight: bold; }
    </style>
</head>
<body class="sb-nav-fixed">
    <?php include '_header.php'; ?>
    <div id="layoutSidenav">
        <?php include '_sidebar.php'; ?>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">View Release Details</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="releases.php">Release Management</a></li>
                        <li class="breadcrumb-item active">View Release</li>
                    </ol>

                    <!-- Debug information (only visible to admins) -->
                    <div class="card mb-4">
                        <div class="card-header bg-warning">
                            <i class="fas fa-bug me-1"></i>
                            Debug Information
                        </div>
                        <div class="card-body">
                            <h5>Table Columns:</h5>
                            <pre><?php echo isset($tableColumns) ? print_r($tableColumns, true) : 'Not available'; ?></pre>
                            
                            <h5>Raw Release Data:</h5>
                            <pre><?php echo isset($rawData) ? print_r($rawData, true) : 'Not available'; ?></pre>
                            
                            <h5>Release Object:</h5>
                            <pre><?php echo isset($release) ? print_r($release, true) : 'Not available'; ?></pre>
                            
                            <?php if (isset($error)): ?>
                                <h5 class="text-danger">Error:</h5>
                                <pre class="text-danger"><?php echo $error; ?></pre>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php if ($release): ?>
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-compact-disc me-1"></i>
                            Release: <?= htmlspecialchars($release->name) ?>
                            <span class="badge bg-<?= getStatusBadgeClass($release->status) ?> ms-2">
                                <?= getStatusText($release->status) ?>
                            </span>
                        </div>
                        <div class="card-body">
                            <!-- Simplified display of release details -->
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tr>
                                        <th colspan="2" class="text-center bg-light">Release Details</th>
                                    </tr>
                                    <tr>
                                        <td width="30%"><strong>Album ID:</strong></td>
                                        <td><?= htmlspecialchars($release->id) ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Album Name:</strong></td>
                                        <td><?= htmlspecialchars($release->name) ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>UPC/EAN:</strong></td>
                                        <td><?= htmlspecialchars($release->upc ?: 'N/A') ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Version/Subtitle:</strong></td>
                                        <td><?= htmlspecialchars($release->version ?: 'N/A') ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Artist(s):</strong></td>
                                        <td>
                                            <?php
                                            // Get artists from the first track as a fallback if release artists aren't available
                                            if (!empty($tracks)) {
                                                $artistNames = array();
                                                foreach ($tracks as $track) {
                                                    if (!empty($track->artistname) && !in_array($track->artistname, $artistNames)) {
                                                        $artistNames[] = $track->artistname;
                                                    }
                                                }
                                                echo !empty($artistNames) ? htmlspecialchars(implode(', ', $artistNames)) : 'N/A';
                                            } else {
                                                echo 'N/A';
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Submitted By:</strong></td>
                                        <td><?= htmlspecialchars($release->submitterName ?? 'N/A') ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Status:</strong></td>
                                        <td>
                                            <span class="badge bg-<?= getStatusBadgeClass($release->status) ?>">
                                                <?= getStatusText($release->status) ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Original Release Date:</strong></td>
                                        <td><?= htmlspecialchars($release->orgReldate ? date("Y-m-d", strtotime($release->orgReldate)) : 'N/A') ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Digital Release Date:</strong></td>
                                        <td><?= htmlspecialchars($release->relDate ? date("Y-m-d", strtotime($release->relDate)) : 'ASAP') ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Created Date:</strong></td>
                                        <td><?= htmlspecialchars(date("Y-m-d H:i", strtotime($release->createdDate))) ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>(C) Line:</strong></td>
                                        <td><?= htmlspecialchars($release->c ?: 'N/A') ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>(P) Line:</strong></td>
                                        <td><?= htmlspecialchars($release->p ?: 'N/A') ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Stores:</strong></td>
                                        <td><?= getStoreNames($release->store, $availableStores) ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Artwork:</strong></td>
                                        <td>
                                            <?php if (!empty($release->art) || !empty($release->artPrev)): ?>
                                                <img src="<?= htmlspecialchars($release->artPrev ?: $release->art) ?>" alt="Artwork" class="img-thumbnail" style="max-width: 200px;">
                                            <?php else: ?>
                                                <p>No artwork provided.</p>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <h4 class="mt-4">Tracks</h4>
                            <?php if (!empty($tracks)): ?>
                                <table class="table table-sm table-bordered">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Track Name</th>
                                            <th>Version</th>
                                            <th>Artist(s)</th>
                                            <th>ISRC</th>
                                            <th>Duration</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($tracks as $index => $track): ?>
                                        <tr>
                                            <td><?= $index + 1 ?></td>
                                            <td><?= htmlspecialchars($track->name) ?></td>
                                            <td><?= htmlspecialchars($track->version ?: '-') ?></td>
                                            <td><?= htmlspecialchars($track->artistname ?: 'N/A') ?></td>
                                            <td><?= htmlspecialchars($track->isrc ?: 'N/A') ?></td>
                                            <td><?= htmlspecialchars($track->duration ? gmdate("i:s", $track->duration) : 'N/A') ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php else: ?>
                                <p>No tracks found for this release.</p>
                            <?php endif; ?>

                            <hr>
                            <h4>Actions</h4>
                            
                            <?php if ($release->status == '3' || $release->status == 3): // Status '3' is 'Checking' (integer or string) ?>
                            <!-- Approval/Rejection actions -->
                            <a href="approve_release.php?id=<?= htmlspecialchars($release->id) ?>" class="btn btn-success"><i class="fas fa-check"></i> Approve Release</a>
                            <a href="reject_release.php?id=<?= htmlspecialchars($release->id) ?>" class="btn btn-danger"><i class="fas fa-times"></i> Reject Release</a>
                            <?php endif; ?>
                            
                            <!-- Claim Release Button -->
                            <?php
                            // Check if release is claimed
                            $isClaimed = isset($release->staffID) && $release->staffID > 0;
                            $isClaimedByCurrentUser = $isClaimed && $release->staffID == $user->id;
                            
                            if (!$isClaimed):
                            ?>
                                <button class="btn btn-primary" onclick="claimRelease(<?= htmlspecialchars($release->id) ?>)">
                                    <i class="fas fa-hand-paper"></i> Claim Release
                                </button>
                            <?php elseif ($isClaimedByCurrentUser): ?>
                                <div class="alert alert-info mt-3">
                                    <i class="fas fa-info-circle"></i> You have claimed this release.
                                </div>
                            <?php else: ?>
                                <div class="alert alert-warning mt-3">
                                    <i class="fas fa-exclamation-triangle"></i> This release has been claimed by another staff member.
                                </div>
                            <?php endif; ?>

                        </div>
                        <div class="card-footer">
                            <a href="releases.php" class="btn btn-secondary">Back to Releases</a>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="alert alert-danger" role="alert">
                        Release not found or you do not have permission to view it.
                    </div>
                    <a href="releases.php" class="btn btn-secondary">Back to Releases</a>
                    <?php endif; ?>
                </div>
            </main>
            <?php include '_footer.php'; ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    
    <script>
    function claimRelease(releaseId) {
        if (confirm('Are you sure you want to claim this release?')) {
            console.log('Attempting to claim release with ID:', releaseId);
            fetch('claim_release.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'release_id=' + releaseId
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Release claimed successfully!');
                    // Refresh the page to update the UI
                    window.location.reload();
                } else {
                    alert('Failed to claim release: ' + data.message);
                }
            })
            .catch((error) => {
                console.error('Error:', error);
                alert('An error occurred while trying to claim the release.');
            });
        }
    }
    
    // Initialize Bootstrap components
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize dropdowns
        var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'))
        var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
            return new bootstrap.Dropdown(dropdownToggleEl)
        });
        
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
        
        // Fix for anchor links with href="#" to prevent page scrolling
        document.querySelectorAll('a[href="#"]').forEach(function(anchor) {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
            });
        });
    });
    </script>
</body>
</html>