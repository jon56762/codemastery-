<?php
require_once 'includes/auth-functions.php';
require_once 'includes/function.php'; // Add this line
requireAuth();

$user = getCurrentUser();
$courseId = $_GET['course_id'] ?? null;
$lessonId = $_GET['lesson_id'] ?? null;

// Initialize messages
$success_message = $_SESSION['success'] ?? '';
$error_message = $_SESSION['error'] ?? '';
unset($_SESSION['success'], $_SESSION['error']); // Clear messages after displaying

if (!$courseId || !$lessonId) {
    $_SESSION['error'] = "Course or lesson not specified";
    header('Location: /my-courses');
    exit;
}

// Get course data using the function instead of manual file reading
$course = getCourseById($courseId);
if (!$course) {
    $_SESSION['error'] = "Course not found";
    header('Location: /my-courses');
    exit;
}

// Ensure curriculum exists
if (empty($course['curriculum'])) {
    $course['curriculum'] = generateSampleCurriculum($course);
}

// Get enrollment data using the function
$enrollment = isUserEnrolled($user['id'], $courseId);
if (!$enrollment) {
    // Enroll user if not already enrolled using the proper function
    $enrollment = enrollStudent($courseId, $user['id']);
    if (!$enrollment) {
        $_SESSION['error'] = "Error enrolling in course";
        header('Location: /my-courses');
        exit;
    }
    $success_message = "Successfully enrolled in the course!";
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
    $firstLesson = $course['curriculum'][0];
    header('Location: /course-player?course_id=' . $courseId . '&lesson_id=' . $firstLesson['id']);
    exit;
}

$isLessonCompleted = in_array($lessonId, $enrollment['completed_lessons'] ?? []);

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['mark_complete'])) {
        $action = $_POST['mark_complete'];
        $lessonId = $_POST['lesson_id'] ?? $lessonId;
        
        // Use the updateLessonProgress function instead of manual handling
        $result = updateLessonProgress($enrollment['id'], $lessonId, $action === 'complete');
        
        if ($result) {
            // Refresh enrollment data
            $enrollment = isUserEnrolled($user['id'], $courseId);
            $success_message = $action === 'complete' 
                ? "Lesson marked as completed! 🎉" 
                : "Lesson marked as incomplete.";
            
            // Check if course is completed
            if ($enrollment['progress'] == 100 && !isset($enrollment['completed_at'])) {
                $success_message = "🎉 Congratulations! You've completed the course!";
            }
        } else {
            $error_message = "Error saving progress. Please try again.";
        }
        
        header('Location: /course-player?course_id=' . $courseId . '&lesson_id=' . $lessonId);
        exit;
    }
    
    if (isset($_POST['save_note'])) {
        $content = trim($_POST['note_content'] ?? '');
        $timestamp = $_POST['note_timestamp'] ?? '00:00';
        
        if (!empty($content)) {
            // Use the saveLessonNote function
            $result = saveLessonNote($user['id'], $courseId, $lessonId, $content, $timestamp);
            if ($result) {
                $success_message = "Note saved successfully!";
            } else {
                $error_message = "Error saving note. Please try again.";
            }
        } else {
            $error_message = "Note content cannot be empty.";
        }
        
        header('Location: /course-player?course_id=' . $courseId . '&lesson_id=' . $lessonId);
        exit;
    }
    
    // Handle note deletion
    if (isset($_POST['delete_note'])) {
        $noteId = $_POST['delete_note'];
        $notes = getFromFile('lesson-notes.json'); // Use correct filename
        
        $updatedNotes = array_filter($notes, function($note) use ($noteId, $user) {
            return !($note['id'] == $noteId && $note['user_id'] == $user['id']);
        });
        
        if (saveToFile('lesson-notes.json', array_values($updatedNotes))) {
            $success_message = "Note deleted successfully!";
        } else {
            $error_message = "Error deleting note. Please try again.";
        }
        
        header('Location: /course-player?course_id=' . $courseId . '&lesson_id=' . $lessonId);
        exit;
    }
}

// Get lesson notes using the correct function
$lessonNotes = getLessonNotes($user['id'], $courseId, $lessonId);

$page_title = $currentLesson['title'] . " - " . $course['title'];
$current_page = 'course-player';

// Include the view
require 'view/partial/nav.php';
require 'view/student/course-player.php';
require 'view/partial/footer.php';
?>