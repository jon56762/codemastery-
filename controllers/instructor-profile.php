<?php
require_once 'includes/auth-functions.php';
require_once 'includes/init.php';
requireRole('instructor');

$user = getCurrentUser();           
$userObj = getCurrentUserObject();  

$instructorId = $user['id'];

$instructorCourses = Course::getByInstructor($instructorId);
$totalCourses = count($instructorCourses);

$totalStudents = 0;
$totalReviews = 0;
$totalRating = 0;
$ratedCourses = 0;
foreach ($instructorCourses as $course) {
    $enrollments = Enrollment::findByCourse($course->getId());
    $totalStudents += count($enrollments);
    if (!empty($course->rating) && $course->rating > 0) {
        $totalRating += $course->rating;
        $ratedCourses++;
        $totalReviews++; 
    }
}
$averageRating = $ratedCourses > 0 ? round($totalRating / $ratedCourses, 1) : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_profile'])) {
        $userObj->name           = trim($_POST['name'] ?? $userObj->name);
        $userObj->bio            = trim($_POST['bio'] ?? '');
        $userObj->website        = trim($_POST['website'] ?? '');
        $userObj->twitter        = trim($_POST['twitter'] ?? '');
        $userObj->linkedin       = trim($_POST['linkedin'] ?? '');
        $userObj->youtube        = trim($_POST['youtube'] ?? '');
        $userObj->specialization = $_POST['specialization'] ?? '';
        $userObj->experience     = $_POST['experience'] ?? '';

        if (empty($userObj->name)) {
            $_SESSION['error'] = "Name is required.";
        } else {
            $userObj->save();
            $_SESSION['user']['name'] = $userObj->name; 
            $_SESSION['success'] = "Profile updated!";
        }
    }

    if (isset($_POST['change_password'])) {
        if (!$userObj->verifyPassword($_POST['current_password'])) {
            $_SESSION['error'] = "Current password is incorrect.";
        } elseif ($_POST['new_password'] !== $_POST['confirm_password']) {
            $_SESSION['error'] = "New passwords do not match.";
        } elseif (strlen($_POST['new_password']) < 6) {
            $_SESSION['error'] = "Password must be at least 6 characters.";
        } else {
            $userObj->changePassword($_POST['new_password']);
            $userObj->save();
            $_SESSION['success'] = "Password changed!";
        }
    }
}

$page_title = "Profile - Instructor Panel";
$current_page = 'instructor-profile';

require 'view/partial/instructor-header.php';
require 'view/instructor/profile.php';