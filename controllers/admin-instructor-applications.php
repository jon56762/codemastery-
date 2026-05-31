<?php
require_once 'includes/init.php';
require_once 'includes/auth-functions.php';
requireAdmin();

$adminUser = getCurrentUser() ?? [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $applicationId = $_POST['application_id'] ?? '';
    if (isset($_POST['approve_application'])) {
        InstructorApplication::approve($applicationId, $adminUser['id']);
        $_SESSION['success'] = "Application approved.";
    } elseif (isset($_POST['reject_application'])) {
        $reason = $_POST['rejection_reason'] ?? '';
        InstructorApplication::reject($applicationId, $adminUser['id'], $reason);
        $_SESSION['success'] = "Application rejected.";
    }
    header('Location: /admin-instructor-applications');
    exit;
}

$applications = InstructorApplication::getAll();
$applicationsArray = array_map(function ($app) {
    $data = $app->toArray();
    $applicant = User::findById($data['user_id'] ?? 0);
    $data['applicant_name']  = $applicant ? $applicant->name  : 'Unknown';
    $data['applicant_email'] = $applicant ? $applicant->email : '';
    return $data;
}, $applications);

$pending_applications   = array_filter($applicationsArray, fn($a) => $a['status'] === 'pending');
$approved_applications  = array_filter($applicationsArray, fn($a) => $a['status'] === 'approved');
$rejected_applications  = array_filter($applicationsArray, fn($a) => $a['status'] === 'rejected');

$applications = $applicationsArray;

$page_title = "Instructor Applications - Admin Panel";
$current_page = 'admin-instructor-applications';

require 'view/partial/admin-header.php';
require 'view/admin/instructor-applications.php';
require 'view/partial/admin-footer.php';