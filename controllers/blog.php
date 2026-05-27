<?php
require_once 'includes/init.php';

$page_title = "Blog - CodeMastery";
$current_page = 'blog';

// Fetch all published blog posts
$blog_posts = BlogPost::getByStatus('published');
$posts_array = array_map(fn($p) => $p->toArray(), $blog_posts);

require 'view/partial/nav.php';
require 'view/blog.php';  
require 'view/partial/footer.php';