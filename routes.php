<?php
session_start();

define('BASE_PATH', '/learning-platform');
define('DATA_PATH', __DIR__ . '/data/');

$url = parse_url($_SERVER['REQUEST_URI'])['path'];

$routes = [
    '/' => 'controllers/index.php',
    '/about' => 'controllers/about.php',
    '/contact' => 'controllers/contact.php',
    '/courses' => 'controllers/courses.php',
    '/pricing' => 'controllers/pricing.php',
    '/blog' => 'controllers/blog.php',
    '/login' => 'controllers/auth.php',
    '/signup' => 'controllers/auth.php',
    '/dashboard' => 'controllers/dashboard.php'
];

function routesToControllers($url, $routes)
{
    if (array_key_exists($url, $routes)) {
        require $routes[$url];
    } else {
        // Check for dynamic routes like /course/{id}
        if (preg_match('/^\/course\/(\d+)$/', $url, $matches)) {
            $_GET['id'] = $matches[1];
            require 'controllers/course-detail.php';
        } elseif (preg_match('/^\/course\/(\d+)\/lesson\/(\d+)$/', $url, $matches)) {
            $_GET['course_id'] = $matches[1];
            $_GET['lesson_id'] = $matches[2];
            require 'controllers/lesson-player.php';
        } else {
            abort();
        }
    }
}

function abort()
{
    http_response_code(404);
    require 'views/404.php';
    die();
}

// Load data functions
require_once 'includes/function.php';

// Handle form submissions for all pages
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['newsletter_email'])) {
        handleNewsletterSignup();
    }
    if (isset($_POST['quick_signup'])) {
        handleQuickSignup();
    }
}

routesToControllers($url, $routes);

// Form handling functions
function handleNewsletterSignup() {
    $email = filter_var($_POST['newsletter_email'], FILTER_VALIDATE_EMAIL);
    if ($email) {
        $newsletter = json_decode(file_get_contents(DATA_PATH . 'newsletter.json'), true) ?? [];
        $newsletter[] = [
            'email' => $email,
            'date' => date('Y-m-d H:i:s')
        ];
        file_put_contents(DATA_PATH . 'newsletter.json', json_encode($newsletter, JSON_PRETTY_PRINT));
        $_SESSION['newsletter_success'] = "Thanks for subscribing! We'll keep you updated.";
    }
}

function handleQuickSignup() {
    $name = trim($_POST['name']);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    
    if ($name && $email) {
        $signups = json_decode(file_get_contents(DATA_PATH . 'quick_signups.json'), true) ?? [];
        $signups[] = [
            'name' => $name,
            'email' => $email,
            'date' => date('Y-m-d H:i:s')
        ];
        file_put_contents(DATA_PATH . 'quick_signups.json', json_encode($signups, JSON_PRETTY_PRINT));
        $_SESSION['signup_success'] = "Welcome aboard, $name! Check your email to get started.";
    }
}
?>