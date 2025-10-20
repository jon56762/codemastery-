<?php
require_once 'includes/function.php';
require_once 'includes/auth-functions.php';

$page_title = "Dashboard - CodeMastery";
$current_page = 'dashboard';

// Require authentication and student role
requireAuth();
if ($_SESSION['user']['role'] !== 'student') {
    $_SESSION['error'] = "You don't have permission to access the student dashboard.";
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

// Sort by most recent enrollment
usort($enrolledCourses, function($a, $b) {
    return strtotime($b['enrollment']['enrolled_at']) - strtotime($a['enrollment']['enrolled_at']);
});

// Get continue learning (in-progress courses)
$continueLearning = array_filter($enrolledCourses, function($item) {
    return $item['enrollment']['progress'] > 0 && $item['enrollment']['progress'] < 100;
});

// Get completed courses
$completedCourses = array_filter($enrolledCourses, function($item) {
    return $item['enrollment']['progress'] >= 100;
});

// Get statistics
$totalCourses = count($enrolledCourses);
$completedCount = count($completedCourses);
$inProgressCount = count($continueLearning);
$totalLearningTime = array_sum(array_column($enrollments, 'progress')); // Simplified - would be actual time in real app

// Get recommended courses (based on category of enrolled courses)
$recommendedCourses = getRecommendedCourses($userId);

// Get achievements
$achievements = getStudentAchievements($userId);

// Get upcoming deadlines (simulated for now)
$upcomingDeadlines = getUpcomingDeadlines($userId);

require 'view/partial/nav.php';
require 'view/student/dashboard.php';
require 'view/partial/footer.php';
?>