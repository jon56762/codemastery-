<div class="container py-5" style="margin-top: 80px;">
    <h2 class="fw-bold mb-4"><i class="fas fa-bell me-2"></i>Notifications</h2>

    <?php if (empty($userNotifications)): ?>
        <div class="text-center py-5">
            <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">No notifications yet</h5>
            <p>We'll let you know when something important happens.</p>
        </div>
    <?php else: ?>
        <div class="list-group">
            <?php foreach ($userNotifications as $notif): ?>
                <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-start <?= empty($notif['read']) ? 'bg-light border-start border-4 border-primary' : '' ?>">
                    <div class="ms-2 me-auto">
                        <div class="d-flex align-items-center">
                            <?php if (empty($notif['read'])): ?>
                                <span class="badge bg-primary me-2">New</span>
                            <?php endif; ?>
                            <h6 class="fw-bold mb-1"><?= htmlspecialchars($notif['title'] ?? 'Notification') ?></h6>
                        </div>
                        <p class="mb-1"><?= htmlspecialchars($notif['message'] ?? '') ?></p>
                        <small class="text-muted">
                            <?= date('M j, Y \a\t g:i A', strtotime($notif['created_at'])) ?>
                        </small>
                        <?php if (!empty($notif['link'])): ?>
                            <a href="<?= $notif['link'] ?>" class="btn btn-sm btn-outline-primary ms-3">
                                <i class="fas fa-arrow-right me-1"></i>View
                            </a>
                        <?php endif; ?>
                    </div>
                    <?php if (empty($notif['read'])): ?>
                        <form method="POST" class="ms-3">
                            <input type="hidden" name="notification_id" value="<?= $notif['id'] ?>">
                            <button type="submit" name="mark_read" class="btn btn-sm btn-outline-success">
                                <i class="fas fa-check"></i> Mark Read
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>