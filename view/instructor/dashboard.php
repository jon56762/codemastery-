<div class="container-fluid py-4 mt-5">
    <!-- Welcome Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 fw-bold mb-1">Welcome back, <?= htmlspecialchars($user['name']) ?>! 👋</h1>
                    <p class="text-muted mb-0">Here's what's happening with your courses today.</p>
                </div>
                <a href="/course-builder" class="btn btn-primary">
                    <i class="fas fa-plus-circle me-2"></i>Create New Course
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted mb-2">Total Revenue</h6>
                            <h3 class="fw-bold text-success">$<?= number_format($totalRevenue, 2) ?></h3>
                        </div>
                        <div class="bg-success text-white rounded-circle p-3">
                            <i class="fas fa-dollar-sign fa-lg"></i>
                        </div>
                    </div>
                    <p class="text-muted small mb-0">Lifetime earnings</p>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted mb-2">Monthly Revenue</h6>
                            <h3 class="fw-bold text-primary">$<?= number_format($monthlyRevenue, 2) ?></h3>
                        </div>
                        <div class="bg-primary text-white rounded-circle p-3">
                            <i class="fas fa-chart-line fa-lg"></i>
                        </div>
                    </div>
                    <p class="text-muted small mb-0">This month's earnings</p>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted mb-2">Total Students</h6>
                            <h3 class="fw-bold text-warning"><?= number_format($totalStudents) ?></h3>
                        </div>
                        <div class="bg-warning text-white rounded-circle p-3">
                            <i class="fas fa-users fa-lg"></i>
                        </div>
                    </div>
                    <p class="text-muted small mb-0">All-time students</p>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted mb-2">Published Courses</h6>
                            <h3 class="fw-bold text-info"><?= count($publishedCourses) ?></h3>
                        </div>
                        <div class="bg-info text-white rounded-circle p-3">
                            <i class="fas fa-book fa-lg"></i>
                        </div>
                    </div>
                    <p class="text-muted small mb-0">Active courses</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Enrollments -->
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="fw-bold mb-0">Recent Enrollments</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($recentEnrollments)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No enrollments yet</h5>
                            <p class="text-muted">Students will appear here when they enroll in your courses.</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Student</th>
                                        <th>Course</th>
                                        <th>Date</th>
                                        <th>Revenue</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach (array_reverse($recentEnrollments) as $enrollment): 
                                        $course = getCourseById($enrollment['course_id']);
                                        $revenue = $course['price'] * 0.7;
                                    ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="<?= getInstructorAvatar('Student', 30) ?>" 
                                                         class="rounded-circle me-2" alt="Student">
                                                    <span>Student #<?= $enrollment['student_id'] ?></span>
                                                </div>
                                            </td>
                                            <td><?= htmlspecialchars($course['title']) ?></td>
                                            <td><?= date('M j, Y', strtotime($enrollment['enrolled_at'])) ?></td>
                                            <td class="text-success fw-semibold">$<?= number_format($revenue, 2) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Quick Actions & Course Stats -->
        <div class="col-lg-4 mb-4">
            <!-- Quick Actions -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="fw-bold mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="/course-builder" class="btn btn-primary btn-lg">
                            <i class="fas fa-plus-circle me-2"></i>Create New Course
                        </a>
                        <a href="/instructor-courses" class="btn btn-outline-dark">
                            <i class="fas fa-book me-2"></i>Manage Courses
                        </a>
                        <a href="#" class="btn btn-outline-dark">
                            <i class="fas fa-chart-bar me-2"></i>View Analytics
                        </a>
                        <a href="#" class="btn btn-outline-dark">
                            <i class="fas fa-dollar-sign me-2"></i>Earnings Report
                        </a>
                    </div>
                </div>
            </div>

            <!-- Course Status -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="fw-bold mb-0">Course Status</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="fw-semibold">Published</span>
                            <span class="fw-semibold text-success"><?= count($publishedCourses) ?></span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-success" 
                                 style="width: <?= count($courses) > 0 ? (count($publishedCourses) / count($courses)) * 100 : 0 ?>%">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="fw-semibold">Draft</span>
                            <span class="fw-semibold text-warning"><?= count($draftCourses) ?></span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-warning" 
                                 style="width: <?= count($courses) > 0 ? (count($draftCourses) / count($courses)) * 100 : 0 ?>%">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-0">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="fw-semibold">Total Courses</span>
                            <span class="fw-semibold text-primary"><?= count($courses) ?></span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-primary" style="width: 100%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Your Courses -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">Your Courses</h5>
                    <a href="/instructor-courses" class="btn btn-sm btn-outline-dark">View All</a>
                </div>
                <div class="card-body">
                    <?php if (empty($courses)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No courses yet</h5>
                            <p class="text-muted mb-4">Create your first course to start earning</p>
                            <a href="/course-builder" class="btn btn-primary btn-lg">
                                <i class="fas fa-plus-circle me-2"></i>Create Your First Course
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="row">
                            <?php foreach (array_slice($courses, 0, 4) as $course): ?>
                                <div class="col-lg-3 col-md-6 mb-4">
                                    <div class="card border-0 shadow-sm h-100 course-card">
                                        <img src="<?= getCourseImage($course) ?>" 
                                             class="card-img-top" alt="<?= htmlspecialchars($course['title']) ?>" 
                                             style="height: 160px; object-fit: cover;">
                                        <div class="card-body">
                                            <span class="badge bg-<?= $course['status'] === 'published' ? 'success' : 'warning' ?> mb-2">
                                                <?= ucfirst($course['status']) ?>
                                            </span>
                                            <h6 class="card-title fw-bold"><?= htmlspecialchars($course['title']) ?></h6>
                                            <p class="card-text text-muted small">
                                                <?= substr($course['description'], 0, 80) ?>...
                                            </p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="h6 mb-0 text-dark">$<?= $course['price'] ?></span>
                                                <span class="badge bg-light text-dark">
                                                    <?= count($course['curriculum'] ?? []) ?> lessons
                                                </span>
                                            </div>
                                        </div>
                                        <div class="card-footer bg-white border-0 pt-0">
                                            <div class="btn-group w-100">
                                                <a href="/course-builder?course_id=<?= $course['id'] ?>" 
                                                   class="btn btn-sm btn-outline-dark">Edit</a>
                                                <a href="/course/<?= $course['id'] ?>" 
                                                   class="btn btn-sm btn-outline-dark">View</a>
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
    </div>
</div>

<style>
.instructor-content {
    background-color: #f8f9fa;
    min-height: calc(100vh - 56px);
}

.course-card {
    transition: transform 0.2s;
}

.course-card:hover {
    transform: translateY(-2px);
}

.progress {
    background-color: #e9ecef;
}
</style>