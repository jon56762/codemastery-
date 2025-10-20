<?php
require_once 'includes/function.php';
require_once 'includes/auth-functions.php';

$page_title = "My Courses - CodeMastery";
$current_page = 'my-courses';

// Require authentication and student role
requireAuth();
if ($_SESSION['user']['role'] !== 'student') {
    $_SESSION['error'] = "You don't have permission to access this page.";
    header('Location: /');
    exit;
}

$userId = $_SESSION['user']['id'];
$user = getUserById($userId);

// Get student's enrollments with course details
$enrollments = getStudentEnrollments($userId);
$enrolledCourses = [];

foreach ($enrollments as $enrollment) {
    $course = getCourseById($enrollment['course_id']);
    if ($course) {
        $enrolledCourses[] = [
            'course' => $course,
            'enrollment' => $enrollment
        ];
    }
}

// Filter and sort handling
$filter = $_GET['filter'] ?? 'all';
$sort = $_GET['sort'] ?? 'recent';

// Apply filters
$filteredCourses = $enrolledCourses;
if ($filter === 'completed') {
    $filteredCourses = array_filter($enrolledCourses, function($item) {
        return $item['enrollment']['progress'] >= 100;
    });
} elseif ($filter === 'in-progress') {
    $filteredCourses = array_filter($enrolledCourses, function($item) {
        return $item['enrollment']['progress'] > 0 && $item['enrollment']['progress'] < 100;
    });
} elseif ($filter === 'not-started') {
    $filteredCourses = array_filter($enrolledCourses, function($item) {
        return $item['enrollment']['progress'] == 0;
    });
}

// Apply sorting
usort($filteredCourses, function($a, $b) use ($sort) {
    switch ($sort) {
        case 'progress':
            return $b['enrollment']['progress'] <=> $a['enrollment']['progress'];
        case 'title':
            return $a['course']['title'] <=> $b['course']['title'];
        case 'recent':
        default:
            return strtotime($b['enrollment']['enrolled_at']) <=> strtotime($a['enrollment']['enrolled_at']);
    }
});

// Handle search
$search = $_GET['search'] ?? '';
if ($search) {
    $filteredCourses = array_filter($filteredCourses, function($item) use ($search) {
        return stripos($item['course']['title'], $search) !== false ||
               stripos($item['course']['description'], $search) !== false;
    });
}

// Get wishlist (placeholder for now)
$wishlist = [];

require 'view/partial/nav.php';
require 'view/student/my-courses.php';
require 'view/partial/footer.php';
?>