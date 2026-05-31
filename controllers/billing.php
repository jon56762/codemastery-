<?php
require_once 'includes/init.php';
require_once 'includes/auth-functions.php';
requireAuth();

$user = getCurrentUser();

$purchaseHistory = [];
$paymentMethods  = [];
$subscriptions   = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['success'] = "Payment method updated (placeholder).";
    header('Location: /billing');
    exit;
}

$page_title = "Billing & Purchases - CodeMastery";
$current_page = 'billing';
require 'view/partial/nav.php';
require 'view/student/billing.php';
require 'view/partial/footer.php';