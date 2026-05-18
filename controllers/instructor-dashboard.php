<?php
require_once 'includes/init.php';
require_once 'includes/auth-functions.php';
requireRole('instructor');

$user = getCurrentUser();
$instructorId = $user['id'];

// Get instructor's courses using your function
// $courses = getCoursesByInstructor($instructorId);
// $publishedCourses = array_filter($courses, function($course) {
//     return $course['status'] === 'published';
// });
// $draftCourses = array_filter($courses, function($course) {
//     return $course['status'] === 'draft';
// });

// // Get enrollments and revenue data using your functions
// $enrollments = getAllEnrollments();
// $instructorEnrollments = [];
// $totalRevenue = 0;
// $totalStudents = 0;

// foreach ($enrollments as $enrollment) {
//     $course = getCourseById($enrollment['course_id']);
//     if ($course && $course['instructor_id'] == $instructorId) {
//         $instructorEnrollments[] = $enrollment;
//         $totalRevenue += $course['price'] * 0.7; // 70% commission
//         $totalStudents++;
//     }
// }

// // Calculate monthly revenue
// $currentMonth = date('Y-m');
// $monthlyRevenue = 0;
// foreach ($instructorEnrollments as $enrollment) {
//     $enrollmentMonth = date('Y-m', strtotime($enrollment['enrolled_at']));
//     if ($enrollmentMonth === $currentMonth) {
//         $course = getCourseById($enrollment['course_id']);
//         $monthlyRevenue += $course['price'] * 0.7;
//     }
// }

// // Get recent enrollments (last 5)
// $recentEnrollments = array_slice(array_reverse($instructorEnrollments), 0, 5);

// // Calculate average rating using your function
// $totalRating = 0;
// $ratedCourses = 0;
// foreach ($publishedCourses as $course) {
//     if (isset($course['rating']) && $course['rating'] > 0) {
//         $totalRating += $course['rating'];
//         $ratedCourses++;
//     }
// }
// $averageRating = $ratedCourses > 0 ? round($totalRating / $ratedCourses, 1) : 0;

$courseObjects = Course::getByInstructor($instructorId);
$courses = array_map(function($c) {
    return $c->toArray();            // convert each Course object to array
}, $courseObjects);

// Filter by status (now on arrays)
$publishedCourses = array_filter($courses, function($c) {
    return $c['status'] === 'published';
});
$draftCourses = array_filter($courses, function($c) {
    return $c['status'] === 'draft';
});

// ---- Build enrollment & revenue data ----
$enrollmentObjects = [];
$totalRevenue = 0;
$totalStudents = 0;

foreach ($courseObjects as $courseObj) {
    $courseId = $courseObj->id;
    $coursePrice = $courseObj->price;
    $enrollmentsForCourse = Enrollment::findByCourse($courseId);
    foreach ($enrollmentsForCourse as $enrollmentObj) {
        $totalRevenue += $coursePrice * 0.7;
        $totalStudents++;
        // Store as array for later sorting/use
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

// Recent 5 enrollments with student names (NEW ARRAY with proper keys)
usort($enrollmentObjects, function($a, $b) {
    return strtotime($b['enrolled_at']) <=> strtotime($a['enrolled_at']);
});
$lastFive = array_slice($enrollmentObjects, 0, 5);

$recentEnrollments = [];
foreach ($lastFive as $e) {
    $student = User::findById($e['user_id']);
    $course = Course::findById($e['course_id']);
    $recentEnrollments[] = [
        'student_name' => $student ? $student->name : 'Unknown Student',
        'course_title' => $course ? $course->title : 'Unknown Course',
        'enrolled_at'  => $e['enrolled_at'],
        'revenue'      => $e['price'] * 0.7
    ];
}

// Average rating (still from arrays)
$totalRating = 0;
$ratedCourses = 0;
foreach ($publishedCourses as $c) {
    if (!empty($c['rating'])) {
        $totalRating += $c['rating'];
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