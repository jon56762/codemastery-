<div class="container-fluid mt-5 py-4">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 fw-bold mb-1">Earnings & Payouts</h1>
                    <p class="text-muted mb-0">Track your earnings and manage payouts</p>
                </div>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#payoutModal"
                        <?= $earningsData['available_balance'] <= 0 ? 'disabled' : '' ?>>
                    <i class="fas fa-money-bill-wave me-2"></i>Request Payout
                </button>
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

            <!-- Earnings Overview -->
            <div class="row mb-4">
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title text-muted mb-2">Available Balance</h6>
                                    <h3 class="fw-bold text-success">$<?= number_format($earningsData['available_balance'], 2) ?></h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-wallet fa-2x text-success"></i>
                                </div>
                            </div>
                            <small class="text-muted">Ready for payout</small>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title text-muted mb-2">Pending Balance</h6>
                                    <h3 class="fw-bold text-warning">$<?= number_format($earningsData['pending_balance'], 2) ?></h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-clock fa-2x text-warning"></i>
                                </div>
                            </div>
                            <small class="text-muted">Clearing in 30 days</small>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title text-muted mb-2">Total Earned</h6>
                                    <h3 class="fw-bold text-primary">$<?= number_format($earningsData['total_earned'], 2) ?></h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-chart-line fa-2x text-primary"></i>
                                </div>
                            </div>
                            <small class="text-muted">All time earnings</small>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title text-muted mb-2">Total Paid Out</h6>
                                    <h3 class="fw-bold text-info">$<?= number_format($earningsData['total_paid_out'], 2) ?></h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-money-check fa-2x text-info"></i>
                                </div>
                            </div>
                            <small class="text-muted">Total withdrawals</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Recent Transactions -->
                <div class="col-lg-8 mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                            <h5 class="fw-bold mb-0">Recent Earnings</h5>
                            <div class="dropdown">
                                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" 
                                        data-bs-toggle="dropdown">
                                    <i class="fas fa-filter me-2"></i>Filter
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="?filter=all">All</a></li>
                                    <li><a class="dropdown-item" href="?filter=this_month">This Month</a></li>
                                    <li><a class="dropdown-item" href="?filter=last_month">Last Month</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Course</th>
                                            <th>Student</th>
                                            <th>Type</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($earningsData['recent_transactions'] as $transaction): ?>
                                            <tr>
                                                <td>
                                                    <small><?= date('M j, Y', strtotime($transaction['date'])) ?></small>
                                                </td>
                                                <td>
                                                    <strong><?= htmlspecialchars($transaction['course_title']) ?></strong>
                                                </td>
                                                <td><?= htmlspecialchars($transaction['student_name']) ?></td>
                                                <td>
                                                    <span class="badge bg-<?= $transaction['type'] === 'sale' ? 'success' : 'info' ?>">
                                                        <?= ucfirst($transaction['type']) ?>
                                                    </span>
                                                </td>
                                                <td class="fw-semibold text-success">$<?= number_format($transaction['amount'], 2) ?></td>
                                                <td>
                                                    <span class="badge bg-<?= $transaction['status'] === 'paid' ? 'success' : 'warning' ?>">
                                                        <?= ucfirst($transaction['status']) ?>
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

                <!-- Payout History & Stats -->
                <div class="col-lg-4">
                    <!-- Next Payout -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="fw-bold mb-0">Next Payout</h5>
                        </div>
                        <div class="card-body">
                            <div class="text-center mb-3">
                                <div class="h4 fw-bold text-success">$<?= number_format($earningsData['available_balance'], 2) ?></div>
                                <small class="text-muted">Available for withdrawal</small>
                            </div>
                            
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">Minimum Payout</small>
                                <div class="fw-semibold">$50.00</div>
                            </div>
                            
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">Processing Time</small>
                                <div class="fw-semibold">3-5 business days</div>
                            </div>
                            
                            <div class="alert alert-info py-2">
                                <small>
                                    <i class="fas fa-info-circle me-2"></i>
                                    Payouts are processed on the 1st and 15th of each month
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Payout History -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="fw-bold mb-0">Recent Payouts</h5>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($payouts)): ?>
                                <?php foreach ($payouts as $payout): ?>
                                    <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                                        <div>
                                            <h6 class="fw-semibold mb-1">$<?= number_format($payout['amount'], 2) ?></h6>
                                            <small class="text-muted"><?= date('M j, Y', strtotime($payout['processed_at'])) ?></small>
                                        </div>
                                        <span class="badge bg-<?= $payout['status'] === 'completed' ? 'success' : 'warning' ?>">
                                            <?= ucfirst($payout['status']) ?>
                                        </span>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="text-center py-3">
                                    <i class="fas fa-money-bill-wave fa-2x text-muted mb-2"></i>
                                    <p class="text-muted small mb-0">No payout history yet</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Earnings by Course -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="fw-bold mb-0">Earnings by Course</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Course</th>
                                            <th>Enrollments</th>
                                            <th>Total Revenue</th>
                                            <th>Your Earnings</th>
                                            <th>Commission Rate</th>
                                            <th>Performance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($earningsData['earnings_by_course'] as $course): ?>
                                            <tr>
                                                <td>
                                                    <strong><?= htmlspecialchars($course['title']) ?></strong>
                                                </td>
                                                <td><?= $course['enrollments'] ?></td>
                                                <td class="text-success">$<?= number_format($course['total_revenue'], 2) ?></td>
                                                <td class="fw-bold text-primary">$<?= number_format($course['instructor_earnings'], 2) ?></td>
                                                <td>
                                                    <span class="badge bg-info"><?= $course['commission_rate'] ?>%</span>
                                                </td>
                                                <td>
                                                    <div class="progress" style="height: 6px;">
                                                        <div class="progress-bar bg-<?= $course['performance'] >= 80 ? 'success' : ($course['performance'] >= 60 ? 'warning' : 'danger') ?>" 
                                                             style="width: <?= $course['performance'] ?>%"></div>
                                                    </div>
                                                    <small class="text-muted"><?= $course['performance'] ?>%</small>
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

