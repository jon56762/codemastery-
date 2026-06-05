<?php
require_once 'includes/init.php';
require_once 'includes/auth-functions.php';
requireRole('instructor');

$user = getCurrentUser();            // array for the header / view
$userObj = getCurrentUserObject();   // object for database operations
$instructorId = $userObj->id;        // use the public id property

// ---- Get instructor's courses as objects ----
$courseObjects = Course::getByInstructor($instructorId);

// ---- Convert to arrays (the view expects arrays) ----
$courses = array_map(fn($c) => $c->toArray(), $courseObjects);

// Published / draft counts
$publishedCourses = array_filter($courses, fn($c) => $c['status'] === 'published');
$draftCourses     = array_filter($courses, fn($c) => $c['status'] === 'draft');

// ---- Enrollment & revenue data ----
$enrollmentObjects = [];
$totalRevenue = 0;
$totalStudents = 0;

foreach ($courseObjects as $courseObj) {
    $courseId    = $courseObj->id;            // use ->id
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

// Recent 5 enrollments with student names
usort($enrollmentObjects, fn($a, $b) =>
    strtotime($b['enrolled_at']) <=> strtotime($a['enrolled_at'])
);
$lastFive = array_slice($enrollmentObjects, 0, 5);

$recentEnrollments = [];
foreach ($lastFive as $e) {
    $student = User::findById($e['user_id']);
    $course  = Course::findById($e['course_id']);
    $recentEnrollments[] = [
        'student_name' => $student ? $student->name : 'Unknown Student',   // use ->name
        'course_title' => $course ? $course->title : 'Unknown Course',
        'enrolled_at'  => $e['enrolled_at'],
        'revenue'      => $e['price'] * 0.7
    ];
}

$totalRating = 0;
$ratedCourses = 0;
foreach ($publishedCourses as $c) {
    if (!empty($c['rating'])) {
        $totalRating += $c['rating'];
        $ratedCourses++;
    }
}
$averageRating = $ratedCourses > 0 ? round($totalRating / $ratedCourses, 1) : 0;

$courses = array_map(function($c) {
    $data = $c->toArray();
    $data['course_count'] = count(Enrollment::findByCourse($c->id));
    return $data;
}, $courseObjects);

$page_title   = "Instructor Dashboard - CodeMastery";
$current_page = 'instructor-dashboard';

require 'view/partial/instructor-header.php';
require 'view/instructor/dashboard.php';
require 'view/partial/footer.php';