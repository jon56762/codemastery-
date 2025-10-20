<?php
// Authentication functions

function loginUser($email, $password) {
    $users = getFromFile('users.json');
    
    foreach ($users as $user) {
        if ($user['email'] === $email && password_verify($password, $user['password'])) {
            if ($user['status'] === 'active') {
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'role' => $user['role']
                ];
                return true;
            } else {
                $_SESSION['error'] = "Your account has been suspended. Please contact support.";
                return false;
            }
        }
    }
    
    $_SESSION['error'] = "Invalid email or password.";
    return false;
}

function logoutUser() {
    unset($_SESSION['user']);
    session_destroy();
}

function registerUser($userData) {
    $users = getFromFile('users.json');
    
    // Check if email already exists
    foreach ($users as $user) {
        if ($user['email'] === $userData['email']) {
            $_SESSION['error'] = "Email already registered. Please login instead.";
            return false;
        }
    }
    
    // Generate new ID
    $newId = 1;
    if (!empty($users)) {
        $ids = array_column($users, 'id');
        $newId = max($ids) + 1;
    }
    
    $user = [
        'id' => $newId,
        'name' => trim($userData['name']),
        'email' => trim($userData['email']),
        'password' => password_hash($userData['password'], PASSWORD_DEFAULT),
        'role' => $userData['role'] ?? 'student', // Use provided role or default to student
        'status' => 'active',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
    ];
    
    $users[] = $user;
    
    if (saveToFile('users.json', $users)) {
        // Auto-login after registration
        $_SESSION['user'] = [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role']
        ];
        
        $_SESSION['success'] = "Welcome to CodeMastery! Your {$user['role']} account has been created successfully.";
        return true;
    }
    
    $_SESSION['error'] = "Registration failed. Please try again.";
    return false;
}

function requireAuth() {
    if (!isset($_SESSION['user'])) {
        $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
        $_SESSION['error'] = "Please login to access this page.";
        header('Location: /login');
        exit;
    }
}

function requireRole($role) {
    requireAuth();
    
    if ($_SESSION['user']['role'] !== $role) {
        $_SESSION['error'] = "You don't have permission to access this page.";
        header('Location: /dashboard');
        exit;
    }
}

function getCurrentUser() {
    return $_SESSION['user'] ?? null;
}

function isLoggedIn() {
    return isset($_SESSION['user']);
}

function redirectIfLoggedIn() {
    if (isLoggedIn()) {
        $user = getCurrentUser();
        
        switch ($user['role']) {
            case 'admin':
                header('Location: /admin');
                break;
            case 'instructor':
                header('Location: /instructor-dashboard');
                break;
            default:
                header('Location: /dashboard');
                break;
        }
        exit;
    }
}
?>