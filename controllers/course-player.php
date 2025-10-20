<?php
require_once 'includes/auth-functions.php';
requireAuth();

$user = getCurrentUser();
$courseId = $_GET['course_id'] ?? null;
$lessonId = $_GET['lesson_id'] ?? null;

if (!$courseId || !$lessonId) {
    header('Location: /my-courses');
    exit;
}

// Get course data
$courses = getFromFile('courses.json');
$course = null;
foreach ($courses as $c) {
    if ($c['id'] == $courseId) {
        $course = $c;
        break;
    }
}

if (!$course) {
    header('Location: /my-courses');
    exit;
}

// Get enrollment data
$enrollments = getFromFile('enrollments.json');
$enrollment = null;
foreach ($enrollments as $e) {
    if ($e['user_id'] == $user['id'] && $e['course_id'] == $courseId) {
        $enrollment = $e;
        break;
    }
}

if (!$enrollment) {
    // Enroll user if not already enrolled
    $enrollment = [
        'user_id' => $user['id'],
        'course_id' => $courseId,
        'progress' => 0,
        'completed_lessons' => [],
        'enrolled_at' => date('Y-m-d H:i:s')
    ];
    $enrollments[] = $enrollment;
    saveToFile('enrollments.json', $enrollments);
}

// Find current lesson and navigation
$currentLesson = null;
$prevLesson = null;
$nextLesson = null;

foreach ($course['curriculum'] as $index => $lesson) {
    if ($lesson['id'] == $lessonId) {
        $currentLesson = $lesson;
        if ($index > 0) $prevLesson = $course['curriculum'][$index - 1];
        if ($index < count($course['curriculum']) - 1) $nextLesson = $course['curriculum'][$index + 1];
        break;
    }
}

if (!$currentLesson) {
    // Redirect to first lesson if not found
    $currentLesson = $course['curriculum'][0];
    $lessonId = $currentLesson['id'];
}

$isLessonCompleted = in_array($lessonId, $enrollment['completed_lessons']);

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['mark_complete'])) {
        $action = $_POST['mark_complete'];
        $lessonId = $_POST['lesson_id'];
        
        if ($action === 'complete') {
            if (!in_array($lessonId, $enrollment['completed_lessons'])) {
                $enrollment['completed_lessons'][] = $lessonId;
            }
        } else {
            $key = array_search($lessonId, $enrollment['completed_lessons']);
            if ($key !== false) {
                unset($enrollment['completed_lessons'][$key]);
                $enrollment['completed_lessons'] = array_values($enrollment['completed_lessons']);
            }
        }
        
        // Update progress
        $totalLessons = count($course['curriculum']);
        $completedLessons = count($enrollment['completed_lessons']);
        $enrollment['progress'] = round(($completedLessons / $totalLessons) * 100);
        
        // Save enrollment
        foreach ($enrollments as &$e) {
            if ($e['user_id'] == $user['id'] && $e['course_id'] == $courseId) {
                $e = $enrollment;
                break;
            }
        }
        saveToFile('enrollments.json', $enrollments);
        
        header('Location: /course-player?course_id=' . $courseId . '&lesson_id=' . $lessonId);
        exit;
    }
    
    if (isset($_POST['save_note'])) {
        $notes = getFromFile('notes.json');
        $newNote = [
            'id' => uniqid(),
            'user_id' => $user['id'],
            'course_id' => $courseId,
            'lesson_id' => $lessonId,
            'content' => $_POST['note_content'],
            'timestamp' => $_POST['note_timestamp'],
            'created_at' => date('Y-m-d H:i:s')
        ];
        $notes[] = $newNote;
        saveToFile('notes.json', $notes);
        
        $_SESSION['success'] = "Note saved successfully!";
        header('Location: /course-player?course_id=' . $courseId . '&lesson_id=' . $lessonId);
        exit;
    }
}

// Get lesson notes
$notes = getFromFile('notes.json');
$lessonNotes = array_filter($notes, function($note) use ($user, $courseId, $lessonId) {
    return $note['user_id'] == $user['id'] && $note['course_id'] == $courseId && $note['lesson_id'] == $lessonId;
});

$page_title = $currentLesson['title'] . " - " . $course['title'];
$current_page = 'course-player';


require 'view/partial/nav.php';
require 'view/student/course-player.php';

?>