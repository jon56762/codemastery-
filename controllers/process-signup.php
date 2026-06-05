<?php
require_once 'includes/init.php';
require_once 'includes/auth-functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
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

    $avatar_path = '/assets/images/avatars/default.png';
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/avatars/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $file_type = $_FILES['profile_picture']['type'];
        $file_size = $_FILES['profile_picture']['size'];

        if (!in_array($file_type, $allowed_types)) {
            $_SESSION['error'] = "Only JPG, PNG, and GIF files are allowed.";
            header('Location: /signup');
            exit;
        }

        if ($file_size > 2 * 1024 * 1024) {
            $_SESSION['error'] = "File size must be less than 2MB.";
            header('Location: /signup');
            exit;
        }

        $file_extension = pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION);
        $filename = 'user_' . time() . '_' . uniqid() . '.' . $file_extension;
        $filepath = $upload_dir . $filename;

        if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $filepath)) {
            $avatar_path = '/' . $filepath;
        } else {
            $_SESSION['error'] = "Failed to upload profile picture. Using default avatar.";
        }
    }

    $newUser = User::create([
        'name'     => $name,
        'email'    => $email,
        'password' => $password,
        'role'     => 'student',
        'avatar'   => $avatar_path
    ]);

    if ($newUser !== false) {
    
        $_SESSION['user'] = $newUser->toArray();
        $_SESSION['success'] = "Welcome to CodeMastery! Your student account has been created successfully.";
        if (function_exists('sendWelcomeEmail')) {

            $emailSent = sendWelcomeEmail($newUser->getEmail(), $newUser->getName(), $newUser->getRole());
            if (!$emailSent) {
                error_log("Welcome email failed to send to: " . $newUser->getEmail());
            }
        }

        if (function_exists('sendNewUserRegistrationNotification')) {
            $adminNotified = sendNewUserRegistrationNotification($newUser->getEmail(), $newUser->getName(), $newUser->getRole());
        }

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
?>