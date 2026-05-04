<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 fw-bold text-dark mb-1">Instructor Applications</h1>
        <p class="text-muted mb-0">Review and manage instructor applications</p>
    </div>
    <!-- <div class="d-flex gap-2">
        <div class="dropdown">
            <button class="btn btn-outline-dark dropdown-toggle" type="button" data-bs-toggle="dropdown">
                <i class="fas fa-filter me-2"></i>Filter
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="?status=all">All Applications</a></li>
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
                        <h6 class="card-title text-muted mb-2">Total Applications</h6>
                        <h3 class="fw-bold text-dark"><?= count($applications) ?></h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-file-alt fa-2x text-primary"></i>
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
                        <h3 class="fw-bold text-warning"><?= count($pending_applications) ?></h3>
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
                        <h3 class="fw-bold text-success"><?= count($approved_applications) ?></h3>
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
                        <h3 class="fw-bold text-danger"><?= count($rejected_applications) ?></h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-times-circle fa-2x text-danger"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Applications Table -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 py-3">
        <h5 class="fw-bold mb-0">All Applications</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Applicant</th>
                        <th>Email</th>
                        <th>Experience</th>
                        <th>Specialization</th>
                        <th>Portfolio</th>
                        <th>Status</th>
                        <th>Submitted</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($applications as $application): ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <?php $applicant = getUserById($application['user_id']); ?>
                                    <img src="<?= htmlspecialchars($applicant['avatar'] ?? '/assets/images/avatars/default.jpg') ?>" 
                                         alt="<?= htmlspecialchars($application['name']) ?>" 
                                         class="rounded-circle me-3" width="40" height="40" style="object-fit: cover;">
                                    <div>
                                        <div class="fw-semibold"><?= htmlspecialchars($application['name']) ?></div>
                                        <small class="text-muted">ID: <?= $application['user_id'] ?></small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <small><?= htmlspecialchars($application['email']) ?></small>
                            </td>
                            <td>
                                <small class="text-muted"><?= htmlspecialchars(substr($application['experience'], 0, 100)) ?>...</small>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark"><?= htmlspecialchars($application['specialization']) ?></span>
                            </td>
                            <td>
                                <?php if (!empty($application['portfolio'])): ?>
                                    <a href="<?= htmlspecialchars($application['portfolio']) ?>" target="_blank" class="btn btn-sm btn-outline-dark">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted">N/A</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge bg-<?= getStatusBadgeColor($application['status']) ?>">
                                    <?= ucfirst($application['status']) ?>
                                </span>
                            </td>
                            <td>
                                <small class="text-muted">
                                    <?= date('M j, Y', strtotime($application['submitted_at'])) ?>
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
                                                    data-bs-toggle="modal" data-bs-target="#viewModal<?= $application['id'] ?>">
                                                <i class="fas fa-eye me-2"></i>View Details
                                            </button>
                                        </li>
                                        <?php if ($application['status'] === 'pending'): ?>
                                            <li>
                                                <form method="POST" class="d-inline">
                                                    <input type="hidden" name="application_id" value="<?= $application['id'] ?>">
                                                    <button type="submit" name="approve_application" class="dropdown-item text-success">
                                                        <i class="fas fa-check me-2"></i>Approve
                                                    </button>
                                                </form>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item text-danger" 
                                                        data-bs-toggle="modal" data-bs-target="#rejectModal<?= $application['id'] ?>">
                                                    <i class="fas fa-times me-2"></i>Reject
                                                </button>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </div>

                                <!-- View Modal -->
                                <div class="modal fade" id="viewModal<?= $application['id'] ?>" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Application Details</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row mb-3">
                                                    <div class="col-md-6">
                                                        <strong>Name:</strong> <?= htmlspecialchars($application['name']) ?>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <strong>Email:</strong> <?= htmlspecialchars($application['email']) ?>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-md-6">
                                                        <strong>Specialization:</strong> <?= htmlspecialchars($application['specialization']) ?>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <strong>Status:</strong> 
                                                        <span class="badge bg-<?= getStatusBadgeColor($application['status']) ?>">
                                                            <?= ucfirst($application['status']) ?>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <strong>Experience:</strong>
                                                    <p class="mt-2"><?= nl2br(htmlspecialchars($application['experience'])) ?></p>
                                                </div>
                                                <?php if (!empty($application['portfolio'])): ?>
                                                    <div class="mb-3">
                                                        <strong>Portfolio:</strong>
                                                        <a href="<?= htmlspecialchars($application['portfolio']) ?>" target="_blank" class="d-block">
                                                            <?= htmlspecialchars($application['portfolio']) ?>
                                                        </a>
                                                    </div>
                                                <?php endif; ?>
                                                <?php if (!empty($application['linkedin'])): ?>
                                                    <div class="mb-3">
                                                        <strong>LinkedIn:</strong>
                                                        <a href="<?= htmlspecialchars($application['linkedin']) ?>" target="_blank" class="d-block">
                                                            <?= htmlspecialchars($application['linkedin']) ?>
                                                        </a>
                                                    </div>
                                                <?php endif; ?>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <strong>Submitted:</strong> 
                                                        <?= date('F j, Y g:i A', strtotime($application['submitted_at'])) ?>
                                                    </div>
                                                    <?php if ($application['status'] !== 'pending'): ?>
                                                        <div class="col-md-6">
                                                            <strong>Reviewed:</strong> 
                                                            <?= date('F j, Y g:i A', strtotime($application['reviewed_at'])) ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <?php if ($application['status'] === 'pending'): ?>
                                                    <form method="POST" class="d-inline">
                                                        <input type="hidden" name="application_id" value="<?= $application['id'] ?>">
                                                        <button type="submit" name="approve_application" class="btn btn-success">
                                                            Approve Application
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Reject Modal -->
                                <div class="modal fade" id="rejectModal<?= $application['id'] ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Reject Application</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form method="POST">
                                                <div class="modal-body">
                                                    <input type="hidden" name="application_id" value="<?= $application['id'] ?>">
                                                    <div class="mb-3">
                                                        <label for="rejection_reason" class="form-label">Reason for rejection</label>
                                                        <textarea class="form-control" id="rejection_reason" name="rejection_reason" 
                                                                  rows="4" placeholder="Please provide a reason for rejecting this application..." required></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" name="reject_application" class="btn btn-danger">Reject Application</button>
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

        <?php if (empty($applications)): ?>
            <div class="text-center py-5">
                <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                <h5 class="fw-bold">No applications found</h5>
                <p class="text-muted">There are no instructor applications to review.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Pending Applications Alert -->
<?php if (!empty($pending_applications)): ?>
<div class="alert alert-warning mt-4">
    <div class="d-flex align-items-center">
        <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
        <div>
            <h6 class="fw-bold mb-1"><?= count($pending_applications) ?> applications awaiting review</h6>
            <p class="mb-0">Review pending applications to onboard new instructors.</p>
        </div>
    </div>
</div>
<?php endif; ?>