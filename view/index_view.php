<!-- Success Messages -->
<?php if (isset($_SESSION['newsletter_success'])): ?>
    <div class="alert alert-success alert-dismissible fade show mb-0" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        <?= $_SESSION['newsletter_success'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['newsletter_success']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['signup_success'])): ?>
    <div class="alert alert-success alert-dismissible fade show mb-0" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        <?= $_SESSION['signup_success'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['signup_success']); ?>
<?php endif; ?>

<!-- Hero Section -->
<section class="hero-section bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">Master Coding Skills That Get You Hired</h1>
                <p class="lead mb-4">Join <?= number_format($platformStats['students']) ?>+ students learning web development, data science, and more. Build projects, earn certificates, and advance your career.</p>
                <div class="d-flex flex-wrap gap-3 mb-4">
                    <a href="<?= BASE_PATH ?>/signup" class="btn btn-light btn-lg px-4 py-2 fw-bold">
                        <i class="fas fa-rocket me-2"></i>Start Learning Free
                    </a>
                    <a href="<?= BASE_PATH ?>/courses" class="btn btn-outline-light btn-lg px-4 py-2">
                        <i class="fas fa-search me-2"></i>Browse Courses
                    </a>
                </div>
                <div class="d-flex flex-wrap gap-4 text-white-50">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-star text-warning me-2"></i>
                        <span>Rated <?= $platformStats['rating'] ?>/5 by students</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-users me-2"></i>
                        <span><?= number_format($platformStats['students']) ?>+ Students</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <img src="<?= BASE_PATH ?>/assets/images/hero-image.svg" alt="Learning Platform" class="img-fluid" style="max-height: 400px;">
            </div>
        </div>
    </div>
</section>

<!-- Trust Indicators -->
<section class="py-4 bg-light">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-2 col-6 mb-3">
                <i class="fas fa-users fa-2x text-primary mb-2"></i>
                <div class="h3 fw-bold text-primary"><?= number_format($platformStats['students']) ?>+</div>
                <div class="text-muted">Students</div>
            </div>
            <div class="col-md-2 col-6 mb-3">
                <i class="fas fa-book fa-2x text-primary mb-2"></i>
                <div class="h3 fw-bold text-primary"><?= $platformStats['courses'] ?>+</div>
                <div class="text-muted">Courses</div>
            </div>
            <div class="col-md-2 col-6 mb-3">
                <i class="fas fa-chalkboard-teacher fa-2x text-primary mb-2"></i>
                <div class="h3 fw-bold text-primary"><?= $platformStats['instructors'] ?>+</div>
                <div class="text-muted">Instructors</div>
            </div>
            <div class="col-md-2 col-6 mb-3">
                <i class="fas fa-star fa-2x text-primary mb-2"></i>
                <div class="h3 fw-bold text-primary"><?= $platformStats['rating'] ?>/5</div>
                <div class="text-muted">Average Rating</div>
            </div>
            <div class="col-md-2 col-6 mb-3">
                <i class="fas fa-trophy fa-2x text-primary mb-2"></i>
                <div class="h3 fw-bold text-primary"><?= number_format($platformStats['success_stories']) ?>+</div>
                <div class="text-muted">Success Stories</div>
            </div>
            <div class="col-md-2 col-6 mb-3">
                <i class="fas fa-headset fa-2x text-primary mb-2"></i>
                <div class="h3 fw-bold text-primary">24/7</div>
                <div class="text-muted">Support</div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Courses -->
