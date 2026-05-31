<?php
require_once 'includes/init.php';
require_once 'includes/auth-functions.php';
requireAdmin();

$user = getCurrentUser();         

$platformStats = getPlatformStats();   

$pendingApplications = count(array_filter(InstructorApplication::getAll(), fn($a) => $a->status === 'pending'));
$pendingTestimonials  = count(array_filter(Testimonial::getAll(), fn($t) => $t->status === 'pending'));
$pendingCourses       = count(array_filter(Course::getAll(), fn($c) => $c->status === 'pending'));
$pendingBlogPosts     = count(BlogPost::getByStatus('pending'));

$page_title = "Admin Dashboard - CodeMastery";
$current_page = 'admin-dashboard';

require 'view/partial/admin-header.php';
require 'view/admin/dashboard.php';
require 'view/partial/admin-footer.php';