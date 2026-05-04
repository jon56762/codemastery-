<!-- Dashboard Header -->
<div class="d-flex justify-content-between align-items-center mb-4 row">
    <div class="col-12">
        <h1 class="h3 fw-bold text-dark mb-1">Admin Dashboard</h1>
        <p class="text-muted mb-0">Welcome back, <?= htmlspecialchars($user['name']) ?>! Here's what's happening today.</p>
    </div>
    <div class="d-flex col-12">
        <button class="btn btn-outline-dark me-2">
            <i class="fas fa-sync-alt me-2"></i>Refresh
        </button>
        <button class="btn btn-dark" onclick="exportReport()">
            <i class="fas fa-download me-2"></i>Export Report
        </button>
    </div>
</div>

<!-- Quick Stats -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title mb-2">Total Users</h6>
                        <h2 class="fw-bold"><?= number_format($platformStats['total_students'] + $platformStats['total_instructors']) ?></h2>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                </div>
                <div class="d-flex justify-content-between">
                    <small><?= number_format($platformStats['total_students']) ?> students</small>
                    <small><?= number_format($platformStats['total_instructors']) ?> instructors</small>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title mb-2">Total Courses</h6>
                        <h2 class="fw-bold"><?= number_format($platformStats['total_courses']) ?></h2>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-book fa-2x"></i>
                    </div>
                </div>
                <small><?= number_format($platformStats['total_enrollments']) ?> enrollments</small>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm bg-warning text-dark">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title mb-2">Pending Reviews</h6>
                        <h2 class="fw-bold"><?= $pendingApplications + $pendingTestimonials + $pendingCourses ?></h2>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                </div>
                <div class="d-flex justify-content-between">
                    <small><?= $pendingApplications ?> applications</small>
                    <small><?= $pendingTestimonials ?> testimonials</small>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title mb-2">Platform Rating</h6>
                        <h2 class="fw-bold"><?= $platformStats['average_rating'] ?>/5</h2>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-star fa-2x"></i>
                    </div>
                </div>
                <small>Based on student feedback</small>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Pending Actions -->
    <div class="col-lg-6 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="fw-bold mb-0">Pending Actions</h5>
            </div>
            <div class="card-body">
                <?php if ($pendingApplications > 0): ?>
                    <div class="alert alert-warning d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-user-plus me-2"></i>
                            <strong><?= $pendingApplications ?> instructor application(s)</strong> pending review
                        </div>
                        <a href="/admin-instructor-applications" class="btn btn-warning btn-sm">
                            Review Now
                        </a>
                    </div>
                <?php endif; ?>
                
                <?php if ($pendingTestimonials > 0): ?>
                    <div class="alert alert-info d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-comment me-2"></i>
                            <strong><?= $pendingTestimonials ?> testimonial(s)</strong> awaiting approval
                        </div>
                        <a href="/admin-testimonials" class="btn btn-info btn-sm text-white">
                            Review Now
                        </a>
                    </div>
                <?php endif; ?>
                
                <?php if ($pendingCourses > 0): ?>
                    <div class="alert alert-success d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-book me-2"></i>
                            <strong><?= $pendingCourses ?> course(s)</strong> need moderation
                        </div>
                        <a href="/admin-courses" class="btn btn-success btn-sm">
                            Review Now
                        </a>
                    </div>
                <?php endif; ?>
                
                <?php if ($pendingApplications + $pendingTestimonials + $pendingCourses === 0): ?>
                    <div class="text-center py-4">
                        <i class="fas fa-check-circle fa-2x text-success mb-3"></i>
                        <p class="text-muted mb-0">All caught up! No pending actions.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Quick Links -->
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="fw-bold mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-6">
                        <a href="/admin-users" class="btn btn-outline-dark w-100 text-start">
                            <i class="fas fa-users me-2"></i>Manage Users
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="/admin-courses" class="btn btn-outline-dark w-100 text-start">
                            <i class="fas fa-book me-2"></i>Course Moderation
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="/admin-analytics" class="btn btn-outline-dark w-100 text-start">
                            <i class="fas fa-chart-bar me-2"></i>View Analytics
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="/admin-settings" class="btn btn-outline-dark w-100 text-start">
                            <i class="fas fa-cog me-2"></i>System Settings
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Activity -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">Recent Activity</h5>
                <a href="#" class="btn btn-sm btn-outline-dark">View All</a>
            </div>
            <div class="card-body">
                <?php if (!empty($recentActivities)): ?>
                    <div class="activity-timeline">
                        <?php foreach ($recentActivities as $activity): ?>
                            <div class="activity-item d-flex mb-3">
                                <div class="activity-icon me-3">
                                    <i class="fas fa-<?= $activity['icon'] ?> text-<?= $activity['color'] ?>"></i>
                                </div>
                                <div class="activity-content flex-grow-1">
                                    <div class="fw-semibold"><?= $activity['title'] ?></div>
                                    <small class="text-muted"><?= $activity['description'] ?></small>
                                    <div class="text-muted small"><?= $activity['time'] ?></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fas fa-history fa-2x text-muted mb-3"></i>
                        <p class="text-muted mb-0">No recent activity</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
function exportReport() {
    // In a real app, this would generate and download a report
    alert('This would export a comprehensive platform report');
}
</script>