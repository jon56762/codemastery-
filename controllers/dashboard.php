<?php
require_once 'includes/init.php';
require_once 'includes/auth-functions.php';
requireAuth();

// Keep user as array (view still uses $user['name'] etc.)
$user = getCurrentUser();
if ($user['role'] !== 'student') {
    header('Location: /');
    exit;
}

$userId = $user['id'];

// 1. Get enrollments as objects
$enrollmentObjects = Enrollment::findByUser($userId);

// 2. Build enrolled courses list (arrays for the view)
$enrolledCourses = [];
$enrollmentsArray = [];   // plain array of enrollment data for average progress calculation
foreach ($enrollmentObjects as $enrollment) {
    $course = Course::findById($enrollment->courseId);
    if ($course) {
        $enrolledCourses[] = [
            'course'     => $course->toArray(),
            'enrollment' => $enrollment->toArray()
        ];
    }
    // collect enrollment array for average progress
    $enrollmentsArray[] = $enrollment->toArray();
}

// Sort by most recent enrollment
usort($enrolledCourses, function($a, $b) {
    return strtotime($b['enrollment']['enrolled_at']) - strtotime($a['enrollment']['enrolled_at']);
});

// 3. Statistics
$totalCourses   = count($enrolledCourses);
$continueLearning = array_filter($enrolledCourses, function($item) {
    return $item['enrollment']['progress'] > 0 && $item['enrollment']['progress'] < 100;
});
$completedCourses = array_filter($enrolledCourses, function($item) {
    return $item['enrollment']['progress'] >= 100;
});
$completedCount  = count($completedCourses);
$inProgressCount = count($continueLearning);

// Average progress across all enrollments
$averageProgress = 0;
if ($totalCourses > 0) {
    $sum = array_sum(array_column($enrollmentsArray, 'progress'));
    $averageProgress = round($sum / $totalCourses);
}

// Completion rate
$completionRate = $totalCourses > 0 ? round(($completedCount / $totalCourses) * 100) : 0;

// 4. Recommended courses (featured for now)
$recommendedCourses = Course::getFeatured(6);
$recommendedCoursesArray = array_map(fn($c) => $c->toArray(), $recommendedCourses);

// 5. Achievements & deadlines (keep old functions if they still work, or replace later)
$achievements      = getStudentAchievements($userId);
$upcomingDeadlines = getUpcomingDeadlines($userId);

// The view now has all the variables it needs
$page_title   = "Dashboard - CodeMastery";
$current_page = 'dashboard';

require 'view/partial/nav.php';
require 'view/student/dashboard.php';
require 'view/partial/footer.php';