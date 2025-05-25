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
    $allReleases = getAllReleases(); // This was added earlier
    foreach ($allReleases as $r) {
        if ($r->id == $release_id) {
            $release = $r;
            break;
        }
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
                            <div class="row">
                                <div class="col-md-4">
                                    <?php if (!empty($release->art) || !empty($release->artPrev)): ?>
                                        <img src="<?= htmlspecialchars($release->artPrev ?: $release->art) ?>" alt="Artwork" class="img-thumbnail release-art-preview">
                                    <?php else: ?>
                                        <p>No artwork provided.</p>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-8">
                                    <p><span class="detail-label">Album ID:</span> <?= htmlspecialchars($release->id) ?></p>
                                    <p><span class="detail-label">UPC/EAN:</span> <?= htmlspecialchars($release->upc ?: 'N/A') ?></p>
                                    <p><span class="detail-label">Version/Subtitle:</span> <?= htmlspecialchars($release->version ?: 'N/A') ?></p>
                                    <p><span class="detail-label">Submitted By:</span> <?= htmlspecialchars($release->submitterName ?? 'N/A') ?></p>
                                    <p><span class="detail-label">Original Release Date:</span> <?= htmlspecialchars($release->orgReldate ? date("Y-m-d", strtotime($release->orgReldate)) : 'N/A') ?></p>
                                    <p><span class="detail-label">Digital Release Date:</span> <?= htmlspecialchars($release->relDate ? date("Y-m-d", strtotime($release->relDate)) : 'ASAP') ?></p>
                                    <p><span class="detail-label">Created Date:</span> <?= htmlspecialchars(date("Y-m-d H:i", strtotime($release->createdDate))) ?></p>
                                    <p><span class="detail-label">(C) Line:</span> <?= htmlspecialchars($release->c ?: 'N/A') ?></p>
                                    <p><span class="detail-label">(P) Line:</span> <?= htmlspecialchars($release->p ?: 'N/A') ?></p>
                                    <p><span class="detail-label">Stores:</span> <?= getStoreNames($release->store, $availableStores) ?></p>
                                    <!-- Add more fields as needed: ytcid, scloud, soundx, juno, tracklib, beatport -->
                                </div>
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

                            <?php if ($release->status == '3' || $release->status == 3): // Status '3' is 'Checking' (integer or string) ?>
                            <hr>
                            <h4>Actions</h4>
                            <!-- TODO: Implement approve_release.php and reject_release.php -->
                            <a href="approve_release.php?id=<?= htmlspecialchars($release->id) ?>" class="btn btn-success"><i class="fas fa-check"></i> Approve Release</a>
                            <a href="reject_release.php?id=<?= htmlspecialchars($release->id) ?>" class="btn btn-danger"><i class="fas fa-times"></i> Reject Release</a>
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
</body>
</html>