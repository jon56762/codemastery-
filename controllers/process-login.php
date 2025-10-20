<?php
require_once 'includes/function.php';
require_once 'includes/auth-functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    if (empty($email) || empty($password)) {
        $_SESSION['error'] = "Please fill in all fields.";
        header('Location: /login');
        exit;
    }
    
    if (loginUser($email, $password)) {
        $user = getCurrentUser();
        $_SESSION['success'] = "Welcome back, " . htmlspecialchars($user['name']) . "!";
        header('Location: /dashboard');
        exit;
    } else {
        header('Location: /login');
        exit;
    }
} else {
    // If someone tries to access this page directly without POST
    header('Location: /login');
    exit;
}
?>