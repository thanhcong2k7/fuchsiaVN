<?php
session_start();
require 'assets/variables/sql.php';

if (!isset($_SESSION["userwtf"])) {
    header("Location: login.php");
    exit;
}

$user = getUser($_SESSION["userwtf"]);
if (!$user || $user->type !== 0) {
    unset($_SESSION["userwtf"]);
    header("Location: login.php");
    exit;
}

$release_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$release = null;
$tracks = [];
$message = '';
$messageType = '';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['approve'])) {
    $upc = filter_input(INPUT_POST, 'upc', FILTER_SANITIZE_STRING);
    
    // Update the release status to Approved (1) and set UPC
    $sql = "UPDATE album SET status = 1, UPCNum = ? WHERE albumID = ?";
    $stmt = $GLOBALS["conn"]->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("si", $upc, $release_id);
        
        if ($stmt->execute()) {
            // Update ISRCs for tracks if provided
            if (isset($_POST['isrc']) && is_array($_POST['isrc'])) {
                foreach ($_POST['isrc'] as $trackId => $isrc) {
                    if (!empty($isrc)) {
                        $updateTrack = "UPDATE track SET isrc = ? WHERE id = ?";
                        $trackStmt = $GLOBALS["conn"]->prepare($updateTrack);
                        if ($trackStmt) {
                            $trackStmt->bind_param("si", $isrc, $trackId);
                            $trackStmt->execute();
                            $trackStmt->close();
                        }
                    }
                }
            }
            
            $message = 'Release approved successfully!';
            $messageType = 'success';
            
            // Redirect back to releases page after successful approval
            header("Location: releases.php?message=" . urlencode($message) . "&type=" . urlencode($messageType));
            exit;
        } else {
            $message = 'Error updating release: ' . $stmt->error;
            $messageType = 'danger';
        }
        $stmt->close();
    } else {
        $message = 'Database error: ' . $GLOBALS["conn"]->error;
        $messageType = 'danger';
    }
}

// Fetch release details
if ($release_id) {
    $allReleases = getAllReleases();
    foreach ($allReleases as $r) {
        if ($r->id == $release_id) {
            $release = $r;
            break;
        }
    }
    
    if ($release) {
        $tracks = getTrackListOfAlbum($release_id);
    } else {
        $message = 'Release not found.';
        $messageType = 'danger';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Approve Release - Fuchsia Admin</title>
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>
<body class="sb-nav-fixed">
    <?php include '_header.php'; ?>
    <div id="layoutSidenav">
        <?php include '_sidebar.php'; ?>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Approve Release</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="releases.php">Release Management</a></li>
                        <li class="breadcrumb-item"><a href="view_release.php?id=<?= $release_id ?>">View Release</a></li>
                        <li class="breadcrumb-item active">Approve Release</li>
                    </ol>
                    
                    <?php if (!empty($message)): ?>
                    <div class="alert alert-<?= $messageType ?>" role="alert">
                        <?= htmlspecialchars($message) ?>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($release): ?>
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-check-circle me-1"></i>
                            Approve Release: <?= htmlspecialchars($release->name) ?>
                        </div>
                        <div class="card-body">
                            <form method="post" action="">
                                <div class="mb-3">
                                    <label for="upc" class="form-label">UPC/EAN Code</label>
                                    <input type="text" class="form-control" id="upc" name="upc" value="<?= htmlspecialchars($release->upc ?: '') ?>" required>
                                    <div class="form-text">Enter the Universal Product Code for this release.</div>
                                </div>
                                
                                <?php if (!empty($tracks)): ?>
                                <h4 class="mt-4">Track ISRCs</h4>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Track Name</th>
                                                <th>ISRC</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($tracks as $index => $track): ?>
                                            <tr>
                                                <td><?= $index + 1 ?></td>
                                                <td><?= htmlspecialchars($track->name) ?></td>
                                                <td>
                                                    <input type="text" class="form-control" name="isrc[<?= $track->id ?>]" value="<?= htmlspecialchars($track->isrc ?: '') ?>" placeholder="Enter ISRC">
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <?php endif; ?>
                                
                                <div class="mt-4">
                                    <button type="submit" name="approve" class="btn btn-success">
                                        <i class="fas fa-check"></i> Approve Release
                                    </button>
                                    <a href="view_release.php?id=<?= $release_id ?>" class="btn btn-secondary">Cancel</a>
                                </div>
                            </form>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="alert alert-danger" role="alert">
                        Release not found or you do not have permission to approve it.
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