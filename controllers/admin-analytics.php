<?php
require_once 'includes/init.php';
require_once 'includes/auth-functions.php';
requireAdmin();
$analyticsData = null;
$startDate = $_GET['start_date'] ?? date('Y-m-01');
$endDate   = $_GET['end_date']   ?? date('Y-m-t');
$period    = $_GET['period'] ?? 'monthly';

// Collect real data using OOP
$allUsers = User::getAll();
$students = User::findByRole('student');
$instructors = User::findByRole('instructor');
$admins = User::findByRole('admin');
$totalUsers = count($allUsers);

$allCourses = Course::getAll();
$publishedCourses = array_filter($allCourses, fn($c) => $c->status === 'published');

$totalRevenue = 0;
$newEnrollments = 0;
foreach ($publishedCourses as $c) {
    $enrollments = Enrollment::findByCourse($c->id);
    $count = count($enrollments);
    $totalRevenue += $count * $c->price;
    $newEnrollments += $count;
}

$totalRating = 0;
$ratedCourses = 0;
foreach ($publishedCourses as $c) {
    if ($c->rating > 0) {
        $totalRating += $c->rating;
        $ratedCourses++;
    }
}
$avgRating = $ratedCourses > 0 ? round($totalRating / $ratedCourses, 1) : 0;

$analyticsData = [
    'total_revenue' => $totalRevenue,
    'revenue_growth' => 15,   // placeholder
    'new_users' => $totalUsers,
    'user_growth' => 12,
    'new_enrollments' => $newEnrollments,
    'enrollment_growth' => 18,
    'avg_rating' => $avgRating,
    'total_reviews' => $ratedCourses,
    'user_distribution' => [
        'students' => count($students),
        'instructors' => count($instructors),
        'admins' => count($admins),
        'student_percent' => $totalUsers ? round(count($students) / $totalUsers * 100) : 0,
        'instructor_percent' => $totalUsers ? round(count($instructors) / $totalUsers * 100) : 0,
        'admin_percent' => $totalUsers ? round(count($admins) / $totalUsers * 100) : 0,
    ],
    'revenue_trends' => [],   // you can fill later
    'top_courses' => array_map(fn($c) => [
        'title' => $c->title,
        'revenue' => count(Enrollment::findByCourse($c->id)) * $c->price,
        'rating' => $c->rating
    ], array_slice($publishedCourses, 0, 3)),
    'detailed_metrics' => [],
];

$page_title = "Platform Analytics - Admin";
$current_page = 'admin-analytics';

require 'view/partial/admin-header.php';
require 'view/admin/analytics.php';
require 'view/partial/admin-footer.php';
?>