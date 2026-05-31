<?php
require_once 'includes/auth-functions.php';
require_once 'includes/init.php';
requireRole('instructor');

$user = getCurrentUser();           
$instructorId = $user['id'];

$courses = Course::getByInstructor($instructorId);
$totalEarned = 0;
$earningsByCourse = [];
foreach ($courses as $course) {
    $enrollments = Enrollment::findByCourse($course->getId());
    $courseRevenue = count($enrollments) * $course->price;
    $instructorEarnings = $courseRevenue * 0.7;
    $totalEarned += $instructorEarnings;
    $earningsByCourse[] = [
        'title'       => $course->title,
        'revenue'     => $courseRevenue,
        'instructor_earnings' => $instructorEarnings,
    ];
}

$earningsData = [
    'available_balance' => $totalEarned,
    'total_earned'      => $totalEarned,
    'earnings_by_course'=> $earningsByCourse,
];
$payouts = []; // placeholder

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['request_payout'])) {
    $_SESSION['success'] = "Payout requested (placeholder).";
    header('Location: /instructor-earnings');
    exit;
}

$page_title = "Earnings - Instructor Panel";
$current_page = 'instructor-earnings';
require 'view/partial/instructor-header.php';
require 'view/instructor/earnings.php';