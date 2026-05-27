<?php
require_once 'includes/init.php';
require_once 'includes/auth-functions.php';
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postId = $_POST['post_id'] ?? 0;
    if (isset($_POST['approve_blog'])) {
        $post = BlogPost::findById($postId);
        if ($post) {
            $post->status = 'published';
            $post->save();
            $_SESSION['success'] = "Blog post published.";
        }
    } elseif (isset($_POST['reject_blog'])) {
        $post = BlogPost::findById($postId);
        if ($post) {
            $post->status = 'rejected';
            $post->save();
            $_SESSION['success'] = "Blog post rejected.";
        }
    } elseif (isset($_POST['delete_blog'])) {
        $post = BlogPost::findById($postId);
        if ($post) {
            $post->delete(); 
            $_SESSION['success'] = "Blog post deleted.";
        }
    }
    header('Location: /admin-blog');
    exit;
}

$blog_posts = BlogPost::getAll();
$blogArray = array_map(fn($p) => $p->toArray(), $blog_posts);

$pending_posts   = array_filter($blogArray, fn($p) => $p['status'] === 'pending');
$published_posts = array_filter($blogArray, fn($p) => $p['status'] === 'published');
$draft_posts     = array_filter($blogArray, fn($p) => $p['status'] === 'draft');

$page_title = "Blog Moderation - Admin Panel";
$current_page = 'admin-blog';
require 'view/partial/admin-header.php';
require 'view/admin/blog.php';
require 'view/partial/admin-footer.php';