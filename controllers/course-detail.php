<?php
require_once 'includes/auth-functions.php';
requireAuth();

$user = getCurrentUser();
$courseId = $_GET['id'] ?? null;

if (!$courseId) {
    header('Location: /courses');
    exit;
}

$course = getCourseById($courseId);
if (!$course) {
    header('Location: /courses');
    exit;
}

// Check if user is enrolled
$isEnrolled = isUserEnrolled($user['id'], $courseId);
$enrollment = $isEnrolled;

// Handle enrollment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enroll'])) {
    $result = enrollStudent($courseId, $user['id']);
    if ($result) {
        $_SESSION['success'] = "Successfully enrolled in " . htmlspecialchars($course['title']) . "!";
        header('Location: /course-player?course_id=' . $courseId . '&lesson_id=1');
        exit;
    } else {
        $_SESSION['error'] = "You are already enrolled in this course!";
        header('Location: /course/' . $courseId);
        exit;
    }
}

// Get related courses
$related_courses = getCoursesByCategory($course['category'], 3);
$related_courses = array_filter($related_courses, function($c) use ($courseId) {
    return $c['id'] != $courseId;
});

$page_title = $course['title'] . ' - CodeMastery';
$current_page = 'course-detail';

require 'view/partial/nav.php';
require 'view/course-detail.php';
require 'view/partial/footer.php';
?>