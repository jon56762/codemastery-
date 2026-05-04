<?php
require_once 'includes/init.php';

$page_title = "Student Testimonials - CodeMastery";
$current_page = 'testimonials';

// Get approved testimonials
$all_testimonials = getFromFile('testimonials.json') ?? [];
$testimonials = array_filter($all_testimonials, function($testimonial) {
    return ($testimonial['status'] ?? '') === 'approved';
});

// Sort by creation date (newest first)
usort($testimonials, function($a, $b) {
    $a_date = $a['created_at'] ?? '1970-01-01';
    $b_date = $b['created_at'] ?? '1970-01-01';
    return strtotime($b_date) <=> strtotime($a_date);
});

$platformStats = getPlatformStats() ?? [];

require 'view/partial/nav.php';
require 'view/testimonials.php';
require 'view/partial/footer.php';
?>