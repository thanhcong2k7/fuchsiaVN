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

// Get release ID from query string if provided
$release_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$release = null;

// Fetch all releases for dropdown
$allReleases = getAllReleases();

// If a specific release is selected, fetch its details
if ($release_id) {
    foreach ($allReleases as $r) {
        if ($r->id == $release_id) {
            $release = $r;
            break;
        }
    }
}

// Sample data for demonstration - in a real implementation, this would come from a database
$months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
$streamData = [100, 150, 200, 250, 300, 350, 400, 450, 500, 550, 600, 650];
$revenueData = [10, 15, 20, 25, 30, 35, 40, 45, 50, 55, 60, 65];

// Calculate totals
$totalStreams = array_sum($streamData);
$totalRevenue = array_sum($revenueData);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Analytics - Fuchsia Admin</title>
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="sb-nav-fixed">
    <?php include '_header.php'; ?>
    <div id="layoutSidenav">
        <?php include '_sidebar.php'; ?>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Analytics Dashboard</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Analytics</li>
                    </ol>
                    
                    <!-- Release Selector -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-filter me-1"></i>
                            Select Release
                        </div>
                        <div class="card-body">
                            <form method="get" action="" class="row g-3">
                                <div class="col-md-6">
                                    <select class="form-select" name="id" id="releaseSelect">
                                        <option value="">All Releases</option>
                                        <?php foreach ($allReleases as $r): ?>
                                            <option value="<?= $r->id ?>" <?= ($release_id == $r->id) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($r->name) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary">Apply Filter</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Summary Cards -->
                    <div class="row">
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-primary text-white mb-4">
                                <div class="card-body">
                                    Total Streams
                                    <span class="fs-3 d-block"><?= number_format($totalStreams) ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-success text-white mb-4">
                                <div class="card-body">
                                    Total Revenue
                                    <span class="fs-3 d-block">$<?= number_format($totalRevenue, 2) ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-warning text-white mb-4">
                                <div class="card-body">
                                    Average Revenue per Stream
                                    <span class="fs-3 d-block">$<?= number_format($totalStreams > 0 ? $totalRevenue / $totalStreams : 0, 4) ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-info text-white mb-4">
                                <div class="card-body">
                                    Projected Monthly Revenue
                                    <span class="fs-3 d-block">$<?= number_format($totalRevenue > 0 ? $totalRevenue / count($months) * 12 : 0, 2) ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Charts -->
                    <div class="row">
                        <div class="col-xl-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <i class="fas fa-chart-area me-1"></i>
                                    Monthly Streams
                                </div>
                                <div class="card-body">
                                    <canvas id="streamChart" width="100%" height="40"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <i class="fas fa-chart-bar me-1"></i>
                                    Monthly Revenue
                                </div>
                                <div class="card-body">
                                    <canvas id="revenueChart" width="100%" height="40"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Data Table -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Monthly Analytics Data
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Month</th>
                                        <th>Streams</th>
                                        <th>Revenue</th>
                                        <th>Revenue per Stream</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php for ($i = 0; $i < count($months); $i++): ?>
                                        <tr>
                                            <td><?= $months[$i] ?></td>
                                            <td><?= number_format($streamData[$i]) ?></td>
                                            <td>$<?= number_format($revenueData[$i], 2) ?></td>
                                            <td>$<?= number_format($streamData[$i] > 0 ? $revenueData[$i] / $streamData[$i] : 0, 4) ?></td>
                                        </tr>
                                    <?php endfor; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Total</th>
                                        <th><?= number_format($totalStreams) ?></th>
                                        <th>$<?= number_format($totalRevenue, 2) ?></th>
                                        <th>$<?= number_format($totalStreams > 0 ? $totalRevenue / $totalStreams : 0, 4) ?></th>
                                    </tr>
                                </tfoot>
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
    <script>
        // Stream Chart
        var streamCtx = document.getElementById('streamChart').getContext('2d');
        var streamChart = new Chart(streamCtx, {
            type: 'line',
            data: {
                labels: <?= json_encode($months) ?>,
                datasets: [{
                    label: 'Streams',
                    data: <?= json_encode($streamData) ?>,
                    backgroundColor: 'rgba(0, 123, 255, 0.2)',
                    borderColor: 'rgba(0, 123, 255, 1)',
                    borderWidth: 2,
                    tension: 0.1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
        
        // Revenue Chart
        var revenueCtx = document.getElementById('revenueChart').getContext('2d');
        var revenueChart = new Chart(revenueCtx, {
            type: 'bar',
            data: {
                labels: <?= json_encode($months) ?>,
                datasets: [{
                    label: 'Revenue ($)',
                    data: <?= json_encode($revenueData) ?>,
                    backgroundColor: 'rgba(40, 167, 69, 0.2)',
                    borderColor: 'rgba(40, 167, 69, 1)',
                    borderWidth: 2
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>