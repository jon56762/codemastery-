<?php
require_once 'includes/init.php';
require_once 'includes/auth-functions.php';
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['testimonial_id'] ?? 0;
    if (isset($_POST['approve_testimonial'])) {
        Testimonial::approve($id);
        $_SESSION['success'] = "Testimonial approved.";
    } elseif (isset($_POST['reject_testimonial'])) {
        Testimonial::reject($id);
        $_SESSION['success'] = "Testimonial rejected.";
    } elseif (isset($_POST['delete_testimonial'])) {
        $t = Testimonial::findById($id);
        if ($t) {
            $t->delete();
            $_SESSION['success'] = "Testimonial deleted.";
        }
    }
    header('Location: /admin-testimonials');
    exit;
}

$testimonials = Testimonial::getAll();
$testimonialsArray = array_map(fn($t) => $t->toArray(), $testimonials);

$pending_testimonials   = array_filter($testimonialsArray, fn($t) => $t['status'] === 'pending');
$approved_testimonials  = array_filter($testimonialsArray, fn($t) => $t['status'] === 'approved');
$rejected_testimonials  = array_filter($testimonialsArray, fn($t) => $t['status'] === 'rejected');

$page_title = "Testimonials Moderation - Admin Panel";
$current_page = 'admin-testimonials';
require 'view/partial/admin-header.php';
require 'view/admin/testimonials.php';
require 'view/partial/admin-footer.php';