<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 fw-bold text-dark mb-1">Course Moderation</h1>
        <p class="text-muted mb-0">Manage and moderate all courses on the platform</p>
    </div>
    <div class="d-flex gap-2">
        <div class="dropdown">
            <button class="btn btn-outline-dark dropdown-toggle" type="button" data-bs-toggle="dropdown">
                <i class="fas fa-filter me-2"></i>Filter
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="?status=all">All Courses</a></li>
                <li><a class="dropdown-item" href="?status=pending">Pending Review</a></li>
                <li><a class="dropdown-item" href="?status=published">Published</a></li>
                <li><a class="dropdown-item" href="?status=draft">Draft</a></li>
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
                        <h6 class="card-title text-muted mb-2">Total Courses</h6>
                        <h3 class="fw-bold text-dark"><?= count($courses) ?></h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-book fa-2x text-primary"></i>
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
                        <h3 class="fw-bold text-warning"><?= count($pending_courses) ?></h3>
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
                        <h3 class="fw-bold text-success"><?= count($published_courses) ?></h3>
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
                        <h3 class="fw-bold text-info"><?= count($draft_courses) ?></h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-edit fa-2x text-info"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Courses Table -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 py-3">
        <h5 class="fw-bold mb-0">All Courses</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Course</th>
                        <th>Instructor</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Students</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($courses as $course): ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="<?= htmlspecialchars($course['thumbnail']) ?>" 
                                         alt="<?= htmlspecialchars($course['title']) ?>" 
                                         class="rounded me-3" width="40" height="40" style="object-fit: cover;">
                                    <div>
                                        <div class="fw-semibold"><?= htmlspecialchars($course['title']) ?></div>
                                        <small class="text-muted">
                                            <?= $course['level'] ?? 'Beginner' ?> • <?= $course['lessons'] ?? 0 ?> lessons
                                        </small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="<?= htmlspecialchars(getUserById($course['instructor_id'])['avatar'] ?? '/assets/images/avatars/default.jpg') ?>" 
                                         alt="<?= htmlspecialchars($course['instructor_name']) ?>" 
                                         class="rounded-circle me-2" width="30" height="30" style="object-fit: cover;">
                                    <small><?= htmlspecialchars($course['instructor_name']) ?></small>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark"><?= htmlspecialchars($course['category']) ?></span>
                            </td>
                            <td class="fw-semibold">
                                <?= $course['price'] > 0 ? '$' . $course['price'] : 'Free' ?>
                            </td>
                            <td>
                                <?php
                                $enrollments = getCourseEnrollments($course['id']);
                                echo count($enrollments);
                                ?>
                            </td>
                            <td>
                                <span class="badge bg-<?= getStatusBadgeColor($course['status']) ?>">
                                    <?= ucfirst($course['status']) ?>
                                </span>
                            </td>
                            <td>
                                <small class="text-muted">
                                    <?= date('M j, Y', strtotime($course['created_at'])) ?>
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
                                            <a class="dropdown-item" href="/course/<?= $course['id'] ?>" target="_blank">
                                                <i class="fas fa-eye me-2"></i>View Course
                                            </a>
                                        </li>
                                        <?php if ($course['status'] === 'pending'): ?>
                                            <li>
                                                <form method="POST" class="d-inline">
                                                    <input type="hidden" name="course_id" value="<?= $course['id'] ?>">
                                                    <button type="submit" name="approve_course" class="dropdown-item text-success">
                                                        <i class="fas fa-check me-2"></i>Approve
                                                    </button>
                                                </form>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item text-danger" 
                                                        data-bs-toggle="modal" data-bs-target="#rejectModal<?= $course['id'] ?>">
                                                    <i class="fas fa-times me-2"></i>Reject
                                                </button>
                                            </li>
                                        <?php elseif ($course['status'] === 'published'): ?>
                                            <li>
                                                <form method="POST" class="d-inline">
                                                    <input type="hidden" name="course_id" value="<?= $course['id'] ?>">
                                                    <button type="submit" name="delete_course" class="dropdown-item text-danger"
                                                            onclick="return confirm('Are you sure you want to delete this course?')">
                                                        <i class="fas fa-trash me-2"></i>Delete
                                                    </button>
                                                </form>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </div>

                                <!-- Reject Modal -->
                                <div class="modal fade" id="rejectModal<?= $course['id'] ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Reject Course</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form method="POST">
                                                <div class="modal-body">
                                                    <input type="hidden" name="course_id" value="<?= $course['id'] ?>">
                                                    <div class="mb-3">
                                                        <label for="rejection_reason" class="form-label">Reason for rejection (optional)</label>
                                                        <textarea class="form-control" id="rejection_reason" name="rejection_reason" 
                                                                  rows="3" placeholder="Provide feedback for the instructor..."></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" name="reject_course" class="btn btn-danger">Reject Course</button>
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

        <?php if (empty($courses)): ?>
            <div class="text-center py-5">
                <i class="fas fa-book fa-3x text-muted mb-3"></i>
                <h5 class="fw-bold">No courses found</h5>
                <p class="text-muted">There are no courses on the platform yet.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Pending Courses Alert -->
<?php if (!empty($pending_courses)): ?>
<div class="alert alert-warning mt-4">
    <div class="d-flex align-items-center">
        <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
        <div>
            <h6 class="fw-bold mb-1"><?= count($pending_courses) ?> courses awaiting approval</h6>
            <p class="mb-0">Review and approve pending courses to make them available to students.</p>
        </div>
    </div>
</div>
<?php endif; ?>