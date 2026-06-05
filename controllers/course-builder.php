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
    if (!$course || $course->instructorId != $userObj->id) {
        $_SESSION['error'] = "Course not found or permission denied.";
        header('Location: /instructor-courses');
        exit;
    }
}

function ensureUploadDir($relativePath) {
    $fullPath = $_SERVER['DOCUMENT_ROOT'] . '/' . ltrim($relativePath, '/');
    if (!is_dir($fullPath)) {
        if (!mkdir($fullPath, 0777, true)) {
            error_log("Failed to create directory: $fullPath");
            return false;
        }
    }
    if (!is_writable($fullPath)) {
        error_log("Directory not writable: $fullPath");
        return false;
    }
    return $fullPath;
}

function handleResourceUpload($courseId, $fieldName) {
    $resources = [];
    if (!isset($_FILES[$fieldName]) || !is_array($_FILES[$fieldName]['name'])) {
        return $resources;
    }

    $uploadDirRel = "uploads/courses/{$courseId}/resources/";
    $uploadDirAbs = ensureUploadDir($uploadDirRel);
    if (!$uploadDirAbs) {
        $_SESSION['error'] = "Upload directory could not be created or is not writable.";
        return $resources;
    }

    foreach ($_FILES[$fieldName]['name'] as $key => $name) {
        if ($_FILES[$fieldName]['error'][$key] !== UPLOAD_ERR_OK) {
            $errorMsg = match($_FILES[$fieldName]['error'][$key]) {
                UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => "File too large.",
                UPLOAD_ERR_PARTIAL => "File only partially uploaded.",
                UPLOAD_ERR_NO_FILE => "No file uploaded.",
                default => "Unknown upload error."
            };
            $_SESSION['error'] = "Failed to upload '$name': $errorMsg";
            continue;
        }

        $safeName = preg_replace('/[^a-zA-Z0-9._-]/', '_', $name);
        $filename = 'resource_' . time() . '_' . uniqid() . '_' . $safeName;
        $filepath = $uploadDirAbs . '/' . $filename;

        if (move_uploaded_file($_FILES[$fieldName]['tmp_name'][$key], $filepath)) {
            $resources[] = [
                'name' => $name,
                'url'  => '/' . $uploadDirRel . $filename,
                'type' => pathinfo($name, PATHINFO_EXTENSION)
            ];
        } else {
            $_SESSION['error'] = "Failed to move uploaded file '$name'.";
            error_log("Move failed for $name to $filepath");
        }
    }
    return $resources;
}

