<?php
require_once 'includes/function.php';

$page_title = "Pricing - CodeMastery";
$current_page = 'pricing';

// Get platform statistics
$platformStats = getPlatformStats();

require 'view/partial/nav.php';
require 'view/pricing.php';
require 'view/partial/footer.php';
?>