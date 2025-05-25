<?php
session_start();
require 'assets/variables/sql.php'; // Contains getUser(), update()

// --- Authentication and Authorization ---
if (!isset($_SESSION["userwtf"])) {
    error_log("Admin area (edit_user): Session 'userwtf' not set. Redirecting to login.");
    header("Location: login.php");
    exit;
}

$loggedInUser = getUser($_SESSION["userwtf"]);

if ($loggedInUser === null) {
    error_log("Admin area (edit_user): User ID " . $_SESSION["userwtf"] . " not found or DB error. Clearing session and redirecting to login.");
    unset($_SESSION["userwtf"]);
    header("Location: login.php");
    exit;
}

error_log("Admin area (edit_user): Checking user type. User ID: " . $loggedInUser->id . ", Type from DB: '" . $loggedInUser->type . "', PHP type: " . gettype($loggedInUser->type));
if ($loggedInUser->type !== 0) { // Admin type is 0 (integer)
    error_log("Admin area (edit_user): User ID " . $_SESSION["userwtf"] . " (type: " . $loggedInUser->type . ") is not an admin. Redirecting to index.");
    header("Location: index.php");
    exit;
}
// $user is the admin performing the action
$adminUser = $loggedInUser;

// --- Get User to Edit ---
$user_to_edit_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$userToEdit = null;
$update_message = '';
$error_message = '';

if ($user_to_edit_id) {
    $userToEdit = getUser($user_to_edit_id);
    if (!$userToEdit) {
        $error_message = "User not found.";
    }
} else {
    $error_message = "No user ID specified.";
}

// --- Handle Form Submission ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $userToEdit) {
    $newName = trim($_POST['name'] ?? '');
    $newLabelName = trim($_POST['labelName'] ?? '');
    $newEmail = trim($_POST['email'] ?? '');
    $newType = filter_input(INPUT_POST, 'type', FILTER_VALIDATE_INT); // 0 for Admin, 1 for User

    // Basic validation
    if (empty($newName) || empty($newEmail)) {
        $error_message = "Name and Email cannot be empty.";
    } elseif (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email format.";
    } elseif ($newType !== 0 && $newType !== 1) {
        $error_message = "Invalid user type selected.";
    } else {
        // Prevent admin from changing their own type to non-admin if they are the only admin
        // This is a basic check; more sophisticated logic might be needed
        if ($userToEdit->id == $adminUser->id && $newType !== 0) {
            // Check if there are other admins
            $allUsers = getAllUsers();
            $otherAdmins = 0;
            foreach ($allUsers as $u) {
                if ($u->type === 0 && $u->id != $adminUser->id) {
                    $otherAdmins++;
                    break;
                }
            }
            if ($otherAdmins === 0) {
                $error_message = "Cannot change your own account type to non-admin as you are the only administrator.";
            }
        }

        if (empty($error_message)) {
            // Prepare updates. Be careful with direct SQL concatenation in a real app.
            // Ideally, use a dedicated updateUser function with prepared statements.
            $updateQueryBase = "UPDATE user SET ";
            $updateFields = [];
            $updateValues = []; // For prepared statements if we were using them here

            if ($userToEdit->name !== $newName) $updateFields[] = "name = '" . $GLOBALS["conn"]->real_escape_string($newName) . "'";
            if ($userToEdit->display !== $newLabelName) $updateFields[] = "labelName = '" . $GLOBALS["conn"]->real_escape_string($newLabelName) . "'";
            if ($userToEdit->email !== $newEmail) $updateFields[] = "email = '" . $GLOBALS["conn"]->real_escape_string($newEmail) . "'";
            if ($userToEdit->type !== $newType) $updateFields[] = "type = " . intval($newType);


            if (!empty($updateFields)) {
                $updateQuery = $updateQueryBase . implode(", ", $updateFields) . " WHERE userID = " . intval($userToEdit->id);
                if (query($updateQuery)) {
                    $update_message = "User details updated successfully!";
                    // Refresh user data
                    $userToEdit = getUser($user_to_edit_id);
                } else {
                    $error_message = "Database error updating user. " . $GLOBALS["conn"]->error;
                }
            } else {
                $update_message = "No changes detected.";
            }
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Edit User - Fuchsia Admin</title>
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
                    <h1 class="mt-4">Edit User</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="users.php">User Management</a></li>
                        <li class="breadcrumb-item active">Edit User</li>
                    </ol>

                    <?php if ($update_message): ?>
                        <div class="alert alert-success"><?= htmlspecialchars($update_message) ?></div>
                    <?php endif; ?>
                    <?php if ($error_message): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error_message) ?></div>
                    <?php endif; ?>

                    <?php if ($userToEdit): ?>
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-user-edit me-1"></i>
                            Editing User: <?= htmlspecialchars($userToEdit->handle) ?> (ID: <?= htmlspecialchars($userToEdit->id) ?>)
                        </div>
                        <div class="card-body">
                            <form method="POST" action="edit_user.php?id=<?= htmlspecialchars($userToEdit->id) ?>">
                                <div class="mb-3">
                                    <label for="username" class="form-label">Username (Cannot Change)</label>
                                    <input type="text" id="username" class="form-control" value="<?= htmlspecialchars($userToEdit->handle) ?>" readonly>
                                </div>
                                <div class="mb-3">
                                    <label for="name" class="form-label">Full Name</label>
                                    <input type="text" name="name" id="name" class="form-control" value="<?= htmlspecialchars($userToEdit->name) ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="labelName" class="form-label">Label/Display Name</label>
                                    <input type="text" name="labelName" id="labelName" class="form-control" value="<?= htmlspecialchars($userToEdit->display) ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" name="email" id="email" class="form-control" value="<?= htmlspecialchars($userToEdit->email) ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="type" class="form-label">User Type</label>
                                    <select name="type" id="type" class="form-select">
                                        <option value="1" <?= ($userToEdit->type == 1) ? 'selected' : '' ?>>Regular User</option>
                                        <option value="0" <?= ($userToEdit->type == 0) ? 'selected' : '' ?>>Administrator</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Registered Date</label>
                                    <input type="text" class="form-control" value="<?= htmlspecialchars($userToEdit->register) ?>" readonly>
                                </div>
                                <!-- Password change should be a separate, more secure process -->
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                <a href="users.php" class="btn btn-secondary">Cancel</a>
                            </form>
                        </div>
                    </div>
                    <?php else: ?>
                        <?php if (empty($error_message)): // Show default error if $userToEdit is null and no specific error was set ?>
                        <div class="alert alert-warning">User could not be loaded or ID is missing.</div>
                        <?php endif; ?>
                        <a href="users.php" class="btn btn-secondary">Back to User List</a>
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