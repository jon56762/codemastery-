<?php
// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database connection (loaded before anything else)
require_once __DIR__ . '/../database/db.php';

// Autoload classes
require_once __DIR__ . '/autoload.php';

// Helper (optional)
function getCurrentUserObject() {
    if (isset($_SESSION['user']['id'])) {
        return User::findById($_SESSION['user']['id']);
    }
    return null;
}