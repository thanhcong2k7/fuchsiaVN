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
$message = '';
$messageType = '';

// Check if rejection_reason column exists
$columnExists = false;
$checkColumnQuery = "SHOW COLUMNS FROM album LIKE 'rejection_reason'";
$columnResult = $GLOBALS["conn"]->query($checkColumnQuery);
if ($columnResult && $columnResult->num_rows > 0) {
    $columnExists = true;
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reject'])) {
    $reason = filter_input(INPUT_POST, 'reason', FILTER_SANITIZE_STRING);
    
    if (empty($reason)) {
        $message = 'Please provide a reason for rejection.';
        $messageType = 'danger';
    } else {
        // Update the release status to Error (2) and store the rejection reason if column exists
        if ($columnExists) {
            $sql = "UPDATE album SET status = 2, rejection_reason = ? WHERE albumID = ?";
            $stmt = $GLOBALS["conn"]->prepare($sql);
            $stmt->bind_param("si", $reason, $release_id);
        } else {
            // If column doesn't exist, just update the status
            $sql = "UPDATE album SET status = 2 WHERE albumID = ?";
            $stmt = $GLOBALS["conn"]->prepare($sql);
            $stmt->bind_param("i", $release_id);
            
            // Store a warning message
            $message = 'Release rejected, but rejection reason could not be stored. Please run the database update.';
            $messageType = 'warning';
        }
        
        if ($stmt) {
            if ($stmt->execute()) {
                if (empty($message)) {
                    $message = 'Release rejected successfully!';
                    $messageType = 'success';
                }
                
                // Redirect back to releases page after successful rejection
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
    
    if (!$release) {
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
    <title>Reject Release - Fuchsia Admin</title>
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
                    <h1 class="mt-4">Reject Release</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="releases.php">Release Management</a></li>
                        <li class="breadcrumb-item"><a href="view_release.php?id=<?= $release_id ?>">View Release</a></li>
                        <li class="breadcrumb-item active">Reject Release</li>
                    </ol>
                    
                    <?php if (!empty($message)): ?>
                    <div class="alert alert-<?= $messageType ?>" role="alert">
                        <?= htmlspecialchars($message) ?>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!$columnExists): ?>
                    <div class="alert alert-warning" role="alert">
                        <strong>Database Update Required:</strong> The rejection_reason column does not exist in the database.
                        Rejections will work, but reasons won't be stored until you
                        <a href="db_updates/update_database.php" class="alert-link">update the database</a>.
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($release): ?>
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-times-circle me-1"></i>
                            Reject Release: <?= htmlspecialchars($release->name) ?>
                        </div>
                        <div class="card-body">
                            <form method="post" action="">
                                <div class="mb-3">
                                    <label for="reason" class="form-label">Rejection Reason</label>
                                    <textarea class="form-control" id="reason" name="reason" rows="5" required></textarea>
                                    <div class="form-text">
                                        Please provide a detailed reason for rejecting this release.
                                        <?php if (!$columnExists): ?>
                                        <strong>Note:</strong> The reason won't be stored until you update the database.
                                        <?php else: ?>
                                        This information will be visible to the submitter.
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <div class="mt-4">
                                    <button type="submit" name="reject" class="btn btn-danger">
                                        <i class="fas fa-times"></i> Reject Release
                                    </button>
                                    <a href="view_release.php?id=<?= $release_id ?>" class="btn btn-secondary">Cancel</a>
                                </div>
                            </form>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="alert alert-danger" role="alert">
                        Release not found or you do not have permission to reject it.
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