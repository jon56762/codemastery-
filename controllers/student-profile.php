<?php
require_once 'includes/init.php';  
require_once 'includes/auth-functions.php';
requireAuth();

// Get current user as object
$user = getCurrentUserObject();
if (!$user) {
    logoutUser();
    header('Location: /login');
    exit;
}


$enrollments = Enrollment::findByUser($user->getId());
$enrolledCourses = [];
foreach ($enrollments as $enrollment) {
    $course = Course::findById($enrollment->courseId);
    if ($course) {
        $enrolledCourses[] = [
            'course'     => $course->toArray(),
            'enrollment' => $enrollment->toArray()
        ];
    }
}

$completedEnrollments = array_filter($enrollments, fn($e) => $e->progress >= 100);
$completedCourses = array_map(fn($e) => Course::findById($e->courseId)?->toArray(), $completedEnrollments);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['update_profile'])) {
        $user->name   = $_POST['name'] ?? $user->name;
        $user->bio    = $_POST['bio'] ?? '';
        $user->skills = !empty($_POST['skills']) ? array_map('trim', explode(',', $_POST['skills'])) : [];
        $user->save();
        $_SESSION['user']['name'] = $user->name; // keep session in sync
        $_SESSION['success'] = 'Profile updated successfully!';
    }

    if (isset($_POST['update_learning_goals'])) {
        $user->learningGoals = $_POST['learning_goals'] ?? '';
        $user->save();
        $_SESSION['success'] = 'Learning goals updated!';
    }

    if (isset($_POST['update_notifications'])) {
        $user->notificationPreferences = [
            'email_notifications' => isset($_POST['email_notifications']),
            'course_updates'      => isset($_POST['course_updates']),
            'newsletter'          => isset($_POST['newsletter'])
        ];
        $user->save();
        $_SESSION['success'] = 'Notification preferences updated!';
    }

    if (isset($_POST['update_privacy'])) {
        $user->privacySettings = [
            'profile_visibility' => $_POST['profile_visibility'] ?? 'public',
            'show_progress'      => isset($_POST['show_progress']),
            'show_achievements'  => isset($_POST['show_achievements'])
        ];
        $user->save();
        $_SESSION['success'] = 'Privacy settings updated!';
    }

    header('Location: /profile');
    exit;
}

// Achievements 
$achievements = getStudentAchievements($user->getId());

$page_title = "Profile - " . $user->name;
$current_page = 'profile';
require 'view/partial/nav.php';
require 'view/student/profile.php';
require 'view/partial/footer.php';