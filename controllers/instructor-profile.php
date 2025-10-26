<?php
require_once 'includes/auth-functions.php';
require_once 'includes/function.php';
requireInstructor();

$user = getCurrentUser();

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_profile'])) {
        $profileData = [
            'name' => trim($_POST['name']),
            'bio' => trim($_POST['bio']),
            'website' => trim($_POST['website'] ?? ''),
            'twitter' => trim($_POST['twitter'] ?? ''),
            'linkedin' => trim($_POST['linkedin'] ?? ''),
            'youtube' => trim($_POST['youtube'] ?? ''),
            'specialization' => $_POST['specialization'] ?? '',
            'experience' => $_POST['experience'] ?? ''
        ];

        if (empty($profileData['name'])) {
            $_SESSION['error'] = "Name is required.";
        } else {
            $result = updateUser($user['id'], $profileData);
            if ($result) {
                $_SESSION['success'] = "Profile updated successfully!";
                // Update session user data
                $_SESSION['user'] = getUserById($user['id']);
                $user = $_SESSION['user'];
            } else {
                $_SESSION['error'] = "Failed to update profile.";
            }
        }
    }
    
    // Handle password change
    if (isset($_POST['change_password'])) {
        $currentPassword = $_POST['current_password'];
        $newPassword = $_POST['new_password'];
        $confirmPassword = $_POST['confirm_password'];
        
        if (!password_verify($currentPassword, $user['password'])) {
            $_SESSION['error'] = "Current password is incorrect.";
        } elseif ($newPassword !== $confirmPassword) {
            $_SESSION['error'] = "New passwords do not match.";
        } elseif (strlen($newPassword) < 6) {
            $_SESSION['error'] = "New password must be at least 6 characters long.";
        } else {
            $result = updateUserPassword($user['id'], $newPassword);
            if ($result) {
                $_SESSION['success'] = "Password changed successfully!";
            } else {
                $_SESSION['error'] = "Failed to change password.";
            }
        }
    }
}

$page_title = "Profile - Instructor Panel";
$current_page = 'instructor-profile';

require 'view/partial/instructor-header.php';
require 'view/instructor/profile.php';
?>