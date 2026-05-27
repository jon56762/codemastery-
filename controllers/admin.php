<?php
require_once 'includes/init.php';
require_once 'includes/auth-functions.php';
requireAdmin();

// Platform stats using OOP
$platformStats = [
    'total_students'    => count(User::findByRole('student')),
    'total_courses'     => count(Course::getPublished()),
    'total_instructors' => count(User::findByRole('instructor')),
    'total_enrollments' => 0, 
    'average_rating'    => 4.5,
];
$pendingApplications = count(InstructorApplication::getAll()); 
$pendingTestimonials  = count(Testimonial::getApproved());    
$pendingCourses       = count(Course::getAll()) - count(Course::getPublished()); 
$pendingBlogPosts     = count(BlogPost::getByStatus('pending'));

$recentActivities = []; 

$page_title = "Admin Dashboard - CodeMastery";
$current_page = 'admin-dashboard';

require 'view/partial/admin-header.php';
require 'view/admin/dashboard.php';
require 'view/partial/admin-footer.php';