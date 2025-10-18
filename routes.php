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
    '/about' => 'controllers/about.php',
    '/contact' => 'controllers/contact.php',
    '/pricing' => 'controllers/pricing.php',
    '/blog' => 'controllers/blog.php',
    '/login' => 'controllers/auth.php',
    '/signup' => 'controllers/auth.php',
    '/logout' => 'controllers/auth.php'
];

function routesToControllers($url, $routes) {
    if (array_key_exists($url, $routes)) {
        require $routes[$url];
    } else {
        abort();
    }
}

function abort() {
    http_response_code(404);
    require 'views/404.php';
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