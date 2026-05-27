<?php
require_once 'includes/init.php';
require_once 'includes/auth-functions.php';
requireRole('instructor');

$user = getCurrentUserObject();   // returns User object (or null)
$courseId = $_GET['course_id'] ?? null;
$course = null;

// Load existing course
if ($courseId) {
    $course = Course::findById($courseId);
    if (!$course || $course->instructorId != $user->getId()) {
        $_SESSION['error'] = "Course not found or you don't have permission to edit it.";
        header('Location: /instructor-courses');
        exit;
    }
}

// Resource upload helper (unchanged)
function handleResourceUpload($courseId, $fieldName) { ... } // keep as is

// Handle ALL form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 1. Course Information Update
    if (isset($_POST['update_course'])) {
        $courseData = [
            'title'            => trim($_POST['title']),
            'description'      => trim($_POST['description']),
            'short_description'=> trim($_POST['short_description']),
            'price'            => floatval($_POST['price']),
            'category'         => $_POST['category'],
            'level'            => $_POST['level'],
            'thumbnail'        => $_POST['thumbnail'] ?? '/assets/images/courses/default.jpg'
        ];

        if (empty($courseData['title']) || empty($courseData['description']) || empty($courseData['category'])) {
            $_SESSION['error'] = "Please fill in all required fields.";
        } else {
            if ($courseId) {
                // Update existing course (object properties)
                $course->title           = $courseData['title'];
                $course->description     = $courseData['description'];
                $course->shortDescription = $courseData['short_description'];
                $course->price           = $courseData['price'];
                $course->category        = $courseData['category'];
                $course->level           = $courseData['level'];
                $course->thumbnail       = $courseData['thumbnail'];
                $result = $course->save();
                $_SESSION['success'] = $result ? "Course updated successfully!" : "Failed to update course.";
            } else {
                // Create new course
                $courseData['instructor_id']   = $user->getId();
                $courseData['instructor_name'] = $user->getName();
                $courseData['status']          = 'draft';
                $courseData['curriculum']      = [];
                $course = Course::create($courseData);
                if ($course) {
                    $_SESSION['success'] = "Course created successfully!";
                    header('Location: /course-builder?course_id=' . $course->getId());
                    exit;
                } else {
                    $_SESSION['error'] = "Failed to create course.";
                }
            }
        }
    }

    // 2. Publish/Unpublish
    if (isset($_POST['update_status'])) {
        $newStatus = $_POST['status'];
        $course->status = $newStatus;
        $result = $course->save();
        $_SESSION['success'] = $result ? "Course " . ($newStatus === 'published' ? 'published' : 'unpublished') . "!" : "Failed.";
        header('Location: /course-builder?course_id=' . $courseId);
        exit;
    }

    // 3. Delete Course
    if (isset($_POST['delete_course'])) {
        if ($course->delete()) {
            $_SESSION['success'] = "Course deleted!";
            header('Location: /instructor-courses');
            exit;
        } else {
            $_SESSION['error'] = "Failed to delete course.";
        }
    }

    // 4. Add New Lesson
    if (isset($_POST['add_lesson'])) {
        $lessonData = [
            'id'          => uniqid(),
            'title'       => trim($_POST['lesson_title']),
            'type'        => $_POST['lesson_type'],
            'duration'    => intval($_POST['lesson_duration']),
            'description' => trim($_POST['lesson_description']),
            'order'       => count($course->curriculum)
        ];
        // handle type-specific fields (video, reading, quiz, exercise) – same logic as before
        switch ($_POST['lesson_type']) {
            case 'video': ... break;
            case 'reading': ... break;
            case 'quiz': ... break;
            case 'exercise': ... break;
        }
        $course->addLesson($lessonData);
        $result = $course->save();
        $_SESSION['success'] = $result ? "Lesson added!" : "Failed.";
        header('Location: /course-builder?course_id=' . $courseId . '&tab=curriculum');
        exit;
    }

    // 5. Update Lesson
    if (isset($_POST['update_lesson'])) {
        $lessonId = $_POST['lesson_id'];
        $result = $course->save();
        $_SESSION['success'] = $result ? "Lesson updated!" : "Failed.";
        header('Location: /course-builder?course_id=' . $courseId . '&tab=curriculum');
        exit;
    }

    // 6. Delete Lesson
    if (isset($_POST['delete_lesson'])) {
        $lessonId = $_POST['lesson_id'];
        $course->deleteLesson($lessonId);
        $result = $course->save();
        $_SESSION['success'] = $result ? "Lesson deleted!" : "Failed.";
        header('Location: /course-builder?course_id=' . $courseId . '&tab=curriculum');
        exit;
    }

    // 7. Reorder Lessons
    if (isset($_POST['reorder_lessons'])) {
        $newOrder = json_decode($_POST['lesson_order'], true);
        $reordered = [];
        foreach ($newOrder as $order => $lid) {
            foreach ($course->curriculum as $lesson) {
                if ($lesson['id'] == $lid) {
                    $lesson['order'] = $order;
                    $reordered[] = $lesson;
                    break;
                }
            }
        }
        $course->setCurriculum($reordered);
        $result = $course->save();
        $_SESSION['success'] = $result ? "Reordered!" : "Failed.";
        header('Location: /course-builder?course_id=' . $courseId . '&tab=curriculum');
        exit;
    }

    // 8. Add Announcement (use Announcement class)
    if (isset($_POST['add_announcement'])) {
        $ann = Announcement::create([
            'course_id'       => $courseId,
            'instructor_id'   => $user->getId(),
            'instructor_name' => $user->getName(),
            'title'           => trim($_POST['announcement_title']),
            'content'         => trim($_POST['announcement_content'])
        ]);
        $_SESSION['success'] = $ann ? "Announcement published!" : "Failed.";
        header('Location: /course-builder?course_id=' . $courseId . '&tab=communication');
        exit;
    }

    header('Location: /course-builder?course_id=' . $courseId);
    exit;
}

// Get announcements for display
$announcements = [];
if ($courseId) {
    $announcements = Announcement::getByCourse($courseId);
}

$activeTab = $_GET['tab'] ?? 'basic';
$page_title = $courseId ? "Edit Course - CodeMastery" : "Create New Course - CodeMastery";
$current_page = 'course-builder';
require 'view/partial/instructor-header.php';
require 'view/instructor/course-builder.php';