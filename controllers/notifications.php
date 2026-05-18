<?php
require_once 'includes/auth-functions.php';
requireAuth();

$user = getCurrentUser();
$userId = $user['id'];

// Load notifications
$notifications = getFromFile('notifications.json');

// Filter for this user, sorted newest first
$userNotifications = array_filter($notifications, function($n) use ($userId) {
    return isset($n['user_id']) && $n['user_id'] == $userId;
});
usort($userNotifications, function($a, $b) {
    return strtotime($b['created_at'] ?? 'now') <=> strtotime($a['created_at'] ?? 'now');
});

// Mark as read (if POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mark_read'])) {
    $notificationId = $_POST['notification_id'] ?? null;
    if ($notificationId) {
        foreach ($notifications as &$n) {
            if (isset($n['id']) && $n['id'] == $notificationId) {
                $n['read'] = true;
                break;
            }
        }
        saveToFile('notifications.json', $notifications);
        // Refresh page to reflect changes
        header('Location: /notifications');
        exit;
    }
}

$page_title = "Notifications - CodeMastery";
$current_page = 'notifications';
require 'view/partial/nav.php';
require 'view/notifications.php';
?>