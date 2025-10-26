<?php
require_once 'includes/auth-functions.php';
require_once 'includes/function.php';
requireAdmin();

$user = getCurrentUser();

// Handle application actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['approve_application'])) {
        $applicationId = $_POST['application_id'];
        if (approveInstructorApplication($applicationId, $user['id'])) {
            $_SESSION['success'] = "Instructor application approved successfully!";
        } else {
            $_SESSION['error'] = "Failed to approve application.";
        }
    } elseif (isset($_POST['reject_application'])) {
        $applicationId = $_POST['application_id'];
        $reason = $_POST['rejection_reason'] ?? '';
        if (rejectInstructorApplication($applicationId, $user['id'], $reason)) {
            $_SESSION['success'] = "Instructor application rejected successfully!";
        } else {
            $_SESSION['error'] = "Failed to reject application.";
        }
    }

    header('Location: /admin-instructor-applications');
    exit;
}

// After approving application
if (approveInstructorApplication($applicationId, $user['id'])) {
    $applicant = getUserById($application['user_id']);
    require_once 'includes/email-functions.php';
    if (sendInstructorApplicationApprovalEmail($applicant['email'], $applicant['name'])) {
        $_SESSION['success'] = "Instructor application approved and notification email sent!";
    } else {
        $_SESSION['success'] = "Instructor application approved, but email notification failed.";
    }
}

// After rejecting application  
if (rejectInstructorApplication($applicationId, $user['id'], $reason)) {
    $applicant = getUserById($application['user_id']);
    require_once 'includes/email-functions.php';
    sendInstructorApplicationRejectionEmail($applicant['email'], $applicant['name'], $reason);
    $_SESSION['success'] = "Instructor application rejected and notification sent.";
}

// Get all applications
$applications = getAllInstructorApplications();
$pending_applications = array_filter($applications, function ($app) {
    return $app['status'] === 'pending';
});
$approved_applications = array_filter($applications, function ($app) {
    return $app['status'] === 'approved';
});
$rejected_applications = array_filter($applications, function ($app) {
    return $app['status'] === 'rejected';
});

$page_title = "Instructor Applications - Admin Panel";
$current_page = 'admin-instructor-applications';

require 'view/partial/admin-header.php';
require 'view/admin/instructor-applications.php';
require 'view/partial/admin-footer.php';