<section class="py-5">
    <div class="container">
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h2 class="fw-bold">Featured Courses</h2>
                <p class="lead text-muted">Start with our most popular courses</p>
            </div>
        </div>
        <div class="row">
            <?php foreach ($featuredCourses as $course): ?>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card course-card h-100">
                    <img src="<?= BASE_PATH . $course['image'] ?>" class="card-img-top" alt="<?= $course['title'] ?>" style="height: 160px; object-fit: cover;">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?= $course['title'] ?></h5>
                        <p class="card-text flex-grow-1"><?= $course['description'] ?></p>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-warning">
                                <?= str_repeat('<i class="fas fa-star"></i>', floor($course['rating'])) ?>
                                <?php if (fmod($course['rating'], 1) != 0): ?>
                                    <i class="fas fa-star-half-alt"></i>
                                <?php endif; ?>
                                <small class="text-muted ms-1"><?= $course['rating'] ?></small>
                            </span>
                            <span class="text-muted">
                                <i class="fas fa-play-circle me-1"></i><?= $course['lessons'] ?> lessons
                            </span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-auto">
                            <?php if ($course['price'] == 0): ?>
                                <span class="badge bg-success">
                                    <i class="fas fa-gift me-1"></i>FREE
                                </span>
                            <?php else: ?>
                                <span class="h5 mb-0 text-primary">
                                    <i class="fas fa-dollar-sign me-1"></i><?= $course['price'] ?>
                                </span>
                            <?php endif; ?>
                            <a href="<?= BASE_PATH ?>/course/<?= $course['id'] ?>" class="btn btn-primary btn-sm">
                                <i class="fas fa-shopping-cart me-1"></i>Enroll Now
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="row mt-4">
            <div class="col-12 text-center">
                <a href="<?= BASE_PATH ?>/courses" class="btn btn-outline-primary btn-lg">
                    <i class="fas fa-book-open me-2"></i>View All Courses
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Key Benefits -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h2 class="fw-bold">Why Choose Our Platform?</h2>
                <p class="lead text-muted">We're committed to your learning success</p>
            </div>
        </div>
        <div class="row">
            <?php foreach ($keyBenefits as $benefit): ?>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="text-center p-4 h-100">
                    <div class="icon-container mb-3">
                        <?= $benefit['icon'] ?>
                    </div>
                    <h5 class="fw-bold"><?= $benefit['title'] ?></h5>
                    <p class="text-muted"><?= $benefit['description'] ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Testimonials -->
<section class="py-5">
    <div class="container">
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h2 class="fw-bold">Student Success Stories</h2>
                <p class="lead text-muted">Hear from our amazing students</p>
            </div>
        </div>
        <div class="row">
            <?php foreach ($testimonials as $testimonial): ?>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="text-warning mb-3">
                            <?= str_repeat('<i class="fas fa-star"></i>', $testimonial['rating']) ?>
                        </div>
                        <p class="card-text fst-italic">"<?= $testimonial['text'] ?>"</p>
                    </div>
                    <div class="card-footer bg-transparent">
                        <div class="d-flex align-items-center">
                            <img src="<?= BASE_PATH . $testimonial['avatar'] ?>" alt="<?= $testimonial['name'] ?>" class="rounded-circle me-3" width="50" height="50">
                            <div>
                                <h6 class="mb-0"><?= $testimonial['name'] ?></h6>
                                <small class="text-muted">
                                    <i class="fas fa-briefcase me-1"></i><?= $testimonial['role'] ?>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Quick Signup CTA -->
<section class="py-5 bg-primary text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h2 class="fw-bold mb-3">Ready to Start Your Journey?</h2>
                <p class="lead mb-4">Join thousands of students who have transformed their careers with our courses.</p>
                <ul class="list-unstyled">
                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Free courses to get started</li>
                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Learn at your own pace</li>
                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Certificate upon completion</li>
                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Project-based learning</li>
                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Community support</li>
                </ul>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body p-4">
                        <h4 class="card-title mb-4">
                            <i class="fas fa-user-plus me-2 text-primary"></i>Start Learning Free
                        </h4>
                        <form method="POST" action="">
                            <div class="mb-3">
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-user"></i>
                                    </span>
                                    <input type="text" name="name" class="form-control" placeholder="Your Name" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                    <input type="email" name="email" class="form-control" placeholder="Your Email" required>
                                </div>
                            </div>
                            <button type="submit" name="quick_signup" class="btn btn-primary w-100 py-2">
                                <i class="fas fa-rocket me-2"></i>Get Started Free
                            </button>
                            <p class="text-muted small mt-3 text-center">
                                <i class="fas fa-lock me-1"></i>We respect your privacy
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Newsletter -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 text-center">
                <i class="fas fa-envelope-open-text fa-3x text-primary mb-3"></i>
                <h3 class="fw-bold mb-3">Stay Updated</h3>
                <p class="text-muted mb-4">Get the latest course updates and programming tips delivered to your inbox.</p>
                <form method="POST" action="" class="d-flex gap-2">
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-envelope"></i>
                        </span>
                        <input type="email" name="newsletter_email" class="form-control" placeholder="Enter your email" required>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane me-1"></i>Subscribe
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>