<?php
$platformStats = getPlatformStats() ?? [];
$current_page = 'home';

if (!function_exists('getInstructorAvatar')) {
    function getInstructorAvatar($instructorId, $default = '/assets/images/avatars/default.png') {
        $instructor = User::findById($instructorId);
        return $instructor ? $instructor->getAvatar() : $default;
    }
}

function getCourseThumbnail($course, $default = '/assets/images/default.jpg') {
    return !empty($course['thumbnail']) ? $course['thumbnail'] : $default;
}
?>

<link rel="stylesheet" href="/assets/css/style.css">

<section class="hero-night">
    <!-- Star icons (learning symbols) -->
    <div class="stars-container">
        <i class="fas fa-book star-icon" style="top: 15%; left: 10%; font-size: 1.2rem; opacity: 0.8; --delay: 0s;"></i>
        <i class="fas fa-laptop-code star-icon" style="top: 25%; left: 80%; font-size: 1.5rem; opacity: 0.6; --delay: 1.5s;"></i>
        <i class="fas fa-lightbulb star-icon" style="top: 60%; left: 20%; font-size: 1.4rem; opacity: 0.7; --delay: 0.8s;"></i>
        <i class="fas fa-graduation-cap star-icon" style="top: 40%; left: 75%; font-size: 1.8rem; opacity: 0.5; --delay: 2.2s;"></i>
        <i class="fas fa-code star-icon" style="top: 70%; left: 65%; font-size: 1.3rem; opacity: 0.6; --delay: 1.0s;"></i>
        <i class="fas fa-brain star-icon" style="top: 12%; left: 45%; font-size: 1.6rem; opacity: 0.7; --delay: 0.4s;"></i>
        <i class="fas fa-rocket star-icon" style="top: 80%; left: 35%; font-size: 1.4rem; opacity: 0.8; --delay: 2.5s;"></i>
        <i class="fas fa-puzzle-piece star-icon" style="top: 55%; left: 55%; font-size: 1.1rem; opacity: 0.5; --delay: 1.8s;"></i>
        <i class="fas fa-award star-icon" style="top: 30%; left: 90%; font-size: 1.7rem; opacity: 0.6; --delay: 0.6s;"></i>
        <i class="fas fa-chalkboard-teacher star-icon" style="top: 85%; left: 15%; font-size: 1.3rem; opacity: 0.7; --delay: 1.2s;"></i>
    </div>

    <div class="container position-relative" style="z-index: 2;">
        <div class="row align-items-center">
            <!-- Left text content -->
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="text-center text-md-start text-lg-start">
                    <h1 class="display-4 fw-bold text-white mb-3">
                        Learn the Skills<br><span class="text-light">That Drive Your Career</span>
                    </h1>
                    <p class="lead text-white-50 mb-4">
                        Join <?= number_format($platformStats['total_students'] ?? 0) ?>+ students and
                        <?= number_format($platformStats['total_instructors'] ?? 0) ?>+ instructors.
                        Master in‑demand skills with real‑world projects.
                    </p>
                </div>
                <center>
                    <div class="d-flex justify-content-center justify-content-md-start justify-content-lg-start flex-wrap gap-3 ">
                        <a href="/courses" class="btn btn-light btn-lg fw-bold px-4 py-3">
                            <i class="fas fa-search me-2"></i>Explore Courses
                        </a>
                        <a href="/become-instructor" class="btn btn-outline-light btn-lg px-4 py-3">
                            <i class="fas fa-chalkboard-teacher me-2"></i>Become an Instructor
                        </a>
                    </div>
                </center>
                <div class="d-flex flex-wrap gap-4 mt-4 text-white-50">
                    <div>
                        <i class="fas fa-star text-warning me-1"></i>
                        Rated <?= $platformStats['average_rating'] ?? '4.8' ?>/5
                    </div>
                    <div>
                        <i class="fas fa-play-circle me-1"></i>
                        <?= number_format($platformStats['total_courses'] ?? 0) ?>+ Courses
                    </div>
                </div>
            </div>

            <!-- Right visual (your screenshot or illustration) -->
            <div class="col-lg-6 text-center">
                <img src="/assets/images/Course.gif"
                    alt="CodeMastery Platform"
                    class="img-fluid hero-image"
                    style="max-height: 500px; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
            </div>
        </div>
    </div>
</section>

<!-- Trust Indicators -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-3 col-6 mb-3">
                <div class="h2 fw-bold text-dark"><?= number_format($platformStats['total_students'] ?? 0) ?>+</div>
                <div class="text-muted">Students</div>
            </div>
            <div class="col-md-3 col-6 mb-3">
                <div class="h2 fw-bold text-dark"><?= number_format($platformStats['total_courses'] ?? 0) ?>+</div>
                <div class="text-muted">Courses</div>
            </div>
            <div class="col-md-3 col-6 mb-3">
                <div class="h2 fw-bold text-dark"><?= number_format($platformStats['total_instructors'] ?? 0) ?>+</div>
                <div class="text-muted">Instructors</div>
            </div>
            <div class="col-md-3 col-6 mb-3">
                <div class="h2 fw-bold text-dark"><?= number_format($platformStats['total_enrollments'] ?? 0) ?>+</div>
                <div class="text-muted">Enrollments</div>
            </div>
        </div>
    </div>
