<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 fw-bold text-dark mb-1">Content Moderation</h1>
        <p class="text-muted mb-0">Manage reported content and user safety</p>
    </div>
    <div class="d-flex gap-2">
        <div class="dropdown">
            <button class="btn btn-outline-dark dropdown-toggle" type="button" data-bs-toggle="dropdown">
                <i class="fas fa-filter me-2"></i>Filter
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="?type=all">All Content</a></li>
                <li><a class="dropdown-item" href="?type=reported">Reported</a></li>
                <li><a class="dropdown-item" href="?type=pending">Pending Reviews</a></li>
                <li><a class="dropdown-item" href="?type=comments">Flagged Comments</a></li>
            </ul>
        </div>
    </div>
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
                        <h6 class="card-title text-muted mb-2">Reported Content</h6>
                        <h3 class="fw-bold text-warning"><?= count($reportedContent) ?></h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-flag fa-2x text-warning"></i>
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
                        <h6 class="card-title text-muted mb-2">Pending Reviews</h6>
                        <h3 class="fw-bold text-info"><?= count($pendingReviews) ?></h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-clock fa-2x text-info"></i>
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
                        <h6 class="card-title text-muted mb-2">Flagged Comments</h6>
                        <h3 class="fw-bold text-danger"><?= count($flaggedComments) ?></h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-comment-slash fa-2x text-danger"></i>
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
                        <h6 class="card-title text-muted mb-2">Resolved Today</h6>
                        <h3 class="fw-bold text-success"><?= count(getResolvedToday()) ?></h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-check-circle fa-2x text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reported Content -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white border-0 py-3">
        <h5 class="fw-bold mb-0">
            <i class="fas fa-flag text-warning me-2"></i>Reported Content
        </h5>
    </div>
    <div class="card-body">
        <?php if (!empty($reportedContent)): ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Content</th>
                            <th>Type</th>
                            <th>Reported By</th>
                            <th>Reason</th>
                            <th>Reported At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reportedContent as $report): ?>
                            <tr>
                                <td>
                                    <div class="fw-semibold"><?= htmlspecialchars($report['content_title']) ?></div>
                                    <small class="text-muted"><?= htmlspecialchars(substr($report['content_preview'], 0, 100)) ?>...</small>
                                </td>
                                <td>
                                    <span class="badge bg-secondary"><?= ucfirst($report['content_type']) ?></span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="<?= htmlspecialchars($report['reporter_avatar']) ?>" 
                                             alt="<?= htmlspecialchars($report['reporter_name']) ?>" 
                                             class="rounded-circle me-2" width="30" height="30" style="object-fit: cover;">
                                        <small><?= htmlspecialchars($report['reporter_name']) ?></small>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-<?= $report['severity'] === 'high' ? 'danger' : ($report['severity'] === 'medium' ? 'warning' : 'info') ?>">
                                        <?= ucfirst($report['reason']) ?>
                                    </span>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <?= date('M j, Y', strtotime($report['reported_at'])) ?>
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
                                                <button type="button" class="dropdown-item" 
                                                        data-bs-toggle="modal" data-bs-target="#viewReportModal<?= $report['id'] ?>">
                                                    <i class="fas fa-eye me-2"></i>View Details
                                                </button>
                                            </li>
                                            <li>
                                                <form method="POST" class="d-inline">
                                                    <input type="hidden" name="content_id" value="<?= $report['content_id'] ?>">
                                                    <input type="hidden" name="content_type" value="<?= $report['content_type'] ?>">
                                                    <button type="submit" name="approve_content" class="dropdown-item text-success">
                                                        <i class="fas fa-check me-2"></i>Approve Content
                                                    </button>
                                                </form>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item text-warning" 
                                                        data-bs-toggle="modal" data-bs-target="#rejectModal<?= $report['id'] ?>">
                                                    <i class="fas fa-times me-2"></i>Reject Content
                                                </button>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <button type="button" class="dropdown-item text-danger" 
                                                        data-bs-toggle="modal" data-bs-target="#banUserModal<?= $report['user_id'] ?>">
                                                    <i class="fas fa-ban me-2"></i>Ban User
                                                </button>
                                            </li>
                                        </ul>
                                    </div>

                                    <!-- View Report Modal -->
                                    <div class="modal fade" id="viewReportModal<?= $report['id'] ?>" tabindex="-1">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Report Details</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <strong>Content Type:</strong> <?= ucfirst($report['content_type']) ?>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <strong>Severity:</strong> 
                                                            <span class="badge bg-<?= $report['severity'] === 'high' ? 'danger' : ($report['severity'] === 'medium' ? 'warning' : 'info') ?>">
                                                                <?= ucfirst($report['severity']) ?>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <strong>Content:</strong>
                                                        <div class="border p-3 mt-2 bg-light">
                                                            <?= nl2br(htmlspecialchars($report['full_content'])) ?>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <strong>Report Reason:</strong>
                                                        <p class="mt-2"><?= htmlspecialchars($report['report_details']) ?></p>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <strong>Reported By:</strong> <?= htmlspecialchars($report['reporter_name']) ?>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <strong>Reported At:</strong> <?= date('F j, Y g:i A', strtotime($report['reported_at'])) ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    <form method="POST" class="d-inline">
                                                        <input type="hidden" name="content_id" value="<?= $report['content_id'] ?>">
                                                        <input type="hidden" name="content_type" value="<?= $report['content_type'] ?>">
                                                        <button type="submit" name="delete_content" class="btn btn-danger">
                                                            Delete Content
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Reject Modal -->
                                    <div class="modal fade" id="rejectModal<?= $report['id'] ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Reject Content</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form method="POST">
                                                    <div class="modal-body">
                                                        <input type="hidden" name="content_id" value="<?= $report['content_id'] ?>">
                                                        <input type="hidden" name="content_type" value="<?= $report['content_type'] ?>">
                                                        <div class="mb-3">
                                                            <label for="rejection_reason" class="form-label">Reason for rejection</label>
                                                            <textarea class="form-control" id="rejection_reason" name="rejection_reason" 
                                                                      rows="3" placeholder="Explain why this content is being rejected..." required></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" name="reject_content" class="btn btn-danger">Reject Content</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Ban User Modal -->
                                    <div class="modal fade" id="banUserModal<?= $report['user_id'] ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Ban User</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form method="POST">
                                                    <div class="modal-body">
                                                        <input type="hidden" name="user_id" value="<?= $report['user_id'] ?>">
                                                        <div class="alert alert-warning">
                                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                                            This will suspend the user's account and restrict their access.
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="ban_reason" class="form-label">Reason for ban</label>
                                                            <textarea class="form-control" id="ban_reason" name="ban_reason" 
                                                                      rows="3" placeholder="Explain why this user is being banned..." required></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" name="ban_user" class="btn btn-danger">Ban User</button>
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
        <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-flag fa-3x text-muted mb-3"></i>
                <h5 class="fw-bold">No reported content</h5>
                <p class="text-muted">All content is clean and safe!</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Quick Actions -->
