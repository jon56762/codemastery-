<?php
require_once 'includes/init.php';

$page_title = "Pricing - CodeMastery";
$current_page = 'pricing';

$platformStats = [
    'total_students'    => count(User::findByRole('student')),
    'total_courses'     => count(Course::getPublished()),
    'total_instructors' => count(User::findByRole('instructor')),
    'total_enrollments' => 0,   // <-- added
    'average_rating'    => 4.5,
];

require 'view/partial/nav.php';
require 'view/pricing.php';
require 'view/partial/footer.php';