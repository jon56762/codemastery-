<?php
require_once 'includes/init.php';
require_once 'includes/auth-functions.php';
requireAdmin();

$adminUser = getCurrentUser() ?? [];

$commissionRate = 30;
$db = Database::getConnection();
$result = $db->query("SELECT setting_value FROM settings WHERE setting_key = 'commission_rate'");
if ($result && $row = $result->fetch_assoc()) {
    $commissionRate = floatval($row['setting_value']);
}

$totalRevenue = 0;
$courses = Course::getAll();   

foreach ($courses as $course) {
    $enrollments = Enrollment::findByCourse($course->getId());
    $totalRevenue += count($enrollments) * $course->price;
}

$platformEarnings = $totalRevenue * ($commissionRate / 100);
$pendingPayoutsTotal = $totalRevenue - $platformEarnings;   
$revenueByCategory = [];
$categoryColors = ['#007bff', '#28a745', '#dc3545', '#ffc107', '#6f42c1', '#17a2b8'];

foreach ($courses as $course) {
    if (empty($course->category)) continue;
    $cat = $course->category;
    if (!isset($revenueByCategory[$cat])) {
        $revenueByCategory[$cat] = 0;
    }
    $enrollments = Enrollment::findByCourse($course->getId());
    $revenueByCategory[$cat] += count($enrollments) * $course->price;
}

$revenueByCategoryArray = [];
$i = 0;
foreach ($revenueByCategory as $cat => $rev) {
    $percentage = $totalRevenue > 0 ? round(($rev / $totalRevenue) * 100) : 0;
    $revenueByCategoryArray[] = [
        'name'       => $cat,
        'revenue'    => $rev,
        'percentage' => $percentage,
        'color'      => $categoryColors[$i % count($categoryColors)]
    ];
    $i++;
}

$monthlyTrends = [];
for ($i = 5; $i >= 0; $i--) {
    $month = date('Y-m', strtotime("-$i months"));
    $monthlyTrends[$month] = 0;
}

foreach ($courses as $course) {
    $enrollments = Enrollment::findByCourse($course->getId());
    foreach ($enrollments as $enrollment) {
        $enrollMonth = date('Y-m', strtotime($enrollment->enrolledAt));
        if (isset($monthlyTrends[$enrollMonth])) {
            $monthlyTrends[$enrollMonth] += $course->price;
        }
    }
}

$monthlyTrendsFormatted = [];
$monthNames = [
    1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June',
    7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
];
foreach ($monthlyTrends as $month => $rev) {
    [$y, $m] = explode('-', $month);
    $monthName = $monthNames[(int)$m] . ' ' . $y;
    $monthlyTrendsFormatted[] = [
        'month'   => $monthName,
        'revenue' => $rev,
        'growth'  => 0  
    ];
}

$pendingPayouts = [];   

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_commission'])) {
        $newRate = floatval($_POST['commission_rate'] ?? 30);
        $stmt = $db->prepare("INSERT INTO settings (setting_key, setting_value) VALUES ('commission_rate', ?) ON DUPLICATE KEY UPDATE setting_value = ?");
        $stmt->bind_param("ss", $newRate, $newRate);
        $stmt->execute();
        $_SESSION['success'] = "Commission rate updated.";
    } else {
        $_SESSION['success'] = "Revenue action processed.";
    }
    header('Location: /admin-revenue');
    exit;
}

$revenueData = [
    'total_revenue'       => $totalRevenue,
    'platform_earnings'   => $platformEarnings,
    'pending_payouts'     => $pendingPayoutsTotal,
    'commission_rate'     => $commissionRate,
    'revenue_by_category' => $revenueByCategoryArray,
    'monthly_trends'      => $monthlyTrendsFormatted,
];

$page_title = "Revenue Management - Admin Panel";
$current_page = 'admin-revenue';

require 'view/partial/admin-header.php';
require 'view/admin/revenue.php';
require 'view/partial/admin-footer.php';