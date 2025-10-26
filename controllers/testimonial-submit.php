<?php
require_once 'includes/function.php';
require_once 'includes/auth-functions.php';

$page_title = "Share Your Experience - CodeMastery";
$current_page = 'testimonial';

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    $_SESSION['error'] = "Please log in to share your experience.";
    $_SESSION['redirect_url'] = '/testimonial-submit';
    header('Location: /login');
    exit;
}

$user = getCurrentUser();

// Handle testimonial submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_testimonial'])) {
    $testimonial_text = trim($_POST['testimonial_text']);
    $rating = intval($_POST['rating']);
    $role = trim($_POST['role']);
    
    // Validation
    if (empty($testimonial_text)) {
        $_SESSION['error'] = "Please share your experience in the testimonial text.";
    } elseif (strlen($testimonial_text) < 10) {
        $_SESSION['error'] = "Testimonial text should be at least 10 characters long.";
    } elseif ($rating < 1 || $rating > 5) {
        $_SESSION['error'] = "Please provide a valid rating (1-5 stars).";
    } elseif (empty($role)) {
        $_SESSION['error'] = "Please specify your current role.";
    } else {
        // Check if user already submitted a testimonial
        $existing_testimonials = getFromFile('testimonials.json');
        $user_has_testimonial = false;
        
        foreach ($existing_testimonials as $testimonial) {
            if ($testimonial['user_id'] == $user['id']) {
                $user_has_testimonial = true;
                break;
            }
        }
        
        if ($user_has_testimonial) {
            $_SESSION['error'] = "You have already submitted a testimonial. Thank you!";
        } else {
            // Submit new testimonial
            $testimonial_data = [
                'id' => count($existing_testimonials) + 1,
                'user_id' => $user['id'],
                'name' => $user['name'],
                'role' => $role,
                'avatar' => $user['avatar'] ?? '/assets/images/avatars/default.jpg',
                'text' => $testimonial_text,
                'rating' => $rating,
                'status' => 'pending', // Admin can approve testimonials
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            $existing_testimonials[] = $testimonial_data;
            
            if (saveToFile('testimonials.json', $existing_testimonials)) {
                $_SESSION['success'] = "Thank you for sharing your experience! Your testimonial has been submitted for review.";
                header('Location: /testimonials');
                exit;
            } else {
                $_SESSION['error'] = "Failed to submit testimonial. Please try again.";
            }
        }
    }
}

require 'view/partial/nav.php';
require 'view/testimonial-submit.php';
require 'view/partial/footer.php';
?>