</section>

<!-- Popular Courses -->
<?php if (!empty($popular_courses)): ?>
<section class="py-5 bg-light">
    <div class="container">
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h2 class="fw-bold">Most Popular Courses</h2>
                <p class="lead text-muted">Top 3 courses loved by our students</p>
            </div>
        </div>
        <div class="row justify-content-center">
            <?php foreach ($popular_courses as $course): ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card course-card h-100 border-0 shadow-sm">
                        <div class="position-relative">
                            <!-- Course Thumbnail with fallback -->
                            <img src="<?= htmlspecialchars(getCourseThumbnail($course)) ?>"
                                class="card-img-top"
                                alt="<?= htmlspecialchars($course['title'] ?? 'Course') ?>"
                                style="height: 200px; object-fit: cover;">
                            <span class="position-absolute top-0 start-0 m-2 badge bg-danger"><i class="fas fa-fire me-1"></i>Popular</span>
                            <div class="position-absolute top-0 end-0 m-2">
                                <span class="badge bg-<?= getCourseLevelBadge($course['level'] ?? 'beginner') ?>"><?= ucfirst($course['level'] ?? 'beginner') ?></span>
                            </div>
                        </div>
                        <div class="card-body d-flex flex-column">
                            <div class="mb-2"><span class="badge bg-light text-dark border"><?= htmlspecialchars($course['category'] ?? 'Uncategorized') ?></span></div>
                            <h5 class="card-title fw-bold"><a href="/course/<?= $course['id'] ?? 0 ?>" class="text-dark text-decoration-none"><?= htmlspecialchars($course['title'] ?? 'Untitled') ?></a></h5>
                            <p class="card-text text-muted small flex-grow-1"><?= htmlspecialchars($course['short_description'] ?? substr($course['description'] ?? '', 0, 100) . '...') ?></p>
                            <div class="d-flex align-items-center mb-2">
                                <div class="text-warning small">
                                    <?= str_repeat('<i class="fas fa-star"></i>', floor($course['rating'] ?? 4)) ?>
                                    <?= (($course['rating'] ?? 4) - floor($course['rating'] ?? 4) >= 0.5) ? '<i class="fas fa-star-half-alt"></i>' : '' ?>
                                    <span class="text-muted ms-1">(<?= $course['rating'] ?? '4.0' ?>)</span>
                                </div>
                                <span class="text-muted small ms-2">• <?= $course['enrollment_count'] ?? 0 ?> students</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-auto">
                                <div class="d-flex align-items-center">
                                    <!-- INSTRUCTOR AVATAR: now fetches real image -->
                                    <?php $instructorAvatar = getInstructorAvatar($course['instructor_id'] ?? 0); ?>
                                    <img src="<?= htmlspecialchars($instructorAvatar) ?>"
                                        alt="<?= htmlspecialchars($course['instructor_name'] ?? 'Instructor') ?>"
                                        class="rounded-circle me-2" width="30" height="30" style="object-fit: cover;">
                                    <small class="text-muted"><?= htmlspecialchars($course['instructor_name'] ?? 'Instructor') ?></small>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold text-dark h5 mb-0"><?= ($course['price'] ?? 0) > 0 ? '$' . ($course['price'] ?? 0) : 'Free' ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-0 pt-0">
                            <a href="/course/<?= $course['id'] ?? 0 ?>" class="btn btn-outline-dark w-100"><i class="fas fa-eye me-2"></i>View Course</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- New Courses -->