<!-- Payout Modal -->
<div class="modal fade" id="payoutModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-money-bill-wave me-2"></i>Request Payout
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="payout_amount" class="form-label fw-semibold">Amount to Withdraw</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" class="form-control" id="payout_amount" name="payout_amount"
                                   min="50" max="<?= $earningsData['available_balance'] ?>" 
                                   step="0.01" value="<?= min($earningsData['available_balance'], 50) ?>" required>
                        </div>
                        <div class="form-text">
                            Minimum withdrawal: $50.00 | Available: $<?= number_format($earningsData['available_balance'], 2) ?>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Payment Method</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method" value="paypal" checked>
                            <label class="form-check-label">
                                <i class="fab fa-paypal me-2 text-primary"></i>PayPal
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method" value="bank_transfer">
                            <label class="form-check-label">
                                <i class="fas fa-university me-2 text-success"></i>Bank Transfer
                            </label>
                        </div>
                    </div>
                    
                    <div class="alert alert-info">
                        <small>
                            <i class="fas fa-info-circle me-2"></i>
                            Payouts are processed within 3-5 business days. A 2.9% processing fee applies.
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="request_payout" class="btn btn-primary">
                        <i class="fas fa-paper-plane me-2"></i>Request Payout
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.card {
    border-radius: 12px;
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #6c757d;
}

.progress {
    border-radius: 10px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Update max amount dynamically
    const payoutAmount = document.getElementById('payout_amount');
    const availableBalance = <?= $earningsData['available_balance'] ?>;
    
    if (payoutAmount) {
        payoutAmount.addEventListener('input', function() {
            if (this.value > availableBalance) {
                this.value = availableBalance;
            }
        });
    }
});
</script>