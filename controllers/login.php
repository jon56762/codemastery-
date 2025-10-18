<?php
require_once 'includes/auth-functions.php';
redirectIfLoggedIn();

$page_title = "Login - CodeMastery";
$current_page = 'login';
require 'view/partial/nav.php';
require 'view/login_view.php';
require 'view/partial/footer.php';
?>