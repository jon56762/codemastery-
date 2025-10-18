<?php
require_once 'includes/auth-functions.php';
redirectIfLoggedIn();

$page_title = "Sign Up - CodeMastery";
$current_page = 'signup';
require 'view/partial/nav.php';
require 'view/signup_view.php';
require 'view/partial/footer.php';
?>