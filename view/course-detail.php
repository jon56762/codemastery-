<link rel="stylesheet" href="/assets/css/course-detail.css">
<div class="container-fluid py-4">

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item"><a href="/courses" class="text-decoration-none">Courses</a></li>
                    <li class="breadcrumb-item"><a href="/courses?category=<?= urlencode($course['category']) ?>" class="text-decoration-none"><?= htmlspecialchars($course['category']) ?></a></li>
                    <li class="breadcrumb-item active"><?= htmlspecialchars($course['title']) ?></li>
                </ol>
            </nav>

            <!-- Course Header -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-8">
                            <span class="badge bg-<?= getCourseLevelBadge($course['level']) ?> mb-2">
                                <?= ucfirst($course['level']) ?>
                            </span>
                            <h1 class="fw-bold mb-3"><?= htmlspecialchars($course['title']) ?></h1>
                            <p class="lead mb-4"><?= htmlspecialchars($course['description']) ?></p>

                            <div class="d-flex flex-wrap gap-4 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="text-warning me-2">
                                        <?= str_repeat('<i class="fas fa-star"></i>', floor($course['rating'] ?? 4)) ?>
                                        <?= ($course['rating'] ?? 4) - floor($course['rating'] ?? 4) >= 0.5 ? '<i class="fas fa-star-half-alt"></i>' : '' ?>
                                    </div>
                                    <span class="fw-semibold"><?= $course['rating'] ?? '4.0' ?></span>
                                    <span class="text-muted ms-1">(<?= $course['enrollment_count'] ?? 0 ?> students)</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-play-circle text-muted me-2"></i>
                                    <span><?= $course['lessons'] ?> lessons</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-clock text-muted me-2"></i>
                                    <span><?= formatDuration($course['duration']) ?></span>
                                </div>
                            </div>

                            <div class="d-flex align-items-center">
                                <img src="<?= getInstructorAvatar($course['instructor_name'], 40) ?>"
                                    alt="<?= htmlspecialchars($course['instructor_name']) ?>"
                                    class="rounded-circle me-3" width="40" height="40">
                                <div>
                                    <div class="fw-semibold">Created by <?= htmlspecialchars($course['instructor_name']) ?></div>
                                    <small class="text-muted">Last updated <?= date('F Y', strtotime($course['updated_at'])) ?></small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="course-price-card p-4 bg-light rounded">
                                <div class="mb-3">
                                    <?php if ($course['price'] > 0): ?>
                                        <div class="h1 fw-bold text-dark">$<?= $course['price'] ?></div>
                                        <div class="text-muted">One-time payment</div>
                                    <?php else: ?>
                                        <div class="h1 fw-bold text-success">Free</div>
                                        <div class="text-muted">Lifetime access</div>
                                    <?php endif; ?>
                                </div>

                                <?php if ($isEnrolled): ?>
                                    <div class="alert alert-success mb-3">
                                        <i class="fas fa-check-circle me-2"></i>
                                        You are enrolled in this course
                                    </div>
                                    
                                    <a href="/course-player?course_id=<?= $course['id'] ?>&lesson_id=1" class="btn btn-dark w-100">
                                        <i class="fas fa-play-circle me-2"></i>Continue Learning
                                    </a>
                                    <div class="progress mb-2" style="height: 8px;">
                                        <div class="progress-bar bg-success"
                                            style="width: <?= $enrollment['progress'] ?? 0 ?>%">
                                        </div>
                                    </div>
                                    <small class="text-muted"><?= $enrollment['progress'] ?? 0 ?>% complete</small>
                                <?php else: ?>
                                    <form method="POST">
                                        <button type="submit" name="enroll" class="btn btn-dark btn-lg w-100 mb-2">
                                            <?php if ($course['price'] > 0): ?>
                                                <i class="fas fa-shopping-cart me-2"></i>Enroll Now
                                            <?php else: ?>
                                                <i class="fas fa-rocket me-2"></i>Enroll for Free
                                            <?php endif; ?>
                                        </button>
                                    </form>
                                    <small class="text-muted">
                                        <i class="fas fa-shield-alt me-1"></i>
                                        30-day money-back guarantee
                                    </small>
                                <?php endif; ?>

                                <div class="mt-3">
                                    <small class="text-muted d-block">
                                        <i class="fas fa-play-circle me-1"></i>
                                        <?= $course['lessons'] ?> lessons
                                    </small>
                                    <small class="text-muted d-block">
                                        <i class="fas fa-clock me-1"></i>
                                        <?= formatDuration($course['duration']) ?> total length
                                    </small>
                                    <small class="text-muted d-block">
                                        <i class="fas fa-infinity me-1"></i>
                                        Full lifetime access
                                    </small>
                                    <small class="text-muted d-block">
                                        <i class="fas fa-mobile-alt me-1"></i>
                                        Access on mobile and TV
                                    </small>
                                    <small class="text-muted d-block">
                                        <i class="fas fa-certificate me-1"></i>
                                        Certificate of completion
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Course Curriculum -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h3 class="fw-bold mb-0">Course Content</h3>
                </div>
                <div class="card-body p-0">
                    <?php if (!empty($course['curriculum'])): ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($course['curriculum'] as $index => $lesson): ?>
                                <div class="list-group-item border-0 py-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3 text-muted">
                                                <i class="fas fa-play-circle"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-1 fw-semibold"><?= htmlspecialchars($lesson['title']) ?></h6>
                                                <small class="text-muted"><?= formatDuration($lesson['duration'] ?? 0) ?></small>
                                            </div>
                                        </div>
                                        <?php if ($hasPreview && $index < 2): ?>
                                            <span class="badge bg-success">Preview</span>
                                        <?php elseif ($isEnrolled): ?>
                                            <span class="badge bg-light text-dark">Available</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Locked</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
                            <h5 class="fw-bold">Curriculum Coming Soon</h5>
                            <p class="text-muted">The instructor is currently preparing the course content.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Requirements -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h3 class="fw-bold mb-0">Requirements</h3>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Basic computer skills</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Internet connection</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>No prior programming experience needed</li>
                        <li class="mb-0"><i class="fas fa-check text-success me-2"></i>Willingness to learn and practice</li>
                    </ul>
                </div>
            </div>

            <!-- Description -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h3 class="fw-bold mb-0">Description</h3>
                </div>
                <div class="card-body">
                    <p><?= nl2br(htmlspecialchars($course['description'])) ?></p>

                    <h5 class="fw-bold mt-4">What You'll Learn</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="list-unstyled">
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Build real-world projects</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Master fundamental concepts</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Develop problem-solving skills</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-unstyled">
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Prepare for technical interviews</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Build a professional portfolio</li>
                                <li class="mb-0"><i class="fas fa-check text-success me-2"></i>Join a community of learners</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Instructor Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body text-center p-4">
                    <img src="<?= getInstructorAvatar($course['instructor_name'], 80) ?>"
                        alt="<?= htmlspecialchars($course['instructor_name']) ?>"
                        class="rounded-circle mb-3" width="80" height="80">
                    <h5 class="fw-bold"><?= htmlspecialchars($course['instructor_name']) ?></h5>
                    <p class="text-muted mb-3">Instructor</p>
                    <div class="d-flex justify-content-center gap-3 mb-3">
                        <div class="text-center">
                            <div class="h6 fw-bold mb-0"><?= count(getCoursesByInstructor($course['instructor_id'])) ?></div>
                            <small class="text-muted">Courses</small>
                        </div>
                        <div class="text-center">
                            <div class="h6 fw-bold mb-0"><?= $course['enrollment_count'] ?? 0 ?></div>
                            <small class="text-muted">Students</small>
                        </div>
                        <div class="text-center">
                            <div class="h6 fw-bold mb-0"><?= $course['rating'] ?? '4.5' ?></div>
                            <small class="text-muted">Rating</small>
                        </div>
                    </div>
                    <p class="text-muted small">
                        Experienced instructor passionate about sharing knowledge and helping students succeed.
                    </p>
                </div>
            </div>

            <!-- Related Courses -->
            <?php if (!empty($related_courses)): ?>
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="fw-bold mb-0">Related Courses</h5>
                    </div>
                    <div class="card-body">
                        <?php foreach ($related_courses as $related): ?>
                            <div class="mb-3 pb-3 border-bottom">
                                <div class="d-flex align-items-start">
                                    <img src="<?= getCourseImage($related) ?>"
                                        alt="<?= htmlspecialchars($related['title']) ?>"
                                        class="rounded me-3" width="60" height="60" style="object-fit: cover;">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 fw-semibold small">
                                            <a href="/course/<?= $related['id'] ?>" class="text-dark text-decoration-none">
                                                <?= htmlspecialchars($related['title']) ?>
                                            </a>
                                        </h6>
                                        <div class="text-muted small"><?= htmlspecialchars($related['instructor_name']) ?></div>
                                        <div class="text-dark fw-bold small"><?= $related['price'] > 0 ? '$' . $related['price'] : 'Free' ?></div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <a href="/courses?category=<?= urlencode($course['category']) ?>" class="btn btn-outline-dark w-100">
                            View All <?= htmlspecialchars($course['category']) ?> Courses
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>