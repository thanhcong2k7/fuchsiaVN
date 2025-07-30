<?php
session_start();

require 'assets/variables/sql.php';
if (!isset($_SESSION["userwtf"])) {
    header("Location: login.php");
} else {
    $user = getUser($_SESSION["userwtf"]);
    if (!$user || $user->type !== 0) { // Ensure admin check is also here for dashboard access
        // If not admin, redirect to login or a non-admin page
        unset($_SESSION["userwtf"]); // Clear session if user is invalid or not admin
        header("Location: login.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Fuchsia Admin Dashboard" />
    <meta name="author" content="Fuchsia Records" />
    <title>Release Review - Fuchsia Admin</title>
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
                    <h1 class="mt-4">Release Review</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Release Review</li>
                    </ol>

                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Releases for Review
                        </div>
                        <div class="card-body">
                            <?php
                            include 'assets/variables/var.php'; // Include the file that likely establishes the database connection
                            
                            // Get current staff user ID from session
                            $currentStaffId = isset($_SESSION['userwtf']) ? $_SESSION['userwtf'] : 0;

                            $unclaimedReleases = getReleasesForReview(null);
                            $claimedReleases = getReleasesForReview($currentStaffId);
                            ?>

                            <h2>Unclaimed Releases</h2>
                            <table id="unclaimedReleasesTable" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Album Name</th>
                                        <th>Submitter</th>
                                        <th>Status</th>
                                        <th>Created Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($unclaimedReleases)): ?>
                                        <tr>
                                            <td colspan="6">No unclaimed releases found.</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($unclaimedReleases as $release): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($release->id); ?></td>
                                                <td><?php echo htmlspecialchars($release->name); ?></td>
                                                <td><?php echo htmlspecialchars($release->submitterName); ?></td>
                                                <td><?php echo htmlspecialchars($release->status); ?></td>
                                                <td><?php echo htmlspecialchars($release->createdDate); ?></td>
                                                <td>
                                                    <button class="btn btn-primary btn-sm"
                                                        onclick="claimRelease(<?php echo $release->id; ?>)">Claim</button>
                                                    <a href="view_release.php?id=<?php echo $release->id; ?>"
                                                        class="btn btn-info btn-sm">View</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>

                            <h2>My Claimed Releases</h2>
                            <table id="claimedReleasesTable" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Album Name</th>
                                        <th>Submitter</th>
                                        <th>Status</th>
                                        <th>Created Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($claimedReleases)): ?>
                                        <tr>
                                            <td colspan="6">No claimed releases found.</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($claimedReleases as $release): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($release->id); ?></td>
                                                <td><?php echo htmlspecialchars($release->name); ?></td>
                                                <td><?php echo htmlspecialchars($release->submitterName); ?></td>
                                                <td><?php echo htmlspecialchars($release->status); ?></td>
                                                <td><?php echo htmlspecialchars($release->createdDate); ?></td>
                                                <td>
                                                    <a href="view_release.php?id=<?php echo $release->id; ?>"
                                                        class="btn btn-info btn-sm">View</a>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
        crossorigin="anonymous"></script>
    <script src="js/datatables-simple-demo.js"></script>
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
                            // Refresh the page to update the tables
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
    </script>
</body>

</html>