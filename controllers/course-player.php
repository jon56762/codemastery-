<?php
require_once 'includes/init.php';
require_once 'includes/auth-functions.php';
requireAuth();

$user = getCurrentUserObject();
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

$enrollment = Enrollment::findByUserAndCourse($user->getId(), $courseId);
if (!$enrollment && $user->getId() != $course->instructorId) {
    $_SESSION['error'] = "You are not enrolled in this course.";
    header('Location: /course/' . $courseId);
    exit;
}

$enrollmentArray = $enrollment ? $enrollment->toArray() : null;
if ($enrollmentArray) {
    $enrollmentArray['completed_lessons'] = $enrollment->completedLessons;
    $enrollmentArray['progress'] = $enrollment->progress;
}

$lessons = $course->curriculum; 
$currentLesson = null;
$currentLessonIndex = -1;
$nextLesson = null;
$prevLesson = null;

if ($lessonId) {
    foreach ($lessons as $index => $lesson) {
        if ($lesson['id'] == $lessonId) {
            $currentLesson = $lesson;
            $currentLessonIndex = $index;
            break;
        }
    }
}

if (!$currentLesson && !empty($lessons)) {
    $currentLesson = $lessons[0];
    $currentLessonIndex = 0;
    $lessonId = $currentLesson['id'];
}

if ($currentLessonIndex >= 0) {
    if ($currentLessonIndex > 0) {
        $prevLesson = $lessons[$currentLessonIndex - 1];
    }
    if ($currentLessonIndex < count($lessons) - 1) {
        $nextLesson = $lessons[$currentLessonIndex + 1];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mark_complete'])) {
    if ($enrollment) {
        $enrollment->toggleLesson($lessonId, true);
        $_SESSION['success'] = "Lesson marked as complete!";
        $enrollment = Enrollment::findByUserAndCourse($user->getId(), $courseId);
        $enrollmentArray = $enrollment->toArray();
        $enrollmentArray['completed_lessons'] = $enrollment->completedLessons;
        $enrollmentArray['progress'] = $enrollment->progress;
    }
}

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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_note'])) {
    $noteId = $_POST['note_id'] ?? null;
    if ($noteId) {
        $note = LessonNote::findById($noteId);
        if ($note && $note->userId == $user->getId()) {
            $note->delete();
            $_SESSION['success'] = "Note deleted.";
        } else {
            $_SESSION['error'] = "Could not delete note.";
        }
    }
}

$notes = [];
if ($lessonId) {
    $noteObjects = LessonNote::findByUserAndLesson($user->getId(), $courseId, $lessonId);
    $notes = array_map(fn($n) => $n->toArray(), $noteObjects);
}

$courseArray = $course->toArray();

$page_title = $currentLesson ? $currentLesson['title'] . " - " . $course->title : $course->title;
require 'view/partial/nav.php';
require 'view/student/course-player.php';
require 'view/partial/footer.php';