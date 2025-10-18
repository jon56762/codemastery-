<?php
require_once 'includes/function.php';
require_once 'includes/auth-functions.php';

$page_title = "Course Details - CodeMastery";
$current_page = 'course-detail';

// Get course ID from URL
$courseId = $_GET['id'] ?? 0;
$course = getCourseById($courseId);

// If course not found, show 404
if (!$course || $course['status'] !== 'published') {
    http_response_code(404);
    require 'views/404.php';
    exit;
}

// Get instructor information
$instructor = getUserById($course['instructor_id']);

// Get related courses (same category)
$related_courses = array_filter(getAllCourses(), function($c) use ($course) {
    return $c['category'] === $course['category'] && 
           $c['id'] != $course['id'] && 
           $c['status'] === 'published';
});
$related_courses = array_slice($related_courses, 0, 3);

// Check if user is enrolled
$isEnrolled = false;
$enrollment = null;
if (isset($_SESSION['user'])) {
    $enrollments = getStudentEnrollments($_SESSION['user']['id']);
    foreach ($enrollments as $enr) {
        if ($enr['course_id'] == $courseId) {
            $isEnrolled = true;
            $enrollment = $enr;
            break;
        }
    }
}

// Handle enrollment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enroll'])) {
    if (!isset($_SESSION['user'])) {
        $_SESSION['error'] = "Please log in to enroll in this course.";
        $_SESSION['redirect_url'] = "/course/" . $courseId;
        header('Location: /login');
        exit;
    }
    
    if ($isEnrolled) {
        $_SESSION['error'] = "You are already enrolled in this course.";
    } else {
        $enrollment = enrollStudent($courseId, $_SESSION['user']['id']);
        if ($enrollment) {
            $_SESSION['success'] = "Successfully enrolled in " . htmlspecialchars($course['title']) . "!";
            $isEnrolled = true;
        } else {
            $_SESSION['error'] = "Enrollment failed. Please try again.";
        }
    }
}

// Handle free preview (if course is paid)
$hasPreview = $course['price'] > 0;

require 'view/partial/nav.php';
require 'view/course-detail.php';
require 'view/partial/footer.php';
?>