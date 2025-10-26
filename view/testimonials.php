<div class="container py-5">
    <!-- Header -->
    <div class="row mb-5">
        <div class="col-12 text-center">
            <h1 class="fw-bold mb-3">What Our Students Say</h1>
            <p class="lead text-muted mb-4">Real stories from our community of <?= number_format($platformStats['total_students']) ?>+ learners</p>
            
            <!-- CTA Button -->
            <?php if (isset($_SESSION['user'])): ?>
                <a href="/testimonial-submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-edit me-2"></i>Share Your Story
                </a>
            <?php else: ?>
                <a href="/login" class="btn btn-outline-dark btn-lg">
                    <i class="fas fa-sign-in-alt me-2"></i>Login to Share Your Story
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Stats -->
    <div class="row mb-5">
        <div class="col-md-3 col-6 text-center mb-3">
            <div class="h2 fw-bold text-primary"><?= number_format($platformStats['total_students']) ?>+</div>
            <div class="text-muted">Students</div>
        </div>
        <div class="col-md-3 col-6 text-center mb-3">
            <div class="h2 fw-bold text-success"><?= count($testimonials) ?></div>
            <div class="text-muted">Testimonials</div>
        </div>
        <div class="col-md-3 col-6 text-center mb-3">
            <div class="h2 fw-bold text-warning"><?= number_format($platformStats['average_rating'], 1) ?></div>
            <div class="text-muted">Average Rating</div>
        </div>
        <div class="col-md-3 col-6 text-center mb-3">
            <div class="h2 fw-bold text-info"><?= number_format($platformStats['total_courses']) ?>+</div>
            <div class="text-muted">Courses</div>
        </div>
    </div>

    <!-- Testimonials Grid -->
    <?php if (empty($testimonials)): ?>
        <div class="text-center py-5">
            <i class="fas fa-comments fa-3x text-muted mb-3"></i>
            <h4 class="fw-bold">No testimonials yet</h4>
            <p class="text-muted mb-4">Be the first to share your experience with our community!</p>
            <?php if (isset($_SESSION['user'])): ?>
                <a href="/testimonial-submit" class="btn btn-primary">
                    Share Your Story
                </a>
            <?php else: ?>
                <a href="/login" class="btn btn-primary">
                    Login to Share
                </a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($testimonials as $testimonial): ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card testimonial-card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <!-- Rating -->
                            <div class="text-warning mb-3">
                                <?= str_repeat('<i class="fas fa-star"></i>', $testimonial['rating']) ?>
                                <?= str_repeat('<i class="far fa-star"></i>', 5 - $testimonial['rating']) ?>
                            </div>
                            
                            <!-- Testimonial Text -->
                            <p class="card-text fst-italic mb-4">"<?= htmlspecialchars($testimonial['text']) ?>"</p>
                            
                            <!-- User Info -->
                            <div class="d-flex align-items-center">
                                <img src="<?= htmlspecialchars($testimonial['avatar']) ?>" 
                                     alt="<?= htmlspecialchars($testimonial['name']) ?>" 
                                     class="rounded-circle me-3" width="50" height="50" style="object-fit: cover;">
                                <div>
                                    <h6 class="fw-bold mb-1"><?= htmlspecialchars($testimonial['name']) ?></h6>
                                    <small class="text-muted"><?= htmlspecialchars($testimonial['role']) ?></small>
                                    <br>
                                    <small class="text-muted">
                                        <?= date('F j, Y', strtotime($testimonial['created_at'])) ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- CTA Section -->
    <div class="row mt-5">
        <div class="col-12 text-center">
            <div class="card border-0 bg-light">
                <div class="card-body py-5">
                    <h3 class="fw-bold mb-3">Ready to Start Your Journey?</h3>
                    <p class="text-muted mb-4">Join thousands of students who have transformed their careers with CodeMastery</p>
                    <div class="d-flex justify-content-center gap-3 flex-wrap">
                        <a href="/courses" class="btn btn-primary btn-lg">
                            <i class="fas fa-search me-2"></i>Explore Courses
                        </a>
                        <a href="/signup" class="btn btn-outline-dark btn-lg">
                            <i class="fas fa-user-plus me-2"></i>Join Free
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.testimonial-card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    border: 1px solid #e9ecef !important;
}

.testimonial-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
}

.card {
    border-radius: 12px;
}
</style>