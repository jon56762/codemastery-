<?php
require_once 'includes/init.php';

$page_title = "About Us - CodeMastery";
$current_page = 'about';

// Get platform statistics
$platformStats = getPlatformStats();

require 'view/partial/nav.php';
require 'view/about_view.php';
require 'view/partial/footer.php';
?>