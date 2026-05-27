<?php
require_once 'includes/init.php';

$page_title = "Student Testimonials - CodeMastery";
$current_page = 'testimonials';

$testimonials = array_map(fn($t) => $t->toArray(), Testimonial::getApproved());

$platformStats = [
    'total_students'    => count(User::findByRole('student')),
    'total_courses'     => count(Course::getPublished()),
    'total_instructors' => count(User::findByRole('instructor')),
    'average_rating'    => 4.5,   
];

require 'view/partial/nav.php';
require 'view/testimonials.php';
require 'view/partial/footer.php';