<?php
require_once 'includes/init.php';
require_once 'includes/auth-functions.php';
requireAuth();

$user = getCurrentUserObject();
if (!$user || $user->role !== 'student') {
    $_SESSION['error'] = "You don't have permission to access this page.";
    header('Location: /');
    exit;
}

$userId = $user->getId();

$enrollmentObjects = Enrollment::findByUser($userId);

$enrolledCourses = [];
foreach ($enrollmentObjects as $enrollment) {
    $course = Course::findById($enrollment->courseId);
    if ($course) {
        $enrolledCourses[] = [
            'course'     => $course->toArray(),
            'enrollment' => $enrollment->toArray()
        ];
    }
}

$filter = $_GET['filter'] ?? 'all';
$sort   = $_GET['sort']   ?? 'recent';

$filteredCourses = $enrolledCourses;
if ($filter === 'completed') {
    $filteredCourses = array_filter($filteredCourses, fn($item) => $item['enrollment']['progress'] >= 100);
} elseif ($filter === 'in-progress') {
    $filteredCourses = array_filter($filteredCourses, fn($item) => $item['enrollment']['progress'] > 0 && $item['enrollment']['progress'] < 100);
} elseif ($filter === 'not-started') {
    $filteredCourses = array_filter($filteredCourses, fn($item) => $item['enrollment']['progress'] == 0);
}

usort($filteredCourses, function($a, $b) use ($sort) {
    switch ($sort) {
        case 'progress': return $b['enrollment']['progress'] <=> $a['enrollment']['progress'];
        case 'title':    return $a['course']['title'] <=> $b['course']['title'];
        default:         return strtotime($b['enrollment']['enrolled_at']) <=> strtotime($a['enrollment']['enrolled_at']);
    }
});

$search = $_GET['search'] ?? '';
if ($search) {
    $filteredCourses = array_filter($filteredCourses, fn($item) =>
        stripos($item['course']['title'], $search) !== false ||
        stripos($item['course']['description'], $search) !== false
    );
}

$wishlist = []; 

$page_title = "My Courses - CodeMastery";
$current_page = 'my-courses';
require 'view/partial/nav.php';
require 'view/student/my-courses.php';
require 'view/partial/footer.php';