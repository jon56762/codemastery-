<?php
require_once 'includes/init.php';
require_once 'includes/auth-functions.php';
requireAuth();
if ($_SESSION['user']['role'] !== 'admin' && $_SESSION['user']['role'] !== 'instructor') {
    $_SESSION['error'] = "Permission denied.";
    header('Location: /blog');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_post'])) {
    $title = trim($_POST['title']);
    $excerpt = trim($_POST['excerpt']);
    $content = trim($_POST['content']);
    $category = trim($_POST['category']);
    $image = trim($_POST['image']);

    if (empty($title) || empty($excerpt) || empty($content) || empty($category)) {
        $_SESSION['error'] = "All fields required.";
    } else {
        $post = BlogPost::create([
            'title'    => $title,
            'excerpt'  => $excerpt,
            'content'  => $content,
            'author'   => $_SESSION['user']['name'],
            'author_id'=> $_SESSION['user']['id'],
            'category' => $category,
            'image'    => $image ?: '/assets/images/blog/default.jpg',
            'status'   => 'published'
        ]);
        if ($post) {
            $_SESSION['success'] = "Blog post published!";
            header('Location: /blog/' . $post->id);
            exit;
        } else {
            $_SESSION['error'] = "Failed to publish.";
        }
    }
}

require 'view/partial/nav.php';
require 'view/blog-creation.php';
require 'view/partial/footer.php';