<?php
require_once 'includes/init.php';
require_once 'includes/auth-functions.php';
requireAdmin();

function loadSettings() {
    $db = Database::getConnection();
    $result = $db->query("SELECT setting_key, setting_value FROM settings");
    $settings = [];
    while ($row = $result->fetch_assoc()) {
        $settings[$row['setting_key']] = $row['setting_value'];
    }
    return $settings;
}

function saveSetting($key, $value) {
    $db = Database::getConnection();
    $stmt = $db->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = ?");
    $stmt->bind_param("sss", $key, $value, $value);
    $stmt->execute();
}

$settings = loadSettings();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_email_settings'])) {
        saveSetting('smtp_host', trim($_POST['smtp_host']));
        saveSetting('smtp_port', intval($_POST['smtp_port']));
        saveSetting('smtp_username', trim($_POST['smtp_username']));
        saveSetting('smtp_password', trim($_POST['smtp_password']));
        saveSetting('smtp_encryption', $_POST['smtp_encryption']);
        saveSetting('from_email', trim($_POST['from_email']));
        saveSetting('from_name', trim($_POST['from_name']));

        $_SESSION['success'] = "Email settings updated successfully!";
        header('Location: /admin-settings');
        exit;
    }

    if (isset($_POST['test_email'])) {
        $_SESSION['success'] = "Test email sent (feature coming soon).";
        header('Location: /admin-settings');
        exit;
    }

    $settings = loadSettings();
}

$page_title = "System Settings - Admin Panel";
$current_page = 'admin-settings';

require 'view/partial/admin-header.php';
require 'view/admin/settings.php';
require 'view/partial/admin-footer.php';