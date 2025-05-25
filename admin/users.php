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

// Fetch all users
$allUsers = getAllUsers(); // Using the new function
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>User Management - Fuchsia Admin</title>
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
                    <h1 class="mt-4">User Management</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">User Management</li>
                    </ol>
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-users me-1"></i>
                            All Users
                        </div>
                        <div class="card-body">
                            <table id="usersTable" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Username</th>
                                        <th>Display Name</th>
                                        <th>Email</th>
                                        <th>Type</th>
                                        <th>Registered</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($allUsers)): ?>
                                        <tr>
                                            <td colspan="7" class="text-center">No users found.</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($allUsers as $managedUser): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($managedUser->id) ?></td>
                                                <td><?= htmlspecialchars($managedUser->handle) ?></td>
                                                <td><?= htmlspecialchars($managedUser->display) ?></td>
                                                <td><?= htmlspecialchars($managedUser->email) ?></td>
                                                <td><?= $managedUser->type == '0' ? '<span class="badge bg-danger">Admin</span>' : '<span class="badge bg-info">User</span>' ?></td>
                                                <td><?= htmlspecialchars(date("Y-m-d", strtotime($managedUser->register))) ?></td>
                                                <td>
                                                    <a href="edit_user.php?id=<?= htmlspecialchars($managedUser->id) ?>" class="btn btn-sm btn-primary" title="Edit User"><i class="fas fa-edit"></i></a>
                                                    <?php if ($managedUser->id != $_SESSION["userwtf"]): // Prevent admin from deleting themselves ?>
                                                        <a href="delete_user.php?id=<?= htmlspecialchars($managedUser->id) ?>" class="btn btn-sm btn-danger" title="Delete User" onclick="return confirm('Are you sure you want to delete this user?');"><i class="fas fa-trash"></i></a>
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
            const usersTable = document.getElementById('usersTable');
            if (usersTable) {
                new simpleDatatables.DataTable(usersTable);
            }
        });
    </script>
</body>
</html>