// Process form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 1. Update course info (existing or new)
    if (isset($_POST['update_course'])) {
        $title = trim($_POST['title']);
        $description = trim($_POST['description']);
        $shortDescription = trim($_POST['short_description']);
        $price = floatval($_POST['price']);
        $category = $_POST['category'];
        $level = $_POST['level'];
        $thumbnail = $_POST['thumbnail'] ?? '/assets/images/courses/default.jpg';

        if (empty($title) || empty($description) || empty($category)) {
            $_SESSION['error'] = "Please fill in all required fields.";
        } else {
            if ($courseId) {
                $course->title = $title;
                $course->description = $description;
                $course->shortDescription = $shortDescription;
                $course->price = $price;
                $course->category = $category;
                $course->level = $level;
                $course->thumbnail = $thumbnail;
                $result = $course->save();
                $_SESSION['success'] = $result ? "Course updated successfully!" : "Failed to update course.";
            } else {
                $courseData = [
                    'instructor_id'    => $userObj->id,
                    'instructor_name'  => $userObj->name,
                    'title'            => $title,
                    'description'      => $description,
                    'short_description'=> $shortDescription,
                    'price'            => $price,
                    'category'         => $category,
                    'level'            => $level,
                    'thumbnail'        => $thumbnail,
                    'status'           => 'draft',
                    'curriculum'       => []
                ];
                $course = Course::create($courseData);
                if ($course) {
                    $_SESSION['success'] = "Course created successfully!";
                    header('Location: /course-builder?course_id=' . $course->id);
                    exit;
                } else {
                    $_SESSION['error'] = "Failed to create course.";
                }
            }
        }
        header('Location: /course-builder?course_id=' . ($courseId ?? ''));
        exit;
    }

    if (isset($_POST['update_status'])) {
        $newStatus = $_POST['status'];
        $course->status = $newStatus;
        $result = $course->save();
        $_SESSION['success'] = $result ? "Course " . ($newStatus === 'published' ? 'published' : 'unpublished') : "Status update failed.";
        header('Location: /course-builder?course_id=' . $courseId);
        exit;
    }

    if (isset($_POST['delete_course'])) {
        if ($course->delete()) {
            $_SESSION['success'] = "Course deleted.";
            header('Location: /instructor-courses');
            exit;
        } else {
            $_SESSION['error'] = "Deletion failed.";
            header('Location: /course-builder?course_id=' . $courseId);
            exit;
        }
    }

    if (isset($_POST['add_lesson'])) {
        $lessonData = [
            'id'          => uniqid(),
            'title'       => trim($_POST['lesson_title']),
            'type'        => $_POST['lesson_type'],
            'duration'    => intval($_POST['lesson_duration']),
            'description' => trim($_POST['lesson_description']),
            'order'       => count($course->curriculum ?? [])
        ];

        switch ($_POST['lesson_type']) {
            case 'video':
                $lessonData['video_url'] = $_POST['video_url'] ?? '';
                $lessonData['content']   = trim($_POST['lesson_content'] ?? '');

                // Handle video file upload
                if (isset($_FILES['video_upload']) && $_FILES['video_upload']['error'] === UPLOAD_ERR_OK) {
                    $uploadDirRel = "uploads/courses/{$courseId}/videos/";
                    $uploadDirAbs = ensureUploadDir($uploadDirRel);
                    if ($uploadDirAbs) {
                        $ext = pathinfo($_FILES['video_upload']['name'], PATHINFO_EXTENSION);
                        $filename = 'video_' . time() . '_' . uniqid() . '.' . $ext;
                        $filepath = $uploadDirAbs . '/' . $filename;
                        if (move_uploaded_file($_FILES['video_upload']['tmp_name'], $filepath)) {
                            $lessonData['video_url'] = '/' . $uploadDirRel . $filename;
                        } else {
                            $_SESSION['error'] = "Failed to upload video file.";
                        }
                    } else {
                        $_SESSION['error'] = "Video upload directory not writable.";
                    }
                }
                break;

            case 'reading':
                $lessonData['content']      = trim($_POST['lesson_content'] ?? '');
                $lessonData['reading_time'] = $_POST['reading_time'] ?? '5 min';
                $lessonData['resources']    = handleResourceUpload($courseId, 'reading_resources');
                break;

            case 'quiz':
                $lessonData['questions']     = json_decode($_POST['quiz_questions'] ?? '[]', true);
                $lessonData['passing_score'] = intval($_POST['passing_score'] ?? 70);
                $lessonData['time_limit']    = intval($_POST['time_limit'] ?? 0);
                $lessonData['instructions']  = trim($_POST['quiz_instructions'] ?? '');
                break;

            case 'exercise':
                $lessonData['instructions']  = trim($_POST['exercise_instructions'] ?? '');
                $lessonData['starter_code']  = trim($_POST['starter_code'] ?? '');
                $lessonData['solution_code'] = trim($_POST['solution_code'] ?? '');
                $lessonData['hints']         = array_filter(array_map('trim', explode("\n", $_POST['exercise_hints'] ?? '')));
                $lessonData['resources']     = handleResourceUpload($courseId, 'exercise_resources');
                break;
        }

        $course->addLesson($lessonData);
        $course->save();

        if (!isset($_SESSION['error'])) {
            $_SESSION['success'] = "Lesson added successfully!";
        }
        header('Location: /course-builder?course_id=' . $courseId . '&tab=curriculum');
        exit;
    }

    if (isset($_POST['update_lesson'])) {
        $lessonId = $_POST['lesson_id'];
        $updated = false;
        foreach ($course->curriculum as &$lesson) {
            if ($lesson['id'] == $lessonId) {
                $lesson['title']       = trim($_POST['edit_lesson_title']);
                $lesson['type']        = $_POST['edit_lesson_type'];
                $lesson['duration']    = intval($_POST['edit_lesson_duration']);
                $lesson['description'] = trim($_POST['edit_lesson_description']);
                $lesson['content']     = trim($_POST['edit_lesson_content']);
                // Additional fields for video, reading, etc. can be updated similarly
                $updated = true;
                break;
            }
        }
        if ($updated) {
            $course->save();
            $_SESSION['success'] = "Lesson updated!";
        } else {
            $_SESSION['error'] = "Lesson not found.";
        }
        header('Location: /course-builder?course_id=' . $courseId . '&tab=curriculum');
        exit;
    }

    if (isset($_POST['delete_lesson'])) {
        $lessonId = $_POST['lesson_id'];
        $course->deleteLesson($lessonId);
        $course->save();
        $_SESSION['success'] = "Lesson deleted.";
        header('Location: /course-builder?course_id=' . $courseId . '&tab=curriculum');
        exit;
    }

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
        $course->curriculum = $reordered;
        $course->save();
        $_SESSION['success'] = "Lesson order updated.";
        header('Location: /course-builder?course_id=' . $courseId . '&tab=curriculum');
        exit;
    }

    if (isset($_POST['add_announcement'])) {
        Announcement::create([
            'course_id'       => $courseId,
            'instructor_id'   => $userObj->id,
            'instructor_name' => $userObj->name,
            'title'           => trim($_POST['announcement_title']),
            'content'         => trim($_POST['announcement_content'])
        ]);
        $_SESSION['success'] = "Announcement published.";
        header('Location: /course-builder?course_id=' . $courseId . '&tab=communication');
        exit;
    }

    header('Location: /course-builder?course_id=' . $courseId);
    exit;
}

$announcements = [];
if ($courseId) {
    $announcements = Announcement::getByCourse($courseId);
}
$enrollmentCount = $courseId ? count(Enrollment::findByCourse($courseId)) : 0;

if ($course && is_object($course)) {
    $course = $course->toArray();
}

$activeTab   = $_GET['tab'] ?? 'basic';
$page_title  = $courseId ? "Edit Course - CodeMastery" : "Create New Course - CodeMastery";
$current_page = 'course-builder';
require 'view/partial/instructor-header.php';
require 'view/instructor/course-builder.php';
?>