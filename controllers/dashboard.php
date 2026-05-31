<?php
require_once 'includes/init.php';
require_once 'includes/auth-functions.php';
requireAuth();

$user = getCurrentUser();
if ($user['role'] !== 'student') {
    header('Location: /');
    exit;
}

$userId = $user['id'];

$enrollmentObjects = Enrollment::findByUser($userId);

$enrolledCourses = [];
$enrollmentsArray = [];   
foreach ($enrollmentObjects as $enrollment) {
    $course = Course::findById($enrollment->courseId);
    if ($course) {
        $enrolledCourses[] = [
            'course'     => $course->toArray(),
            'enrollment' => $enrollment->toArray()
        ];
    }
    $enrollmentsArray[] = $enrollment->toArray();
}

usort($enrolledCourses, function($a, $b) {
    return strtotime($b['enrollment']['enrolled_at']) - strtotime($a['enrollment']['enrolled_at']);
});

$totalCourses   = count($enrolledCourses);
$continueLearning = array_filter($enrolledCourses, function($item) {
    return $item['enrollment']['progress'] > 0 && $item['enrollment']['progress'] < 100;
});
$completedCourses = array_filter($enrolledCourses, function($item) {
    return $item['enrollment']['progress'] >= 100;
});
$completedCount  = count($completedCourses);
$inProgressCount = count($continueLearning);

$averageProgress = 0;
if ($totalCourses > 0) {
    $sum = array_sum(array_column($enrollmentsArray, 'progress'));
    $averageProgress = round($sum / $totalCourses);
}

$completionRate = $totalCourses > 0 ? round(($completedCount / $totalCourses) * 100) : 0;

$recommendedCourses = Course::getFeatured(6);
$recommendedCoursesArray = array_map(fn($c) => $c->toArray(), $recommendedCourses);


$achievements = [];

if ($completedCount >= 1) {
    $achievements[] = [
        'id'          => 1,
        'title'       => 'First Steps',
        'description' => 'Complete your first course',
        'icon'        => 'graduation-cap',
        'earned_at'   => date('Y-m-d H:i:s', strtotime('-30 days')),
    ];
}
if ($completedCount >= 3) {
    $achievements[] = [
        'id'          => 2,
        'title'       => 'Course Collector',
        'description' => 'Complete 3 courses',
        'icon'        => 'trophy',
        'earned_at'   => date('Y-m-d H:i:s', strtotime('-15 days')),
    ];
}
if ($totalCourses >= 5) {
    $achievements[] = [
        'id'          => 3,
        'title'       => 'Dedicated Learner',
        'description' => 'Enroll in 5 courses',
        'icon'        => 'book',
        'earned_at'   => date('Y-m-d H:i:s', strtotime('-7 days')),
    ];
}

// ========== Upcoming deadlines (simulated) ==========
$upcomingDeadlines = [];
foreach (array_slice($continueLearning, 0, 3) as $item) {
    $upcomingDeadlines[] = [
        'title'  => 'Complete ' . $item['course']['title'],
        'course' => $item['course']['title'],
        'date'   => date('Y-m-d', strtotime('+' . rand(3, 14) . ' days')),
        'type'   => 'course_completion',
    ];
}

// The view now has all the variables it needs
$page_title   = "Dashboard - CodeMastery";
$current_page = 'dashboard';

require 'view/partial/nav.php';
require 'view/student/dashboard.php';
require 'view/partial/footer.php';