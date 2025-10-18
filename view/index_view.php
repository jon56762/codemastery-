<?php
$platformStats = getPlatformStats();
$testimonials = getFromFile('testimonials.json');
$current_page = 'home';

// Get courses for the homepage
$featured_courses = getFeaturedCourses(6);
$new_courses = array_slice(getAllCourses(), 0, 4); // Get first 4 courses as new
$popular_courses = array_filter(getAllCourses(), function($course) {
    return ($course['enrollment_count'] ?? 0) > 100;
});
$popular_courses = array_slice($popular_courses, 0, 4);
?>

<link rel="stylesheet" href="/assets/css/style.css">

<!-- Hero Section -->
<section class="hero-section bg-light py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">Learn the Skills That Drive Your Career Forward</h1>
                <p class="lead mb-4">Join <?= number_format($platformStats['total_students']) ?>+ students and <?= number_format($platformStats['total_instructors']) ?>+ instructors in our vibrant learning community. Master in-demand skills with project-based courses.</p>
                <div class="d-flex flex-wrap gap-3 mb-4">
                    <center>
                        <a href="<?= BASE_PATH ?>/courses" class="btn btn-primary px-4 py-3 mb-2 fw-bold">
                            <i class="fas fa-search me-2"></i>Explore Courses
                        </a>
                        <a href="/become-instructor" class="btn btn-outline-dark px-4 mb-2 py-3">
                            <i class="fas fa-chalkboard-teacher me-2"></i>Teach on CodeMastery
                        </a>
                    </center>
                </div>
                <div class="d-flex flex-wrap gap-4 text-muted">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-star text-warning me-2"></i>
                        <span>Rated <?= $platformStats['average_rating'] ?>/5 by students</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-play-circle me-2"></i>
                        <span><?= number_format($platformStats['total_courses']) ?>+ Courses</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <img src="/assets/images/womanbackground.jpg" alt="Learning Community" class="img-fluid rounded-3 shadow">
            </div>
        </div>
    </div>
</section>

<!-- Trust Indicators -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-3 col-6 mb-3">
                <div class="h2 fw-bold text-dark"><?= number_format($platformStats['total_students']) ?>+</div>
                <div class="text-muted">Students</div>
            </div>
            <div class="col-md-3 col-6 mb-3">
                <div class="h2 fw-bold text-dark"><?= number_format($platformStats['total_courses']) ?>+</div>
                <div class="text-muted">Courses</div>
            </div>
            <div class="col-md-3 col-6 mb-3">
                <div class="h2 fw-bold text-dark"><?= number_format($platformStats['total_instructors']) ?>+</div>
                <div class="text-muted">Instructors</div>
            </div>
            <div class="col-md-3 col-6 mb-3">
                <div class="h2 fw-bold text-dark"><?= number_format($platformStats['total_enrollments']) ?>+</div>
                <div class="text-muted">Enrollments</div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Courses -->
