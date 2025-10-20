<?php
require_once 'includes/auth-functions.php';
requireAuth();

$user = getCurrentUser();
$userId = $user['id'];

// Get user data
$users = getFromFile('users.json');
$currentUser = null;
foreach ($users as $u) {
    if ($u['id'] == $userId) {
        $currentUser = $u;
        break;
    }
}

if (!$currentUser) {
    logoutUser();
    header('Location: /login');
    exit;
}

// Get enrollments for stats
$enrollments = getFromFile('enrollments.json');
$userEnrollments = array_filter($enrollments, function($e) use ($userId) {
    return $e['user_id'] == $userId;
});

$courses = getFromFile('courses.json');
$enrolledCourses = array_filter($courses, function($course) use ($userEnrollments) {
    foreach ($userEnrollments as $e) {
        if ($e['course_id'] == $course['id']) {
            return true;
        }
    }
    return false;
});

$completedCourses = array_filter($userEnrollments, function($e) {
    return $e['progress'] == 100;
});

$totalLearningTime = 0; // Calculate from lesson durations

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_profile'])) {
        $currentUser['name'] = $_POST['name'] ?? $currentUser['name'];
        $currentUser['bio'] = $_POST['bio'] ?? '';
        
        // Handle skills
        $skills = $_POST['skills'] ?? '';
        $currentUser['skills'] = array_map('trim', explode(',', $skills));
        
        // Update user in array
        foreach ($users as &$u) {
            if ($u['id'] == $userId) {
                $u = $currentUser;
                break;
            }
        }
        
        saveToFile('users.json', $users);
        $_SESSION['user']['name'] = $currentUser['name']; // Update session
        $_SESSION['success'] = 'Profile updated successfully!';
    }
    
    if (isset($_POST['update_learning_goals'])) {
        $currentUser['learning_goals'] = $_POST['learning_goals'] ?? '';
        
        foreach ($users as &$u) {
            if ($u['id'] == $userId) {
                $u = $currentUser;
                break;
            }
        }
        
        saveToFile('users.json', $users);
        $_SESSION['success'] = 'Learning goals updated successfully!';
    }
    
    if (isset($_POST['update_notifications'])) {
        $currentUser['notification_preferences'] = [
            'email_notifications' => isset($_POST['email_notifications']),
            'course_updates' => isset($_POST['course_updates']),
            'newsletter' => isset($_POST['newsletter'])
        ];
        
        foreach ($users as &$u) {
            if ($u['id'] == $userId) {
                $u = $currentUser;
                break;
            }
        }
        
        saveToFile('users.json', $users);
        $_SESSION['success'] = 'Notification preferences updated successfully!';
    }
    
    if (isset($_POST['update_privacy'])) {
        $currentUser['privacy_settings'] = [
            'profile_visibility' => $_POST['profile_visibility'] ?? 'public',
            'show_progress' => isset($_POST['show_progress']),
            'show_achievements' => isset($_POST['show_achievements'])
        ];
        
        foreach ($users as &$u) {
            if ($u['id'] == $userId) {
                $u = $currentUser;
                break;
            }
        }
        
        saveToFile('users.json', $users);
        $_SESSION['success'] = 'Privacy settings updated successfully!';
    }
    
    // Redirect to avoid resubmission
    header('Location: /profile');
    exit;
}

// Get achievements

$achievements = getStudentAchievements($userId);

$page_title = "Profile - " . $currentUser['name'];
$current_page = 'profile';
require 'view/partial/nav.php';
require 'view/student/profile.php';
require 'view/partial/footer.php';
?>