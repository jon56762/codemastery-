<link rel="stylesheet" href="/assets/css/blog-readmore.css">
<div class="container py-5">
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item"><a href="/blog" class="text-decoration-none">Blog</a></li>
                    <li class="breadcrumb-item"><a href="/blog?category=<?= urlencode($post['category']) ?>" class="text-decoration-none"><?= htmlspecialchars($post['category']) ?></a></li>
                    <li class="breadcrumb-item active"><?= htmlspecialchars($post['title']) ?></li>
                </ol>
            </nav>

            <!-- Blog Post -->
            <article class="card border-0 shadow-sm mb-5">
                <!-- Featured Image -->
                <img src="<?= htmlspecialchars($post['image']) ?>" 
                     class="card-img-top" 
                     alt="<?= htmlspecialchars($post['title']) ?>"
                     style="height: 400px; object-fit: cover;">

                <div class="card-body p-4">
                    <!-- Category & Meta -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="badge bg-light text-dark"><?= htmlspecialchars($post['category']) ?></span>
                        <div class="text-muted small">
                            <i class="fas fa-eye me-1"></i><?= $post['views'] ?? 0 ?> views
                            <i class="fas fa-heart me-1"></i><?= $likeCount ?> likes
                            <i class="fas fa-comment ms-2 me-1"></i><?= count($comments) ?> comments
                        </div>
                    </div>

                    <!-- Title -->
                    <h1 class="card-title fw-bold mb-3"><?= htmlspecialchars($post['title']) ?></h1>

                    <!-- Excerpt -->
                    <p class="lead text-muted mb-4"><?= htmlspecialchars($post['excerpt']) ?></p>

                    <!-- Author & Date -->
                    <div class="d-flex align-items-center mb-4 pb-3 border-bottom">
                        <img src="https://via.placeholder.com/50x50/007bff/ffffff?text=<?= substr($post['author'], 0, 1) ?>" 
                             alt="<?= htmlspecialchars($post['author']) ?>" 
                             class="rounded-circle me-3" width="50" height="50">
                        <div>
                            <div class="fw-semibold"><?= htmlspecialchars($post['author']) ?></div>
                            <small class="text-muted">
                                <i class="fas fa-calendar me-1"></i>
                                <?= date('F j, Y', strtotime($post['published_at'])) ?>
                            </small>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="blog-content">
                        <?= nl2br(htmlspecialchars($post['content'])) ?>
                    </div>

                    <!-- Tags -->
                    <div class="mt-4 pt-3 border-top">
                        <strong class="me-2">Tags:</strong>
                        <?php
                        $tags = [$post['category'], 'programming', 'education'];
                        foreach ($tags as $tag): 
                        ?>
                            <span class="badge bg-light text-dark me-1">#<?= htmlspecialchars($tag) ?></span>
                        <?php endforeach; ?>
                    </div>

                    <!-- Actions -->
                    <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                        <div>
                            <!-- Like Button -->
                            <form method="POST" class="d-inline">
                                <?php if ($userLiked): ?>
                                    <button type="submit" name="like_action" value="unlike" class="btn btn-danger btn-sm me-2">
                                        <i class="fas fa-heart me-1"></i>Liked (<?= $likeCount ?>)
                                    </button>
                                <?php else: ?>
                                    <button type="submit" name="like_action" value="like" class="btn btn-outline-dark btn-sm me-2">
                                        <i class="far fa-heart me-1"></i>Like (<?= $likeCount ?>)
                                    </button>
                                <?php endif; ?>
                            </form>

                            <!-- Share Button -->
                            <button class="btn btn-outline-dark btn-sm me-2" onclick="sharePost()">
                                <i class="fas fa-share me-1"></i>Share
                            </button>

                            <?php if (isset($_SESSION['user']) && ($_SESSION['user']['role'] === 'admin' || $_SESSION['user']['role'] === 'instructor')): ?>
                                <a href="/blog-creation?edit=<?= $post['id'] ?>" class="btn btn-outline-dark btn-sm">
                                    <i class="fas fa-edit me-1"></i>Edit
                                </a>
                            <?php endif; ?>
                        </div>
                        <a href="/blog" class="btn btn-dark btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>Back to Blog
                        </a>
                    </div>
                </div>
            </article>

            <!-- Comments Section -->
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h4 class="fw-bold mb-4">
                        <i class="fas fa-comments me-2"></i>Comments (<?= count($comments) ?>)
                    </h4>

                    <!-- Add Comment Form -->
                    <?php if (isset($_SESSION['user'])): ?>
                        <div class="mb-4">
                            <form method="POST">
                                <div class="mb-3">
                                    <label for="comment_content" class="form-label fw-semibold">Add a Comment</label>
                                    <textarea class="form-control" id="comment_content" name="comment_content" 
                                              rows="3" placeholder="Share your thoughts..." 
                                              required><?= htmlspecialchars($_POST['comment_content'] ?? '') ?></textarea>
                                </div>
                                <button type="submit" name="add_comment" class="btn btn-dark">
                                    <i class="fas fa-paper-plane me-1"></i>Post Comment
                                </button>
                            </form>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info mb-4">
                            <i class="fas fa-info-circle me-2"></i>
                            Please <a href="/login" class="alert-link">log in</a> to leave a comment.
                        </div>
                    <?php endif; ?>

                    <!-- Comments List -->
                    <?php if (empty($comments)): ?>
                        <div class="text-center py-4">
                            <i class="fas fa-comment-slash fa-2x text-muted mb-3"></i>
                            <p class="text-muted">No comments yet. Be the first to comment!</p>
                        </div>
                    <?php else: ?>
                        <div class="comments-list">
                            <?php foreach ($comments as $comment): ?>
                                <div class="comment-item border-bottom pb-3 mb-3">
                                    <div class="d-flex align-items-start">
                                        <img src="https://via.placeholder.com/40x40/007bff/ffffff?text=<?= substr($comment['user_name'], 0, 1) ?>" 
                                             alt="<?= htmlspecialchars($comment['user_name']) ?>" 
                                             class="rounded-circle me-3" width="40" height="40">
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-start mb-1">
                                                <h6 class="fw-semibold mb-0"><?= htmlspecialchars($comment['user_name']) ?></h6>
                                                <small class="text-muted">
                                                    <?= date('M j, Y g:i A', strtotime($comment['created_at'])) ?>
                                                </small>
                                            </div>
                                            <p class="text-muted mb-0"><?= nl2br(htmlspecialchars($comment['content'])) ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Author Bio -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">About the Author</h5>
                    <div class="d-flex align-items-start mb-3">
                        <img src="https://via.placeholder.com/60x60/007bff/ffffff?text=<?= substr($post['author'], 0, 1) ?>" 
                             alt="<?= htmlspecialchars($post['author']) ?>" 
                             class="rounded-circle me-3" width="60" height="60">
                        <div>
                            <h6 class="fw-semibold mb-1"><?= htmlspecialchars($post['author']) ?></h6>
                            <p class="text-muted small mb-0">Experienced instructor and content creator</p>
                        </div>
                    </div>
                    <p class="text-muted small">
                        Passionate about sharing knowledge and helping students succeed in their coding journey.
                    </p>
                </div>
            </div>

            <!-- Related Posts -->
            <?php if (!empty($related_posts)): ?>
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">Related Posts</h5>
                        <?php foreach ($related_posts as $related): ?>
                            <div class="mb-3 pb-3 border-bottom">
                                <h6 class="fw-semibold mb-1">
                                    <a href="/blog/<?= $related['id'] ?>" class="text-dark text-decoration-none">
                                        <?= htmlspecialchars($related['title']) ?>
                                    </a>
                                </h6>
                                <small class="text-muted">
                                    <?= date('M j, Y', strtotime($related['published_at'])) ?>
                                </small>
                            </div>
                        <?php endforeach; ?>
                        <a href="/blog?category=<?= urlencode($post['category']) ?>" class="btn btn-outline-dark w-100">
                            View All <?= htmlspecialchars($post['category']) ?> Posts
                        </a>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Blog Stats -->
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">Blog Statistics</h5>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Total Posts:</span>
                        <span class="fw-semibold"><?= count(getBlogPosts()) ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Categories:</span>
                        <span class="fw-semibold">7</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Total Views:</span>
                        <span class="fw-semibold"><?= array_sum(array_column(getBlogPosts(), 'views')) ?></span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Community:</span>
                        <span class="fw-semibold"><?= number_format($platformStats['total_students']) ?>+ readers</span>
                    </div>
                </div>
            </div>

            <!-- Newsletter -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">Stay Updated</h5>
                    <p class="text-muted small mb-3">Get the latest blog posts and coding tips delivered to your inbox.</p>
                    <form method="POST" action="">
                        <div class="mb-3">
                            <input type="email" class="form-control" placeholder="Enter your email" required>
                        </div>
                        <button type="submit" name="newsletter_signup" class="btn btn-dark w-100">
                            <i class="fas fa-envelope me-2"></i>Subscribe
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Share functionality
function sharePost() {
    const postTitle = "<?= addslashes($post['title']) ?>";
    const postUrl = window.location.href;
    
    if (navigator.share) {
        // Use Web Share API if available
        navigator.share({
            title: postTitle,
            url: postUrl
        }).then(() => {
            console.log('Thanks for sharing!');
        }).catch(console.error);
    } else {
        // Fallback: copy to clipboard and show message
        navigator.clipboard.writeText(postUrl).then(() => {
            alert('Link copied to clipboard! Share it with your friends.');
        }).catch(() => {
            // Final fallback: show URL
            alert('Share this post: ' + postUrl);
        });
    }
}

// Add some interactive features
document.addEventListener('DOMContentLoaded', function() {
    // Smooth scrolling for comments
    const commentLinks = document.querySelectorAll('a[href="#comments"]');
    commentLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('comments').scrollIntoView({
                behavior: 'smooth'
            });
        });
    });
    
    // Auto-resize comment textarea
    const commentTextarea = document.getElementById('comment_content');
    if (commentTextarea) {
        commentTextarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
    }
});
</script>