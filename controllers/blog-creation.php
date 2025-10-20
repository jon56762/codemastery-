<?php
require_once 'includes/function.php';
require_once 'includes/auth-functions.php';

$page_title = "Create Blog Post - CodeMastery";
$current_page = 'blog';

// Require admin or instructor role for blog creation
requireAuth();
if ($_SESSION['user']['role'] !== 'admin' && $_SESSION['user']['role'] !== 'instructor') {
    $_SESSION['error'] = "You don't have permission to create blog posts.";
    header('Location: /blog');
    exit;
}

// Handle blog post creation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_post'])) {
    $title = trim($_POST['title']);
    $excerpt = trim($_POST['excerpt']);
    $content = trim($_POST['content']);
    $category = trim($_POST['category']);
    $image = trim($_POST['image']);
    
    // Validation
    if (empty($title) || empty($excerpt) || empty($content) || empty($category)) {
        $_SESSION['error'] = "Please fill in all required fields.";
    } else {
        $blog_posts = getBlogPosts();
        
        // Generate new ID
        $newId = 1;
        if (!empty($blog_posts)) {
            $ids = array_column($blog_posts, 'id');
            $newId = max($ids) + 1;
        }
        
        $new_post = [
            'id' => $newId,
            'title' => $title,
            'excerpt' => $excerpt,
            'content' => $content,
            'author' => $_SESSION['user']['name'],
            'category' => $category,
            'image' => $image ?: '/assets/images/blog/default.jpg',
            'published_at' => date('Y-m-d H:i:s'),
            'status' => 'published',
            'views' => 0,
            'likes' => 0
        ];
        
        $blog_posts[] = $new_post;
        
        if (saveToFile('blog.json', $blog_posts)) {
            $_SESSION['success'] = "Blog post published successfully!";
            header('Location: /blog');
            exit;
        } else {
            $_SESSION['error'] = "Failed to publish blog post. Please try again.";
        }
    }
}

require 'view/partial/nav.php';
require 'view/blog-creation.php';
require 'view/partial/footer.php';
?>