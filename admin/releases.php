<?php
session_start();
require 'assets/variables/sql.php';

if (!isset($_SESSION["userwtf"])) {
    error_log("Admin area: Session 'userwtf' not set. Redirecting to login.");
    header("Location: login.php");
    exit;
}

$loggedInUser = getUser($_SESSION["userwtf"]);

if ($loggedInUser === null) {
    error_log("Admin area: User ID " . $_SESSION["userwtf"] . " not found or DB error. Clearing session and redirecting to login.");
    unset($_SESSION["userwtf"]); // Clear potentially invalid session ID
    header("Location: login.php");
    exit;
}

error_log("Admin area: Checking user type. User ID: " . $loggedInUser->id . ", Type from DB: '" . $loggedInUser->type . "', PHP type: " . gettype($loggedInUser->type));
if ($loggedInUser->type !== 0) { // Compare integer with integer
    error_log("Admin area: User ID " . $_SESSION["userwtf"] . " (type: " . $loggedInUser->type . ") is not an admin. Redirecting to index.");
    header("Location: index.php"); // Redirect non-admins to the main dashboard or an error page
    exit;
}
// If we reach here, $loggedInUser is a valid admin user object.
// For clarity, let's assign it to $user if other parts of the page expect that variable name.
$user = $loggedInUser;

// Fetch all releases
$allReleases = getAllReleases(); // Using the new function

function getStatusText($statusCode) {
    switch ($statusCode) {
        case '0': return 'Draft';
        case '1': return 'Accepted';
        case '2': return 'Error';
        case '3': return 'Checking';
        default: return 'Unknown (' . htmlspecialchars($statusCode) . ')';
    }
}

function getStatusBadgeClass($statusCode) {
    switch ($statusCode) {
        case '0': return 'secondary'; // Draft
        case '1': return 'success';   // Accepted
        case '2': return 'danger';    // Error
        case '3': return 'warning';   // Checking
        default: return 'dark';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Release Management - Fuchsia Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
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
                    <h1 class="mt-4">Release Management</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Release Management</li>
                    </ol>
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-compact-disc me-1"></i>
                            All Releases
                        </div>
                        <div class="card-body">
                            <table id="releasesTable" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>UPC</th>
                                        <th>Album Name</th>
                                        <th>Artist(s)</th>
                                        <th>Status</th>
                                        <th>Submitted By</th>
                                        <th>Created Date</th>
                                        <th>Release Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($allReleases)): ?>
                                        <tr>
                                            <td colspan="9" class="text-center">No releases found.</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($allReleases as $release): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($release->id) ?></td>
                                                <td><?= htmlspecialchars($release->upc) ?></td>
                                                <td><?= htmlspecialchars($release->name) ?></td>
                                                <td><?= htmlspecialchars($release->submitterName ?? 'N/A') ?></td> <!-- Using submitterName for now -->
                                                <td>
                                                    <span class="badge bg-<?= getStatusBadgeClass($release->status) ?>">
                                                        <?= getStatusText($release->status) ?>
                                                    </span>
                                                </td>
                                                <td><?= htmlspecialchars($release->submitterName ?? 'N/A') ?></td>
                                                <td><?= htmlspecialchars(date("Y-m-d H:i", strtotime($release->createdDate))) ?></td>
                                                <td><?= htmlspecialchars($release->relDate ? date("Y-m-d", strtotime($release->relDate)) : 'ASAP') ?></td>
                                                <td>
                                                    <a href="view_release.php?id=<?= htmlspecialchars($release->id) ?>" class="btn btn-sm btn-info" title="View Details"><i class="fas fa-eye"></i></a>
                                                    <?php if ($release->status == '3'): // Status '3' is 'Checking' ?>
                                                        <a href="approve_release.php?id=<?= htmlspecialchars($release->id) ?>" class="btn btn-sm btn-success" title="Approve"><i class="fas fa-check"></i></a>
                                                        <a href="reject_release.php?id=<?= htmlspecialchars($release->id) ?>" class="btn btn-sm btn-danger" title="Reject"><i class="fas fa-times"></i></a>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
            <?php include '_footer.php'; ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
    <script>
        window.addEventListener('DOMContentLoaded', event => {
            const releasesTable = document.getElementById('releasesTable');
            if (releasesTable) {
                new simpleDatatables.DataTable(releasesTable);
            }
        });
    </script>
</body>
</html>