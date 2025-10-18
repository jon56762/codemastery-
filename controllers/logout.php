<?php
require_once 'includes/auth-functions.php';
logoutUser();
$_SESSION['success'] = "You have been logged out successfully.";
header('Location: /login');
exit;
?>