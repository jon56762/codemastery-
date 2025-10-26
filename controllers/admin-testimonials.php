<?php
require_once 'includes/auth-functions.php';
require_once 'includes/function.php';
requireAdmin();

$user = getCurrentUser();

// Handle testimonial actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['approve_testimonial'])) {
        $testimonialId = $_POST['testimonial_id'];
        if (approveTestimonial($testimonialId)) {
            $_SESSION['success'] = "Testimonial approved successfully!";
        } else {
            $_SESSION['error'] = "Failed to approve testimonial.";
        }
    } elseif (isset($_POST['reject_testimonial'])) {
        $testimonialId = $_POST['testimonial_id'];
        if (rejectTestimonial($testimonialId)) {
            $_SESSION['success'] = "Testimonial rejected successfully!";
        } else {
            $_SESSION['error'] = "Failed to reject testimonial.";
        }
    } elseif (isset($_POST['delete_testimonial'])) {
        $testimonialId = $_POST['testimonial_id'];
        if (deleteTestimonial($testimonialId)) {
            $_SESSION['success'] = "Testimonial deleted successfully!";
        } else {
            $_SESSION['error'] = "Failed to delete testimonial.";
        }
    }
    
    header('Location: /admin-testimonials');
    exit;
}

// Get all testimonials
$testimonials = getTestimonials();
$pending_testimonials = array_filter($testimonials, function($testimonial) {
    return ($testimonial['status'] ?? 'pending') === 'pending';
});
$approved_testimonials = array_filter($testimonials, function($testimonial) {
    return ($testimonial['status'] ?? 'approved') === 'approved';
});
$rejected_testimonials = array_filter($testimonials, function($testimonial) {
    return ($testimonial['status'] ?? 'approved') === 'rejected';
});

$page_title = "Testimonials Moderation - Admin Panel";
$current_page = 'admin-testimonials';

require 'view/partial/admin-header.php';
require 'view/admin/testimonials.php';
require 'view/partial/admin-footer.php';
?>