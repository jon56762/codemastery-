<?php
require_once 'includes/auth-functions.php';
require_once 'includes/function.php';
requireAdmin();

$user = getCurrentUser();

// Handle user actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_role'])) {
        $userId = $_POST['user_id'];
        $newRole = $_POST['new_role'];
        
        if (updateUserRole($userId, $newRole)) {
            $_SESSION['success'] = "User role updated successfully!";
        } else {
            $_SESSION['error'] = "Failed to update user role.";
        }
    } elseif (isset($_POST['suspend_user'])) {
        $userId = $_POST['user_id'];
        if (updateUserStatus($userId, 'suspended')) {
            $_SESSION['success'] = "User suspended successfully!";
        } else {
            $_SESSION['error'] = "Failed to suspend user.";
        }
    } elseif (isset($_POST['activate_user'])) {
        $userId = $_POST['user_id'];
        if (updateUserStatus($userId, 'active')) {
            $_SESSION['success'] = "User activated successfully!";
        } else {
            $_SESSION['error'] = "Failed to activate user.";
        }
    } elseif (isset($_POST['delete_user'])) {
        $userId = $_POST['user_id'];
        if (deleteUser($userId)) {
            $_SESSION['success'] = "User deleted successfully!";
        } else {
            $_SESSION['error'] = "Failed to delete user.";
        }
    }
    
    // Refresh page to show updated data
    header('Location: /admin-users');
    exit;
}

// Get all users
$users = getAllUsers();
$students = array_filter($users, function($user) {
    return $user['role'] === 'student' && $user['status'] === 'active';
});
$instructors = array_filter($users, function($user) {
    return $user['role'] === 'instructor' && $user['status'] === 'active';
});
$admins = array_filter($users, function($user) {
    return $user['role'] === 'admin';
});
$suspended_users = array_filter($users, function($user) {
    return $user['status'] === 'suspended';
});

// Filter users if search is provided
$search = $_GET['search'] ?? '';
if ($search) {
    $users = array_filter($users, function($user) use ($search) {
        return stripos($user['name'], $search) !== false || 
               stripos($user['email'], $search) !== false;
    });
}

$page_title = "User Management - Admin Panel";
$current_page = 'admin-users';

require 'view/partial/admin-header.php';
require 'view/admin/users.php';
require 'view/partial/admin-footer.php';
?>