<?php
require_once 'includes/init.php';
require_once 'includes/auth-functions.php';
requireAuth();

$user = getCurrentUserObject();   // returns User object
$courseId = $_GET['course_id'] ?? null;
$lessonId = $_GET['lesson_id'] ?? null;

if (!$courseId) {
    $_SESSION['error'] = "Course not found.";
    header('Location: /my-courses');
    exit;
}

$course = Course::findById($courseId);
if (!$course) {
    $_SESSION['error'] = "Course not found.";
    header('Location: /my-courses');
    exit;
}

// Check enrollment (or if instructor)
$enrollment = Enrollment::findByUserAndCourse($user->getId(), $courseId);
if (!$enrollment && $user->getId() != $course->instructorId) {
    $_SESSION['error'] = "You are not enrolled in this course.";
    header('Location: /course/' . $courseId);
    exit;
}

$lessons = $course->curriculum;
$currentLesson = null;
$currentLessonIndex = -1;
$nextLesson = null;
$prevLesson = null;

// Find the requested lesson
if ($lessonId) {
    foreach ($lessons as $index => $lesson) {
        if ($lesson['id'] == $lessonId) {
            $currentLesson = $lesson;
            $currentLessonIndex = $index;
            break;
        }
    }
}

// Default to first lesson
if (!$currentLesson && !empty($lessons)) {
    $currentLesson = $lessons[0];
    $currentLessonIndex = 0;
    $lessonId = $currentLesson['id'];
}

// Previous / next lesson
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
        $enrollment->toggleLesson($lessonId, true);
        $_SESSION['success'] = "Lesson marked as complete!";
        // Reload enrollment to reflect updated progress
        $enrollment = Enrollment::findByUserAndCourse($user->getId(), $courseId);
    }
}

// Handle note saving (now using LessonNote class)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_note'])) {
    $noteContent = trim($_POST['note_content'] ?? '');
    $timestamp   = $_POST['timestamp'] ?? '00:00';

    if (!empty($noteContent)) {
        $note = LessonNote::create([
            'user_id'    => $user->getId(),
            'course_id'  => $courseId,
            'lesson_id'  => $lessonId,
            'content'    => $noteContent,
            'timestamp'  => $timestamp
        ]);
        if ($note) {
            $_SESSION['success'] = "Note saved successfully!";
        } else {
            $_SESSION['error'] = "Failed to save note.";
        }
    }
}

// Handle note deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_note'])) {
    $noteId = $_POST['note_id'] ?? null;
    if ($noteId) {
        $note = LessonNote::findById($noteId);
        // Only allow the owner to delete
        if ($note && $note->userId == $user->getId()) {
            $note->delete();
            $_SESSION['success'] = "Note deleted.";
        } else {
            $_SESSION['error'] = "Could not delete note.";
        }
    }
}

// Get notes for this lesson (using new class)
$notes = [];
if ($lessonId) {
    $notes = LessonNote::findByUserAndLesson($user->getId(), $courseId, $lessonId);
}

$page_title = $currentLesson ? $currentLesson['title'] . " - " . $course->title : $course->title;
require 'view/partial/nav.php';
require 'view/student/course-player.php';
require 'view/partial/footer.php';