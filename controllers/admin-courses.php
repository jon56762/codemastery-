<?php
require_once 'includes/auth-functions.php';
require_once 'includes/init.php';
requireAdmin();

$user = getCurrentUser() ?? [];

// Handle course actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['approve_course'])) {
        $courseId = $_POST['course_id'] ?? 0;
        if (updateCourseStatusAdmin($courseId, 'published')) {
            $_SESSION['success'] = "Course approved and published successfully!";
        } else {
            $_SESSION['error'] = "Failed to approve course.";
        }
    } elseif (isset($_POST['reject_course'])) {
        $courseId = $_POST['course_id'] ?? 0;
        $reason = $_POST['rejection_reason'] ?? '';
        if (updateCourseStatusAdmin($courseId, 'rejected')) {
            $_SESSION['success'] = "Course rejected successfully!";
            // In a real app, you'd notify the instructor
        } else {
            $_SESSION['error'] = "Failed to reject course.";
        }
    } elseif (isset($_POST['delete_course'])) {
        $courseId = $_POST['course_id'] ?? 0;
        if (deleteCourseAdmin($courseId)) {
            $_SESSION['success'] = "Course deleted successfully!";
        } else {
            $_SESSION['error'] = "Failed to delete course.";
        }
    }
    
    // Refresh page to show updated data
    header('Location: /admin-courses');
    exit;
}

// Get all courses for moderation
$courses = getAllCourses() ?? [];
$pending_courses = array_filter($courses, function($course) {
    return ($course['status'] ?? '') === 'pending';
});
$published_courses = array_filter($courses, function($course) {
    return ($course['status'] ?? '') === 'published';
});
$draft_courses = array_filter($courses, function($course) {
    return ($course['status'] ?? '') === 'draft';
});

$page_title = "Course Moderation - Admin Panel";
$current_page = 'admin-courses';

require 'view/partial/admin-header.php';
require 'view/admin/courses.php';
require 'view/partial/admin-footer.php';
?>