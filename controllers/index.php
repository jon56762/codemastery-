<?php
require_once 'includes/init.php';

$page_title = "CodeMastery - Learn to Code from Industry Experts";

$platformStats = getPlatformStats();

$publishedCourses = Course::getPublished(); 

$coursesArray = array_map(fn($c) => $c->toArray(), $publishedCourses);

usort($coursesArray, fn($a, $b) => ($b['enrollment_count'] ?? 0) <=> ($a['enrollment_count'] ?? 0));
$popular_courses = array_slice($coursesArray, 0, 3);

usort($coursesArray, fn($a, $b) => strtotime($b['created_at'] ?? 'now') <=> strtotime($a['created_at'] ?? 'now'));
$new_courses = array_slice($coursesArray, 0, 4);

$testimonials = [];
if (class_exists('Testimonial')) {
    $testimonials = array_map(fn($t) => $t->toArray(), Testimonial::getApproved());
}

require 'view/partial/nav.php';
require 'view/index_view.php';
require 'view/partial/footer.php';