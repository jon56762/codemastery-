<?php
require_once 'includes/function.php';

$page_title = "Blog - CodeMastery";
$current_page = 'blog';

// Get blog posts
$blog_posts = getBlogPosts();

require 'view/partial/nav.php';
require 'view/blog.php';
require 'view/partial/footer.php';
?>