<?php
require_once 'includes/auth-functions.php';
require_once 'includes/function.php';
requireAdmin();

$user = getCurrentUser();

// Get platform statistics
$platformStats = getPlatformStats();
$pendingApplications = getPendingApplicationsCount();
$pendingTestimonials = getPendingTestimonialsCount();
$pendingCourses = getPendingCoursesCount();

// Get pending counts
$pendingApplications = getPendingApplicationsCount();
$pendingTestimonials = getPendingTestimonialsCount();
$pendingCourses = getPendingCoursesCount();
$pendingBlogPosts = count(getBlogPostsByStatus('pending'));

// Get recent activities
$recentActivities = getRecentAdminActivities();

$page_title = "Admin Dashboard - CodeMastery";
$current_page = 'admin-dashboard';

require 'view/partial/admin-header.php';
require 'view/admin/dashboard.php';
require 'view/partial/admin-footer.php';
?>