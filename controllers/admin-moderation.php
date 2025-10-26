<?php
require_once 'includes/auth-functions.php';
require_once 'includes/function.php';
requireAdmin();

$user = getCurrentUser();

// Handle moderation actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['approve_content'])) {
        $contentId = $_POST['content_id'];
        $contentType = $_POST['content_type'];
        if (approveContent($contentId, $contentType)) {
            $_SESSION['success'] = "Content approved successfully!";
        } else {
            $_SESSION['error'] = "Failed to approve content.";
        }
    } elseif (isset($_POST['reject_content'])) {
        $contentId = $_POST['content_id'];
        $contentType = $_POST['content_type'];
        $reason = $_POST['rejection_reason'] ?? '';
        if (rejectContent($contentId, $contentType, $reason)) {
            $_SESSION['success'] = "Content rejected successfully!";
        } else {
            $_SESSION['error'] = "Failed to reject content.";
        }
    } elseif (isset($_POST['delete_content'])) {
        $contentId = $_POST['content_id'];
        $contentType = $_POST['content_type'];
        if (deleteContent($contentId, $contentType)) {
            $_SESSION['success'] = "Content deleted successfully!";
        } else {
            $_SESSION['error'] = "Failed to delete content.";
        }
    } elseif (isset($_POST['ban_user'])) {
        $userId = $_POST['user_id'];
        $reason = $_POST['ban_reason'] ?? '';
        if (banUser($userId, $reason)) {
            $_SESSION['success'] = "User banned successfully!";
        } else {
            $_SESSION['error'] = "Failed to ban user.";
        }
    }
    
    header('Location: /admin-moderation');
    exit;
}

// Get all content for moderation
$reportedContent = getReportedContent();
$pendingReviews = getPendingReviews();
$flaggedComments = getFlaggedComments();

$page_title = "Content Moderation - Admin Panel";
$current_page = 'admin-moderation';

require 'view/partial/admin-header.php';
require 'view/admin/moderation.php';
require 'view/partial/admin-footer.php';
?>