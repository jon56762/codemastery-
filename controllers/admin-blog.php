<?php
require_once 'includes/auth-functions.php';
require_once 'includes/init.php';
requireAdmin();

$user = getCurrentUser() ?? [];

// Handle blog actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['approve_blog'])) {
        $postId = $_POST['post_id'] ?? 0;
        if (updateBlogPostStatus($postId, 'published')) {
            // Get post and author for email
            $post = getBlogPostById($postId) ?? [];
            if (!empty($post)) {
                $author = getUserById($post['author_id'] ?? 0) ?? [];
                if (!empty($author)) {
                    require_once 'includes/email-functions.php';
                    sendBlogPostApprovalEmail($author['email'] ?? '', $author['name'] ?? '', $post['title'] ?? '', $post['id'] ?? 0);
                }
            }
            $_SESSION['success'] = "Blog post approved and published successfully!";
        } else {
            $_SESSION['error'] = "Failed to approve blog post.";
        }
    } elseif (isset($_POST['reject_blog'])) {
        $postId = $_POST['post_id'] ?? 0;
        $reason = $_POST['rejection_reason'] ?? '';
        if (updateBlogPostStatus($postId, 'rejected')) {
            $_SESSION['success'] = "Blog post rejected successfully!";
            // Optionally send rejection email
        } else {
            $_SESSION['error'] = "Failed to reject blog post.";
        }
    } elseif (isset($_POST['delete_blog'])) {
        $postId = $_POST['post_id'] ?? 0;
        if (deleteBlogPost($postId)) {
            $_SESSION['success'] = "Blog post deleted successfully!";
        } else {
            $_SESSION['error'] = "Failed to delete blog post.";
        }
    }
    
    header('Location: /admin-blog');
    exit;
}

// Get all blog posts
$blog_posts = getBlogPosts() ?? [];
$pending_posts = array_filter($blog_posts, function($post) {
    return ($post['status'] ?? '') === 'pending';
});
$published_posts = array_filter($blog_posts, function($post) {
    return ($post['status'] ?? '') === 'published';
});
$draft_posts = array_filter($blog_posts, function($post) {
    return ($post['status'] ?? '') === 'draft';
});

$page_title = "Blog Moderation - Admin Panel";
$current_page = 'admin-blog';

require 'view/partial/admin-header.php';
require 'view/admin/blog.php';
require 'view/partial/admin-footer.php';
?>