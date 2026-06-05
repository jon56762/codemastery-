<?php
require_once 'includes/auth-functions.php';
require_once 'includes/init.php';
requireRole('instructor');

$user = getCurrentUser();           
$instructorId = $user['id'];

$courses = Course::getByInstructor($instructorId);
$totalEarned = 0;
$earningsByCourse = [];
$recentTransactions = [];
foreach ($courses as $course) {
    $enrollments = Enrollment::findByCourse($course->id);
    $courseRevenue = count($enrollments) * $course->price;
    $instructorEarnings = $courseRevenue * 0.7;
    $totalEarned += $instructorEarnings;
    $earningsByCourse[] = [
        'title'               => $course->title,
        'enrollments'         => count($enrollments),
        'total_revenue'       => $courseRevenue,
        'instructor_earnings'  => $instructorEarnings,
        'commission_rate'     => 70,
        'performance'         => 0 
    ];
}

$earningsData = [
    'available_balance'   => $totalEarned,   
    'pending_balance'     => 0,
    'total_earned'        => $totalEarned,
    'total_paid_out'      => 0,
    'recent_transactions' => $recentTransactions,
    'earnings_by_course'  => $earningsByCourse,
];

$payouts = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['request_payout'])) {
    $_SESSION['success'] = "Payout requested (placeholder).";
    header('Location: /instructor-earnings');
    exit;
}

$page_title = "Earnings - Instructor Panel";
$current_page = 'instructor-earnings';
require 'view/partial/instructor-header.php';
require 'view/instructor/earnings.php';