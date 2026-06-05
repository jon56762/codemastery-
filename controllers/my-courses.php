<?php
require_once 'includes/init.php';
require_once 'includes/auth-functions.php';
requireAuth();

$user = getCurrentUserObject();
$userId = $user->getId();

$enrollments = Enrollment::findByUser($userId);

$enrolledCourses = [];
foreach ($enrollments as $enrollment) {
    $course = Course::findById($enrollment->courseId);
    if ($course && $course->status === 'published') {
        $enrolledCourses[] = [
            'course' => $course->toArray(),
            'enrollment' => $enrollment->toArray()
        ];
    }
}

$search = $_GET['search'] ?? '';
$filter = $_GET['filter'] ?? 'all';
$sort = $_GET['sort'] ?? 'recent';

$filteredCourses = array_filter($enrolledCourses, function($item) use ($filter) {
    $progress = $item['enrollment']['progress'] ?? 0;
    if ($filter === 'completed') return $progress >= 100;
    if ($filter === 'in-progress') return $progress > 0 && $progress < 100;
    if ($filter === 'not-started') return $progress == 0;
    return true;
});

if ($search) {
    $filteredCourses = array_filter($filteredCourses, function($item) use ($search) {
        return stripos($item['course']['title'], $search) !== false ||
               stripos($item['course']['description'], $search) !== false;
    });
}


usort($filteredCourses, function($a, $b) use ($sort) {
    if ($sort === 'progress') {
        return ($b['enrollment']['progress'] ?? 0) <=> ($a['enrollment']['progress'] ?? 0);
    }
    if ($sort === 'title') {
        return strcmp($a['course']['title'], $b['course']['title']);
    }
    return strtotime($b['enrollment']['enrolled_at'] ?? 'now') <=> strtotime($a['enrollment']['enrolled_at'] ?? 'now');
});

$wishlist = []; 

$page_title = "My Courses - CodeMastery";
$current_page = 'my-courses';
require 'view/partial/nav.php';
require 'view/student/my-courses.php';
require 'view/partial/footer.php';