<?php if (!empty($new_courses)): ?>
<section class="py-5 bg-white">
    <div class="container">
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h2 class="fw-bold">New & Noteworthy</h2>
                <p class="lead text-muted">Recently added courses to expand your skills</p>
            </div>
        </div>
        <div class="row">
            <?php foreach ($new_courses as $course): ?>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card border-0 shadow-sm h-100 course-card">
                        <div class="position-relative">
                            <img src="<?= htmlspecialchars(getCourseThumbnail($course)) ?>"
                                class="card-img-top"
                                alt="<?= htmlspecialchars($course['title'] ?? 'Course') ?>"
                                style="height: 160px; object-fit: cover;">
                            <span class="position-absolute top-0 start-0 m-2 badge bg-success">New</span>
                        </div>
                        <div class="card-body">
                            <h6 class="card-title fw-bold"><a href="/course/<?= $course['id'] ?? 0 ?>" class="text-dark text-decoration-none"><?= htmlspecialchars($course['title'] ?? 'Untitled') ?></a></h6>
                            <p class="card-text text-muted small"><?= htmlspecialchars(substr($course['short_description'] ?? $course['description'] ?? '', 0, 80) . '...') ?></p>
                            <div class="d-flex justify-content-between align-items-center mt-auto">
                                <div class="d-flex align-items-center">
                                    <?php $instructorAvatar = getInstructorAvatar($course['instructor_id'] ?? 0); ?>
                                    <img src="<?= htmlspecialchars($instructorAvatar) ?>"
                                        alt="<?= htmlspecialchars($course['instructor_name'] ?? 'Instructor') ?>"
                                        class="rounded-circle me-2" width="25" height="25" style="object-fit: cover;">
                                    <small class="text-muted"><?= htmlspecialchars($course['instructor_name'] ?? 'Instructor') ?></small>
                                </div>
                                <span class="fw-bold text-dark"><?= ($course['price'] ?? 0) > 0 ? '$' . ($course['price'] ?? 0) : 'Free' ?></span>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent">
                            <a href="/course/<?= $course['id'] ?? 0 ?>" class="btn btn-sm btn-outline-dark w-100">Explore</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-4">
            <a href="/courses" class="btn btn-dark btn-lg">View All Courses <i class="fas fa-arrow-right ms-2"></i></a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Why Choose CodeMastery -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h2 class="fw-bold">Why Learn With CodeMastery?</h2>
                <p class="lead text-muted">We're building the future of education</p>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="text-center p-4">
                    <div class="mb-3">
                        <i class="fas fa-laptop-code fa-3x text-dark"></i>
                    </div>
                    <h5 class="fw-bold">Project-Based Learning</h5>
                    <p class="text-muted">Learn by building real-world projects that showcase your skills to employers.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="text-center p-4">
                    <div class="mb-3">
                        <i class="fas fa-users fa-3x text-dark"></i>
                    </div>
                    <h5 class="fw-bold">Expert Instructors</h5>
                    <p class="text-muted">Learn from industry professionals with years of real-world experience.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="text-center p-4">
                    <div class="mb-3">
                        <i class="fas fa-graduation-cap fa-3x text-dark"></i>
                    </div>
                    <h5 class="fw-bold">Career Support</h5>
                    <p class="text-muted">Get help with job preparation, portfolio building, and career advancement.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Become Instructor CTA -->
<section class="py-5 bg-dark text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h2 class="fw-bold mb-3">Ready to Share Your Knowledge?</h2>
                <p class="lead mb-4">Join our instructor community and earn money while teaching what you love.</p>
                <ul class="list-unstyled">
                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Reach thousands of students</li>
                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Set your own course prices</li>
                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Earn 70% commission on sales</li>
                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Flexible teaching schedule</li>
                </ul>
            </div>
            <div class="col-lg-4 text-center">
                <a href="/become-instructor" class="btn btn-light btn-lg px-4">
                    <i class="fas fa-chalkboard-teacher me-2"></i>Start Teaching
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials (now uses the $testimonials array from the controller) -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h2 class="fw-bold">Success Stories</h2>
                <p class="lead text-muted">Hear from our students and instructors</p>
                <a href="/testimonials" class="btn btn-outline-dark">View All Testimonials</a>
            </div>
        </div>
        <div class="row">
            <?php
            $display_testimonials = array_slice($testimonials, 0, 3);
            if (empty($display_testimonials)): ?>
                <div class="col-12 text-center">
                    <p class="text-muted">No testimonials yet. <a href="/testimonial-submit">Be the first to share!</a></p>
                </div>
            <?php else: ?>
                <?php foreach ($display_testimonials as $testimonial): ?>
                    <!-- testimonial card -->
                    <div class="col-lg-4 mb-4">
                        <div class="card h-100 border-0 shadow-sm testimonial-card">
                            <div class="card-body">
                                <div class="text-warning mb-3">
                                    <?= str_repeat('<i class="fas fa-star"></i>', $testimonial['rating'] ?? 5) ?>
                                    <?= str_repeat('<i class="far fa-star"></i>', 5 - ($testimonial['rating'] ?? 5)) ?>
                                </div>
                                <p class="card-text fst-italic">"<?= htmlspecialchars($testimonial['text'] ?? '') ?>"</p>
                            </div>
                            <div class="card-footer bg-transparent">
                                <div class="d-flex align-items-center">
                                    <img src="<?= htmlspecialchars($testimonial['avatar'] ?? '/assets/images/avatars/default.png') ?>"
                                        alt="<?= htmlspecialchars($testimonial['name'] ?? 'Anonymous') ?>"
                                        class="rounded-circle me-3" width="50" height="50" style="object-fit: cover;">
                                    <div>
                                        <h6 class="mb-0 fw-bold"><?= htmlspecialchars($testimonial['name'] ?? 'Anonymous') ?></h6>
                                        <small class="text-muted"><?= htmlspecialchars($testimonial['role'] ?? 'Student') ?></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Newsletter -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 text-center">
                <i class="fas fa-envelope-open-text fa-3x text-dark mb-3"></i>
                <h3 class="fw-bold mb-3">Stay Updated</h3>
                <p class="text-muted mb-4">Get the latest course updates and programming tips delivered to your inbox.</p>
                <form method="POST" action="" class="d-flex gap-2">
                    <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
                    <button type="submit" name="newsletter_signup" class="btn btn-dark">
                        <i class="fas fa-paper-plane me-1"></i>Subscribe
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>