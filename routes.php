<?php
session_start();

define('BASE_PATH', '/codemastery');
define('DATA_PATH', __DIR__ . '/data/');

// Load core functions
require_once 'includes/function.php';

$url = parse_url($_SERVER['REQUEST_URI'])['path'];

$routes = [
    '/' => 'controllers/index.php',
    '/courses' => 'controllers/courses.php',
    '/course' => 'controllers/course-detail.php',
    '/blog-creation' => 'controllers/blog-creation.php',
    '/about' => 'controllers/about.php',
    '/contact' => 'controllers/contact.php',
    '/pricing' => 'controllers/pricing.php',
    '/blog' => 'controllers/blog.php',
    '/login' => 'controllers/login.php',
    '/signup' => 'controllers/signup.php',
    '/dashboard' => 'controllers/dashboard.php',
    '/logout' => 'controllers/logout.php',
    '/become-instructor' => 'controllers/become-instructor.php',
    '/instructor-dashboard' => 'controllers/instructor-dashboard.php',
    '/instructor-courses' => 'controllers/instructor-courses.php',
    '/course-builder' => 'controllers/course-builder.php',
    '/profile' => 'controllers/student-profile.php',
    '/my-courses' => 'controllers/my-courses.php',
    '/process-login' => 'controllers/process-login.php',
    '/process-signup' => 'controllers/process-signup.php',
    '/instructor-analytics' => 'controllers/instructor-analytics.php',
    '/instructor-earnings' => 'controllers/instructor-earnings.php',
    '/testimonials' => 'controllers/testimonials.php',
    '/testimonial-submit' => 'controllers/testimonial-submit.php',
    '/instructor-profile' => 'controllers/instructor-profile.php',
    '/course-player' => 'controllers/course-player.php',
    '/admin' => 'controllers/admin.php',
    '/admin-analytics' => 'controllers/admin-analytics.php',
    '/admin-revenue' => 'controllers/admin-revenue.php',
    '/admin-settings' => 'controllers/admin-settings.php',
    '/admin-courses' => 'controllers/admin-courses.php',
    '/admin-users' => 'controllers/admin-users.php',
    '/admin-instructor-applications' => 'controllers/admin-instructor-applications.php',
    '/admin-blog' => 'controllers/admin-blog.php',
    '/admin-testimonials' => 'controllers/admin-testimonials.php',
    '/admin-moderation' => 'controllers/admin-moderation.php',
    '/billing' => 'controllers/billing.php',
    '/certificates' => 'controllers/certificates.php',
    '/create_admin' => 'create_admin.php'
];

// Handle course player routes
if (strpos($url, '/course-player') === 0) {
    require 'controllers/course-player.php';
    exit;
}

// Handle course detail routes
if (preg_match('#^/course/(\d+)$#', $url, $matches)) {
    $_GET['id'] = $matches[1];
    require 'controllers/course-detail.php';
    exit;
}

// Handle blog readmore routes - FIXED
if (preg_match('#^/blog/(\d+)$#', $url, $matches)) {
    $_GET['id'] = $matches[1];
    require 'controllers/blog-readmore.php';
    exit;
}

// Handle BASE_PATH in URLs
$url = str_replace(BASE_PATH, '', $url);

function routesToControllers($url, $routes) {
    if (array_key_exists($url, $routes)) {
        require $routes[$url];
    } else {
        abort();
    }
}

function abort() {
    http_response_code(404);
    require 'view/404.php';
    die();
}

// Test email route
if ($url === '/test-email' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'includes/auth-functions.php';
    require_once 'includes/function.php';
    requireAdmin();
    
    require_once 'includes/email-functions.php';
    $testEmail = $_POST['test_email'];
    
    header('Content-Type: application/json');
    if (testEmailConfiguration($testEmail)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Check your email configuration']);
    }
    exit;
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['newsletter_signup'])) {
        handleNewsletterSignup($_POST['email']);
    }
}

routesToControllers($url, $routes);
?>