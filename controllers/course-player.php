<?php
require_once 'includes/auth-functions.php';
requireAuth();

$user = getCurrentUser();
$courseId = $_GET['course_id'] ?? null;
$lessonId = $_GET['lesson_id'] ?? null;

if (!$courseId) {
    $_SESSION['error'] = "Course not found.";
    header('Location: /my-courses');
    exit;
}

$course = getCourseById($courseId);
if (!$course) {
    $_SESSION['error'] = "Course not found.";
    header('Location: /my-courses');
    exit;
}

// Check if user is enrolled
$enrollment = isUserEnrolled($user['id'], $courseId);
if (!$enrollment && $user['id'] != $course['instructor_id']) {
    $_SESSION['error'] = "You are not enrolled in this course.";
    header('Location: /course/' . $courseId);
    exit;
}

// Get all lessons
$lessons = $course['curriculum'] ?? [];
$currentLesson = null;
$currentLessonIndex = -1;
$nextLesson = null;
$prevLesson = null;

// Find current lesson and set up navigation
if ($lessonId) {
    foreach ($lessons as $index => $lesson) {
        if ($lesson['id'] == $lessonId) {
            $currentLesson = $lesson;
            $currentLessonIndex = $index;
            break;
        }
    }
}

// If no specific lesson provided, show first lesson
if (!$currentLesson && !empty($lessons)) {
    $currentLesson = $lessons[0];
    $currentLessonIndex = 0;
    $lessonId = $currentLesson['id'];
}

// Set up next/previous lessons
if ($currentLessonIndex >= 0) {
    if ($currentLessonIndex > 0) {
        $prevLesson = $lessons[$currentLessonIndex - 1];
    }
    if ($currentLessonIndex < count($lessons) - 1) {
        $nextLesson = $lessons[$currentLessonIndex + 1];
    }
}

// Handle lesson completion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mark_complete'])) {
    if ($enrollment) {
        $result = updateLessonProgress($enrollment['id'], $lessonId, true);
        if ($result) {
            $_SESSION['success'] = "Lesson marked as complete!";
            // Refresh enrollment data
            $enrollment = getEnrollmentById($enrollment['id']);
        } else {
            $_SESSION['error'] = "Failed to update progress.";
        }
    }
}

// Handle note saving
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_note'])) {
    $noteContent = $_POST['note_content'] ?? '';
    $timestamp = $_POST['timestamp'] ?? '00:00';
    
    if (!empty($noteContent)) {
        $result = saveLessonNote($user['id'], $courseId, $lessonId, $noteContent, $timestamp);
        if ($result) {
            $_SESSION['success'] = "Note saved successfully!";
        } else {
            $_SESSION['error'] = "Failed to save note.";
        }
    }
}

// Handle note deletion - ADD THIS SECTION
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_note'])) {
    $noteId = $_POST['note_id'] ?? null;
    
    if ($noteId) {
        $result = deleteLessonNote($noteId, $user['id']);
        if ($result) {
            $_SESSION['success'] = "Note deleted successfully!";
        } else {
            $_SESSION['error'] = "Failed to delete note.";
        }
    }
}

// Get lesson notes
$notes = [];
if ($lessonId) {
    $notes = getLessonNotes($user['id'], $courseId, $lessonId);
}

$page_title = $currentLesson ? $currentLesson['title'] . " - " . $course['title'] : $course['title'];
require 'view/partial/nav.php';
require 'view/student/course-player.php';
require 'view/partial/footer.php';
?>