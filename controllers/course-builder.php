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
        $courses = array_filter($courses, function($c) use ($courseId) {
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
    
    // 4. Add New Lesson
    if (isset($_POST['add_lesson'])) {
        $lessonData = [
            'id' => uniqid(),
            'title' => trim($_POST['lesson_title']),
            'type' => $_POST['lesson_type'],
            'duration' => intval($_POST['lesson_duration']),
            'content' => trim($_POST['lesson_content']),
            'description' => trim($_POST['lesson_description']),
            'video_url' => $_POST['video_url'] ?? '',
            'resources' => [],
            'order' => count($course['curriculum'] ?? [])
        ];
        
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
    
    // 5. Update Lesson
    if (isset($_POST['update_lesson'])) {
        $lessonId = $_POST['lesson_id'];
        $updated = false;
        
        foreach ($course['curriculum'] as &$lesson) {
            if ($lesson['id'] == $lessonId) {
                $lesson['title'] = trim($_POST['edit_lesson_title']);
                $lesson['type'] = $_POST['edit_lesson_type'];
                $lesson['duration'] = intval($_POST['edit_lesson_duration']);
                $lesson['content'] = trim($_POST['edit_lesson_content']);
                $lesson['description'] = trim($_POST['edit_lesson_description']);
                $lesson['video_url'] = $_POST['edit_video_url'] ?? '';
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
        }
        
        header('Location: /course-builder?course_id=' . $courseId . '&tab=curriculum');
        exit;
    }
    
    // 6. Delete Lesson
    if (isset($_POST['delete_lesson'])) {
        $lessonId = $_POST['lesson_id'];
        $course['curriculum'] = array_filter($course['curriculum'], function($lesson) use ($lessonId) {
            return $lesson['id'] != $lessonId;
        });
        
        // Reorder lessons
        foreach ($course['curriculum'] as $index => &$lesson) {
            $lesson['order'] = $index;
        }
        
        $result = updateCourse($courseId, ['curriculum' => array_values($course['curriculum'])]);
        if ($result) {
            $_SESSION['success'] = "Lesson deleted successfully!";
        } else {
            $_SESSION['error'] = "Failed to delete lesson.";
        }
        
        header('Location: /course-builder?course_id=' . $courseId . '&tab=curriculum');
        exit;
    }
    
    // 7. Reorder Lessons
    if (isset($_POST['reorder_lessons'])) {
        $newOrder = json_decode($_POST['lesson_order'], true);
        $reorderedCurriculum = [];
        
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
            'id' => uniqid(),
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
    $announcements = array_filter($allAnnouncements, function($ann) use ($courseId) {
        return $ann['course_id'] == $courseId;
    });
    
    // Sort announcements by date (newest first)
    usort($announcements, function($a, $b) {
        return strtotime($b['created_at']) - strtotime($a['created_at']);
    });
}

// Get active tab from URL or default to basic
$activeTab = $_GET['tab'] ?? 'basic';

$page_title = $courseId ? "Edit Course - CodeMastery" : "Create New Course - CodeMastery";
$current_page = 'course-builder';
require 'view/partial/instructor-header.php';
require 'view/instructor/course-builder.php';
require 'view/partial/footer.php';
?>
