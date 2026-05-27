<?php
require_once 'includes/init.php';
require_once 'includes/auth-functions.php';
requireAdmin();

// Handle course actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $courseId = $_POST['course_id'] ?? null;

    if (isset($_POST['approve_course']) && $courseId) {
        $course = Course::findById($courseId);
        if ($course && $course->status === 'pending') {
            $course->status = 'published';
            $course->save();
            $_SESSION['success'] = "Course published!";
        }
    } elseif (isset($_POST['reject_course']) && $courseId) {
        $course = Course::findById($courseId);
        if ($course) {
            $course->status = 'rejected';
            $course->save();
            $_SESSION['success'] = "Course rejected.";
        }
    } elseif (isset($_POST['delete_course']) && $courseId) {
        $course = Course::findById($courseId);
        if ($course) {
            $course->delete();
            $_SESSION['success'] = "Course deleted.";
        }
    }
    header('Location: /admin-courses');
    exit;
}

// Fetch courses
$courses = Course::getAll();
// Convert to arrays for the view 
$coursesArray = array_map(fn($c) => $c->toArray(), $courses);

$pending_courses   = array_filter($coursesArray, fn($c) => $c['status'] === 'pending');
$published_courses = array_filter($coursesArray, fn($c) => $c['status'] === 'published');
$draft_courses     = array_filter($coursesArray, fn($c) => $c['status'] === 'draft');

$page_title = "Course Moderation - Admin Panel";
$current_page = 'admin-courses';

require 'view/partial/admin-header.php';
require 'view/admin/courses.php';
require 'view/partial/admin-footer.php';