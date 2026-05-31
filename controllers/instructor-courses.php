<?php
require_once 'includes/init.php';
require_once 'includes/auth-functions.php';
requireRole('instructor');

$user = getCurrentUser();           // array for the header
$instructorId = $user['id'];

$courses = Course::getByInstructor($instructorId);

// Handle course actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_course'])) {
        $course = Course::findById($_POST['course_id']);
        if ($course && $course->instructorId == $instructorId) {
            $course->delete();
            $_SESSION['success'] = "Course deleted!";
        }
        header('Location: /instructor-courses');
        exit;
    }
    if (isset($_POST['update_status'])) {
        $course = Course::findById($_POST['course_id']);
        if ($course && $course->instructorId == $instructorId) {
            $course->status = $_POST['status'];
            $course->save();
            $_SESSION['success'] = "Status updated!";
        }
        header('Location: /instructor-courses');
        exit;
    }
}

// Convert to arrays for the view
$coursesArray = array_map(fn($c) => $c->toArray(), $courses);

$page_title = "My Courses - Instructor Dashboard";
$current_page = 'instructor-courses';
require 'view/partial/instructor-header.php';
require 'view/instructor/courses.php';
require 'view/partial/footer.php';