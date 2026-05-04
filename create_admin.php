<?php
session_start();
require_once 'includes/function.php';

// Check if we're in a safe environment (localhost or development)
$isLocalhost = in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1']);

if (!$isLocalhost) {
    die("This script can only run on localhost for security reasons.");
}

// Get all users
$users = getAllUsers();

// Check if admin already exists
$adminExists = false;
foreach ($users as $user) {
    if ($user['role'] === 'admin') {
        $adminExists = true;
        break;
    }
}

if (!$adminExists) {
    // Create admin user
    $adminUser = [
        'id' => count($users) + 1,
        'name' => 'Platform Administrator',
        'email' => 'successjonathan567@gmail.com',
        'password' => password_hash('succ00$$', PASSWORD_DEFAULT),
        'role' => 'admin',
        'status' => 'active',
        'avatar' => '/assets/images/avatars/default.jpg',
        'bio' => 'System Administrator',
        'skills' => ['Management', 'Development'],
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
    ];

    $users[] = $adminUser;

    if (saveToFile('users.json', $users)) {
        echo "<h3>✅ Admin User Created Successfully!</h3>";
        echo "<p><strong>Email:</strong> admin@codemastery.com</p>";
        echo "<p><strong>Password:</strong> admin123</p>";
        echo "<p><strong>Role:</strong> Administrator</p>";
        echo "<br>";
        echo "<a href='/login' class='btn btn-primary'>Go to Login</a>";
        echo " | ";
        echo "<a href='/admin' class='btn btn-success'>Go to Admin Panel</a>";
    } else {
        echo "❌ Failed to create admin user. Check file permissions.";
    }
} else {
    echo "ℹ️ Admin user already exists. You can login with existing admin credentials.";
    
    // Show existing admin users
    echo "<h4>Existing Admin Users:</h4>";
    foreach ($users as $user) {
        if ($user['role'] === 'admin') {
            echo "<p><strong>Email:</strong> {$user['email']} | <strong>Name:</strong> {$user['name']}</p>";
        }
    }
    
    echo "<br>";
    echo "<a href='/login' class='btn btn-primary'>Go to Login</a>";
}
?>