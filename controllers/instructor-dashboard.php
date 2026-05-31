<?php
require_once 'includes/init.php';
require_once 'includes/auth-functions.php';
requireRole('instructor');

$user = getCurrentUser();       
$instructorId = $user['id'];

$courseObjects = Course::getByInstructor($instructorId);
$courses = array_map(fn($c) => $c->toArray(), $courseObjects);

$publishedCourses = array_filter($courses, fn($c) => $c['status'] === 'published');
$draftCourses     = array_filter($courses, fn($c) => $c['status'] === 'draft');

$enrollmentObjects = [];
$totalRevenue = 0;
$totalStudents = 0;

foreach ($courseObjects as $courseObj) {
    $courseId    = $courseObj->getId();
    $coursePrice = $courseObj->price;
    $enrollmentsForCourse = Enrollment::findByCourse($courseId);
    foreach ($enrollmentsForCourse as $enrollmentObj) {
        $totalRevenue += $coursePrice * 0.7;
        $totalStudents++;
        $enrollmentObjects[] = [
            'user_id'      => $enrollmentObj->userId,
            'course_id'    => $courseId,
            'enrolled_at'  => $enrollmentObj->enrolledAt,
            'price'        => $coursePrice
        ];
    }
}

// Monthly revenue
$currentMonth = date('Y-m');
$monthlyRevenue = 0;
foreach ($enrollmentObjects as $e) {
    if (date('Y-m', strtotime($e['enrolled_at'])) === $currentMonth) {
        $monthlyRevenue += $e['price'] * 0.7;
    }
}

// Recent 5 enrollments
usort($enrollmentObjects, fn($a, $b) =>
    strtotime($b['enrolled_at']) <=> strtotime($a['enrolled_at'])
);
$lastFive = array_slice($enrollmentObjects, 0, 5);

$recentEnrollments = [];
foreach ($lastFive as $e) {
    $student = User::findById($e['user_id']);
    $course  = Course::findById($e['course_id']);
    $recentEnrollments[] = [
        'student_name' => $student ? $student->getName() : 'Unknown Student',
        'course_title' => $course ? $course->title : 'Unknown Course',
        'enrolled_at'  => $e['enrolled_at'],
        'revenue'      => $e['price'] * 0.7
    ];
}

// Average rating
$totalRating = 0;
$ratedCourses = 0;
foreach ($publishedCourses as $c) {
    if (!empty($c['rating'])) {
        $totalRating += $c['rating'];
        $ratedCourses++;
    }
}
$averageRating = $ratedCourses > 0 ? round($totalRating / $ratedCourses, 1) : 0;

// Data for the view
$page_title   = "Instructor Dashboard - CodeMastery";
$current_page = 'instructor-dashboard';

require 'view/partial/instructor-header.php';
require 'view/instructor/dashboard.php';
require 'view/partial/footer.php';