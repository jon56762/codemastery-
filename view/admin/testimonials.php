<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 fw-bold text-dark mb-1">Testimonials Moderation</h1>
        <p class="text-muted mb-0">Manage and approve student testimonials</p>
    </div>
    <!-- <div class="d-flex gap-2">
        <div class="dropdown">
            <button class="btn btn-outline-dark dropdown-toggle" type="button" data-bs-toggle="dropdown">
                <i class="fas fa-filter me-2"></i>Filter
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="?status=all">All Testimonials</a></li>
                <li><a class="dropdown-item" href="?status=pending">Pending</a></li>
                <li><a class="dropdown-item" href="?status=approved">Approved</a></li>
                <li><a class="dropdown-item" href="?status=rejected">Rejected</a></li>
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
                        <h6 class="card-title text-muted mb-2">Total Testimonials</h6>
                        <h3 class="fw-bold text-dark"><?= count($testimonials) ?></h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-comment fa-2x text-primary"></i>
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
                        <h3 class="fw-bold text-warning"><?= count($pending_testimonials) ?></h3>
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
                        <h6 class="card-title text-muted mb-2">Approved</h6>
                        <h3 class="fw-bold text-success"><?= count($approved_testimonials) ?></h3>
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
                        <h6 class="card-title text-muted mb-2">Rejected</h6>
                        <h3 class="fw-bold text-danger"><?= count($rejected_testimonials) ?></h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-times-circle fa-2x text-danger"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Testimonials Grid -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 py-3">
        <h5 class="fw-bold mb-0">All Testimonials</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <?php foreach ($testimonials as $testimonial): ?>
                <div class="col-lg-6 mb-4">
                    <div class="card h-100 border <?= ($testimonial['status'] ?? 'pending') === 'pending' ? 'border-warning' : (($testimonial['status'] ?? 'approved') === 'approved' ? 'border-success' : 'border-danger') ?>">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <img src="<?= htmlspecialchars($testimonial['avatar'] ?? '/assets/images/avatars/default.jpg') ?>" 
                                     alt="<?= htmlspecialchars($testimonial['name']) ?>" 
                                     class="rounded-circle me-3" width="50" height="50" style="object-fit: cover;">
                                <div>
                                    <h6 class="fw-bold mb-1"><?= htmlspecialchars($testimonial['name']) ?></h6>
                                    <small class="text-muted"><?= htmlspecialchars($testimonial['role'] ?? 'Student') ?></small>
                                </div>
                                <div class="ms-auto">
                                    <span class="badge bg-<?= getStatusBadgeColor($testimonial['status'] ?? 'pending') ?>">
                                        <?= ucfirst($testimonial['status'] ?? 'pending') ?>
                                    </span>
                                </div>
                            </div>
                            
                            <p class="card-text">"<?= htmlspecialchars($testimonial['text']) ?>"</p>
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-warning">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="fas fa-star<?= $i <= ($testimonial['rating'] ?? 5) ? '' : '-o' ?>"></i>
                                    <?php endfor; ?>
                                </div>
                                <small class="text-muted">
                                    <?= date('M j, Y', strtotime($testimonial['created_at'] ?? $testimonial['submitted_at'] ?? 'now')) ?>
                                </small>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-top-0">
                            <div class="d-flex gap-2">
                                <?php if (($testimonial['status'] ?? 'pending') === 'pending'): ?>
                                    <form method="POST" class="d-inline">
                                        <input type="hidden" name="testimonial_id" value="<?= $testimonial['id'] ?>">
                                        <button type="submit" name="approve_testimonial" class="btn btn-success btn-sm">
                                            <i class="fas fa-check me-1"></i>Approve
                                        </button>
                                    </form>
                                    <form method="POST" class="d-inline">
                                        <input type="hidden" name="testimonial_id" value="<?= $testimonial['id'] ?>">
                                        <button type="submit" name="reject_testimonial" class="btn btn-danger btn-sm">
                                            <i class="fas fa-times me-1"></i>Reject
                                        </button>
                                    </form>
                                <?php endif; ?>
                                <form method="POST" class="d-inline">
                                    <input type="hidden" name="testimonial_id" value="<?= $testimonial['id'] ?>">
                                    <button type="submit" name="delete_testimonial" class="btn btn-outline-dark btn-sm"
                                            onclick="return confirm('Are you sure you want to delete this testimonial?')">
                                        <i class="fas fa-trash me-1"></i>Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if (empty($testimonials)): ?>
            <div class="text-center py-5">
                <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                <h5 class="fw-bold">No testimonials found</h5>
                <p class="text-muted">There are no testimonials to moderate.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Pending Testimonials Alert -->
<?php if (!empty($pending_testimonials)): ?>
<div class="alert alert-warning mt-4">
    <div class="d-flex align-items-center">
        <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
        <div>
            <h6 class="fw-bold mb-1"><?= count($pending_testimonials) ?> testimonials awaiting approval</h6>
            <p class="mb-0">Review and approve pending testimonials to display them on the platform.</p>
        </div>
    </div>
</div>
<?php endif; ?>