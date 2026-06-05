<?php 
require_once 'includes/init.php';
require_once 'includes/auth-functions.php';
requireRole('instructor');

$user = getCurrentUser();          
$instructorId = $user['id'];
$courseObjects = Course::getByInstructor($instructorId);

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

$courses = array_map(function($c) {
    $data = $c->toArray();
    $data['course_count'] = count(Enrollment::findByCourse($c->id));
    return $data;
}, $courseObjects);

$page_title = "My Courses - Instructor Dashboard";
$current_page = 'instructor-courses';
require 'view/partial/instructor-header.php';
require 'view/instructor/courses.php';
require 'view/partial/footer.php';