<?php
require_once 'includes/init.php';
require_once 'includes/auth-functions.php';
requireAdmin();

// Handle user actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['user_id'] ?? 0;

    if (isset($_POST['update_role'])) {
        $user = User::findById($userId);
        if ($user) {
            $user->role = $_POST['new_role'];
            $user->save();
            $_SESSION['success'] = "Role updated.";
        }
    } elseif (isset($_POST['suspend_user'])) {
        $user = User::findById($userId);
        if ($user) {
            $user->status = 'suspended';
            $user->save();
            $_SESSION['success'] = "User suspended.";
        }
    } elseif (isset($_POST['activate_user'])) {
        $user = User::findById($userId);
        if ($user) {
            $user->status = 'active';
            $user->save();
            $_SESSION['success'] = "User activated.";
        }
    } elseif (isset($_POST['delete_user'])) {
        $user = User::findById($userId);
        if ($user) {
            $user->delete();
            $_SESSION['success'] = "User deleted.";
        }
    }
    header('Location: /admin-users');
    exit;
}

$search = $_GET['search'] ?? '';
$users = User::getAll();
if ($search) {
    $users = array_filter($users, fn($u) =>
        stripos($u->name, $search) !== false || stripos($u->email, $search) !== false
    );
}
// Convert to arrays for the view
$usersArray = array_map(fn($u) => $u->toArray(), $users);

$page_title = "User Management - Admin Panel";
$current_page = 'admin-users';
require 'view/partial/admin-header.php';
require 'view/admin/users.php';
require 'view/partial/admin-footer.php';