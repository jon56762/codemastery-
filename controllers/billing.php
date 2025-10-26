<?php
require_once 'includes/auth-functions.php';
require_once 'includes/function.php';
requireAuth();

$user = getCurrentUser();

// Handle billing actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_payment_method'])) {
        $paymentData = [
            'card_number' => substr($_POST['card_number'], -4),
            'expiry_date' => $_POST['expiry_date'],
            'card_holder' => $_POST['card_holder']
        ];
        
        if (updatePaymentMethod($user['id'], $paymentData)) {
            $_SESSION['success'] = "Payment method updated successfully!";
        } else {
            $_SESSION['error'] = "Failed to update payment method.";
        }
    } elseif (isset($_POST['request_refund'])) {
        $purchaseId = $_POST['purchase_id'];
        $reason = $_POST['refund_reason'] ?? '';
        if (requestRefund($user['id'], $purchaseId, $reason)) {
            $_SESSION['success'] = "Refund request submitted successfully!";
        } else {
            $_SESSION['error'] = "Failed to submit refund request.";
        }
    }
    
    header('Location: /billing');
    exit;
}

// Get student billing data
$purchaseHistory = getStudentPurchaseHistory($user['id']);
$paymentMethods = getStudentPaymentMethods($user['id']);
$subscriptions = getStudentSubscriptions($user['id']);

$page_title = "Billing & Purchases - CodeMastery";
$current_page = 'billing';

require 'view/partial/nav.php';
require 'view/student/billing.php';
require 'view/partial/footer.php';
?>