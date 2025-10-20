<?php
require_once 'includes/auth-functions.php';
requireRole('instructor');

$user = getCurrentUser();
$instructorId = $user['id'];

// Get instructor's courses using your function
$courses = getCoursesByInstructor($instructorId);

// Handle course actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_course'])) {
        $courseId = $_POST['course_id'];
        
        // Remove course from all courses
        $allCourses = getAllCourses();
        $allCourses = array_filter($allCourses, function($course) use ($courseId) {
            return $course['id'] != $courseId;
        });
        
        // Reindex array
        $allCourses = array_values($allCourses);
        saveToFile('courses.json', $allCourses);
        
        $_SESSION['success'] = "Course deleted successfully!";
        header('Location: /instructor-courses');
        exit;
    }
    
    if (isset($_POST['update_status'])) {
        $courseId = $_POST['course_id'];
        $newStatus = $_POST['status'];
        
        // Update course status using your function
        $result = updateCourse($courseId, ['status' => $newStatus]);
        
        if ($result) {
            $_SESSION['success'] = "Course status updated successfully!";
        } else {
            $_SESSION['error'] = "Failed to update course status.";
        }
        header('Location: /instructor-courses');
        exit;
    }
}

$page_title = "My Courses - Instructor Dashboard";
$current_page = 'instructor-courses';
require 'view/partial/instructor-header.php';
require 'view/instructor/courses.php';
require 'view/partial/footer.php';
?>