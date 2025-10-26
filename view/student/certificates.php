<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 fw-bold text-dark mb-1">My Certificates</h1>
                    <p class="text-muted mb-0">Showcase your learning achievements and earned certificates</p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-dark" onclick="printCertificates()">
                        <i class="fas fa-print me-2"></i>
                    </button>
                    <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#shareAllModal">
                        <i class="fas fa-share-alt me-2"></i>
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

            <!-- Certificates Grid -->
            <?php if (!empty($certificates)): ?>
                <div class="row">
                    <?php foreach ($certificates as $certificate): ?>
                        <div class="col-lg-6 col-xl-4 mb-4">
                            <div class="card border-0 shadow-sm h-100 certificate-card">
                                <div class="card-body text-center p-4">
                                    <div class="certificate-badge mb-3">
                                        <i class="fas fa-award fa-3x text-warning"></i>
                                    </div>
                                    <h5 class="fw-bold text-dark mb-2"><?= htmlspecialchars($certificate['course_title']) ?></h5>
                                    <p class="text-muted mb-3">Certificate of Completion</p>
                                    
                                    <div class="certificate-details mb-4">
                                        <div class="row text-start small">
                                            <div class="col-6">
                                                <strong>Student:</strong><br>
                                                <span class="text-muted"><?= htmlspecialchars($user['name']) ?></span>
                                            </div>
                                            <div class="col-6">
                                                <strong>Completed:</strong><br>
                                                <span class="text-muted"><?= date('M j, Y', strtotime($certificate['issued_date'])) ?></span>
                                            </div>
                                        </div>
                                        <div class="row text-start small mt-2">
                                            <div class="col-12">
                                                <strong>Certificate ID:</strong><br>
                                                <span class="text-muted font-monospace"><?= $certificate['certificate_id'] ?></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="certificate-actions">
                                        <form method="POST" class="d-inline">
                                            <input type="hidden" name="certificate_id" value="<?= $certificate['id'] ?>">
                                            <button type="submit" name="download_certificate" class="btn btn-primary btn-sm">
                                                <i class="fas fa-download me-1"></i>Download PDF
                                            </button>
                                        </form>
                                        <div class="dropdown d-inline">
                                            <button class="btn btn-outline-dark btn-sm dropdown-toggle" type="button" 
                                                    data-bs-toggle="dropdown">
                                                <i class="fas fa-share-alt me-1"></i>Share
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <form method="POST" class="d-inline">
                                                        <input type="hidden" name="certificate_id" value="<?= $certificate['id'] ?>">
                                                        <input type="hidden" name="platform" value="linkedin">
                                                        <button type="submit" name="share_certificate" class="dropdown-item">
                                                            <i class="fab fa-linkedin me-2 text-primary"></i>LinkedIn
                                                        </button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <form method="POST" class="d-inline">
                                                        <input type="hidden" name="certificate_id" value="<?= $certificate['id'] ?>">
                                                        <input type="hidden" name="platform" value="twitter">
                                                        <button type="submit" name="share_certificate" class="dropdown-item">
                                                            <i class="fab fa-twitter me-2 text-info"></i>Twitter
                                                        </button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <form method="POST" class="d-inline">
                                                        <input type="hidden" name="certificate_id" value="<?= $certificate['id'] ?>">
                                                        <input type="hidden" name="platform" value="facebook">
                                                        <button type="submit" name="share_certificate" class="dropdown-item">
                                                            <i class="fab fa-facebook me-2 text-primary"></i>Facebook
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                    <!-- Verification Badge -->
                                    <div class="mt-3">
                                        <small class="text-muted">
                                            <i class="fas fa-shield-alt me-1 text-success"></i>
                                            Verifiable Certificate
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <!-- No Certificates State -->
                <div class="text-center py-5">
                    <div class="empty-state">
                        <i class="fas fa-award fa-4x text-muted mb-4"></i>
                        <h3 class="fw-bold text-dark mb-3">No Certificates Yet</h3>
                        <p class="text-muted mb-4">Complete courses to earn certificates and showcase your achievements</p>
                        <div class="row justify-content-center">
                            <div class="col-md-8">
                                <div class="card border-0 bg-light">
                                    <div class="card-body p-4">
                                        <h5 class="fw-bold mb-3">How to earn certificates:</h5>
                                        <div class="row text-start">
                                            <div class="col-md-6 mb-3">
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0">
                                                        <i class="fas fa-play-circle text-primary me-3"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="fw-semibold">Complete Course Content</h6>
                                                        <small class="text-muted">Watch all video lessons and complete exercises</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0">
                                                        <i class="fas fa-tasks text-success me-3"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="fw-semibold">Pass Assessments</h6>
                                                        <small class="text-muted">Successfully complete quizzes and assignments</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0">
                                                        <i class="fas fa-project-diagram text-warning me-3"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="fw-semibold">Finish Final Project</h6>
                                                        <small class="text-muted">Complete the course final project</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0">
                                                        <i class="fas fa-chart-line text-info me-3"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="fw-semibold">Achieve 80% Progress</h6>
                                                        <small class="text-muted">Maintain consistent learning progress</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <a href="/my-courses" class="btn btn-primary mt-4">
                            <i class="fas fa-book me-2"></i>Continue Learning
                        </a>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Achievements Section -->
            <?php if (!empty($achievements)): ?>
                <div class="row mt-5">
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white border-0 py-3">
                                <h5 class="fw-bold mb-0">
                                    <i class="fas fa-trophy text-warning me-2"></i>Learning Achievements
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <?php foreach ($achievements as $achievement): ?>
                                        <div class="col-md-6 col-lg-4 mb-3">
                                            <div class="d-flex align-items-center p-3 border rounded">
                                                <div class="achievement-icon me-3">
                                                    <i class="fas fa-<?= $achievement['icon'] ?> fa-2x text-<?= $achievement['color'] ?? 'warning' ?>"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="fw-semibold mb-1"><?= htmlspecialchars($achievement['title']) ?></h6>
                                                    <p class="text-muted mb-1 small"><?= htmlspecialchars($achievement['description']) ?></p>
                                                    <small class="text-muted">
                                                        <i class="fas fa-calendar me-1"></i>
                                                        Earned <?= date('M j, Y', strtotime($achievement['earned_at'])) ?>
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Share All Modal -->
<div class="modal fade" id="shareAllModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Share All Certificates</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Share your complete learning portfolio with your network:</p>
                <div class="d-grid gap-2">
                    <button class="btn btn-outline-primary text-start">
                        <i class="fab fa-linkedin me-2"></i>Share on LinkedIn Profile
                    </button>
                    <button class="btn btn-outline-info text-start">
                        <i class="fab fa-twitter me-2"></i>Post on Twitter
                    </button>
                    <button class="btn btn-outline-primary text-start">
                        <i class="fab fa-facebook me-2"></i>Share on Facebook
                    </button>
                    <button class="btn btn-outline-dark text-start">
                        <i class="fas fa-envelope me-2"></i>Email Portfolio
                    </button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<style>
