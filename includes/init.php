<?php
// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../database/db.php';

require_once __DIR__ . '/autoload.php';

function getCurrentUserObject() {
    if (isset($_SESSION['user']['id'])) {
        return User::findById($_SESSION['user']['id']);
    }
    return null;
}