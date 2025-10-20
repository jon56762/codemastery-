<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="fw-bold mb-2">My Courses</h1>
                    <p class="text-muted mb-0">Manage your learning journey and track your progress</p>
                </div>
                <a href="/courses" class="btn btn-dark">
                    <i class="fas fa-plus me-2"></i>Browse Courses
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row mb-4">
        <div class="col-md-3 col-6 mb-3">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body py-3">
                    <div class="h4 fw-bold text-dark mb-1"><?= count($enrolledCourses) ?></div>
                    <small class="text-muted">Total Courses</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-3">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body py-3">
                    <div class="h4 fw-bold text-success mb-1">
                        <?= count(array_filter($enrolledCourses, function($item) { 
                            return $item['enrollment']['progress'] >= 100; 
                        })) ?>
                    </div>
                    <small class="text-muted">Completed</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-3">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body py-3">
                    <div class="h4 fw-bold text-warning mb-1">
                        <?= count(array_filter($enrolledCourses, function($item) { 
                            return $item['enrollment']['progress'] > 0 && $item['enrollment']['progress'] < 100; 
                        })) ?>
                    </div>
                    <small class="text-muted">In Progress</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-3">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body py-3">
                    <div class="h4 fw-bold text-info mb-1">
                        <?= round(array_sum(array_map(function($item) { 
                            return $item['enrollment']['progress']; 
                        }, $enrolledCourses)) / max(count($enrolledCourses), 1)) ?>%
                    </div>
                    <small class="text-muted">Avg Progress</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Filters Sidebar -->
        <div class="col-lg-3">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">Filters & Sort</h6>
                    
                    <!-- Search -->
                    <div class="mb-4">
                        <form method="GET" class="d-flex">
                            <input type="text" name="search" class="form-control form-control-sm" 
                                   placeholder="Search my courses..." value="<?= htmlspecialchars($search) ?>">
                            <button type="submit" class="btn btn-dark btn-sm ms-2">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                    </div>

                    <!-- Status Filter -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Status</label>
                        <div class="list-group list-group-flush">
                            <a href="?filter=all<?= $search ? '&search=' . urlencode($search) : '' ?>" 
                               class="list-group-item list-group-item-action border-0 px-0 py-2 <?= $filter === 'all' ? 'active' : '' ?>">
                                <div class="d-flex justify-content-between">
                                    <span>All Courses</span>
                                    <span class="badge bg-dark"><?= count($enrolledCourses) ?></span>
                                </div>
                            </a>
                            <a href="?filter=completed<?= $search ? '&search=' . urlencode($search) : '' ?>" 
                               class="list-group-item list-group-item-action border-0 px-0 py-2 <?= $filter === 'completed' ? 'active' : '' ?>">
                                <div class="d-flex justify-content-between">
                                    <span>Completed</span>
                                    <span class="badge bg-success">
                                        <?= count(array_filter($enrolledCourses, function($item) { 
                                            return $item['enrollment']['progress'] >= 100; 
                                        })) ?>
                                    </span>
                                </div>
                            </a>
                            <a href="?filter=in-progress<?= $search ? '&search=' . urlencode($search) : '' ?>" 
                               class="list-group-item list-group-item-action border-0 px-0 py-2 <?= $filter === 'in-progress' ? 'active' : '' ?>">
                                <div class="d-flex justify-content-between">
                                    <span>In Progress</span>
                                    <span class="badge bg-warning">
                                        <?= count(array_filter($enrolledCourses, function($item) { 
                                            return $item['enrollment']['progress'] > 0 && $item['enrollment']['progress'] < 100; 
                                        })) ?>
                                    </span>
                                </div>
                            </a>
                            <a href="?filter=not-started<?= $search ? '&search=' . urlencode($search) : '' ?>" 
                               class="list-group-item list-group-item-action border-0 px-0 py-2 <?= $filter === 'not-started' ? 'active' : '' ?>">
                                <div class="d-flex justify-content-between">
                                    <span>Not Started</span>
                                    <span class="badge bg-secondary">
                                        <?= count(array_filter($enrolledCourses, function($item) { 
                                            return $item['enrollment']['progress'] == 0; 
                                        })) ?>
                                    </span>
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- Sort Options -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Sort By</label>
                        <select class="form-select" onchange="window.location.href = this.value">
                            <option value="?sort=recent<?= $filter !== 'all' ? '&filter=' . $filter : '' ?><?= $search ? '&search=' . urlencode($search) : '' ?>" 
                                    <?= $sort === 'recent' ? 'selected' : '' ?>>Recently Added</option>
                            <option value="?sort=progress<?= $filter !== 'all' ? '&filter=' . $filter : '' ?><?= $search ? '&search=' . urlencode($search) : '' ?>" 
                                    <?= $sort === 'progress' ? 'selected' : '' ?>>Progress</option>
                            <option value="?sort=title<?= $filter !== 'all' ? '&filter=' . $filter : '' ?><?= $search ? '&search=' . urlencode($search) : '' ?>" 
                                    <?= $sort === 'title' ? 'selected' : '' ?>>Title A-Z</option>
                        </select>
                    </div>

                    <!-- Clear Filters -->
                    <a href="/my-courses" class="btn btn-outline-dark w-100">Clear All</a>
                </div>
            </div>

            <!-- Wishlist (Placeholder) -->
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">Wishlist</h6>
                    <?php if (empty($wishlist)): ?>
                        <div class="text-center py-3">
                            <i class="fas fa-heart fa-2x text-muted mb-2"></i>
                            <p class="text-muted small mb-0">No courses in wishlist</p>
                        </div>
                    <?php else: ?>
                        <!-- Wishlist items would go here -->
                    <?php endif; ?>
                    <a href="/courses" class="btn btn-outline-dark w-100 mt-2">
                        <i class="fas fa-search me-1"></i>Find Courses
                    </a>
                </div>
            </div>
        </div>

        <!-- Courses Grid -->
        <div class="col-lg-9">
            <?php if (empty($filteredCourses)): ?>
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
                        <h4 class="fw-bold mb-3">No courses found</h4>
                        <p class="text-muted mb-4">
                            <?php if ($search): ?>
                                No courses match your search "<?= htmlspecialchars($search) ?>"
                            <?php elseif ($filter !== 'all'): ?>
                                No courses match the selected filter
                            <?php else: ?>
                                You haven't enrolled in any courses yet
                            <?php endif; ?>
                        </p>
                        <a href="/courses" class="btn btn-dark">
                            <i class="fas fa-plus me-2"></i>Browse Courses
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <div class="row">
                    <?php foreach ($filteredCourses as $item): 
                        $course = $item['course'];
                        $enrollment = $item['enrollment'];
                        $isCompleted = $enrollment['progress'] >= 100;
                        $isInProgress = $enrollment['progress'] > 0 && $enrollment['progress'] < 100;
                    ?>
                        <div class="col-xl-4 col-lg-6 mb-4">
                            <div class="card course-card h-100 border-0 shadow-sm">
                                <div class="position-relative">
                                    <img src="<?= getCourseImage($course) ?>" 
                                         class="card-img-top" 
                                         alt="<?= htmlspecialchars($course['title']) ?>"
                                         style="height: 160px; object-fit: cover;">
                                    
                                    <!-- Progress Badge -->
                                    <div class="position-absolute top-0 start-0 m-2">
                                        <?php if ($isCompleted): ?>
                                            <span class="badge bg-success">
                                                <i class="fas fa-check me-1"></i>Completed
                                            </span>
                                        <?php elseif ($isInProgress): ?>
                                            <span class="badge bg-warning">
                                                <?= $enrollment['progress'] ?>% Complete
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Not Started</span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <!-- Level Badge -->
                                    <div class="position-absolute top-0 end-0 m-2">
                                        <span class="badge bg-<?= getCourseLevelBadge($course['level']) ?>">
                                            <?= ucfirst($course['level']) ?>
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="card-body d-flex flex-column">
                                    <div class="mb-2">
                                        <span class="badge bg-light text-dark border">
                                            <?= htmlspecialchars($course['category']) ?>
                                        </span>
                                    </div>
                                    
                                    <h5 class="card-title fw-bold">
                                        <a href="/course/<?= $course['id'] ?>" class="text-dark text-decoration-none">
                                            <?= htmlspecialchars($course['title']) ?>
                                        </a>
                                    </h5>
                                    
                                    <p class="card-text text-muted small flex-grow-1">
                                        <?= htmlspecialchars($course['short_description'] ?? substr($course['description'], 0, 100) . '...') ?>
                                    </p>
                                    
                                    <!-- Progress Bar -->
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between mb-1">
                                            <small class="text-muted">Progress</small>
                                            <small class="fw-semibold"><?= $enrollment['progress'] ?>%</small>
                                        </div>
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar 
                                                <?= $isCompleted ? 'bg-success' : ($isInProgress ? 'bg-warning' : 'bg-secondary') ?>" 
                                                style="width: <?= $enrollment['progress'] ?>%">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between align-items-center mt-auto">
                                        <div class="d-flex align-items-center">
                                            <img src="<?= getInstructorAvatar($course['instructor_name'], 30) ?>" 
                                                 alt="<?= htmlspecialchars($course['instructor_name']) ?>" 
                                                 class="rounded-circle me-2" width="30" height="30">
                                            <small class="text-muted"><?= htmlspecialchars($course['instructor_name']) ?></small>
                                        </div>
                                        <small class="text-muted">
                                            <?= $course['lessons'] ?> lessons
                                        </small>
                                    </div>
                                </div>
                                
                                <div class="card-footer bg-transparent border-0 pt-0">
                                    <div class="d-grid gap-2">
                                        <?php if ($isCompleted): ?>
                                            <a href="/course/<?= $course['id'] ?>" class="btn btn-outline-success btn-sm">
                                                <i class="fas fa-redo me-1"></i>Review Course
                                            </a>
                                        <?php elseif ($isInProgress): ?>
                                            <a href="/course-player?course_id=<?= $course['id'] ?>&lesson_id=1" 
                                               class="btn btn-dark btn-sm">
                                                <i class="fas fa-play-circle me-1"></i>Continue Learning
                                            </a>
                                        <?php else: ?>
                                            <a href="/course-player?course_id=<?= $course['id'] ?>&lesson_id=1" 
                                               class="btn btn-dark btn-sm">
                                                <i class="fas fa-play me-1"></i>Start Learning
                                            </a>
                                        <?php endif; ?>
                                        
                                        <div class="btn-group w-100">
                                            <a href="/course/<?= $course['id'] ?>" class="btn btn-outline-dark btn-sm">
                                                <i class="fas fa-eye me-1"></i>View
                                            </a>
                                            <button class="btn btn-outline-dark btn-sm">
                                                <i class="fas fa-heart me-1"></i>Wishlist
                                            </button>
                                            <button class="btn btn-outline-dark btn-sm">
                                                <i class="fas fa-ellipsis-h"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.course-card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    border: 1px solid #e9ecef !important;
}

.course-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
}

.list-group-item.active {
    background-color: #000 !important;
    border-color: #000 !important;
}

.card-img-top {
    border-radius: 8px 8px 0 0;
}

.progress {
    background-color: #f8f9fa;
}

.badge.bg-success { background-color: #28a745 !important; }
.badge.bg-warning { background-color: #ffc107 !important; color: #000; }
.badge.bg-danger { background-color: #dc3545 !important; }

.course-card .card-title a:hover {
    color: #000 !important;
    text-decoration: underline !important;
}
</style>

<script>
// Add some interactivity
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit search when typing stops
    let searchTimeout;
    const searchInput = document.querySelector('input[name="search"]');
    
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                this.form.submit();
            }, 500);
        });
    }

    // Add loading states for buttons
    const buttons = document.querySelectorAll('.btn');
    buttons.forEach(button => {
        button.addEventListener('click', function() {
            if (this.classList.contains('btn-dark') || this.classList.contains('btn-success')) {
                const originalText = this.innerHTML;
                this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Loading...';
                this.disabled = true;
                
                // Reset after 2 seconds (in real app, this would be after the action completes)
                setTimeout(() => {
                    this.innerHTML = originalText;
                    this.disabled = false;
                }, 2000);
            }
        });
    });
});
</script>