.certificate-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    border: 2px solid transparent;
}

.certificate-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
    border-color: #ffc107;
}

.certificate-badge {
    background: linear-gradient(135deg, #fff3cd, #ffecb5);
    border-radius: 50%;
    width: 80px;
    height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
}

.certificate-details {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 15px;
}

.empty-state {
    max-width: 600px;
    margin: 0 auto;
}

.achievement-icon {
    flex-shrink: 0;
}
</style>

<script>
function printCertificates() {
    window.print();
}

// Add certificate verification functionality
function verifyCertificate(certificateId) {
    const verificationUrl = `${window.location.origin}/verify-certificate/${certificateId}`;
    prompt('Certificate Verification URL:', verificationUrl);
}

// Share certificate on social media
function shareCertificate(platform, certificateId) {
    const url = `${window.location.origin}/certificate/${certificateId}`;
    const text = `I just earned a certificate on CodeMastery! Check it out:`;
    
    const shareUrls = {
        linkedin: `https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(url)}`,
        twitter: `https://twitter.com/intent/tweet?text=${encodeURIComponent(text)}&url=${encodeURIComponent(url)}`,
        facebook: `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`
    };
    
    if (shareUrls[platform]) {
        window.open(shareUrls[platform], '_blank', 'width=600,height=400');
    }
}
</script>