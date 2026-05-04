<?php
require_once 'includes/auth-functions.php';
require_once 'includes/init.php';
requireInstructor();

$user = getCurrentUser();

// Get earnings data
$earningsData = getInstructorEarnings($user['id']);
$payouts = getInstructorPayouts($user['id']);

// Handle payout request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['request_payout'])) {
    $amount = floatval($_POST['payout_amount']);
    $availableBalance = $earningsData['available_balance'];
    
    if ($amount <= 0) {
        $_SESSION['error'] = "Please enter a valid amount.";
    } elseif ($amount > $availableBalance) {
        $_SESSION['error'] = "Requested amount exceeds available balance.";
    } else {
        $result = requestPayout($user['id'], $amount);
        if ($result) {
            $_SESSION['success'] = "Payout request submitted successfully!";
            // Refresh data
            $earningsData = getInstructorEarnings($user['id']);
            $payouts = getInstructorPayouts($user['id']);
        } else {
            $_SESSION['error'] = "Failed to submit payout request.";
        }
    }
}

$page_title = "Earnings - Instructor Panel";
$current_page = 'instructor-earnings';

require 'view/partial/instructor-header.php';
require 'view/instructor/earnings.php';
?>