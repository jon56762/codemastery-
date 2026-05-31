<?php
require_once 'includes/init.php';
require_once 'includes/auth-functions.php';
requireAuth();

$user = getCurrentUserObject();
if (!$user) {
    logoutUser();
    header('Location: /login');
    exit;
}

$enrollments = Enrollment::findByUser($user->getId());
$completedCount = count(array_filter($enrollments, fn($e) => $e->progress >= 100));
$totalCourses = count($enrollments);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_profile'])) {
        $user->name   = $_POST['name'] ?? $user->name;
        $user->bio    = $_POST['bio'] ?? '';
        $user->skills = !empty($_POST['skills']) ? array_map('trim', explode(',', $_POST['skills'])) : [];
        $user->save();
        $_SESSION['user']['name'] = $user->name;
        $_SESSION['success'] = 'Profile updated!';
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


$achievements = [];
if ($completedCount >= 1) {
    $achievements[] = [
        'id'          => 1,
        'title'       => 'First Steps',
        'description' => 'Complete your first course',
        'icon'        => 'graduation-cap',
        'earned_at'   => date('Y-m-d H:i:s', strtotime('-30 days'))
    ];
}
if ($completedCount >= 3) {
    $achievements[] = [
        'id'          => 2,
        'title'       => 'Course Collector',
        'description' => 'Complete 3 courses',
        'icon'        => 'trophy',
        'earned_at'   => date('Y-m-d H:i:s', strtotime('-15 days'))
    ];
}
if ($totalCourses >= 5) {
    $achievements[] = [
        'id'          => 3,
        'title'       => 'Dedicated Learner',
        'description' => 'Enroll in 5 courses',
        'icon'        => 'book',
        'earned_at'   => date('Y-m-d H:i:s', strtotime('-7 days'))
    ];
}

$page_title = "Profile - " . $user->name;
$current_page = 'profile';
require 'view/partial/nav.php';
require 'view/student/profile.php';
require 'view/partial/footer.php';