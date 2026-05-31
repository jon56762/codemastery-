<?php
require_once 'includes/auth-functions.php';
require_once 'includes/init.php';
requireRole('instructor');

$user = getCurrentUser();
$instructorId = $user['id'];

$courses = Course::getByInstructor($instructorId);
$totalStudents = 0;
$totalRevenue = 0;
$coursePerformance = [];

foreach ($courses as $course) {
    $enrollments = Enrollment::findByCourse($course->getId());
    $totalStudents += count($enrollments);
    $totalRevenue  += count($enrollments) * $course->price * 0.7;
    $coursePerformance[] = [
        'title'       => $course->title,
        'enrollments' => count($enrollments),
        'revenue'     => count($enrollments) * $course->price * 0.7,
        'trend'       => 'up',
        'change'      => 0,
        'status'      => $course->status,
        'completion_rate' => 0,
        'rating'         => $course->rating ?: 0,
        'new_enrollments' => 0,
        'satisfaction'    => 0
    ];

    $analyticsData = [
        'total_revenue'      => $totalRevenue,
        'revenue_change'     => 0,
        'new_students'       => $totalStudents,
        'student_change'     => 0,
        'completion_rate'    => 0,
        'avg_rating'         => 4.5,
        'total_reviews'      => 0,
        'active_students_rate'    => 0,
        'lesson_completion_rate'  => 0,
        'assignment_submission_rate' => 0,
        'course_performance'       => $coursePerformance,
        'top_courses'              => $coursePerformance,
        'detailed_analytics'       => $coursePerformance
    ];
}

$page_title = "Analytics - Instructor Panel";
$current_page = 'instructor-analytics';
require 'view/partial/instructor-header.php';
require 'view/instructor/analytics.php';
