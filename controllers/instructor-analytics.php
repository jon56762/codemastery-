<?php
require_once 'includes/auth-functions.php';
require_once 'includes/function.php';
requireInstructor();

$user = getCurrentUser();

// Get date range for analytics (default: last 30 days)
$startDate = $_GET['start_date'] ?? date('Y-m-d', strtotime('-30 days'));
$endDate = $_GET['end_date'] ?? date('Y-m-d');

// Get instructor analytics data
$analyticsData = getInstructorAnalytics($user['id'], $startDate, $endDate);

$page_title = "Analytics - Instructor Panel";
$current_page = 'instructor-analytics';

require 'view/partial/instructor-header.php';
require 'view/instructor/analytics.php';
?>