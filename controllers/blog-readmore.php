<?php
require_once 'includes/init.php';
require_once 'includes/auth-functions.php';

$page_title = "Blog Post - CodeMastery";
$current_page = 'blog';

$postId = $_GET['id'] ?? 0;

$post = BlogPost::findById($postId);

if (!$post) {
    http_response_code(404);
    require 'view/404.php';
    exit;
}

if (!is_array($post->likes)) {
    $post->likes = [];
}

$likeCount = count($post->likes);
$userLiked = false;
if (isset($_SESSION['user'])) {
    $userLiked = in_array($_SESSION['user']['id'], $post->likes);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['like_action'])) {
    if (!isset($_SESSION['user'])) {
        $_SESSION['error'] = "Please log in to like posts.";
    } else {
        $userId = $_SESSION['user']['id'];
        if ($_POST['like_action'] === 'like') {
            $post->like($userId);
        } else {
            $post->unlike($userId);
        }
        $post->save();
        header("Location: /blog/" . $postId);
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_comment'])) {
    if (!isset($_SESSION['user'])) {
        $_SESSION['error'] = "Please log in to comment.";
    } else {
        $content = trim($_POST['comment_content']);
        if (!empty($content)) {
            $db = Database::getConnection();
            $stmt = $db->prepare("INSERT INTO blog_comments (post_id, user_id, user_name, content) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("iiss", $postId, $_SESSION['user']['id'], $_SESSION['user']['name'], $content);
            $stmt->execute();
            $_SESSION['success'] = "Comment added!";
        } else {
            $_SESSION['error'] = "Please enter a comment.";
        }
        header("Location: /blog/" . $postId);
        exit;
    }
}

$db = Database::getConnection();
$result = $db->prepare("SELECT * FROM blog_comments WHERE post_id = ? AND status = 'approved' ORDER BY created_at ASC");
$result->bind_param("i", $postId);
$result->execute();
$comments = $result->get_result()->fetch_all(MYSQLI_ASSOC);


$platformStats = getPlatformStats();

$page_title = $post->title . " - CodeMastery Blog";
require 'view/partial/nav.php';
require 'view/blog-readmore.php';
require 'view/partial/footer.php';