<?php
// Calculate statistics
$completionRate = $totalCourses > 0 ? round(($completedCount / $totalCourses) * 100) : 0;
$averageProgress = $totalCourses > 0 ? round(array_sum(array_column($enrollments, 'progress')) / $totalCourses) : 0;
?>

<div class="container-fluid py-4">
    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 bg-dark text-white">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h1 class="fw-bold mb-2">Welcome back, <?= htmlspecialchars($user['name']) ?>! 👋</h1>
                            <p class="mb-0 text-white-50">Continue your learning journey and track your progress.</p>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="display-6 fw-bold text-warning">Day <?= rand(15, 365) ?></div>
                            <small class="text-white-50">Learning streak</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Left Column - Main Content -->
        <div class="col-lg-8">
            <!-- Learning Statistics -->
            <div class="row mb-4">
                <div class="col-md-3 col-6 mb-3">
                    <div class="card border-0 shadow-sm text-center">
                        <div class="card-body py-3">
                            <div class="h4 fw-bold text-dark mb-1"><?= $totalCourses ?></div>
                            <small class="text-muted">Enrolled Courses</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-3">
                    <div class="card border-0 shadow-sm text-center">
                        <div class="card-body py-3">
                            <div class="h4 fw-bold text-success mb-1"><?= $completedCount ?></div>
                            <small class="text-muted">Completed</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-3">
                    <div class="card border-0 shadow-sm text-center">
                        <div class="card-body py-3">
                            <div class="h4 fw-bold text-warning mb-1"><?= $inProgressCount ?></div>
                            <small class="text-muted">In Progress</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-3">
                    <div class="card border-0 shadow-sm text-center">
                        <div class="card-body py-3">
                            <div class="h4 fw-bold text-info mb-1"><?= $averageProgress ?>%</div>
                            <small class="text-muted">Avg Progress</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Continue Learning Section -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="fw-bold mb-0">Continue Learning</h3>
                        <a href="/my-courses" class="btn btn-outline-dark btn-sm">View All</a>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (empty($continueLearning)): ?>
                        <div class="text-center py-4">
                            <i class="fas fa-play-circle fa-3x text-muted mb-3"></i>
                            <h5 class="fw-bold">No courses in progress</h5>
                            <p class="text-muted mb-3">Start learning to see your progress here.</p>
                            <a href="/courses" class="btn btn-dark">Browse Courses</a>
                        </div>
                    <?php else: ?>
                        <div class="row">
                            <?php foreach (array_slice($continueLearning, 0, 2) as $item):
                                $course = $item['course'];
                                $enrollment = $item['enrollment'];
                            ?>
                                <div class="col-md-6 mb-3">
                                    <div class="card border-0 h-100">
                                        <div class="position-relative">
                                            <img src="<?= getCourseImage($course) ?>"
                                                class="card-img-top"
                                                alt="<?= htmlspecialchars($course['title']) ?>"
                                                style="height: 120px; object-fit: cover;">
                                            <div class="position-absolute top-0 end-0 m-2">
                                                <span class="badge bg-<?= getCourseLevelBadge($course['level']) ?>">
                                                    <?= ucfirst($course['level']) ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <h6 class="card-title fw-bold">
                                                <a href="/course/<?= $course['id'] ?>" class="text-dark text-decoration-none">
                                                    <?= htmlspecialchars($course['title']) ?>
                                                </a>
                                            </h6>
                                            <div class="progress mb-2" style="height: 6px;">
                                                <div class="progress-bar bg-success" style="width: <?= $enrollment['progress'] ?>%"></div>
                                            </div>
                                            <small class="text-muted"><?= $enrollment['progress'] ?>% complete</small>
                                        </div>
                                        <div class="card-footer bg-transparent border-0 pt-0">
                                            <a href="/course/<?= $course['id'] ?>/learn" class="btn btn-dark btn-sm w-100">
                                                <i class="fas fa-play-circle me-1"></i>Continue
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Recently Accessed Courses -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h3 class="fw-bold mb-0">Recently Accessed</h3>
                </div>
                <div class="card-body">
                    <?php if (empty($enrolledCourses)): ?>
                        <div class="text-center py-3">
                            <p class="text-muted mb-0">You haven't enrolled in any courses yet.</p>
                        </div>
                    <?php else: ?>
                        <div class="row">
                            <?php foreach (array_slice($enrolledCourses, 0, 4) as $item):
                                $course = $item['course'];
                                $enrollment = $item['enrollment'];
                            ?>
                                <div class="col-lg-3 col-md-6 mb-3">
                                    <div class="card border-0 h-100">
                                        <img src="<?= getCourseImage($course) ?>"
                                            class="card-img-top"
                                            alt="<?= htmlspecialchars($course['title']) ?>"
                                            style="height: 100px; object-fit: cover;">
                                        <div class="card-body p-3">
                                            <h6 class="card-title fw-bold small">
                                                <a href="/course/<?= $course['id'] ?>" class="text-dark text-decoration-none">
                                                    <?= htmlspecialchars($course['title']) ?>
                                                </a>
                                            </h6>
                                            <div class="progress mb-1" style="height: 4px;">
                                                <div class="progress-bar bg-<?= $enrollment['progress'] >= 100 ? 'success' : 'warning' ?>"
                                                    style="width: <?= $enrollment['progress'] ?>%"></div>
                                            </div>
                                            <small class="text-muted"><?= $enrollment['progress'] ?>%</small>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Recommended Courses -->
            <?php if (!empty($recommendedCourses)): ?>
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0 py-3">
                        <h3 class="fw-bold mb-0">Recommended For You</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <?php foreach (array_slice($recommendedCourses, 0, 3) as $course): ?>
                                <div class="col-md-4 mb-3">
                                    <div class="card border-0 h-100">
                                        <img src="<?= getCourseImage($course) ?>"
                                            class="card-img-top"
                                            alt="<?= htmlspecialchars($course['title']) ?>"
                                            style="height: 100px; object-fit: cover;">
                                        <div class="card-body p-3">
                                            <h6 class="card-title fw-bold small">
                                                <a href="/course/<?= $course['id'] ?>" class="text-dark text-decoration-none">
                                                    <?= htmlspecialchars($course['title']) ?>
                                                </a>
                                            </h6>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="fw-bold text-dark"><?= $course['price'] > 0 ? '$' . $course['price'] : 'Free' ?></span>
                                                <span class="badge bg-light text-dark small"><?= $course['level'] ?></span>
                                            </div>
                                        </div>
                                        <div class="card-footer bg-transparent border-0 pt-0">
                                            <a href="/course-player?course_id=<?= $course['id'] ?>&lesson_id=1" class="btn btn-dark w-100">
                                                <i class="fas fa-play-circle me-2"></i>Continue Learning
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Right Column - Sidebar -->
        <div class="col-lg-4">
            <!-- Progress Overview -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="fw-bold mb-0">Learning Progress</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="position-relative d-inline-block">
                            <div class="progress-circle" data-progress="<?= $completionRate ?>">
                                <span class="progress-value fw-bold"><?= $completionRate ?>%</span>
                            </div>
                        </div>
                        <div class="mt-2">
                            <small class="text-muted">Overall Completion Rate</small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <small class="text-muted">Courses Completed</small>
                            <small class="fw-semibold"><?= $completedCount ?>/<?= $totalCourses ?></small>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-success" style="width: <?= $totalCourses > 0 ? ($completedCount / $totalCourses) * 100 : 0 ?>%"></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <small class="text-muted">In Progress</small>
                            <small class="fw-semibold"><?= $inProgressCount ?></small>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-warning" style="width: <?= $totalCourses > 0 ? ($inProgressCount / $totalCourses) * 100 : 0 ?>%"></div>
                        </div>
                    </div>

                    <div class="mb-0">
                        <div class="d-flex justify-content-between mb-1">
                            <small class="text-muted">Not Started</small>
                            <small class="fw-semibold"><?= $totalCourses - $completedCount - $inProgressCount ?></small>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-light" style="width: <?= $totalCourses > 0 ? (($totalCourses - $completedCount - $inProgressCount) / $totalCourses) * 100 : 0 ?>%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Upcoming Deadlines -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="fw-bold mb-0">Upcoming Deadlines</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($upcomingDeadlines)): ?>
                        <div class="text-center py-3">
                            <i class="fas fa-calendar-check fa-2x text-muted mb-2"></i>
                            <p class="text-muted small mb-0">No upcoming deadlines</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($upcomingDeadlines as $deadline): ?>
                            <div class="d-flex align-items-center mb-3 pb-2 border-bottom">
                                <div class="flex-shrink-0">
                                    <div class="bg-light rounded p-2 text-center">
                                        <div class="fw-bold text-dark"><?= date('d', strtotime($deadline['date'])) ?></div>
                                        <small class="text-muted"><?= date('M', strtotime($deadline['date'])) ?></small>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="fw-semibold mb-0 small"><?= htmlspecialchars($deadline['title']) ?></h6>
                                    <small class="text-muted"><?= htmlspecialchars($deadline['course']) ?></small>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Achievement Badges -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0">Achievements</h5>
                        <span class="badge bg-dark"><?= count($achievements) ?></span>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (empty($achievements)): ?>
                        <div class="text-center py-3">
                            <i class="fas fa-trophy fa-2x text-muted mb-2"></i>
                            <p class="text-muted small mb-0">Complete courses to earn achievements</p>
                        </div>
                    <?php else: ?>
                        <div class="row">
                            <?php foreach (array_slice($achievements, 0, 4) as $achievement): ?>
                                <div class="col-6 mb-3 text-center">
                                    <div class="achievement-badge bg-warning rounded-circle d-inline-flex align-items-center justify-content-center mb-2"
                                        style="width: 50px; height: 50px;">
                                        <i class="fas fa-<?= $achievement['icon'] ?> text-white"></i>
                                    </div>
                                    <h6 class="fw-semibold small mb-0"><?= htmlspecialchars($achievement['title']) ?></h6>
                                    <small class="text-muted"><?= date('M Y', strtotime($achievement['earned_at'])) ?></small>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <?php if (count($achievements) > 4): ?>
                            <div class="text-center mt-2">
                                <a href="/achievements" class="btn btn-outline-dark btn-sm">View All</a>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .progress-circle {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: conic-gradient(#28a745 <?= $completionRate * 3.6 ?>deg, #e9ecef 0deg);
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    .progress-circle::before {
        content: '';
        position: absolute;
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: white;
    }

    .progress-value {
        position: relative;
        z-index: 1;
        font-size: 1.5rem;
    }

    .achievement-badge {
        background: linear-gradient(135deg, #ffc107, #fd7e14);
    }

    .card {
        border: 1px solid #e9ecef !important;
    }

    .card-header {
        border-bottom: 2px solid #000;
    }

    .progress {
        background-color: #f8f9fa;
    }
</style>

<script>
    // Animate progress circles when they come into view
    document.addEventListener('DOMContentLoaded', function() {
        const progressCircle = document.querySelector('.progress-circle');
        if (progressCircle) {
            const progress = progressCircle.getAttribute('data-progress');
            progressCircle.style.background = `conic-gradient(#28a745 ${progress * 3.6}deg, #e9ecef 0deg)`;
        }
    });

    // Add some interactive elements
    document.querySelectorAll('.card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
            this.style.transition = 'transform 0.2s ease';
        });

        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
</script>