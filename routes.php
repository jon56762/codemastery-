<?php
session_start();

define('BASE_PATH', '/codemastery');
define('DATA_PATH', __DIR__ . '/data/');

// Load core functions
require_once 'includes/function.php';

$url = parse_url($_SERVER['REQUEST_URI'])['path'];

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
// $url = str_replace(BASE_PATH, '', $url);

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


$routes = [];


function route($path, $controller, $method) {
    global $routes;
    $routes[$path] = ['controller' => $controller, 'method' => $method];
}

function run() {
    global $routes;
    $uri = parse_url($_SERVER['REQUEST_URI'])['path'];
    if (array_key_exists($uri, $routes)) {
        $controllerName = $routes[$uri]['controller'];
        $methodName = $routes[$uri]['method'];

        $controller = new $controllerName();
        $controller->$methodName();
    }else {
        http_response_code(404);
        require 'view/404.php';
    }
    
}

function view($fileName) {
    return require_once "controllers/{$fileName}.php";
}

?>