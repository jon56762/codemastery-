<div class="container py-5">
    <div class="row">
        <div class="col-lg-8">
            <h1 class="fw-bold mb-3"><?= htmlspecialchars($post->title) ?></h1>
            <p class="text-muted">
                By <?= htmlspecialchars($post->author) ?> • <?= date('F j, Y', strtotime($post->publishedAt)) ?> • <?= $likeCount ?> likes
            </p>
            <hr>
            <div class="mb-4">
                <?= $post->content ?>
            </div>

            <!-- Like button -->
            <?php if (isset($_SESSION['user'])): ?>
                <form method="POST" class="d-inline">
                    <?php if ($userLiked): ?>
                        <button type="submit" name="like_action" value="unlike" class="btn btn-outline-danger btn-sm">
                            <i class="fas fa-heart"></i> Unlike
                        </button>
                    <?php else: ?>
                        <button type="submit" name="like_action" value="like" class="btn btn-outline-primary btn-sm">
                            <i class="far fa-heart"></i> Like
                        </button>
                    <?php endif; ?>
                </form>
            <?php else: ?>
                <a href="/login" class="btn btn-outline-primary btn-sm">Login to Like</a>
            <?php endif; ?>

            <hr>
            <h4>Comments (<?= count($comments) ?>)</h4>
            <?php foreach ($comments as $comment): ?>
                <div class="mb-3 p-3 border rounded">
                    <strong><?= htmlspecialchars($comment['user_name']) ?></strong>
                    <small class="text-muted"><?= date('M j, Y', strtotime($comment['created_at'])) ?></small>
                    <p class="mt-1 mb-0"><?= htmlspecialchars($comment['content']) ?></p>
                </div>
            <?php endforeach; ?>

            <?php if (isset($_SESSION['user'])): ?>
                <form method="POST" class="mt-4">
                    <textarea name="comment_content" rows="3" class="form-control mb-2" placeholder="Add a comment..." required></textarea>
                    <button type="submit" name="add_comment" class="btn btn-primary btn-sm">Post Comment</button>
                </form>
            <?php else: ?>
                <p><a href="/login">Log in</a> to leave a comment.</p>
            <?php endif; ?>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="fw-bold">About CodeMastery</h5>
                    <p class="small text-muted">Learn to code from industry experts. Join <?= number_format($platformStats['total_students'] ?? 0) ?>+ students.</p>
                </div>
            </div>
        </div>
    </div>
</div>