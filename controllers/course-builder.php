<?php
require_once 'includes/init.php';
require_once 'includes/auth-functions.php';
requireRole('instructor');

$userObj = getCurrentUserObject(); 
$user = getCurrentUser();          
$courseId = $_GET['course_id'] ?? null;
$course = null;

if ($courseId) {
    $course = Course::findById($courseId);
    if (!$course || $course->instructorId != $user->getId()) {
        $_SESSION['error'] = "Course not found or you don't have permission.";
        header('Location: /instructor-courses');
        exit;
    }
}

function handleResourceUpload($courseId, $fieldName) {
    $resources = [];
    if (isset($_FILES[$fieldName]) && is_array($_FILES[$fieldName]['name'])) {
        $upload_dir = "uploads/courses/{$courseId}/resources/";
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
        foreach ($_FILES[$fieldName]['name'] as $key => $name) {
            if ($_FILES[$fieldName]['error'][$key] === UPLOAD_ERR_OK) {
                $filename = 'resource_' . time() . '_' . uniqid() . '_' . $name;
                $filepath = $upload_dir . $filename;
                if (move_uploaded_file($_FILES[$fieldName]['tmp_name'][$key], $filepath)) {
                    $resources[] = [
                        'name' => $name,
                        'url' => '/' . $filepath,
                        'type' => pathinfo($name, PATHINFO_EXTENSION)
                    ];
                }
            }
        }
    }
    return $resources;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 1. Update course info
    if (isset($_POST['update_course'])) {
        $courseData = [
            'title' => trim($_POST['title']),
            'description' => trim($_POST['description']),
            'short_description' => trim($_POST['short_description']),
            'price' => floatval($_POST['price']),
            'category' => $_POST['category'],
            'level' => $_POST['level'],
            'thumbnail' => $_POST['thumbnail'] ?? '/assets/images/courses/default.jpg'
        ];

        if (empty($courseData['title']) || empty($courseData['description']) || empty($courseData['category'])) {
            $_SESSION['error'] = "Please fill in all required fields.";
        } else {
            if ($courseId) {
                $course->title = $courseData['title'];
                $course->description = $courseData['description'];
                $course->shortDescription = $courseData['short_description'];
                $course->price = $courseData['price'];
                $course->category = $courseData['category'];
                $course->level = $courseData['level'];
                $course->thumbnail = $courseData['thumbnail'];
                $result = $course->save();
                $_SESSION['success'] = $result ? "Course updated!" : "Failed to update.";
            } else {
                $courseData['instructor_id'] = $user->getId();
                $courseData['instructor_name'] = $user->getName();
                $courseData['status'] = 'draft';
                $courseData['curriculum'] = [];
                $course = Course::create($courseData);
                if ($course) {
                    $_SESSION['success'] = "Course created!";
                    header('Location: /course-builder?course_id=' . $course->getId());
                    exit;
                } else {
                    $_SESSION['error'] = "Failed to create course.";
                }
            }
        }
    }

    // 2. Update status
    if (isset($_POST['update_status'])) {
        $course->status = $_POST['status'];
        $course->save();
        $_SESSION['success'] = "Status updated!";
        header('Location: /course-builder?course_id=' . $courseId);
        exit;
    }

    // 3. Delete course
    if (isset($_POST['delete_course'])) {
        if ($course->delete()) {
            $_SESSION['success'] = "Course deleted!";
            header('Location: /instructor-courses');
            exit;
        }
    }

    // 4. Add lesson
    if (isset($_POST['add_lesson'])) {
        $lessonData = [
            'id' => uniqid(),
            'title' => trim($_POST['lesson_title']),
            'type' => $_POST['lesson_type'],
            'duration' => intval($_POST['lesson_duration']),
            'description' => trim($_POST['lesson_description']),
            'order' => count($course->curriculum)
        ];

        switch ($_POST['lesson_type']) {
            case 'video':
                $lessonData['video_url'] = $_POST['video_url'] ?? '';
                $lessonData['content'] = trim($_POST['lesson_content'] ?? '');
                if (isset($_FILES['video_upload']) && $_FILES['video_upload']['error'] === UPLOAD_ERR_OK) {
                    $upload_dir = "uploads/courses/{$courseId}/videos/";
                    if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
                    $filename = 'video_' . time() . '_' . uniqid() . '.' . pathinfo($_FILES['video_upload']['name'], PATHINFO_EXTENSION);
                    $filepath = $upload_dir . $filename;
                    if (move_uploaded_file($_FILES['video_upload']['tmp_name'], $filepath)) {
                        $lessonData['video_url'] = '/' . $filepath;
                    }
                }
                break;
            case 'reading':
                $lessonData['content'] = trim($_POST['lesson_content'] ?? '');
                $lessonData['reading_time'] = $_POST['reading_time'] ?? '5 min';
                $lessonData['resources'] = handleResourceUpload($courseId, 'reading_resources');
                break;
            case 'quiz':
                $lessonData['questions'] = json_decode($_POST['quiz_questions'] ?? '[]', true) ?? [];
                $lessonData['passing_score'] = intval($_POST['passing_score'] ?? 70);
                $lessonData['time_limit'] = intval($_POST['time_limit'] ?? 0);
                $lessonData['instructions'] = trim($_POST['quiz_instructions'] ?? '');
                break;
            case 'exercise':
                $lessonData['instructions'] = trim($_POST['exercise_instructions'] ?? '');
                $lessonData['starter_code'] = trim($_POST['starter_code'] ?? '');
                $lessonData['solution_code'] = trim($_POST['solution_code'] ?? '');
                $lessonData['hints'] = array_filter(array_map('trim', explode("\n", $_POST['exercise_hints'] ?? '')));
                $lessonData['resources'] = handleResourceUpload($courseId, 'exercise_resources');
                break;
        }
        $course->addLesson($lessonData);
        $course->save();
        $_SESSION['success'] = "Lesson added!";
        header('Location: /course-builder?course_id=' . $courseId . '&tab=curriculum');
        exit;
    }

    // 5. Update lesson
    if (isset($_POST['update_lesson'])) {
        $course->save();
        $_SESSION['success'] = "Lesson updated!";
        header('Location: /course-builder?course_id=' . $courseId . '&tab=curriculum');
        exit;
    }

    // 6. Delete lesson
    if (isset($_POST['delete_lesson'])) {
        $course->deleteLesson($_POST['lesson_id']);
        $course->save();
        $_SESSION['success'] = "Lesson deleted!";
        header('Location: /course-builder?course_id=' . $courseId . '&tab=curriculum');
        exit;
    }

    // 7. Reorder lessons
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
        $course->save();
        $_SESSION['success'] = "Reordered!";
        header('Location: /course-builder?course_id=' . $courseId . '&tab=curriculum');
        exit;
    }

    // 8. Add announcement
    if (isset($_POST['add_announcement'])) {
        Announcement::create([
            'course_id' => $courseId,
            'instructor_id' => $user->getId(),
            'instructor_name' => $user->getName(),
            'title' => trim($_POST['announcement_title']),
            'content' => trim($_POST['announcement_content'])
        ]);
        $_SESSION['success'] = "Announcement published!";
        header('Location: /course-builder?course_id=' . $courseId . '&tab=communication');
        exit;
    }

    header('Location: /course-builder?course_id=' . $courseId);
    exit;
}

$announcements = $courseId ? Announcement::getByCourse($courseId) : [];
$activeTab = $_GET['tab'] ?? 'basic';
$page_title = $courseId ? "Edit Course - CodeMastery" : "Create New Course - CodeMastery";
$current_page = 'course-builder';
$enrollmentCount = $courseId ? count(Enrollment::findByCourse($courseId)) : 0;
require 'view/partial/instructor-header.php';
require 'view/instructor/course-builder.php';