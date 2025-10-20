<?php
require_once 'includes/auth-functions.php';
requireRole('instructor');

$user = getCurrentUser();
$instructorId = $user['id'];

// Get instructor's courses using your function
$courses = getCoursesByInstructor($instructorId);
$publishedCourses = array_filter($courses, function($course) {
    return $course['status'] === 'published';
});
$draftCourses = array_filter($courses, function($course) {
    return $course['status'] === 'draft';
});

// Get enrollments and revenue data using your functions
$enrollments = getAllEnrollments();
$instructorEnrollments = [];
$totalRevenue = 0;
$totalStudents = 0;

foreach ($enrollments as $enrollment) {
    $course = getCourseById($enrollment['course_id']);
    if ($course && $course['instructor_id'] == $instructorId) {
        $instructorEnrollments[] = $enrollment;
        $totalRevenue += $course['price'] * 0.7; // 70% commission
        $totalStudents++;
    }
}

// Calculate monthly revenue
$currentMonth = date('Y-m');
$monthlyRevenue = 0;
foreach ($instructorEnrollments as $enrollment) {
    $enrollmentMonth = date('Y-m', strtotime($enrollment['enrolled_at']));
    if ($enrollmentMonth === $currentMonth) {
        $course = getCourseById($enrollment['course_id']);
        $monthlyRevenue += $course['price'] * 0.7;
    }
}

// Get recent enrollments (last 5)
$recentEnrollments = array_slice(array_reverse($instructorEnrollments), 0, 5);

// Calculate average rating using your function
$totalRating = 0;
$ratedCourses = 0;
foreach ($publishedCourses as $course) {
    if (isset($course['rating']) && $course['rating'] > 0) {
        $totalRating += $course['rating'];
        $ratedCourses++;
    }
}
$averageRating = $ratedCourses > 0 ? round($totalRating / $ratedCourses, 1) : 0;

$page_title = "Instructor Dashboard - CodeMastery";
$current_page = 'instructor-dashboard';
require 'view/partial/instructor-header.php';
require 'view/instructor/dashboard.php';
require 'view/partial/footer.php';
?>