<?php
require_once 'includes/functions.php';
require_once 'includes/auth-functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validation
    if (empty($name) || empty($email) || empty($password)) {
        $_SESSION['error'] = "Please fill in all fields.";
        header('Location: /signup');
        exit;
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Please enter a valid email address.";
        header('Location: /signup');
        exit;
    }
    
    if (strlen($password) < 6) {
        $_SESSION['error'] = "Password must be at least 6 characters long.";
        header('Location: /signup');
        exit;
    }
    
    if ($password !== $confirm_password) {
        $_SESSION['error'] = "Passwords do not match.";
        header('Location: /signup');
        exit;
    }
    
    // Use the createUser function from functions.php
    $user = createUser([
        'name' => $name, 
        'email' => $email, 
        'password' => $password,
        'role' => 'student'
    ]);
    
    if ($user) {
        // Auto-login after registration
        $_SESSION['user'] = [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role']
        ];
        
        $_SESSION['success'] = "Welcome to CodeMastery! Your account has been created successfully.";
        header('Location: /dashboard');
        exit;
    } else {
        $_SESSION['error'] = "This email is already registered. Please use a different email or login.";
        header('Location: /signup');
        exit;
    }
} else {
    // If someone tries to access this page directly without POST
    header('Location: /signup');
    exit;
}
?>