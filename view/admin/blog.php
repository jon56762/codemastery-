<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 fw-bold text-dark mb-1">Blog Moderation</h1>
        <p class="text-muted mb-0">Manage and moderate blog posts</p>
    </div>
    <!-- <div class="d-flex gap-2">
        <div class="dropdown">
            <button class="btn btn-outline-dark dropdown-toggle" type="button" data-bs-toggle="dropdown">
                <i class="fas fa-filter me-2"></i>Filter
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="?status=all">All Posts</a></li>
                <li><a class="dropdown-item" href="?status=pending">Pending Review</a></li>
                <li><a class="dropdown-item" href="?status=published">Published</a></li>
                <li><a class="dropdown-item" href="?status=draft">Draft</a></li>
            </ul>
        </div>
    </div> -->
</div>

<!-- Success/Error Messages -->
<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= $_SESSION['success'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= $_SESSION['error'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-muted mb-2">Total Posts</h6>
                        <h3 class="fw-bold text-dark"><?= count($blog_posts) ?></h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-blog fa-2x text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-muted mb-2">Pending Review</h6>
                        <h3 class="fw-bold text-warning"><?= count($pending_posts) ?></h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-clock fa-2x text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-muted mb-2">Published</h6>
                        <h3 class="fw-bold text-success"><?= count($published_posts) ?></h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-check-circle fa-2x text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-muted mb-2">Drafts</h6>
                        <h3 class="fw-bold text-info"><?= count($draft_posts) ?></h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-edit fa-2x text-info"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Blog Posts Table -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 py-3">
        <h5 class="fw-bold mb-0">All Blog Posts</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Post</th>
                        <th>Author</th>
                        <th>Category</th>
                        <th>Likes</th>
                        <th>Comments</th>
                        <th>Status</th>
                        <th>Published</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($blog_posts as $post): ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="<?= htmlspecialchars($post['image'] ?? '/assets/images/blog/default.jpg') ?>" 
                                         alt="<?= htmlspecialchars($post['title']) ?>" 
                                         class="rounded me-3" width="50" height="40" style="object-fit: cover;">
                                    <div>
                                        <div class="fw-semibold"><?= htmlspecialchars($post['title']) ?></div>
                                        <small class="text-muted">
                                            <?= htmlspecialchars(substr($post['excerpt'], 0, 60)) ?>...
                                        </small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <small><?= htmlspecialchars($post['author']) ?></small>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark"><?= htmlspecialchars($post['category']) ?></span>
                            </td>
                            <td>
                                <small class="text-muted">
                                    <i class="fas fa-heart text-danger me-1"></i>
                                    <?= getLikeCount($post['id']) ?>
                                </small>
                            </td>
                            <td>
                                <small class="text-muted">
                                    <i class="fas fa-comment text-info me-1"></i>
                                    <?= getCommentCount($post['id']) ?>
                                </small>
                            </td>
                            <td>
                                <span class="badge bg-<?= getStatusBadgeColor($post['status']) ?>">
                                    <?= ucfirst($post['status']) ?>
                                </span>
                            </td>
                            <td>
                                <small class="text-muted">
                                    <?= date('M j, Y', strtotime($post['published_at'])) ?>
                                </small>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-dark dropdown-toggle" type="button" 
                                            data-bs-toggle="dropdown">
                                        Actions
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item" href="/blog/<?= $post['id'] ?>" target="_blank">
                                                <i class="fas fa-eye me-2"></i>View Post
                                            </a>
                                        </li>
                                        <?php if ($post['status'] === 'pending'): ?>
                                            <li>
                                                <form method="POST" class="d-inline">
                                                    <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                                                    <button type="submit" name="approve_blog" class="dropdown-item text-success">
                                                        <i class="fas fa-check me-2"></i>Approve
                                                    </button>
                                                </form>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item text-danger" 
                                                        data-bs-toggle="modal" data-bs-target="#rejectBlogModal<?= $post['id'] ?>">
                                                    <i class="fas fa-times me-2"></i>Reject
                                                </button>
                                            </li>
                                        <?php elseif ($post['status'] === 'published'): ?>
                                            <li>
                                                <form method="POST" class="d-inline">
                                                    <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                                                    <button type="submit" name="delete_blog" class="dropdown-item text-danger"
                                                            onclick="return confirm('Are you sure you want to delete this blog post?')">
                                                        <i class="fas fa-trash me-2"></i>Delete
                                                    </button>
                                                </form>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </div>

                                <!-- Reject Blog Modal -->
                                <div class="modal fade" id="rejectBlogModal<?= $post['id'] ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Reject Blog Post</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form method="POST">
                                                <div class="modal-body">
                                                    <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                                                    <div class="mb-3">
                                                        <label for="rejection_reason" class="form-label">Reason for rejection (optional)</label>
                                                        <textarea class="form-control" id="rejection_reason" name="rejection_reason" 
                                                                  rows="3" placeholder="Provide feedback for the author..."></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" name="reject_blog" class="btn btn-danger">Reject Post</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <?php if (empty($blog_posts)): ?>
            <div class="text-center py-5">
                <i class="fas fa-blog fa-3x text-muted mb-3"></i>
                <h5 class="fw-bold">No blog posts found</h5>
                <p class="text-muted">There are no blog posts to moderate.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Pending Posts Alert -->
<?php if (!empty($pending_posts)): ?>
<div class="alert alert-warning mt-4">
    <div class="d-flex align-items-center">
        <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
        <div>
            <h6 class="fw-bold mb-1"><?= count($pending_posts) ?> blog posts awaiting approval</h6>
            <p class="mb-0">Review and approve pending blog posts to make them visible to users.</p>
        </div>
    </div>
</div>
<?php endif; ?>