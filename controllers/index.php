<?php
require_once 'includes/init.php';
$page_title = "CodeMastery - Learn to Code from Industry Experts";
$testimonials = array_map(fn($t) => $t->toArray(), Testimonial::getApproved());
require 'view/partial/nav.php';
require 'view/index_view.php';
require 'view/partial/footer.php';
?>