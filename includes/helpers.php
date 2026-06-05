<?php
function getCourseImage($course) {
    $thumbnail = is_array($course) ? ($course['thumbnail'] ?? '') : ($course->thumbnail ?? '');
    return !empty($thumbnail) ? $thumbnail : '/assets/images/courses/default.jpg';
}

function getInstructorAvatar($instructorName, $size = 30) {
    // You can later fetch real avatar from DB by name
    return '/assets/images/avatars/default.png';
}

function getCourseLevelBadge($level) {
    switch ($level) {
        case 'beginner': return 'success';
        case 'intermediate': return 'warning';
        case 'advanced': return 'danger';
        default: return 'secondary';
    }
}

function formatDuration($minutes) {
    if ($minutes < 60) return $minutes . ' min';
    $hours = floor($minutes / 60);
    $mins = $minutes % 60;
    return $hours . 'h ' . ($mins ? $mins . 'min' : '');
}

function formatFileSize($bytes) {
    if ($bytes === 0) return '0 Bytes';
    $k = 1024;
    $sizes = ['Bytes', 'KB', 'MB', 'GB'];
    $i = floor(log($bytes) / log($k));
    return round($bytes / pow($k, $i), 2) . ' ' . $sizes[$i];
}

function getPlatformStats() {
    $db = Database::getConnection();
    $stats = [];
    $result = $db->query("SELECT COUNT(DISTINCT user_id) as students FROM enrollments");
    $stats['total_students'] = $result->fetch_assoc()['students'] ?? 0;
    $result = $db->query("SELECT COUNT(*) as instructors FROM users WHERE role = 'instructor'");
    $stats['total_instructors'] = $result->fetch_assoc()['instructors'] ?? 0;
    $result = $db->query("SELECT COUNT(*) as courses FROM courses WHERE status = 'published'");
    $stats['total_courses'] = $result->fetch_assoc()['courses'] ?? 0;
    $result = $db->query("SELECT COUNT(*) as enrollments FROM enrollments");
    $stats['total_enrollments'] = $result->fetch_assoc()['enrollments'] ?? 0;
    $result = $db->query("SELECT AVG(rating) as avg_rating FROM courses WHERE rating > 0");
    $stats['average_rating'] = round($result->fetch_assoc()['avg_rating'] ?? 4.8, 1);
    return $stats;
}

function handleNewsletterSignup($email) {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    }
    $db = Database::getConnection();
    $stmt = $db->prepare("INSERT IGNORE INTO newsletter_subscribers (email) VALUES (?)");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $_SESSION['success'] = "Thanks for subscribing!";
    return true;
}