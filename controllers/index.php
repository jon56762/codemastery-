<?php
// Get data for landing page
$featuredCourses = getFeaturedCourses();
$testimonials = getTestimonials();
$platformStats = getPlatformStats();
$keyBenefits = getKeyBenefits();

$page_title = "Learn to Code - Master Programming Skills";
require 'view/partial/nav.php';
require 'view/index_view.php';
require 'view/partial/footer.php';
?>