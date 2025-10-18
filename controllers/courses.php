<?php
require_once 'includes/function.php';

$page_title = "Courses - CodeMastery";
$current_page = 'courses';

// Get all courses and filter data
$all_courses = getAllCourses();
$categories = getCourseCategories();
$levels = ['beginner', 'intermediate', 'advanced'];

// Filter and search handling
$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';
$level = $_GET['level'] ?? '';
$price = $_GET['price'] ?? '';
$sort = $_GET['sort'] ?? 'newest';

// Filter courses based on criteria
$filtered_courses = array_filter($all_courses, function($course) use ($search, $category, $level, $price) {
    // Search filter
    if ($search && stripos($course['title'], $search) === false && stripos($course['description'], $search) === false) {
        return false;
    }
    
    // Category filter
    if ($category && $course['category'] !== $category) {
        return false;
    }
    
    // Level filter
    if ($level && $course['level'] !== $level) {
        return false;
    }
    
    // Price filter
    if ($price === 'free' && $course['price'] > 0) {
        return false;
    }
    if ($price === 'paid' && $course['price'] <= 0) {
        return false;
    }
    
    // Only show published courses
    return $course['status'] === 'published';
});

// Sort courses
usort($filtered_courses, function($a, $b) use ($sort) {
    switch ($sort) {
        case 'price_low':
            return $a['price'] <=> $b['price'];
        case 'price_high':
            return $b['price'] <=> $a['price'];
        case 'rating':
            return ($b['rating'] ?? 0) <=> ($a['rating'] ?? 0);
        case 'popular':
            return ($b['enrollment_count'] ?? 0) <=> ($a['enrollment_count'] ?? 0);
        default: // newest
            return strtotime($b['created_at']) <=> strtotime($a['created_at']);
    }
});

// Get featured courses for sidebar
$featured_courses = getFeaturedCourses(3);

require 'view/partial/nav.php';
require 'view/courses.php';
require 'view/partial/footer.php';
?>