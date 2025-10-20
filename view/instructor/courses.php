<div class="container-fluid py-4 mt-5">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 fw-bold mb-1">My Courses</h1>
                    <p class="text-muted mb-0">Manage and organize your course portfolio</p>
                </div>
                <a href="/course-builder" class="btn btn-primary">
                    <i class="fas fa-plus-circle me-2"></i>Create New Course
                </a>
            </div>
        </div>
    </div>

    <!-- Courses Table -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="fw-bold mb-0">All Courses (<?= count($courses) ?>)</h5>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($courses)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No courses created yet</h5>
                            <p class="text-muted mb-4">Start by creating your first course</p>
                            <a href="/course-builder" class="btn btn-primary btn-lg">
                                <i class="fas fa-plus-circle me-2"></i>Create Your First Course
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Course</th>
                                        <th>Status</th>
                                        <th>Price</th>
                                        <th>Students</th>
                                        <th>Rating</th>
                                        <th>Last Updated</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($courses as $course): 
                                        $enrollments = getCourseEnrollments($course['id']);
                                        $rating = $course['rating'] ?? 0;
                                    ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="<?= getCourseImage($course) ?>" 
                                                         alt="<?= htmlspecialchars($course['title']) ?>" 
                                                         class="rounded me-3" width="60" height="40" style="object-fit: cover;">
                                                    <div>
                                                        <h6 class="fw-bold mb-1"><?= htmlspecialchars($course['title']) ?></h6>
                                                        <small class="text-muted"><?= $course['category'] ?> • <?= count($course['curriculum'] ?? []) ?> lessons</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <form method="POST" class="d-inline">
                                                    <input type="hidden" name="course_id" value="<?= $course['id'] ?>">
                                                    <select name="status" class="form-select form-select-sm" onchange="this.form.submit()" 
                                                            data-course-id="<?= $course['id'] ?>">
                                                        <option value="draft" <?= $course['status'] === 'draft' ? 'selected' : '' ?>>Draft</option>
                                                        <option value="published" <?= $course['status'] === 'published' ? 'selected' : '' ?>>Published</option>
                                                        <option value="archived" <?= $course['status'] === 'archived' ? 'selected' : '' ?>>Archived</option>
                                                    </select>
                                                    <input type="hidden" name="update_status" value="1">
                                                </form>
                                            </td>
                                            <td>
                                                <span class="fw-bold text-dark">$<?= $course['price'] ?></span>
                                            </td>
                                            <td>
                                                <span class="fw-semibold"><?= count($enrollments) ?></span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <span class="text-warning me-1">
                                                        <i class="fas fa-star"></i>
                                                    </span>
                                                    <span class="fw-semibold"><?= $rating ?></span>
                                                    <small class="text-muted ms-1">(<?= $course['reviews_count'] ?? 0 ?>)</small>
                                                </div>
                                            </td>
                                            <td>
                                                <small class="text-muted"><?= date('M j, Y', strtotime($course['updated_at'])) ?></small>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="/course-builder?course_id=<?= $course['id'] ?>" 
                                                       class="btn btn-outline-dark" title="Edit Course">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="/course/<?= $course['id'] ?>" 
                                                       class="btn btn-outline-dark" title="View Course">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <form method="POST" class="d-inline" 
                                                          onsubmit="return confirm('Are you sure you want to delete this course? This action cannot be undone.')">
                                                        <input type="hidden" name="course_id" value="<?= $course['id'] ?>">
                                                        <button type="submit" name="delete_course" class="btn btn-outline-danger" title="Delete Course">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row mt-4">
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card border-0 bg-primary text-white shadow-sm">
                <div class="card-body text-center py-4">
                    <i class="fas fa-book fa-2x mb-3"></i>
                    <h3 class="fw-bold mb-1"><?= count($courses) ?></h3>
                    <p class="mb-0">Total Courses</p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card border-0 bg-success text-white shadow-sm">
                <div class="card-body text-center py-4">
                    <i class="fas fa-check-circle fa-2x mb-3"></i>
                    <h3 class="fw-bold mb-1"><?= count(array_filter($courses, function($c) { return $c['status'] === 'published'; })) ?></h3>
                    <p class="mb-0">Published</p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card border-0 bg-warning text-white shadow-sm">
                <div class="card-body text-center py-4">
                    <i class="fas fa-edit fa-2x mb-3"></i>
                    <h3 class="fw-bold mb-1"><?= count(array_filter($courses, function($c) { return $c['status'] === 'draft'; })) ?></h3>
                    <p class="mb-0">In Draft</p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card border-0 bg-info text-white shadow-sm">
                <div class="card-body text-center py-4">
                    <i class="fas fa-users fa-2x mb-3"></i>
                    <h3 class="fw-bold mb-1">
                        <?= array_sum(array_map(function($course) { 
                            return count(getCourseEnrollments($course['id'])); 
                        }, $courses)) ?>
                    </h3>
                    <p class="mb-0">Total Students</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add confirmation for status changes to published
    const statusSelects = document.querySelectorAll('select[name="status"]');
    statusSelects.forEach(select => {
        select.addEventListener('change', function(e) {
            if (this.value === 'published') {
                const courseId = this.getAttribute('data-course-id');
                if (!confirm('Are you sure you want to publish this course? It will become available to all students.')) {
                    e.preventDefault();
                    this.form.reset();
                }
            }
        });
    });
});
</script>