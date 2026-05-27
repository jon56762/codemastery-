<?php
require_once 'includes/init.php';
require_once 'includes/auth-functions.php';
requireAuth();

$user = getCurrentUserObject();
$courseId = $_GET['id'] ?? null;

if (!$courseId) { header('Location: /courses'); exit; }

$course = Course::findById($courseId);
if (!$course) { header('Location: /courses'); exit; }

$enrollment = Enrollment::findByUserAndCourse($user->getId(), $courseId);
$isEnrolled = $enrollment ? true : false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enroll'])) {
    $enroll = Enrollment::enroll($user->getId(), $courseId);
    if ($enroll) {
        $_SESSION['success'] = "Enrolled!";
        header('Location: /course-player?course_id=' . $courseId . '&lesson_id=1');
        exit;
    } else {
        $_SESSION['error'] = "Already enrolled or an error occurred.";
        header('Location: /course/' . $courseId);
        exit;
    }
}

// Related courses
$related = array_filter(Course::getPublished(), fn($c) => $c->category === $course->category && $c->id != $courseId);
$related = array_slice($related, 0, 3);
$relatedArray = array_map(fn($c) => $c->toArray(), $related);

$page_title = $course->title . ' - CodeMastery';
$current_page = 'course-detail';
require 'view/partial/nav.php';
require 'view/course-detail.php';
require 'view/partial/footer.php';