<?php
require_once 'includes/function.php';

$page_title = "Student Testimonials - CodeMastery";
$current_page = 'testimonials';

// Get approved testimonials
$all_testimonials = getFromFile('testimonials.json');
$testimonials = array_filter($all_testimonials, function($testimonial) {
    return $testimonial['status'] === 'approved';
});

// Sort by creation date (newest first)
usort($testimonials, function($a, $b) {
    return strtotime($b['created_at']) <=> strtotime($a['created_at']);
});

$platformStats = getPlatformStats();

require 'view/partial/nav.php';
require 'view/testimonials.php';
require 'view/partial/footer.php';
?>