<?php
require_once 'includes/auth-functions.php';
requireAuth();

$user = getCurrentUser();

// Check if user is already an instructor
if ($user['role'] === 'instructor') {
    $_SESSION['error'] = "You are already an instructor!";
    header('Location: /instructor-dashboard');
    exit;
}

// Check if user already has a pending application
$applications = getAllInstructorApplications();
$pendingApplication = null;
foreach ($applications as $app) {
    if ($app['user_id'] == $user['id'] && $app['status'] === 'pending') {
        $pendingApplication = $app;
        break;
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_application'])) {
    $applicationData = [
        'user_id' => $user['id'],
        'name' => $user['name'],
        'email' => $user['email'],
        'experience' => trim($_POST['experience']),
        'specialization' => trim($_POST['specialization']),
        'portfolio' => trim($_POST['portfolio'] ?? ''),
        'linkedin' => trim($_POST['linkedin'] ?? ''),
        'message' => trim($_POST['message'] ?? '')
    ];

    // Validate required fields
    if (empty($applicationData['experience']) || empty($applicationData['specialization'])) {
        $_SESSION['error'] = "Please fill in all required fields.";
    } else {
        $result = submitInstructorApplication($applicationData);
        if ($result) {
            $_SESSION['success'] = "Your application has been submitted successfully! We'll review it and get back to you within 3-5 business days.";
            header('Location: /dashboard');
            exit;
        } else {
            $_SESSION['error'] = "You already have a pending application. Please wait for it to be reviewed.";
        }
    }
}

$page_title = "Become an Instructor - CodeMastery";
$current_page = 'become-instructor';
require 'view/partial/nav.php';
require 'view/become-instructor.php';
require 'view/partial/footer.php';
?>