<?php
require_once 'includes/init.php';
require_once 'includes/auth-functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    // Remove role selection - all new users are students
    $role = 'student';

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

    // Handle profile picture upload
    $avatar_path = '/assets/images/avatars/default.png';
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/avatars/';

        // Create directory if it doesn't exist
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $file_type = $_FILES['profile_picture']['type'];
        $file_size = $_FILES['profile_picture']['size'];

        // Validate file type
        if (!in_array($file_type, $allowed_types)) {
            $_SESSION['error'] = "Only JPG, PNG, and GIF files are allowed.";
            header('Location: /signup');
            exit;
        }

        // Validate file size (2MB max)
        if ($file_size > 2 * 1024 * 1024) {
            $_SESSION['error'] = "File size must be less than 2MB.";
            header('Location: /signup');
            exit;
        }

        // Generate unique filename
        $file_extension = pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION);
        $filename = 'user_' . time() . '_' . uniqid() . '.' . $file_extension;
        $filepath = $upload_dir . $filename;

        // Move uploaded file
        if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $filepath)) {
            $avatar_path = '/' . $filepath;
        } else {
            $_SESSION['error'] = "Failed to upload profile picture. Using default avatar.";
        }
    }

    // Use the createUser function with avatar - role is always 'student'
    // Use the OOP User::create method
    $newUser = User::create([
        'name'     => $name,
        'email'    => $email,
        'password' => $password,
        'role'     => 'student',
        'avatar'   => $avatar_path
    ]);

    if ($newUser) {
        // $newUser is a User object, so convert to array for session
        $_SESSION['user'] = $newUser->toArray();

        $_SESSION['success'] = "Welcome to CodeMastery! Your student account has been created successfully.";
        header('Location: /dashboard');
        exit;
    } else {
        $_SESSION['error'] = "This email is already registered. Please use a different email or login.";
        header('Location: /signup');
        exit;
    }

} else {
    header('Location: /signup');
    exit;
}

// After sending welcome email to user
$emailSent = sendWelcomeEmail($user['email'], $user['name'], $user['role']);

// Also notify admin (optional)
$adminNotified = sendNewUserRegistrationNotification($user['email'], $user['name'], $user['role']);

if (!$emailSent) {
    error_log("Welcome email failed to send to: " . $user['email']);
}
