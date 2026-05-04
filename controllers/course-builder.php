<?php
require_once 'includes/auth-functions.php';
requireRole('instructor');

$user = getCurrentUser();
$courseId = $_GET['course_id'] ?? null;
$course = null;

// Load existing course
if ($courseId) {
    $course = getCourseById($courseId);
    if (!$course || $course['instructor_id'] != $user['id']) {
        $_SESSION['error'] = "Course not found or you don't have permission to edit it.";
        header('Location: /instructor-courses');
        exit;
    }
}

// Add this helper function for resource uploads
function handleResourceUpload($courseId, $fieldName)
{
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

// Handle ALL form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 1. Course Information Update
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
                $result = updateCourse($courseId, $courseData);
                if ($result) {
                    $_SESSION['success'] = "Course updated successfully!";
                } else {
                    $_SESSION['error'] = "Failed to update course.";
                }
            } else {
                $courseData['instructor_id'] = $user['id'];
                $courseData['instructor_name'] = $user['name'];
                $courseData['status'] = 'draft';
                $courseData['curriculum'] = [];

                $result = createCourse($courseData);
                if ($result) {
                    $_SESSION['success'] = "Course created successfully!";
                    header('Location: /course-builder?course_id=' . $result['id']);
                    exit;
                } else {
                    $_SESSION['error'] = "Failed to create course.";
                }
            }
        }
    }

    // 2. Publish/Unpublish Course
    if (isset($_POST['update_status'])) {
        $newStatus = $_POST['status'];
        $result = updateCourse($courseId, ['status' => $newStatus]);

        if ($result) {
            $_SESSION['success'] = "Course " . ($newStatus === 'published' ? 'published' : 'unpublished') . " successfully!";
        } else {
            $_SESSION['error'] = "Failed to update course status.";
        }

        header('Location: /course-builder?course_id=' . $courseId);
        exit;
    }

    // 3. Delete Course
    if (isset($_POST['delete_course'])) {
        $courses = getAllCourses();
        $courses = array_filter($courses, function ($c) use ($courseId) {
            return $c['id'] != $courseId;
        });

        if (saveToFile('courses.json', array_values($courses))) {
            $_SESSION['success'] = "Course deleted successfully!";
            header('Location: /instructor-courses');
            exit;
        } else {
            $_SESSION['error'] = "Failed to delete course.";
        }
    }

    // 4. Add New Lesson - ENHANCED VERSION
    if (isset($_POST['add_lesson'])) {
        $lessonData = [
            'id' => uniqid(),
            'title' => trim($_POST['lesson_title']),
            'type' => $_POST['lesson_type'],
            'duration' => intval($_POST['lesson_duration']),
            'description' => trim($_POST['lesson_description']),
            'order' => count($course['curriculum'] ?? [])
        ];

        // Handle different lesson types
        switch ($_POST['lesson_type']) {
            case 'video':
                $lessonData['video_url'] = $_POST['video_url'] ?? '';
                $lessonData['content'] = trim($_POST['lesson_content'] ?? '');
                // Handle video upload
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
                // Handle reading resources
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

        // Initialize curriculum array if it doesn't exist
        if (!isset($course['curriculum'])) {
            $course['curriculum'] = [];
        }

        $course['curriculum'][] = $lessonData;

        $result = updateCourse($courseId, ['curriculum' => $course['curriculum']]);

        if ($result) {
            $_SESSION['success'] = "Lesson added successfully!";
        } else {
            $_SESSION['error'] = "Failed to add lesson.";
        }

        header('Location: /course-builder?course_id=' . $courseId . '&tab=curriculum');
        exit;
    }

    // 5. Update Lesson - ENHANCED VERSION
    if (isset($_POST['update_lesson'])) {
        $lessonId = $_POST['lesson_id'];
        $updated = false;

        foreach ($course['curriculum'] as &$lesson) {
            if ($lesson['id'] == $lessonId) {
                $lesson['title'] = trim($_POST['edit_lesson_title']);
                $lesson['type'] = $_POST['edit_lesson_type'];
                $lesson['duration'] = intval($_POST['edit_lesson_duration']);
                $lesson['description'] = trim($_POST['edit_lesson_description']);

                // Update type-specific fields
                switch ($_POST['edit_lesson_type']) {
                    case 'video':
                        $lesson['video_url'] = $_POST['edit_video_url'] ?? '';
                        $lesson['content'] = trim($_POST['edit_lesson_content'] ?? '');
                        break;

                    case 'reading':
                        $lesson['content'] = trim($_POST['edit_lesson_content'] ?? '');
                        $lesson['reading_time'] = $_POST['edit_reading_time'] ?? '5 min';
                        break;

                    case 'quiz':
                        $lesson['questions'] = json_decode($_POST['edit_quiz_questions'] ?? '[]', true) ?? [];
                        $lesson['passing_score'] = intval($_POST['edit_passing_score'] ?? 70);
                        $lesson['time_limit'] = intval($_POST['edit_time_limit'] ?? 0);
                        $lesson['instructions'] = trim($_POST['edit_quiz_instructions'] ?? '');
                        break;

                    case 'exercise':
                        $lesson['instructions'] = trim($_POST['edit_exercise_instructions'] ?? '');
                        $lesson['starter_code'] = trim($_POST['edit_starter_code'] ?? '');
                        $lesson['solution_code'] = trim($_POST['edit_solution_code'] ?? '');
                        $lesson['hints'] = array_filter(array_map('trim', explode("\n", $_POST['edit_exercise_hints'] ?? '')));
                        break;
                }

                $updated = true;
                break;
            }
        }

        if ($updated) {
            $result = updateCourse($courseId, ['curriculum' => $course['curriculum']]);
            if ($result) {
                $_SESSION['success'] = "Lesson updated successfully!";
            } else {
                $_SESSION['error'] = "Failed to update lesson.";
            }
        } else {
            $_SESSION['error'] = "Lesson not found for update.";
        }

        header('Location: /course-builder?course_id=' . $courseId . '&tab=curriculum');
        exit;
    }



    // 6. Delete Lesson - FIXED VERSION
    if (isset($_POST['delete_lesson'])) {
        $lessonId = $_POST['lesson_id'];

        error_log("Deleting lesson {$lessonId} from course {$courseId}");
        error_log("Before deletion: " . count($course['curriculum']) . " lessons");

        $course['curriculum'] = array_filter($course['curriculum'], function ($lesson) use ($lessonId) {
            return $lesson['id'] != $lessonId;
        });

        // Reorder lessons
        foreach ($course['curriculum'] as $index => &$lesson) {
            $lesson['order'] = $index;
        }

        error_log("After deletion: " . count($course['curriculum']) . " lessons");

        $result = updateCourse($courseId, ['curriculum' => array_values($course['curriculum'])]);
        if ($result) {
            $_SESSION['success'] = "Lesson deleted successfully!";
        } else {
            $_SESSION['error'] = "Failed to delete lesson.";
        }

        header('Location: /course-builder?course_id=' . $courseId . '&tab=curriculum');
        exit;
    }

    // 7. Reorder Lessons - FIXED VERSION
    if (isset($_POST['reorder_lessons'])) {
        $newOrder = json_decode($_POST['lesson_order'], true);
        $reorderedCurriculum = [];

        error_log("Reordering lessons: " . print_r($newOrder, true));

        foreach ($newOrder as $order => $lessonId) {
            foreach ($course['curriculum'] as $lesson) {
                if ($lesson['id'] == $lessonId) {
                    $lesson['order'] = $order;
                    $reorderedCurriculum[] = $lesson;
                    break;
                }
            }
        }

        $result = updateCourse($courseId, ['curriculum' => $reorderedCurriculum]);
        if ($result) {
            $_SESSION['success'] = "Lesson order updated!";
        } else {
            $_SESSION['error'] = "Failed to reorder lessons.";
        }

        header('Location: /course-builder?course_id=' . $courseId . '&tab=curriculum');
        exit;
    }

    // 8. Add Announcement
    if (isset($_POST['add_announcement'])) {
        $announcements = getFromFile('announcements.json');
        $announcementData = [
            'announcement_id' => uniqid(),
            'course_id' => $courseId,
            'instructor_id' => $user['id'],
            'instructor_name' => $user['name'],
            'title' => trim($_POST['announcement_title']),
            'content' => trim($_POST['announcement_content']),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $announcements[] = $announcementData;
        $result = saveToFile('announcements.json', $announcements);

        if ($result) {
            $_SESSION['success'] = "Announcement published!";
        } else {
            $_SESSION['error'] = "Failed to publish announcement.";
        }

        header('Location: /course-builder?course_id=' . $courseId . '&tab=communication');
        exit;
    }

    // Redirect to prevent form resubmission
    header('Location: /course-builder?course_id=' . $courseId);
    exit;
}

// Get course announcements
$announcements = [];
if ($courseId) {
    $allAnnouncements = getFromFile('announcements.json');
    $announcements = array_filter($allAnnouncements, function ($ann) use ($courseId) {
        return $ann['course_id'] == $courseId;
    });

    // Sort announcements by date (newest first)
    usort($announcements, function ($a, $b) {
        return strtotime($b['created_at']) - strtotime($a['created_at']);
    });
}

// Get active tab from URL or default to basic
$activeTab = $_GET['tab'] ?? 'basic';

$page_title = $courseId ? "Edit Course - CodeMastery" : "Create New Course - CodeMastery";
$current_page = 'course-builder';
require 'view/partial/instructor-header.php';
require 'view/instructor/course-builder.php';
