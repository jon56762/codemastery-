<?php
require_once 'includes/init.php';
require_once 'includes/auth-functions.php';
requireAuth();

$user = getCurrentUserObject();
if ($user->role === 'instructor') {
    $_SESSION['error'] = "You are already an instructor.";
    header('Location: /instructor-dashboard');
    exit;
}

// Check for pending application
$existing = array_filter(InstructorApplication::getAll(), fn($app) => $app->userId == $user->getId() && $app->status === 'pending');
$pendingApplication = !empty($existing) ? reset($existing) : null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_application'])) {
    $data = [
        'user_id'        => $user->getId(),
        'name'           => $user->name,
        'email'          => $user->email,
        'experience'     => $_POST['experience'],
        'specialization' => $_POST['specialization'],
        'portfolio'      => $_POST['portfolio'] ?? '',
        'linkedin'       => $_POST['linkedin'] ?? ''
    ];
    if (empty($data['experience']) || empty($data['specialization'])) {
        $_SESSION['error'] = "Please fill in all required fields.";
    } else {
        $app = InstructorApplication::submit($data);
        if ($app) {
            $_SESSION['success'] = "Application submitted!";
            header('Location: /dashboard');
            exit;
        } else {
            $_SESSION['error'] = "You already have a pending application.";
        }
    }
}

$page_title = "Become an Instructor - CodeMastery";
$current_page = 'become-instructor';
require 'view/partial/nav.php';
require 'view/become-instructor.php';
require 'view/partial/footer.php';