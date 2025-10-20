<?php
require_once 'includes/function.php';

$page_title = "Blog Post - CodeMastery";
$current_page = 'blog';

// Get blog post ID from URL
$postId = $_GET['id'] ?? 0;
$blog_posts = getBlogPosts();
$post = null;

// Find the specific blog post
foreach ($blog_posts as $blog_post) {
    if ($blog_post['id'] == $postId) {
        $post = $blog_post;
        break;
    }
}

// If post not found, show 404
if (!$post) {
    http_response_code(404);
    require 'views/404.php';
    exit;
}

// Update page title with post title
$page_title = $post['title'] . " - CodeMastery Blog";

// Get related posts (same category)
$related_posts = array_filter($blog_posts, function($p) use ($post) {
    return $p['category'] === $post['category'] && $p['id'] != $post['id'];
});
$related_posts = array_slice($related_posts, 0, 3);

// Get platform stats for sidebar
$platformStats = getPlatformStats();

// Get comments for this post
$comments = getComments($postId);

// Check if user liked this post
$userLiked = false;
$likeCount = getLikeCount($postId);
if (isset($_SESSION['user'])) {
    $userLiked = hasUserLikedPost($postId, $_SESSION['user']['id']);
}

// Handle like/unlike action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['like_action'])) {
    if (!isset($_SESSION['user'])) {
        $_SESSION['error'] = "Please log in to like posts.";
    } else {
        $userId = $_SESSION['user']['id'];
        
        if ($_POST['like_action'] === 'like') {
            if (likeBlogPost($postId, $userId)) {
                $userLiked = true;
                $likeCount = getLikeCount($postId);
                $_SESSION['success'] = "Post liked!";
            }
        } elseif ($_POST['like_action'] === 'unlike') {
            if (unlikeBlogPost($postId, $userId)) {
                $userLiked = false;
                $likeCount = getLikeCount($postId);
                $_SESSION['success'] = "Post unliked!";
            }
        }
        
        // Redirect to avoid form resubmission
        header("Location: /blog/" . $postId);
        exit;
    }
}

// Handle comment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_comment'])) {
    if (!isset($_SESSION['user'])) {
        $_SESSION['error'] = "Please log in to comment.";
    } else {
        $content = trim($_POST['comment_content']);
        
        if (empty($content)) {
            $_SESSION['error'] = "Please enter a comment.";
        } else {
            $commentData = [
                'user_id' => $_SESSION['user']['id'],
                'user_name' => $_SESSION['user']['name'],
                'content' => $content
            ];
            
            if (addComment($postId, $commentData)) {
                $_SESSION['success'] = "Comment added successfully!";
                // Refresh comments
                $comments = getComments($postId);
                // Clear form
                unset($_POST['comment_content']);
            } else {
                $_SESSION['error'] = "Failed to add comment. Please try again.";
            }
        }
    }
}

require 'view/partial/nav.php';
require 'view/blog-readmore.php';
require 'view/partial/footer.php';
?>