<?php if (!empty($featured_courses)): ?>
<section class="py-5 bg-light">
    <div class="container">
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h2 class="fw-bold">Featured Courses</h2>
                <p class="lead text-muted">Most popular courses chosen by our students</p>
            </div>
        </div>
        <div class="row">
            <?php foreach ($featured_courses as $course): ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card course-card h-100 border-0 shadow-sm">
                        <div class="position-relative">
                            <img src="<?= htmlspecialchars($course['thumbnail']) ?>" 
                                 class="card-img-top" 
                                 alt="<?= htmlspecialchars($course['title']) ?>"
                                 style="height: 200px; object-fit: cover;">
                            <?php if ($course['featured']): ?>
                                <span class="position-absolute top-0 start-0 m-2 badge bg-dark">
                                    <i class="fas fa-star me-1"></i>Featured
                                </span>
                            <?php endif; ?>
                            <div class="position-absolute top-0 end-0 m-2">
                                <span class="badge bg-<?= getCourseLevelBadge($course['level']) ?>">
                                    <?= ucfirst($course['level']) ?>
                                </span>
                            </div>
                        </div>
                        
                        <div class="card-body d-flex flex-column">
                            <div class="mb-2">
                                <span class="badge bg-light text-dark border">
                                    <?= htmlspecialchars($course['category']) ?>
                                </span>
                            </div>
                            
                            <h5 class="card-title fw-bold">
                                <a href="/course/<?= $course['id'] ?>" class="text-dark text-decoration-none">
                                    <?= htmlspecialchars($course['title']) ?>
                                </a>
                            </h5>
                            
                            <p class="card-text text-muted small flex-grow-1">
                                <?= htmlspecialchars($course['short_description'] ?? substr($course['description'], 0, 100) . '...') ?>
                            </p>
                            
                            <div class="d-flex align-items-center mb-2">
                                <div class="text-warning small">
                                    <?= str_repeat('<i class="fas fa-star"></i>', floor($course['rating'] ?? 4)) ?>
                                    <?= ($course['rating'] ?? 4) - floor($course['rating'] ?? 4) >= 0.5 ? '<i class="fas fa-star-half-alt"></i>' : '' ?>
                                    <span class="text-muted ms-1">(<?= $course['rating'] ?? '4.0' ?>)</span>
                                </div>
                                <span class="text-muted small ms-2">• <?= $course['enrollment_count'] ?? 0 ?> students</span>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center mt-auto">
                                <div class="d-flex align-items-center">
                                    <img src="https://via.placeholder.com/30x30/007bff/ffffff?text=<?= substr($course['instructor_name'], 0, 1) ?>" 
                                         alt="<?= htmlspecialchars($course['instructor_name']) ?>" 
                                         class="rounded-circle me-2" width="30" height="30">
                                    <small class="text-muted"><?= htmlspecialchars($course['instructor_name']) ?></small>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold text-dark h5 mb-0">
                                        <?= $course['price'] > 0 ? '$' . $course['price'] : 'Free' ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card-footer bg-transparent border-0 pt-0">
                            <a href="/course/<?= $course['id'] ?>" class="btn btn-outline-dark w-100">
                                <i class="fas fa-eye me-2"></i>View Course
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-4">
            <a href="/courses" class="btn btn-dark btn-lg">
                View All Courses <i class="fas fa-arrow-right ms-2"></i>
            </a>
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
                    <div class="card border-0 shadow-sm h-100">
                        <img src="<?= htmlspecialchars($course['thumbnail']) ?>" 
                             class="card-img-top" 
                             alt="<?= htmlspecialchars($course['title']) ?>"
                             style="height: 160px; object-fit: cover;">
                        <div class="card-body">
                            <span class="badge bg-success mb-2">New</span>
                            <h6 class="card-title fw-bold">
                                <a href="/course/<?= $course['id'] ?>" class="text-dark text-decoration-none">
                                    <?= htmlspecialchars($course['title']) ?>
                                </a>
                            </h6>
                            <p class="card-text text-muted small">
                                <?= htmlspecialchars(substr($course['short_description'] ?? $course['description'], 0, 80) . '...') ?>
                            </p>
                        </div>
                        <div class="card-footer bg-transparent">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-bold text-dark">
                                    <?= $course['price'] > 0 ? '$' . $course['price'] : 'Free' ?>
                                </span>
                                <a href="/course/<?= $course['id'] ?>" class="btn btn-sm btn-outline-dark">
                                    Explore
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
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

<!-- Popular Categories -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h2 class="fw-bold">Popular Categories</h2>
                <p class="lead text-muted">Explore courses by category</p>
            </div>
        </div>
        <div class="row">
            <?php 
            $categories = getCourseCategories();
            $category_icons = [
                'Web Development' => 'fas fa-code',
                'Data Science' => 'fas fa-chart-bar',
                'Mobile Development' => 'fas fa-mobile-alt',
                'Machine Learning' => 'fas fa-robot',
                'Programming' => 'fas fa-laptop-code'
            ];
            
            foreach (array_slice($categories, 0, 6) as $category): 
                $category_courses = array_filter(getAllCourses(), function($course) use ($category) {
                    return $course['category'] === $category && $course['status'] === 'published';
                });
            ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center p-4">
                            <div class="mb-3">
                                <i class="<?= $category_icons[$category] ?? 'fas fa-book' ?> fa-2x text-dark"></i>
                            </div>
                            <h5 class="fw-bold"><?= htmlspecialchars($category) ?></h5>
                            <p class="text-muted mb-3"><?= count($category_courses) ?> courses available</p>
                            <a href="/courses?category=<?= urlencode($category) ?>" class="btn btn-outline-dark">
                                Explore <?= htmlspecialchars($category) ?>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
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

<!-- Testimonials -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h2 class="fw-bold">Success Stories</h2>
                <p class="lead text-muted">Hear from our students and instructors</p>
            </div>
        </div>
        <div class="row">
            <?php foreach ($testimonials as $testimonial): ?>
                <div class="col-lg-4 mb-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body">
                            <div class="text-warning mb-3">
                                <?= str_repeat('<i class="fas fa-star"></i>', $testimonial['rating']) ?>
                            </div>
                            <p class="card-text fst-italic">"<?= htmlspecialchars($testimonial['text']) ?>"</p>
                        </div>
                        <div class="card-footer bg-transparent">
                            <div class="d-flex align-items-center">
                                <img src="https://via.placeholder.com/50x50/007bff/ffffff?text=<?= substr($testimonial['name'], 0, 1) ?>"
                                    alt="<?= htmlspecialchars($testimonial['name']) ?>"
                                    class="rounded-circle me-3" width="50" height="50">
                                <div>
                                    <h6 class="mb-0 fw-bold"><?= htmlspecialchars($testimonial['name']) ?></h6>
                                    <small class="text-muted"><?= htmlspecialchars($testimonial['role']) ?></small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
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

