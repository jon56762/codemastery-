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
    '/course-player' => 'controllers/course-player.php',
    '/dashboard' => 'controllers/dashboard.php'
];

if (preg_match('#^/course/(\d+)$#', $url, $matches)) {
    $_GET['id'] = $matches[1];
    require 'controllers/course-detail.php';
    exit;
}

if (preg_match('#^/blog/(\d+)$#', $url, $matches)) {
    $_GET['id'] = $matches[1];
    require 'controllers/blog-readmore.php';
    exit;
}

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

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['newsletter_signup'])) {
        handleNewsletterSignup($_POST['email']);
    }
}

routesToControllers($url, $routes);
?>