<?php
require_once 'includes/auth-functions.php';
require_once 'includes/init.php';
requireAdmin();

$user = getCurrentUser() ?? [];

// Get date range for analytics
$startDate = $_GET['start_date'] ?? date('Y-m-01'); // First day of current month
$endDate = $_GET['end_date'] ?? date('Y-m-t'); // Last day of current month
$period = $_GET['period'] ?? 'monthly'; // monthly, quarterly, yearly

// Get real platform analytics data from file storage
$analyticsData = getRealPlatformAnalytics($startDate, $endDate, $period);

$page_title = "Platform Analytics - Admin Panel";
$current_page = 'admin-analytics';

require 'view/partial/admin-header.php';
require 'view/admin/analytics.php';
require 'view/partial/admin-footer.php';
?>