<div class="row">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="fw-bold mb-0">
                    <i class="fas fa-clock text-info me-2"></i>Pending Reviews
                </h5>
            </div>
            <div class="card-body">
                <?php if (!empty($pendingReviews)): ?>
                    <?php foreach (array_slice($pendingReviews, 0, 5) as $review): ?>
                        <div class="d-flex align-items-center mb-3 p-2 border rounded">
                            <img src="<?= htmlspecialchars($review['user_avatar']) ?>" 
                                 alt="<?= htmlspecialchars($review['user_name']) ?>" 
                                 class="rounded-circle me-3" width="40" height="40" style="object-fit: cover;">
                            <div class="flex-grow-1">
                                <h6 class="fw-semibold mb-1"><?= htmlspecialchars($review['content_title']) ?></h6>
                                <small class="text-muted">By <?= htmlspecialchars($review['user_name']) ?></small>
                            </div>
                            <div class="text-end">
                                <small class="text-muted d-block"><?= date('M j', strtotime($review['created_at'])) ?></small>
                                <span class="badge bg-<?= $review['type'] === 'comment' ? 'info' : 'primary' ?>">
                                    <?= ucfirst($review['type']) ?>
                                </span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-3">
                        <small class="text-muted">No pending reviews</small>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="fw-bold mb-0">
                    <i class="fas fa-comment-slash text-danger me-2"></i>Flagged Comments
                </h5>
            </div>
            <div class="card-body">
                <?php if (!empty($flaggedComments)): ?>
                    <?php foreach (array_slice($flaggedComments, 0, 5) as $comment): ?>
                        <div class="mb-3 p-2 border rounded">
                            <div class="d-flex justify-content-between mb-2">
                                <strong><?= htmlspecialchars($comment['user_name']) ?></strong>
                                <small class="text-muted"><?= date('M j', strtotime($comment['created_at'])) ?></small>
                            </div>
                            <p class="mb-2"><?= htmlspecialchars(substr($comment['content'], 0, 100)) ?>...</p>
                            <div class="d-flex gap-2">
                                <button class="btn btn-success btn-sm">
                                    <i class="fas fa-check me-1"></i>Approve
                                </button>
                                <button class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash me-1"></i>Delete
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-3">
                        <small class="text-muted">No flagged comments</small>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>