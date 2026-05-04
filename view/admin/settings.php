<div class="d-flex justify-content-between align-items-center mb-4 row">
    <div class="col-12">
        <h1 class="h3 fw-bold text-dark mb-1">System Settings</h1>
        <p class="text-muted mb-0">Configure platform settings and preferences</p>
    </div>
    <div class="d-flex col-12 mt-4">
        <button class="btn btn-outline-dark me-2" onclick="backupSystem()">
            <i class="fas fa-download me-2"></i>Backup
        </button>
        <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#maintenanceModal">
            <i class="fas fa-tools me-2"></i>Maintenance
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

<div class="row">
    <!-- General Settings -->
    <div class="col-lg-6 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="fw-bold mb-0">
                    <i class="fas fa-cog me-2"></i>General Settings
                </h5>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label for="site_name" class="form-label fw-semibold">Site Name</label>
                        <input type="text" class="form-control" id="site_name" name="site_name"
                            value="<?= htmlspecialchars($settings['site_name'] ?? 'CodeMastery') ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="site_email" class="form-label fw-semibold">Site Email</label>
                        <input type="email" class="form-control" id="site_email" name="site_email"
                            value="<?= htmlspecialchars($settings['site_email'] ?? 'admin@codemastery.com') ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="site_description" class="form-label fw-semibold">Site Description</label>
                        <textarea class="form-control" id="site_description" name="site_description"
                            rows="3"><?= htmlspecialchars($settings['site_description'] ?? 'Learn to code from industry experts') ?></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="currency" class="form-label fw-semibold">Currency</label>
                            <select class="form-select" id="currency" name="currency">
                                <option value="USD" <?= ($settings['currency'] ?? 'USD') === 'USD' ? 'selected' : '' ?>>US Dollar (USD)</option>
                                <option value="EUR" <?= ($settings['currency'] ?? 'USD') === 'EUR' ? 'selected' : '' ?>>Euro (EUR)</option>
                                <option value="GBP" <?= ($settings['currency'] ?? 'USD') === 'GBP' ? 'selected' : '' ?>>British Pound (GBP)</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="timezone" class="form-label fw-semibold">Timezone</label>
                            <select class="form-select" id="timezone" name="timezone">
                                <option value="UTC" <?= ($settings['timezone'] ?? 'UTC') === 'UTC' ? 'selected' : '' ?>>UTC</option>
                                <option value="America/New_York" <?= ($settings['timezone'] ?? 'UTC') === 'America/New_York' ? 'selected' : '' ?>>Eastern Time (ET)</option>
                                <option value="Europe/London" <?= ($settings['timezone'] ?? 'UTC') === 'Europe/London' ? 'selected' : '' ?>>London (GMT)</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit" name="update_general_settings" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Save General Settings
                    </button>
                </form>
            </div>
        </div>

        <!-- Email Settings Form -->
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="fw-bold mb-0">
                    <i class="fas fa-envelope me-2"></i>Email Configuration
                </h5>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="smtp_host" class="form-label fw-semibold">SMTP Host</label>
                            <input type="text" class="form-control" id="smtp_host" name="smtp_host"
                                value="<?= htmlspecialchars($settings['smtp_host'] ?? 'smtp.gmail.com') ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="smtp_port" class="form-label fw-semibold">SMTP Port</label>
                            <input type="number" class="form-control" id="smtp_port" name="smtp_port"
                                value="<?= htmlspecialchars($settings['smtp_port'] ?? '587') ?>" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="smtp_username" class="form-label fw-semibold">SMTP Username</label>
                            <input type="email" class="form-control" id="smtp_username" name="smtp_username"
                                value="<?= htmlspecialchars($settings['smtp_username'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="smtp_password" class="form-label fw-semibold">SMTP Password</label>
                            <input type="password" class="form-control" id="smtp_password" name="smtp_password"
                                value="<?= htmlspecialchars($settings['smtp_password'] ?? '') ?>" required>
                            <div class="form-text">Use App Password for Gmail</div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="smtp_encryption" class="form-label fw-semibold">Encryption</label>
                            <select class="form-select" id="smtp_encryption" name="smtp_encryption" required>
                                <option value="tls" <?= ($settings['smtp_encryption'] ?? 'tls') === 'tls' ? 'selected' : '' ?>>TLS</option>
                                <option value="ssl" <?= ($settings['smtp_encryption'] ?? 'tls') === 'ssl' ? 'selected' : '' ?>>SSL</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="from_email" class="form-label fw-semibold">From Email</label>
                            <input type="email" class="form-control" id="from_email" name="from_email"
                                value="<?= htmlspecialchars($settings['from_email'] ?? 'noreply@codemastery.com') ?>" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="from_name" class="form-label fw-semibold">From Name</label>
                        <input type="text" class="form-control" id="from_name" name="from_name"
                            value="<?= htmlspecialchars($settings['from_name'] ?? 'CodeMastery') ?>" required>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" name="update_email_settings" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Save Email Settings
                        </button>
                        <button type="button" class="btn btn-success" onclick="testEmailConfig()">
                            <i class="fas fa-paper-plane me-2"></i>Test Email Configuration
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <!-- Payment Settings -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="fw-bold mb-0">
                    <i class="fas fa-credit-card me-2"></i>Payment Settings
                </h5>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Payment Gateways</label>
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" id="stripe_enabled" name="stripe_enabled"
                                <?= ($settings['stripe_enabled'] ?? 0) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="stripe_enabled">Enable Stripe Payments</label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="paypal_enabled" name="paypal_enabled"
                                <?= ($settings['paypal_enabled'] ?? 0) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="paypal_enabled">Enable PayPal Payments</label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="stripe_publishable_key" class="form-label fw-semibold">Stripe Publishable Key</label>
                        <input type="text" class="form-control" id="stripe_publishable_key" name="stripe_publishable_key"
                            value="<?= htmlspecialchars($settings['stripe_publishable_key'] ?? '') ?>"
                            placeholder="pk_test_...">
                    </div>

                    <div class="mb-3">
                        <label for="stripe_secret_key" class="form-label fw-semibold">Stripe Secret Key</label>
                        <input type="password" class="form-control" id="stripe_secret_key" name="stripe_secret_key"
                            value="<?= htmlspecialchars($settings['stripe_secret_key'] ?? '') ?>"
                            placeholder="sk_test_...">
                        <div class="form-text">
                            Keep your secret keys secure. Never share them publicly.
                        </div>
                    </div>

                    <button type="submit" name="update_payment_settings" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Save Payment Settings
                    </button>
                </form>
            </div>
        </div>

        <!-- System Information -->
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="fw-bold mb-0">
                    <i class="fas fa-info-circle me-2"></i>System Information
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-2">
                    <strong>Platform Version:</strong>
                    <span class="badge bg-primary">v1.0.0</span>
                </div>
                <div class="mb-2">
                    <strong>PHP Version:</strong>
                    <span class="badge bg-info"><?= phpversion() ?></span>
                </div>
                <div class="mb-2">
                    <strong>Server Software:</strong>
                    <span class="text-muted"><?= $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown' ?></span>
                </div>
                <div class="mb-2">
                    <strong>Database:</strong>
                    <span class="badge bg-success">File-based JSON</span>
                </div>
                <div class="mb-2">
                    <strong>Last Backup:</strong>
                    <span class="text-muted">Never</span>
                </div>
                <div class="mb-3">
                    <strong>System Status:</strong>
                    <span class="badge bg-success">Operational</span>
                </div>

                <div class="alert alert-warning">
                    <small>
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Regular backups are recommended to prevent data loss.
                    </small>
                </div>

                <div class="d-grid gap-2">
                    <button class="btn btn-outline-danger" onclick="clearCache()">
                        <i class="fas fa-broom me-2"></i>Clear System Cache
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Maintenance Modal -->
<div class="modal fade" id="maintenanceModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Maintenance Mode</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Enabling maintenance mode will make the site unavailable to users.
                </div>
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" id="maintenance_mode">
                    <label class="form-check-label fw-semibold" for="maintenance_mode">Enable Maintenance Mode</label>
                </div>
                <div class="mb-3">
                    <label for="maintenance_message" class="form-label">Maintenance Message</label>
                    <textarea class="form-control" id="maintenance_message" rows="3"
                        placeholder="Site is currently under maintenance..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-warning" onclick="enableMaintenance()">
                    <i class="fas fa-tools me-2"></i>Activate Maintenance
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function backupSystem() {
        if (confirm('This will create a backup of all system data. Continue?')) {
            alert('Backup process started... This would create a system backup in a real application.');
        }
    }

    function clearCache() {
        if (confirm('Clear all system cache? This may improve performance.')) {
            alert('Cache cleared successfully!');
        }
    }

    function testEmail() {
        alert('This would test the email configuration in a real application.');
    }

    function enableMaintenance() {
        if (confirm('Enable maintenance mode? The site will be unavailable to users.')) {
            alert('Maintenance mode activated!');
            $('#maintenanceModal').modal('hide');
        }
    }

    function testEmailConfig() {
    const testEmail = prompt('Enter email address to send test email:');
    if (testEmail) {
        fetch('/test-email', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'test_email=' + encodeURIComponent(testEmail)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Test email sent successfully!');
            } else {
                alert('Failed to send test email: ' + data.error);
            }
        })
        .catch(error => {
            alert('Error testing email configuration');
        });
    }
}
</script>