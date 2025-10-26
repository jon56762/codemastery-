<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 fw-bold text-dark mb-1">Platform Analytics</h1>
        <p class="text-muted mb-0">Comprehensive insights into platform performance</p>
    </div>
    <div class="d-flex gap-2">
        <!-- Date Range Filter -->
        <form method="GET" class="d-flex gap-2">
            <input type="date" class="form-control" name="start_date" value="<?= $startDate ?>">
            <span class="align-self-center">to</span>
            <input type="date" class="form-control" name="end_date" value="<?= $endDate ?>">
            <select class="form-select" name="period" onchange="this.form.submit()">
                <option value="monthly" <?= $period === 'monthly' ? 'selected' : '' ?>>Monthly</option>
                <option value="quarterly" <?= $period === 'quarterly' ? 'selected' : '' ?>>Quarterly</option>
                <option value="yearly" <?= $period === 'yearly' ? 'selected' : '' ?>>Yearly</option>
            </select>
            <button type="submit" class="btn btn-dark">
                <i class="fas fa-filter me-2"></i>Apply
            </button>
        </form>
    </div>
</div>

<!-- Key Metrics -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-muted mb-2">Total Revenue</h6>
                        <h3 class="fw-bold text-success">$<?= number_format($analyticsData['total_revenue'], 2) ?></h3>
                        <small class="text-<?= $analyticsData['revenue_growth'] >= 0 ? 'success' : 'danger' ?>">
                            <i class="fas fa-arrow-<?= $analyticsData['revenue_growth'] >= 0 ? 'up' : 'down' ?> me-1"></i>
                            <?= abs($analyticsData['revenue_growth']) ?>%
                        </small>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-dollar-sign fa-2x text-success"></i>
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
                        <h6 class="card-title text-muted mb-2">New Users</h6>
                        <h3 class="fw-bold text-primary"><?= number_format($analyticsData['new_users']) ?></h3>
                        <small class="text-<?= $analyticsData['user_growth'] >= 0 ? 'success' : 'danger' ?>">
                            <i class="fas fa-arrow-<?= $analyticsData['user_growth'] >= 0 ? 'up' : 'down' ?> me-1"></i>
                            <?= abs($analyticsData['user_growth']) ?>%
                        </small>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-users fa-2x text-primary"></i>
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
                        <h6 class="card-title text-muted mb-2">Course Enrollments</h6>
                        <h3 class="fw-bold text-info"><?= number_format($analyticsData['new_enrollments']) ?></h3>
                        <small class="text-<?= $analyticsData['enrollment_growth'] >= 0 ? 'success' : 'danger' ?>">
                            <i class="fas fa-arrow-<?= $analyticsData['enrollment_growth'] >= 0 ? 'up' : 'down' ?> me-1"></i>
                            <?= abs($analyticsData['enrollment_growth']) ?>%
                        </small>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-book fa-2x text-info"></i>
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
                        <h6 class="card-title text-muted mb-2">Avg. Rating</h6>
                        <h3 class="fw-bold text-warning"><?= $analyticsData['avg_rating'] ?>/5</h3>
                        <small class="text-muted">
                            Based on <?= number_format($analyticsData['total_reviews']) ?> reviews
                        </small>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-star fa-2x text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Revenue Chart -->
    <div class="col-lg-8 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="fw-bold mb-0">Revenue Trends</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($analyticsData['revenue_trends'])): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Period</th>
                                    <th>Revenue</th>
                                    <th>Enrollments</th>
                                    <th>Avg. Course Price</th>
                                    <th>Growth</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($analyticsData['revenue_trends'] as $trend): ?>
                                    <tr>
                                        <td><?= $trend['period'] ?></td>
                                        <td class="text-success fw-semibold">$<?= number_format($trend['revenue'], 2) ?></td>
                                        <td><?= number_format($trend['enrollments']) ?></td>
                                        <td>$<?= number_format($trend['avg_price'], 2) ?></td>
                                        <td>
                                            <span class="badge bg-<?= $trend['growth'] >= 0 ? 'success' : 'danger' ?>">
                                                <?= $trend['growth'] >= 0 ? '+' : '' ?><?= $trend['growth'] ?>%
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5 bg-light rounded">
                        <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Revenue Analytics</h5>
                        <p class="text-muted">No revenue data available for the selected period.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- User Analytics -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="fw-bold mb-0">User Distribution</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span>Students</span>
                        <span class="fw-semibold">
                            <?= number_format($analyticsData['user_distribution']['students']) ?> 
                            (<?= $analyticsData['user_distribution']['student_percent'] ?>%)
                        </span>
                    </div>
                    <div class="progress mb-3" style="height: 8px;">
                        <div class="progress-bar bg-success" style="width: <?= $analyticsData['user_distribution']['student_percent'] ?>%"></div>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span>Instructors</span>
                        <span class="fw-semibold">
                            <?= number_format($analyticsData['user_distribution']['instructors']) ?> 
                            (<?= $analyticsData['user_distribution']['instructor_percent'] ?>%)
                        </span>
                    </div>
                    <div class="progress mb-3" style="height: 8px;">
                        <div class="progress-bar bg-info" style="width: <?= $analyticsData['user_distribution']['instructor_percent'] ?>%"></div>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span>Admins</span>
                        <span class="fw-semibold">
                            <?= number_format($analyticsData['user_distribution']['admins']) ?> 
                            (<?= $analyticsData['user_distribution']['admin_percent'] ?>%)
                        </span>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-danger" style="width: <?= $analyticsData['user_distribution']['admin_percent'] ?>%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Performing Courses -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="fw-bold mb-0">Top Courses</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($analyticsData['top_courses'])): ?>
                    <?php foreach ($analyticsData['top_courses'] as $index => $course): ?>
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" 
                                     style="width: 35px; height: 35px;">
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
                <?php else: ?>
                    <div class="text-center py-3">
                        <small class="text-muted">No course data available</small>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Detailed Analytics Table -->
<div class="card border-0 shadow-sm mt-4">
    <div class="card-header bg-white border-0 py-3">
        <h5 class="fw-bold mb-0">Detailed Platform Metrics</h5>
    </div>
    <div class="card-body">
        <?php if (!empty($analyticsData['detailed_metrics'])): ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Metric</th>
                            <th>Current Period</th>
                            <th>Previous Period</th>
                            <th>Growth</th>
                            <th>Trend</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($analyticsData['detailed_metrics'] as $metric): ?>
                            <tr>
                                <td class="fw-semibold"><?= $metric['name'] ?></td>
                                <td><?= $metric['current'] ?></td>
                                <td><?= $metric['previous'] ?></td>
                                <td>
                                    <span class="badge bg-<?= $metric['growth'] >= 0 ? 'success' : 'danger' ?>">
                                        <?= $metric['growth'] >= 0 ? '+' : '' ?><?= $metric['growth'] ?>%
                                    </span>
                                </td>
                                <td>
                                    <i class="fas fa-arrow-<?= $metric['growth'] >= 0 ? 'up text-success' : 'down text-danger' ?>"></i>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center py-4">
                <i class="fas fa-chart-bar fa-2x text-muted mb-3"></i>
                <p class="text-muted">No detailed metrics available for the selected period.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
function exportAnalytics() {
    alert('This would export analytics data as CSV/PDF');
}
</script>