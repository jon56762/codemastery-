<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 fw-bold text-dark mb-1">Revenue Management</h1>
        <p class="text-muted mb-0">Manage platform revenue and instructor payouts</p>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-outline-dark" onclick="exportFinancialReport()">
            <i class="fas fa-download me-2"></i>Export Report
        </button>
        <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#commissionModal">
            <i class="fas fa-percentage me-2"></i>Commission Settings
        </button>
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

<!-- Revenue Overview -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title mb-2">Total Revenue</h6>
                        <h2 class="fw-bold">$<?= number_format($revenueData['total_revenue'], 2) ?></h2>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-dollar-sign fa-2x"></i>
                    </div>
                </div>
                <small>All time platform revenue</small>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title mb-2">Platform Earnings</h6>
                        <h2 class="fw-bold">$<?= number_format($revenueData['platform_earnings'], 2) ?></h2>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-building fa-2x"></i>
                    </div>
                </div>
                <small>After instructor payouts</small>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm bg-warning text-dark">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title mb-2">Pending Payouts</h6>
                        <h2 class="fw-bold">$<?= number_format($revenueData['pending_payouts'], 2) ?></h2>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                </div>
                <small><?= count($pendingPayouts) ?> requests</small>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title mb-2">Commission Rate</h6>
                        <h2 class="fw-bold"><?= $revenueData['commission_rate'] ?>%</h2>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-percentage fa-2x"></i>
                    </div>
                </div>
                <small>Platform commission</small>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Pending Payouts -->
    <div class="col-lg-6 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">Pending Payouts</h5>
                <span class="badge bg-warning"><?= count($pendingPayouts) ?> pending</span>
            </div>
            <div class="card-body">
                <?php if (empty($pendingPayouts)): ?>
                    <div class="text-center py-4">
                        <i class="fas fa-check-circle fa-2x text-success mb-3"></i>
                        <p class="text-muted mb-0">No pending payouts</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Instructor</th>
                                    <th>Amount</th>
                                    <th>Requested</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($pendingPayouts as $payout): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="<?= htmlspecialchars($payout['instructor_avatar']) ?>" 
                                                     alt="<?= htmlspecialchars($payout['instructor_name']) ?>" 
                                                     class="rounded-circle me-2" width="32" height="32" style="object-fit: cover;">
                                                <div>
                                                    <div class="fw-semibold"><?= htmlspecialchars($payout['instructor_name']) ?></div>
                                                    <small class="text-muted"><?= $payout['courses_count'] ?> courses</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="fw-bold text-success">$<?= number_format($payout['amount'], 2) ?></td>
                                        <td>
                                            <small class="text-muted">
                                                <?= date('M j, Y', strtotime($payout['requested_at'])) ?>
                                            </small>
                                        </td>
                                        <td>
                                            <form method="POST" class="d-inline">
                                                <input type="hidden" name="payout_id" value="<?= $payout['id'] ?>">
                                                <button type="submit" name="process_payout" class="btn btn-success btn-sm">
                                                    <i class="fas fa-check me-1"></i>Process
                                                </button>
                                            </form>
                                            <button class="btn btn-outline-dark btn-sm" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#payoutDetailsModal<?= $payout['id'] ?>">
                                                <i class="fas fa-eye me-1"></i>Details
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Payout Details Modal -->
                                    <div class="modal fade" id="payoutDetailsModal<?= $payout['id'] ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Payout Details</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <strong>Instructor:</strong> <?= htmlspecialchars($payout['instructor_name']) ?>
                                                    </div>
                                                    <div class="mb-3">
                                                        <strong>Amount:</strong> $<?= number_format($payout['amount'], 2) ?>
                                                    </div>
                                                    <div class="mb-3">
                                                        <strong>Requested:</strong> <?= date('F j, Y g:i A', strtotime($payout['requested_at'])) ?>
                                                    </div>
                                                    <div class="mb-3">
                                                        <strong>Payment Method:</strong> <?= ucfirst($payout['payment_method']) ?>
                                                    </div>
                                                    <div>
                                                        <strong>Breakdown:</strong>
                                                        <ul class="mt-2">
                                                            <?php foreach ($payout['breakdown'] as $item): ?>
                                                                <li><?= htmlspecialchars($item['course']) ?>: $<?= number_format($item['amount'], 2) ?></li>
                                                            <?php endforeach; ?>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    <form method="POST" class="d-inline">
                                                        <input type="hidden" name="payout_id" value="<?= $payout['id'] ?>">
                                                        <button type="submit" name="process_payout" class="btn btn-success">
                                                            Process Payout
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Revenue Breakdown -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="fw-bold mb-0">Revenue Breakdown</h5>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <h6 class="fw-semibold mb-3">By Category</h6>
                    <?php if (!empty($revenueData['revenue_by_category'])): ?>
                        <?php foreach ($revenueData['revenue_by_category'] as $category): ?>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span><?= htmlspecialchars($category['name']) ?></span>
                                    <span class="fw-semibold">$<?= number_format($category['revenue'], 2) ?></span>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar" style="width: <?= $category['percentage'] ?>%; background-color: <?= $category['color'] ?>"></div>
                                </div>
                                <small class="text-muted"><?= $category['percentage'] ?>% of total</small>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center py-3">
                            <small class="text-muted">No revenue data by category</small>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="border-top pt-3">
                    <h6 class="fw-semibold mb-3">Monthly Trends</h6>
                    <?php if (!empty($revenueData['monthly_trends'])): ?>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Month</th>
                                        <th>Revenue</th>
                                        <th>Growth</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($revenueData['monthly_trends'] as $trend): ?>
                                        <tr>
                                            <td><?= $trend['month'] ?></td>
                                            <td class="fw-semibold text-success">$<?= number_format($trend['revenue'], 2) ?></td>
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
                        <div class="text-center py-3">
                            <small class="text-muted">No monthly trend data available</small>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Commission Settings Modal -->
<div class="modal fade" id="commissionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Commission Settings</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="commission_rate" class="form-label fw-semibold">Platform Commission Rate</label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="commission_rate" name="commission_rate" 
                                   min="10" max="50" step="0.1" value="<?= $revenueData['commission_rate'] ?>" required>
                            <span class="input-group-text">%</span>
                        </div>
                        <div class="form-text">
                            Current rate: <?= $revenueData['commission_rate'] ?>%. Instructors receive <?= 100 - $revenueData['commission_rate'] ?>% of course sales.
                        </div>
                    </div>
                    <div class="alert alert-warning">
                        <small>
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Changing commission rate will affect future sales only. Existing payouts will not be modified.
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="update_commission" class="btn btn-primary">Update Commission</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function exportFinancialReport() {
    alert('This would export a comprehensive financial report');
}
</script>