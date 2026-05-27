<?php
require_once 'includes/init.php';
require_once 'includes/auth-functions.php';

if (!isset($_SESSION['user'])) { ... redirect to login }

$user = getCurrentUserObject();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_testimonial'])) {
    $text = trim($_POST['testimonial_text']);
    $rating = intval($_POST['rating']);
    $role = trim($_POST['role']);

    // validation...

    // Check if user already submitted
    $existing = array_filter(Testimonial::getAll(), fn($t) => $t->userId == $user->getId());
    if (!empty($existing)) {
        $_SESSION['error'] = "You already submitted a testimonial.";
    } else {
        $testimonial = Testimonial::submit([
            'user_id' => $user->getId(),
            'name'    => $user->getName(),
            'role'    => $role,
            'avatar'  => $user->getAvatar(),
            'text'    => $text,
            'rating'  => $rating
        ]);
        if ($testimonial) {
            $_SESSION['success'] = "Thank you! Pending review.";
            header('Location: /testimonials');
            exit;
        } else {
            $_SESSION['error'] = "Submission failed.";
        }
    }
}

require 'view/partial/nav.php';
require 'view/testimonial-submit.php';
require 'view/partial/footer.php';