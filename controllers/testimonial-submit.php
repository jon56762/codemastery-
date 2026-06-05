<?php
require_once 'includes/init.php';
require_once 'includes/auth-functions.php';
requireAuth(); 

$user = getCurrentUserObject();

$existing = array_filter(Testimonial::getAll(), fn($t) => $t->userId == $user->getId());
if (!empty($existing)) {
    $_SESSION['error'] = "You have already submitted a testimonial. Thank you!";
    header('Location: /testimonials');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_testimonial'])) {
    $text = trim($_POST['testimonial_text']);
    $rating = intval($_POST['rating']);
    $role = trim($_POST['role']);

    $errors = [];
    if (empty($text)) {
        $errors[] = "Please enter your testimonial message.";
    }
    if (strlen($text) > 500) {
        $errors[] = "Testimonial must be 500 characters or less.";
    }
    if ($rating < 1 || $rating > 5) {
        $errors[] = "Please select a valid rating.";
    }
    if (empty($role)) {
        $errors[] = "Please enter your current role.";
    }

    if (empty($errors)) {
        $testimonial = Testimonial::submit([
            'user_id' => $user->getId(),
            'name'    => $user->getName(),
            'role'    => $role,
            'avatar'  => $user->getAvatar(),
            'text'    => $text,
            'rating'  => $rating
        ]);
        if ($testimonial) {
            $_SESSION['success'] = "Thank you for your testimonial! It will be reviewed and published soon.";
            header('Location: /testimonials');
            exit;
        } else {
            $_SESSION['error'] = "Failed to submit testimonial. Please try again.";
        }
    } else {
        $_SESSION['error'] = implode("<br>", $errors);
    }
}

// Convert user object to array for the view
$userArray = $user->toArray();

// Helper functions for stats (you can move these to helpers.php)
function getStudentEnrollmentsCount($userId) {
    $enrollments = Enrollment::findByUser($userId);
    return count($enrollments);
}
function getCompletedCoursesCount($userId) {
    $enrollments = Enrollment::findByUser($userId);
    $completed = 0;
    foreach ($enrollments as $e) {
        if ($e->progress >= 100) $completed++;
    }
    return $completed;
}
function getStudentAchievementsCount($userId) {
    // Simple placeholder – replace with real achievement logic if needed
    $completed = getCompletedCoursesCount($userId);
    $achievements = 0;
    if ($completed >= 1) $achievements++;
    if ($completed >= 3) $achievements++;
    if ($completed >= 5) $achievements++;
    return $achievements;
}

$enrolledCount = getStudentEnrollmentsCount($user->getId());
$completedCount = getCompletedCoursesCount($user->getId());
$achievementsCount = getStudentAchievementsCount($user->getId());

$page_title = "Submit a Testimonial - CodeMastery";
$current_page = 'testimonial-submit';

require 'view/partial/nav.php';
require 'view/testimonial-submit.php';
require 'view/partial/footer.php';