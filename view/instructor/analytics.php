<div class="container-fluid mt-5 py-4">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 fw-bold mb-1">Course Analytics</h1>
                    <p class="text-muted mb-0">Track your course performance and student engagement</p>
                </div>
                <div class="d-flex gap-2">
                    <!-- Date Range Filter -->
                    <form method="GET" class="d-flex gap-2">
                        <input type="date" class="form-control" name="start_date" value="<?= $startDate ?>">
                        <span class="align-self-center">to</span>
                        <input type="date" class="form-control" name="end_date" value="<?= $endDate ?>">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter me-2"></i>Apply
                        </button>
                    </form>
                </div>
            </div>

            <!-- Stats Overview -->
            <div class="row mb-4">
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title text-muted mb-2">Total Revenue</h6>
                                    <h3 class="fw-bold text-success">$<?= number_format($analyticsData['total_revenue'], 2) ?></h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-dollar-sign fa-2x text-success"></i>
                                </div>
                            </div>
                            <small class="text-muted"><?= $analyticsData['revenue_change'] ?>% from previous period</small>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title text-muted mb-2">New Students</h6>
                                    <h3 class="fw-bold text-primary"><?= $analyticsData['new_students'] ?></h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-users fa-2x text-primary"></i>
                                </div>
                            </div>
                            <small class="text-muted"><?= $analyticsData['student_change'] ?>% from previous period</small>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title text-muted mb-2">Course Completion</h6>
                                    <h3 class="fw-bold text-info"><?= $analyticsData['completion_rate'] ?>%</h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-flag-checkered fa-2x text-info"></i>
                                </div>
                            </div>
                            <small class="text-muted">Average course completion rate</small>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title text-muted mb-2">Avg Rating</h6>
                                    <h3 class="fw-bold text-warning"><?= $analyticsData['avg_rating'] ?>/5</h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-star fa-2x text-warning"></i>
                                </div>
                            </div>
                            <small class="text-muted">Based on <?= $analyticsData['total_reviews'] ?> reviews</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Revenue Chart -->
                <div class="col-lg-8 mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="fw-bold mb-0">Revenue Overview</h5>
                        </div>
                        <div class="card-body">
                            <div class="text-center py-5">
                                <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Revenue Chart</h5>
                                <p class="text-muted">Revenue visualization would appear here with a chart library</p>
                                <div class="table-responsive mt-4">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Course</th>
                                                <th>Enrollments</th>
                                                <th>Revenue</th>
                                                <th>Trend</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($analyticsData['course_performance'] as $course): ?>
                                                <tr>
                                                    <td>
                                                        <strong><?= htmlspecialchars($course['title']) ?></strong>
                                                    </td>
                                                    <td><?= $course['enrollments'] ?></td>
                                                    <td class="text-success">$<?= number_format($course['revenue'], 2) ?></td>
                                                    <td>
                                                        <span class="badge bg-<?= $course['trend'] === 'up' ? 'success' : 'danger' ?>">
                                                            <i class="fas fa-arrow-<?= $course['trend'] === 'up' ? 'up' : 'down' ?> me-1"></i>
                                                            <?= $course['change'] ?>%
                                                        </span>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Student Engagement -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="fw-bold mb-0">Student Engagement</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-4">
                                <h6 class="fw-semibold">Active Students</h6>
                                <div class="progress mb-2" style="height: 8px;">
                                    <div class="progress-bar bg-success" style="width: <?= $analyticsData['active_students_rate'] ?>%"></div>
                                </div>
                                <small class="text-muted"><?= $analyticsData['active_students_rate'] ?>% of students active this period</small>
                            </div>

                            <div class="mb-4">
                                <h6 class="fw-semibold">Lesson Completion</h6>
                                <div class="progress mb-2" style="height: 8px;">
                                    <div class="progress-bar bg-info" style="width: <?= $analyticsData['lesson_completion_rate'] ?>%"></div>
                                </div>
                                <small class="text-muted"><?= $analyticsData['lesson_completion_rate'] ?>% average lesson completion</small>
                            </div>

                            <div>
                                <h6 class="fw-semibold">Assignment Submission</h6>
                                <div class="progress mb-2" style="height: 8px;">
                                    <div class="progress-bar bg-warning" style="width: <?= $analyticsData['assignment_submission_rate'] ?>%"></div>
                                </div>
                                <small class="text-muted"><?= $analyticsData['assignment_submission_rate'] ?>% assignments submitted</small>
                            </div>
                        </div>
                    </div>

                    <!-- Top Performing Courses -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="fw-bold mb-0">Top Performing Courses</h5>
                        </div>
                        <div class="card-body">
                            <?php foreach ($analyticsData['top_courses'] as $index => $course): ?>
                                <div class="d-flex align-items-center mb-3">
                                    <div class="flex-shrink-0">
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                             style="width: 40px; height: 40px;">
                                            <?= $index + 1 ?>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="fw-semibold mb-1"><?= htmlspecialchars($course['title']) ?></h6>
                                        <div class="d-flex justify-content-between">
                                            <small class="text-muted">$<?= number_format($course['revenue'], 2) ?></small>
                                            <small class="text-warning">
                                                <i class="fas fa-star me-1"></i><?= $course['rating'] ?>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detailed Analytics -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="fw-bold mb-0">Detailed Course Analytics</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Course</th>
                                            <th>Enrollments</th>
                                            <th>Completion Rate</th>
                                            <th>Avg Rating</th>
                                            <th>Revenue</th>
                                            <th>Student Satisfaction</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($analyticsData['detailed_analytics'] as $course): ?>
                                            <tr>
                                                <td>
                                                    <strong><?= htmlspecialchars($course['title']) ?></strong>
                                                    <br>
                                                    <small class="text-muted"><?= $course['status'] ?></small>
                                                </td>
                                                <td>
                                                    <div class="fw-semibold"><?= $course['enrollments'] ?></div>
                                                    <small class="text-muted"><?= $course['new_enrollments'] ?> new</small>
                                                </td>
                                                <td>
                                                    <div class="progress" style="height: 6px;">
                                                        <div class="progress-bar bg-success" style="width: <?= $course['completion_rate'] ?>%"></div>
                                                    </div>
                                                    <small class="text-muted"><?= $course['completion_rate'] ?>%</small>
                                                </td>
                                                <td>
                                                    <div class="text-warning">
                                                        <?= str_repeat('★', floor($course['rating'])) ?><?= str_repeat('☆', 5 - floor($course['rating'])) ?>
                                                        <small class="text-muted">(<?= $course['rating'] ?>)</small>
                                                    </div>
                                                </td>
                                                <td class="text-success fw-semibold">$<?= number_format($course['revenue'], 2) ?></td>
                                                <td>
                                                    <span class="badge bg-<?= $course['satisfaction'] >= 80 ? 'success' : ($course['satisfaction'] >= 60 ? 'warning' : 'danger') ?>">
                                                        <?= $course['satisfaction'] ?>%
                                                    </span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border-radius: 12px;
}

.progress {
    border-radius: 10px;
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #6c757d;
}
</style>