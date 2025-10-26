<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 fw-bold text-dark mb-1">Billing & Purchases</h1>
                    <p class="text-muted mb-0">Manage your payment methods and view purchase history</p>
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

            <div class="row">
                <!-- Purchase History -->
                <div class="col-lg-8 mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="fw-bold mb-0">Purchase History</h5>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($purchaseHistory)): ?>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Course</th>
                                                <th>Date</th>
                                                <th>Amount</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($purchaseHistory as $purchase): ?>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <img src="<?= htmlspecialchars($purchase['course_image']) ?>" 
                                                                 alt="<?= htmlspecialchars($purchase['course_title']) ?>" 
                                                                 class="rounded me-3" width="40" height="40" style="object-fit: cover;">
                                                            <div>
                                                                <div class="fw-semibold"><?= htmlspecialchars($purchase['course_title']) ?></div>
                                                                <small class="text-muted">by <?= htmlspecialchars($purchase['instructor_name']) ?></small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <small class="text-muted">
                                                            <?= date('M j, Y', strtotime($purchase['purchase_date'])) ?>
                                                        </small>
                                                    </td>
                                                    <td class="fw-bold text-success">
                                                        $<?= number_format($purchase['amount'], 2) ?>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-<?= $purchase['status'] === 'completed' ? 'success' : ($purchase['status'] === 'refunded' ? 'warning' : 'secondary') ?>">
                                                            <?= ucfirst($purchase['status']) ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="dropdown">
                                                            <button class="btn btn-sm btn-outline-dark dropdown-toggle" type="button" 
                                                                    data-bs-toggle="dropdown">
                                                                Actions
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                <li>
                                                                    <a class="dropdown-item" href="/course-player?course=<?= $purchase['course_id'] ?>">
                                                                        <i class="fas fa-play me-2"></i>Continue Learning
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a class="dropdown-item" href="/course/<?= $purchase['course_id'] ?>" target="_blank">
                                                                        <i class="fas fa-eye me-2"></i>View Course
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a class="dropdown-item" href="#">
                                                                        <i class="fas fa-download me-2"></i>Download Invoice
                                                                    </a>
                                                                </li>
                                                                <?php if ($purchase['status'] === 'completed' && $purchase['can_refund']): ?>
                                                                    <li>
                                                                        <button type="button" class="dropdown-item text-warning" 
                                                                                data-bs-toggle="modal" data-bs-target="#refundModal<?= $purchase['id'] ?>">
                                                                            <i class="fas fa-undo me-2"></i>Request Refund
                                                                        </button>
                                                                    </li>
                                                                <?php endif; ?>
                                                            </ul>
                                                        </div>

                                                        <!-- Refund Modal -->
                                                        <?php if ($purchase['status'] === 'completed' && $purchase['can_refund']): ?>
                                                        <div class="modal fade" id="refundModal<?= $purchase['id'] ?>" tabindex="-1">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title">Request Refund</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                                    </div>
                                                                    <form method="POST">
                                                                        <div class="modal-body">
                                                                            <input type="hidden" name="purchase_id" value="<?= $purchase['id'] ?>">
                                                                            <div class="alert alert-info">
                                                                                <small>
                                                                                    <i class="fas fa-info-circle me-2"></i>
                                                                                    Refund requests are typically processed within 5-7 business days.
                                                                                </small>
                                                                            </div>
                                                                            <div class="mb-3">
                                                                                <label for="refund_reason" class="form-label">Reason for refund</label>
                                                                                <textarea class="form-control" id="refund_reason" name="refund_reason" 
                                                                                          rows="3" placeholder="Please explain why you're requesting a refund..." required></textarea>
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                            <button type="submit" name="request_refund" class="btn btn-warning">Request Refund</button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-5">
                                    <i class="fas fa-shopping-bag fa-3x text-muted mb-3"></i>
                                    <h5 class="fw-bold">No purchases yet</h5>
                                    <p class="text-muted">Your purchase history will appear here</p>
                                    <a href="/courses" class="btn btn-primary mt-2">
                                        <i class="fas fa-book me-2"></i>Browse Courses
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Payment Methods & Subscriptions -->
                <div class="col-lg-4">
                    <!-- Payment Methods -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                            <h5 class="fw-bold mb-0">Payment Methods</h5>
                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#paymentMethodModal">
                                <i class="fas fa-plus me-1"></i>Add
                            </button>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($paymentMethods)): ?>
                                <?php foreach ($paymentMethods as $method): ?>
                                    <div class="d-flex justify-content-between align-items-center mb-3 p-3 border rounded">
                                        <div>
                                            <div class="fw-semibold">
                                                <i class="fas fa-credit-card me-2 text-muted"></i>
                                                **** **** **** <?= $method['card_number'] ?>
                                            </div>
                                            <small class="text-muted">Expires <?= $method['expiry_date'] ?></small>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="default_payment" 
                                                   <?= $method['is_default'] ? 'checked' : '' ?>>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="text-center py-3">
                                    <i class="fas fa-credit-card fa-2x text-muted mb-2"></i>
                                    <p class="text-muted mb-0">No payment methods added</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Subscriptions -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="fw-bold mb-0">Subscriptions</h5>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($subscriptions)): ?>
                                <?php foreach ($subscriptions as $subscription): ?>
                                    <div class="mb-3 p-3 border rounded">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h6 class="fw-semibold mb-0"><?= htmlspecialchars($subscription['plan_name']) ?></h6>
                                            <span class="badge bg-<?= $subscription['status'] === 'active' ? 'success' : 'warning' ?>">
                                                <?= ucfirst($subscription['status']) ?>
                                            </span>
                                        </div>
                                        <small class="text-muted d-block">$<?= number_format($subscription['price'], 2) ?>/<?= $subscription['billing_cycle'] ?></small>
                                        <small class="text-muted">Renews on <?= date('M j, Y', strtotime($subscription['next_billing_date'])) ?></small>
                                        <div class="mt-2">
                                            <button class="btn btn-outline-dark btn-sm me-2">Manage</button>
                                            <button class="btn btn-outline-danger btn-sm">Cancel</button>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="text-center py-3">
                                    <i class="fas fa-gem fa-2x text-muted mb-2"></i>
                                    <p class="text-muted mb-2">No active subscriptions</p>
                                    <a href="/pricing" class="btn btn-outline-primary btn-sm">View Plans</a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Payment Method Modal -->
<div class="modal fade" id="paymentMethodModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Payment Method</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="card_holder" class="form-label">Card Holder Name</label>
                        <input type="text" class="form-control" id="card_holder" name="card_holder" required>
                    </div>
                    <div class="mb-3">
                        <label for="card_number" class="form-label">Card Number</label>
                        <input type="text" class="form-control" id="card_number" name="card_number" 
                               placeholder="1234 5678 9012 3456" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="expiry_date" class="form-label">Expiry Date</label>
                            <input type="text" class="form-control" id="expiry_date" name="expiry_date" 
                                   placeholder="MM/YY" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="cvv" class="form-label">CVV</label>
                            <input type="text" class="form-control" id="cvv" name="cvv" 
                                   placeholder="123" required>
                        </div>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="default_payment" name="default_payment" checked>
                        <label class="form-check-label" for="default_payment">
                            Set as default payment method
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="update_payment_method" class="btn btn-primary">Add Payment Method</button>
                </div>
            </form>
        </div>
    </div>
</div>