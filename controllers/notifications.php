<?php
require_once 'includes/init.php';
require_once 'includes/auth-functions.php';
requireAuth();

$user = getCurrentUserObject();   
$userId = $user->getId();

$notifications = Notification::findByUser($userId);


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mark_read'])) {
    $notificationId = $_POST['notification_id'] ?? null;
    if ($notificationId) {
        $notif = Notification::findById($notificationId);
        if ($notif && $notif->userId == $userId) {
            $notif->markRead();  
        }
    }
    header('Location: /notifications');
    exit;
}


$notificationsArray = array_map(fn($n) => $n->toArray(), $notifications);

$page_title = "Notifications - CodeMastery";
$current_page = 'notifications';
require 'view/partial/nav.php';
require 'view/notifications.php';   
require 'view/partial/footer.php';