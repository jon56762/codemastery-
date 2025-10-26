<?php
require_once 'includes/auth-functions.php';
require_once 'includes/function.php';
requireAdmin();

$user = getCurrentUser();

// Handle payout actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['process_payout'])) {
        $payoutId = $_POST['payout_id'];
        if (processPayout($payoutId)) {
            $_SESSION['success'] = "Payout processed successfully!";
        } else {
            $_SESSION['error'] = "Failed to process payout.";
        }
    } elseif (isset($_POST['update_commission'])) {
        $newRate = floatval($_POST['commission_rate']);
        if (updateCommissionRate($newRate)) {
            $_SESSION['success'] = "Commission rate updated successfully!";
        } else {
            $_SESSION['error'] = "Failed to update commission rate.";
        }
    }
    
    header('Location: /admin-revenue');
    exit;
}

// Get real revenue data from file storage
$revenueData = getRealPlatformRevenue();
$pendingPayouts = getRealPendingPayouts();

$page_title = "Revenue Management - Admin Panel";
$current_page = 'admin-revenue';

require 'view/partial/admin-header.php';
require 'view/admin/revenue.php';
require 'view/partial/admin-footer.php';
?>