<?php
require_once 'includes/init.php';

$page_title = "Courses - CodeMastery";
$current_page = 'courses';

$publishedCourses = Course::getPublished();

$courses = array_map(fn($c) => $c->toArray(), $publishedCourses);

$search   = $_GET['search']   ?? '';
$category = $_GET['category'] ?? '';
$level    = $_GET['level']    ?? '';
$price    = $_GET['price']    ?? '';
$sort     = $_GET['sort']     ?? 'newest';

if ($search) {
    $courses = array_filter($courses, fn($c) =>
        stripos($c['title'], $search) !== false ||
        stripos($c['description'], $search) !== false
    );
}
if ($category) {
    $courses = array_filter($courses, fn($c) => $c['category'] === $category);
}
if ($level) {
    $courses = array_filter($courses, fn($c) => $c['level'] === $level);
}
if ($price === 'free') {
    $courses = array_filter($courses, fn($c) => $c['price'] == 0);
} elseif ($price === 'paid') {
    $courses = array_filter($courses, fn($c) => $c['price'] > 0);
}

usort($courses, function($a, $b) use ($sort) {
    switch ($sort) {
        case 'price_low': return $a['price'] <=> $b['price'];
        case 'price_high': return $b['price'] <=> $a['price'];
        case 'rating': return ($b['rating'] ?? 0) <=> ($a['rating'] ?? 0);
        case 'popular': return ($b['enrollment_count'] ?? 0) <=> ($a['enrollment_count'] ?? 0);
        default: return strtotime($b['created_at'] ?? 'now') <=> strtotime($a['created_at'] ?? 'now');
    }
});

$featuredCourses = array_map(fn($c) => $c->toArray(), Course::getFeatured(3));

$allCategories = [];
foreach ($publishedCourses as $c) {
    if (!empty($c->category)) $allCategories[] = $c->category;
}
$categories = array_unique($allCategories);
sort($categories);

require 'view/partial/nav.php';
require 'view/courses.php';
require 'view/partial/footer.php';