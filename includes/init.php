<?php
//require_once __DIR__ . '/helpers.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../database/db.php';

require_once __DIR__ . '/autoload.php';

spl_autoload_register(function ($class) {
    $paths = [
        __DIR__ . '/../classes/',
        __DIR__ . '/../models/'
    ];
    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

function getCurrentUserObject() {
    if (isset($_SESSION['user']['id'])) {
        return User::findById($_SESSION['user']['id']);
    }
    return null;
}