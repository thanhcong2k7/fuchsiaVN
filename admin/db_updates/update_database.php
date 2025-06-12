<?php
session_start();
require '../assets/variables/sql.php';

// Check if user is logged in and is an admin
if (!isset($_SESSION["userwtf"])) {
    header("Location: ../login.php");
    exit;
}

$user = getUser($_SESSION["userwtf"]);
if (!$user || $user->type !== 0) {
    unset($_SESSION["userwtf"]);
    header("Location: ../login.php");
    exit;
}

$message = '';
$messageType = '';

// Check if rejection_reason column exists
$columnExists = false;
$checkColumnQuery = "SHOW COLUMNS FROM album LIKE 'rejection_reason'";
$columnResult = $GLOBALS["conn"]->query($checkColumnQuery);
if ($columnResult && $columnResult->num_rows > 0) {
    $columnExists = true;
    $message = "The rejection_reason column already exists in the database.";
    $messageType = "info";
}

// Process the update if requested
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    try {
        if ($columnExists) {
            $message = "The rejection_reason column already exists. No update needed.";
            $messageType = "info";
        } else {
            // Add the rejection_reason column
            $sql = "ALTER TABLE album ADD COLUMN rejection_reason TEXT NULL COMMENT 'Reason for rejection when status is 2'";
            $result = $GLOBALS["conn"]->query($sql);
            
            if ($result === false) {
                throw new Exception("Error adding column: " . $GLOBALS["conn"]->error);
            }
            
            // Check if the column was added successfully
            $checkColumnQuery = "SHOW COLUMNS FROM album LIKE 'rejection_reason'";
            $columnResult = $GLOBALS["conn"]->query($checkColumnQuery);
            if ($columnResult && $columnResult->num_rows > 0) {
                $message = "Database updated successfully! The rejection_reason column has been added.";
                $messageType = "success";
            } else {
                throw new Exception("Column was not added successfully.");
            }
        }
    } catch (Exception $e) {
        $message = "Error updating database: " . $e->getMessage();
        $messageType = "danger";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Update Database - Fuchsia Admin</title>
    <link href="../css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>
<body class="sb-nav-fixed">
    <?php include '../_header.php'; ?>
    <div id="layoutSidenav">
        <?php include '../_sidebar.php'; ?>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Update Database</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="../index.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Update Database</li>
                    </ol>
                    
                    <?php if (!empty($message)): ?>
                    <div class="alert alert-<?= $messageType ?>" role="alert">
                        <?= htmlspecialchars($message) ?>
                    </div>
                    <?php endif; ?>
                    
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-database me-1"></i>
                            Database Updates
                        </div>
                        <div class="card-body">
                            <p>This page allows you to update the database schema to support new features.</p>
                            <p>The following updates will be applied:</p>
                            <ul>
                                <li>Add <code>rejection_reason</code> column to the <code>album</code> table for storing rejection reasons</li>
                            </ul>
                            
                            <form method="post" action="">
                                <div class="mt-4">
                                    <button type="submit" name="update" class="btn btn-primary">
                                        <i class="fas fa-sync"></i> Update Database
                                    </button>
                                    <a href="../index.php" class="btn btn-secondary">Cancel</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </main>
            <?php include '../_footer.php'; ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="../js/scripts.js"></script>
</body>
</html>