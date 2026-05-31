<?php
require_once 'includes/init.php';
require_once 'includes/auth-functions.php';
requireAdmin();

// Handle user actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['user_id'] ?? 0;

    if (isset($_POST['update_role'])) {
        $u = User::findById($userId);
        if ($u) {
            $u->role = $_POST['new_role'];
            $u->save();
            $_SESSION['success'] = "Role updated.";
        }
    } elseif (isset($_POST['suspend_user'])) {
        $u = User::findById($userId);
        if ($u) {
            $u->status = 'suspended';
            $u->save();
            $_SESSION['success'] = "User suspended.";
        }
    } elseif (isset($_POST['activate_user'])) {
        $u = User::findById($userId);
        if ($u) {
            $u->status = 'active';
            $u->save();
            $_SESSION['success'] = "User activated.";
        }
    } elseif (isset($_POST['delete_user'])) {
        $u = User::findById($userId);
        if ($u) {
            $u->delete();
            $_SESSION['success'] = "User deleted.";
        }
    }
    header('Location: /admin-users');
    exit;
}

$search = $_GET['search'] ?? '';

// Get users as objects
$userObjects = User::getAll();
if ($search) {
    $userObjects = array_filter($userObjects, fn($u) =>
        stripos($u->name, $search) !== false || stripos($u->email, $search) !== false
    );
}

// Convert to arrays
$usersArray = array_map(fn($u) => $u->toArray(), $userObjects);

// Pre-calculate course/enrollment counts
foreach ($usersArray as &$user) {
    if ($user['role'] === 'instructor') {
        $user['course_count'] = count(Course::getByInstructor($user['id']));
    } else {
        $user['course_count'] = count(Enrollment::findByUser($user['id']));
    }
}
unset($user);

// Build role/status filter arrays for the stat cards
$students       = array_filter($usersArray, fn($u) => $u['role'] === 'student' && $u['status'] === 'active');
$instructors   = array_filter($usersArray, fn($u) => $u['role'] === 'instructor' && $u['status'] === 'active');
$suspended_users = array_filter($usersArray, fn($u) => $u['status'] === 'suspended');

// Assign the array back to $users (view expects $users)
$users = $usersArray;

$page_title = "User Management - Admin Panel";
$current_page = 'admin-users';

require 'view/partial/admin-header.php';
require 'view/admin/users.php';
require 'view/partial/admin-footer.php';