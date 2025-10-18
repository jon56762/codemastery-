<?php
// Include auth functions
require_once 'includes/auth-functions.php';

$action = $_GET['action'] ?? 'login';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['login'])) {
        handleLogin($_POST);
    } elseif (isset($_POST['register'])) {
        handleRegistration($_POST);
    }
}

// Handle GET requests (login, signup, logout)
switch ($action) {
    case 'login':
        showLoginPage();
        break;
    case 'signup':
        showSignupPage();
        break;
    case 'logout':
        handleLogout();
        break;
    default:
        showLoginPage();
        break;
}

function handleLogin($data) {
    $email = trim($data['email']);
    $password = $data['password'];
    $remember = isset($data['remember']);
    
    if (empty($email) || empty($password)) {
        $_SESSION['error'] = "Please fill in all fields.";
        header('Location: ' . '/login');
        exit;
    }
    
    if (loginUser($email, $password)) {
        $user = getCurrentUser();
        $redirect_url = $_SESSION['redirect_url'] ?? getDefaultRedirect($user['role']);
        unset($_SESSION['redirect_url']);
        
        $_SESSION['success'] = "Welcome back, " . htmlspecialchars($user['name']) . "!";
        header('Location: ' . $redirect_url);
        exit;
    } else {
        header('Location: ' . '/login');
        exit;
    }
}

function handleRegistration($data) {
    $name = trim($data['name']);
    $email = trim($data['email']);
    $password = $data['password'];
    $confirm_password = $data['confirm_password'];
    
    // Validation
    if (empty($name) || empty($email) || empty($password)) {
        $_SESSION['error'] = "Please fill in all fields.";
        header('Location: ' . '/signup');
        exit;
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Please enter a valid email address.";
        header('Location: ' . '/signup');
        exit;
    }
    
    if (strlen($password) < 6) {
        $_SESSION['error'] = "Password must be at least 6 characters long.";
        header('Location: ' . '/signup');
        exit;
    }
    
    if ($password !== $confirm_password) {
        $_SESSION['error'] = "Passwords do not match.";
        header('Location: ' . '/signup');
        exit;
    }
    
    if (registerUser(['name' => $name, 'email' => $email, 'password' => $password])) {
        $user = getCurrentUser();
        $redirect_url = getDefaultRedirect($user['role']);
        
        header('Location: ' . $redirect_url);
        exit;
    } else {
        header('Location: ' . '/signup');
        exit;
    }
}

function handleLogout() {
    logoutUser();
    $_SESSION['success'] = "You have been logged out successfully.";
    header('Location: ' . '/login');
    exit;
}

function showLoginPage() {
    redirectIfLoggedIn();
    
    $page_title = "Login - CodeMastery";
    $current_page = 'login';
    require 'view/partial/nav.php';
    require 'view/login_view.php';
    require 'view/partial/footer.php';
}

function showSignupPage() {
    redirectIfLoggedIn();
    
    $page_title = "Sign Up - CodeMastery";
    $current_page = 'signup';
    require 'view/partial/nav.php';
    require 'view/signup_view.php';
    require 'view/partial/footer.php';
}

function getDefaultRedirect($role) {
    switch ($role) {
        case 'admin':
            return '/admin';
        case 'instructor':
            return '/instructor-dashboard';
        default:
            return '/dashboard';
    }
}
?>