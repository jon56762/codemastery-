<?php
require_once 'includes/init.php';
require_once 'includes/auth-functions.php';
requireAuth();

$user = getCurrentUserObject();    
if (!$user) {
    header('Location: /login');
    exit;
}

$userId = $user->getId();
$studentName = $user->getName();

$enrollments = Enrollment::findByUser($userId);

$certificates = [];
foreach ($enrollments as $enrollment) {
    if ($enrollment->progress >= 100) {
        $course = Course::findById($enrollment->courseId);
        if ($course) {
            $certificates[] = [
                'id'             => uniqid(),
                'certificate_id' => 'CM' . strtoupper(uniqid()),
                'course_id'      => $course->getId(),
                'course_title'   => $course->title,
                'issued_date'    => $enrollment->enrolledAt,   
                'student_name'   => $studentName
            ];
        }
    }
}

$completedCount = count(array_filter($enrollments, fn($e) => $e->progress >= 100));
$totalCourses = count($enrollments);
$achievements = [];
if ($completedCount >= 1) {
    $achievements[] = [
        'id'          => 1,
        'title'       => 'First Steps',
        'description' => 'Complete your first course',
        'icon'        => 'graduation-cap',
        'earned_at'   => date('Y-m-d H:i:s', strtotime('-30 days'))
    ];
}
if ($completedCount >= 3) {
    $achievements[] = [
        'id'          => 2,
        'title'       => 'Course Collector',
        'description' => 'Complete 3 courses',
        'icon'        => 'trophy',
        'earned_at'   => date('Y-m-d H:i:s', strtotime('-15 days'))
    ];
}
if ($totalCourses >= 5) {
    $achievements[] = [
        'id'          => 3,
        'title'       => 'Dedicated Learner',
        'description' => 'Enroll in 5 courses',
        'icon'        => 'book',
        'earned_at'   => date('Y-m-d H:i:s', strtotime('-7 days'))
    ];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['download_certificate'])) {
        $_SESSION['success'] = "Certificate downloaded (placeholder).";
    } elseif (isset($_POST['share_certificate'])) {
        $_SESSION['success'] = "Certificate shared (placeholder).";
    }
    header('Location: /certificates');
    exit;
}

$page_title = "My Certificates - CodeMastery";
$current_page = 'certificates';
require 'view/partial/nav.php';
require 'view/student/certificates.php';
require 'view/partial/footer.php';