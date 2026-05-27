<?php
require_once 'includes/init.php'; 

$page_title = "Courses - CodeMastery";
$current_page = 'courses';


$search   = $_GET['search']   ?? '';
$category = $_GET['category'] ?? '';
$level    = $_GET['level']    ?? '';
$price    = $_GET['price']    ?? '';
$sort     = $_GET['sort']     ?? 'newest';

$courses = Course::getPublished();

$filtered_courses = array_filter($courses, function($course) use ($search, $category, $level, $price) {
    // Search filter (check title and description)
    if ($search && stripos($course->title, $search) === false && stripos($course->description, $search) === false) {
        return false;
    }
    // Category filter
    if ($category && $course->category !== $category) {
        return false;
    }
    // Level filter
    if ($level && $course->level !== $level) {
        return false;
    }
    // Price filter
    if ($price === 'free' && $course->price > 0) {
        return false;
    }
    if ($price === 'paid' && $course->price <= 0) {
        return false;
    }
    return true;
});

usort($filtered_courses, function($a, $b) use ($sort) {
    switch ($sort) {
        case 'price_low':
            return $a->price <=> $b->price;
        case 'price_high':
            return $b->price <=> $a->price;
        case 'rating':
            return ($b->rating ?? 0) <=> ($a->rating ?? 0);
        case 'popular':
            return ($b->enrollmentCount ?? 0) <=> ($a->enrollmentCount ?? 0);
        default: 
            return strtotime($b->createdAt) <=> strtotime($a->createdAt);
    }
});

$all_published = Course::getPublished();
$categories = [];
foreach ($all_published as $c) {
    if (!empty($c->category)) {
        $categories[] = $c->category;
    }
}
$categories = array_unique($categories);
sort($categories);

// Get featured courses for the sidebar (using the same Course method)
$featured_courses = Course::getFeatured(3);

// Convert course objects to arrays for the view (the view still expects arrays)
$filtered_courses = array_map(fn($c) => $c->toArray(), $filtered_courses);
$featured_courses = array_map(fn($c) => $c->toArray(), $featured_courses);

$levels = ['beginner', 'intermediate', 'advanced'];

require 'view/partial/nav.php';
require 'view/courses.php';
require 'view/partial/footer.php';