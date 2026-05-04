<?php
require_once 'includes/auth-functions.php';
require_once 'includes/init.php';
requireAdmin();

$user = getCurrentUser();

// Get current settings
$settings = getPlatformSettings();

// Handle settings update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle email settings update
    if (isset($_POST['update_email_settings'])) {
        $emailSettings = [
            'smtp_host' => trim($_POST['smtp_host']),
            'smtp_port' => intval($_POST['smtp_port']),
            'smtp_username' => trim($_POST['smtp_username']),
            'smtp_password' => trim($_POST['smtp_password']),
            'smtp_encryption' => $_POST['smtp_encryption'],
            'from_email' => trim($_POST['from_email']),
            'from_name' => trim($_POST['from_name'])
        ];

        if (updatePlatformSettings($emailSettings)) {
            $_SESSION['success'] = "Email settings updated successfully!";
        } else {
            $_SESSION['error'] = "Failed to update email settings.";
        }
    }

    // Handle test email
    if (isset($_POST['test_email'])) {
        require_once 'includes/email-functions.php';
        $testEmail = $_POST['test_email'];

        if (testEmailConfiguration($testEmail)) {
            $_SESSION['success'] = "Test email sent successfully to {$testEmail}!";
        } else {
            $_SESSION['error'] = "Failed to send test email. Check your email configuration.";
        }

        header('Location: /admin-settings');
        exit;
    }

    // Refresh settings
    $settings = getPlatformSettings();
}

$page_title = "System Settings - Admin Panel";
$current_page = 'admin-settings';

require 'view/partial/admin-header.php';
require 'view/admin/settings.php';
require 'view/partial/admin-footer.php';
