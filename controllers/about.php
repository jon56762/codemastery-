<?php
require_once 'includes/init.php';

$page_title = "About Us - CodeMastery";
$current_page = 'about';

$platformStats = getPlatformStats();   // OOP version

require 'view/partial/nav.php';
require 'view/about_view.php';
require 'view/partial/footer.php';