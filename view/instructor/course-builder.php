<link rel="stylesheet" href="/assets/css/course-builder.css">
<div class="container-fluid py-4 mt-5">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 fw-bold mb-1">
                        <?= $courseId ? 'Edit: ' . htmlspecialchars($course['title']) : 'Create New Course' ?>
                    </h1>
                    <p class="text-muted mb-0">
                        <?= $courseId ? 'Manage your course content and settings' : 'Build your course step by step' ?>
                    </p>
                </div>
                <div class="d-flex gap-2">
                    <a href="/instructor-courses" class="btn btn-outline-dark">
                        <i class="fas fa-arrow-left me-2"></i>Back
                    </a>
                    <?php if ($courseId): ?>
                        <a href="/course/<?= $courseId ?>" class="btn btn-outline-primary" target="_blank">
                            <i class="fas fa-eye me-2"></i>Preview
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Mobile-Friendly Navigation -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-0">
                    <!-- Mobile Dropdown -->
                    <div class="d-lg-none">
                        <select class="form-select border-0 bg-light rounded-0" id="mobileTabSelector" style="font-size: 1.1rem; padding: 1rem;">
                            <option value="basic" <?= $activeTab === 'basic' ? 'selected' : '' ?>>
                                <i class="fas fa-info-circle me-2"></i> Basic Info
                            </option>
                            <?php if ($courseId): ?>
                                <option value="curriculum" <?= $activeTab === 'curriculum' ? 'selected' : '' ?>>
                                    <i class="fas fa-graduation-cap me-2"></i> Curriculum
                                </option>
                                <option value="communication" <?= $activeTab === 'communication' ? 'selected' : '' ?>>
                                    <i class="fas fa-comments me-2"></i> Communication
                                </option>
                                <option value="settings" <?= $activeTab === 'settings' ? 'selected' : '' ?>>
                                    <i class="fas fa-cog me-2"></i> Settings
                                </option>
                            <?php endif; ?>
                        </select>
                    </div>

                    <!-- Desktop Tabs -->
                    <div class="d-none d-lg-block">
                        <ul class="nav nav-pills nav-justified" id="courseBuilderTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link <?= $activeTab === 'basic' ? 'active' : '' ?>"
                                    data-tab="basic" type="button">
                                    <i class="fas fa-info-circle me-2"></i>Basic Info
                                </button>
                            </li>
                            <?php if ($courseId): ?>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link <?= $activeTab === 'curriculum' ? 'active' : '' ?>"
                                        data-tab="curriculum" type="button">
                                        <i class="fas fa-graduation-cap me-2"></i>Curriculum
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link <?= $activeTab === 'communication' ? 'active' : '' ?>"
                                        data-tab="communication" type="button">
                                        <i class="fas fa-comments me-2"></i>Communication
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link <?= $activeTab === 'settings' ? 'active' : '' ?>"
                                        data-tab="settings" type="button">
                                        <i class="fas fa-cog me-2"></i>Settings
                                    </button>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Tab Content -->
            <div class="tab-content">
                <!-- Basic Information Tab -->
                <div class="tab-pane <?= $activeTab === 'basic' ? 'show active' : '' ?>" id="basicContent">
                    <form method="POST">
                        <input type="hidden" name="update_course" value="1">
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="card border-0 shadow-sm mb-4">
                                    <div class="card-header bg-white border-0 py-3">
                                        <h5 class="fw-bold mb-0">
                                            <i class="fas fa-info-circle me-2"></i>Course Information
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <!-- Course Title -->
                                        <div class="mb-4">
                                            <label for="title" class="form-label fw-semibold">
                                                Course Title <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control form-control-lg" id="title" name="title"
                                                value="<?= htmlspecialchars($course['title'] ?? '') ?>"
                                                placeholder="e.g., Complete Web Development Bootcamp 2024" required>
                                        </div>

                                        <!-- Short Description -->
                                        <div class="mb-4">
                                            <label for="short_description" class="form-label fw-semibold">
                                                Short Description <span class="text-danger">*</span>
                                            </label>
                                            <textarea class="form-control" id="short_description" name="short_description"
                                                rows="2" placeholder="Brief overview of your course (max 200 characters)..."
                                                maxlength="200" required><?= htmlspecialchars($course['short_description'] ?? '') ?></textarea>
                                            <div class="form-text">
                                                <span id="shortDescCount"><?= strlen($course['short_description'] ?? '') ?></span>/200 characters
                                            </div>
                                        </div>

                                        <!-- Full Description -->
                                        <div class="mb-4">
                                            <label for="description" class="form-label fw-semibold">
                                                Course Description <span class="text-danger">*</span>
                                            </label>
                                            <textarea class="form-control" id="description" name="description"
                                                rows="6" placeholder="Detailed description of what students will learn, course requirements, and who this course is for..."
                                                required><?= htmlspecialchars($course['description'] ?? '') ?></textarea>
                                        </div>

                                        <!-- Course Category & Level -->
                                        <div class="row">
                                            <div class="col-md-6 mb-4">
                                                <label for="category" class="form-label fw-semibold">
                                                    Category <span class="text-danger">*</span>
                                                </label>
                                                <select class="form-select" id="category" name="category" required>
                                                    <option value="">Select a category</option>
                                                    <option value="Web Development" <?= ($course['category'] ?? '') === 'Web Development' ? 'selected' : '' ?>>Web Development</option>
                                                    <option value="Data Science" <?= ($course['category'] ?? '') === 'Data Science' ? 'selected' : '' ?>>Data Science</option>
                                                    <option value="Mobile Development" <?= ($course['category'] ?? '') === 'Mobile Development' ? 'selected' : '' ?>>Mobile Development</option>
                                                    <option value="Programming" <?= ($course['category'] ?? '') === 'Programming' ? 'selected' : '' ?>>Programming</option>
                                                    <option value="Machine Learning" <?= ($course['category'] ?? '') === 'Machine Learning' ? 'selected' : '' ?>>Machine Learning</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-4">
                                                <label for="level" class="form-label fw-semibold">
                                                    Difficulty Level <span class="text-danger">*</span>
                                                </label>
                                                <select class="form-select" id="level" name="level" required>
                                                    <option value="beginner" <?= ($course['level'] ?? '') === 'beginner' ? 'selected' : '' ?>>Beginner</option>
                                                    <option value="intermediate" <?= ($course['level'] ?? '') === 'intermediate' ? 'selected' : '' ?>>Intermediate</option>
                                                    <option value="advanced" <?= ($course['level'] ?? '') === 'advanced' ? 'selected' : '' ?>>Advanced</option>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Course Price -->
                                        <div class="mb-4">
                                            <label for="price" class="form-label fw-semibold">
                                                Course Price ($) <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text">$</span>
                                                <input type="number" class="form-control" id="price" name="price"
                                                    value="<?= $course['price'] ?? '49.99' ?>" min="0" step="0.01" required>
                                            </div>
                                        </div>

                                        <!-- Course Thumbnail -->
                                        <div class="mb-4">
                                            <label for="thumbnail" class="form-label fw-semibold">Course Thumbnail</label>
                                            <input type="url" class="form-control" id="thumbnail" name="thumbnail"
                                                value="<?= htmlspecialchars($course['thumbnail'] ?? '') ?>"
                                                placeholder="https://example.com/thumbnail.jpg">

                                            <?php if ($courseId && !empty($course['thumbnail'])): ?>
                                                <div class="mt-2">
                                                    <img src="<?= getCourseImage($course) ?>"
                                                        alt="Course thumbnail" class="img-thumbnail" style="max-height: 200px;">
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- Quick Curriculum Setup for New Courses -->
                                <?php if (!$courseId): ?>
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-header bg-white border-0 py-3">
                                            <h5 class="fw-bold mb-0">
                                                <i class="fas fa-graduation-cap me-2"></i>Quick Curriculum Setup
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <p class="text-muted mb-3">
                                                After creating your course, you'll be able to add lessons, videos, and build your complete curriculum.
                                            </p>
                                            <div class="alert alert-info">
                                                <i class="fas fa-lightbulb me-2"></i>
                                                <strong>Tip:</strong> Plan 5-10 lessons to start. You can always add more later!
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Sidebar Actions -->
                            <div class="col-lg-4">
                                <div class="card border-0 shadow-sm mb-4">
                                    <div class="card-header bg-white border-0 py-3">
                                        <h5 class="fw-bold mb-0">Course Status</h5>
                                    </div>
                                    <div class="card-body">
                                        <?php if ($courseId): ?>
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Current Status</label>
                                                <div class="alert alert-<?= $course['status'] === 'published' ? 'success' : 'warning' ?> py-2">
                                                    <i class="fas fa-<?= $course['status'] === 'published' ? 'check-circle' : 'edit' ?> me-2"></i>
                                                    <strong><?= ucfirst($course['status']) ?></strong>
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <div class="alert alert-info py-2">
                                                <i class="fas fa-info-circle me-2"></i>
                                                <strong>Draft Mode</strong>
                                            </div>
                                        <?php endif; ?>

                                        <div class="d-grid gap-2">
                                            <button type="submit" class="btn btn-primary btn-lg">
                                                <i class="fas fa-save me-2"></i>
                                                <?= $courseId ? 'Save Changes' : 'Create Course' ?>
                                            </button>

                                            <?php if ($courseId && $course['status'] !== 'published'): ?>
                                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#publishModal">
                                                    <i class="fas fa-rocket me-2"></i>Publish Course
                                                </button>
                                            <?php elseif ($courseId): ?>
                                                <form method="POST" class="d-inline">
                                                    <input type="hidden" name="status" value="draft">
                                                    <button type="submit" name="update_status" class="btn btn-warning w-100"
                                                        onclick="return confirm('Are you sure you want to unpublish this course?')">
                                                        <i class="fas fa-pause me-2"></i>Unpublish
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- Quick Stats -->
                                <?php if ($courseId): ?>
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-header bg-white border-0 py-3">
                                            <h5 class="fw-bold mb-0">Course Stats</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row text-center">
                                                <div class="col-6 mb-3">
                                                    <div class="h4 fw-bold text-primary mb-1"><?= count($course['curriculum'] ?? []) ?></div>
                                                    <small class="text-muted">Lessons</small>
                                                </div>
                                                <div class="col-6 mb-3">
                                                    <div class="h4 fw-bold text-success mb-1"><?= count(getCourseEnrollments($courseId)) ?></div>
                                                    <small class="text-muted">Students</small>
                                                </div>
                                            </div>
                                            <div class="progress mb-2" style="height: 6px;">
                                                <div class="progress-bar bg-info" style="width: 0%"></div>
                                            </div>
                                            <small class="text-muted">Course completion rate: 0%</small>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </form>
                </div>

                <?php if ($courseId): ?>
                    <!-- Curriculum Tab -->
                    <div class="tab-pane <?= $activeTab === 'curriculum' ? 'show active' : '' ?>" id="curriculumContent">
                        <div class="row">
                            <div class="col-lg-8">
                                <!-- Curriculum Header -->
                                <div class="card border-0 shadow-sm mb-4">
                                    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="fw-bold mb-0">
                                                <i class="fas fa-graduation-cap me-2"></i>Course Curriculum
                                            </h5>
                                            <small class="text-muted">Build your course content lesson by lesson</small>
                                        </div>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge bg-primary fs-6"><?= count($course['curriculum'] ?? []) ?> lessons</span>
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addLessonModal">
                                                <i class="fas fa-plus me-2"></i>Add Lesson
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <!-- Curriculum List -->
                                        <div id="curriculumList" class="sortable-list">
                                            <?php if (empty($course['curriculum'])): ?>
                                                <div class="text-center py-5">
                                                    <i class="fas fa-graduation-cap fa-3x text-muted mb-3"></i>
                                                    <h5 class="text-muted">No lessons yet</h5>
                                                    <p class="text-muted mb-4">Start building your course by adding your first lesson.</p>
                                                    <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#addLessonModal">
                                                        <i class="fas fa-plus me-2"></i>Add Your First Lesson
                                                    </button>
                                                </div>
                                            <?php else: ?>
                                                <?php
                                                // Sort lessons by order
                                                $sortedCurriculum = $course['curriculum'];
                                                usort($sortedCurriculum, function ($a, $b) {
                                                    return ($a['order'] ?? 0) - ($b['order'] ?? 0);
                                                });
                                                ?>

                                                <?php foreach ($sortedCurriculum as $index => $lesson): ?>
                                                    <div class="card border-0 shadow-sm mb-3 curriculum-item" data-lesson-id="<?= $lesson['id'] ?>">
                                                        <div class="card-body">
                                                            <div class="d-flex align-items-center">
                                                                <div class="drag-handle me-3 text-muted cursor-grab">
                                                                    <i class="fas fa-bars fa-lg"></i>
                                                                </div>
                                                                <div class="flex-grow-1">
                                                                    <div class="d-flex justify-content-between align-items-start">
                                                                        <div>
                                                                            <h6 class="fw-bold mb-1">
                                                                                <?= ($index + 1) ?>. <?= htmlspecialchars($lesson['title']) ?>
                                                                            </h6>
                                                                            <p class="text-muted small mb-1"><?= htmlspecialchars($lesson['description'] ?? 'No description') ?></p>
                                                                            <div class="d-flex flex-wrap gap-2">
                                                                                <span class="badge bg-<?= $lesson['type'] === 'video' ? 'primary' : 'secondary' ?>">
                                                                                    <i class="fas fa-<?= $lesson['type'] === 'video' ? 'play-circle' : 'file-text' ?> me-1"></i>
                                                                                    <?= ucfirst($lesson['type']) ?>
                                                                                </span>
                                                                                <span class="text-muted small">
                                                                                    <i class="fas fa-clock me-1"></i><?= formatDuration($lesson['duration'] ?? 0) ?>
                                                                                </span>
                                                                                <?php if ($lesson['type'] === 'video' && !empty($lesson['video_url'])): ?>
                                                                                    <span class="badge bg-success">
                                                                                        <i class="fas fa-link me-1"></i>Video Ready
                                                                                    </span>
                                                                                <?php endif; ?>
                                                                            </div>
                                                                        </div>
                                                                        <div class="btn-group btn-group-sm">
                                                                            <button type="button" class="btn btn-outline-dark edit-lesson"
                                                                                data-lesson='<?= htmlspecialchars(json_encode($lesson)) ?>'>
                                                                                <i class="fas fa-edit"></i>
                                                                            </button>
                                                                            <form method="POST" class="d-inline"
                                                                                onsubmit="return confirm('Are you sure you want to delete this lesson?')">
                                                                                <input type="hidden" name="lesson_id" value="<?= $lesson['id'] ?>">
                                                                                <button type="submit" name="delete_lesson" class="btn btn-outline-danger">
                                                                                    <i class="fas fa-trash"></i>
                                                                                </button>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </div>

                                        <!-- Save Order Button -->
                                        <?php if (!empty($course['curriculum'])): ?>
                                            <form method="POST" id="reorderForm">
                                                <input type="hidden" name="lesson_order" id="lessonOrder">
                                                <input type="hidden" name="reorder_lessons" value="1">
                                                <button type="submit" class="btn btn-success mt-3" id="saveOrderBtn" style="display: none;">
                                                    <i class="fas fa-save me-2"></i>Save Lesson Order
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Curriculum Tips & Stats -->
                            <div class="col-lg-4">
                                <div class="card border-0 shadow-sm mb-4">
                                    <div class="card-header bg-white border-0 py-3">
                                        <h5 class="fw-bold mb-0">
                                            <i class="fas fa-chart-bar me-2"></i>Curriculum Stats
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="text-center mb-4">
                                            <div class="h2 fw-bold text-primary"><?= count($course['curriculum'] ?? []) ?></div>
                                            <small class="text-muted">Total Lessons</small>
                                        </div>

                                        <div class="mb-3">
                                            <small class="text-muted d-block mb-1">Total Duration</small>
                                            <div class="h5 fw-bold text-success">
                                                <?= formatDuration(array_sum(array_column($course['curriculum'] ?? [], 'duration'))) ?>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <small class="text-muted d-block mb-1">Content Types</small>
                                            <div class="small">
                                                <?php
                                                $lessonTypes = array_count_values(array_column($course['curriculum'] ?? [], 'type'));
                                                foreach ($lessonTypes as $type => $count):
                                                ?>
                                                    <div class="d-flex justify-content-between mb-1">
                                                        <span><?= ucfirst($type) ?></span>
                                                        <span class="fw-bold"><?= $count ?></span>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-white border-0 py-3">
                                        <h5 class="fw-bold mb-0">
                                            <i class="fas fa-lightbulb me-2"></i>Quick Tips
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <h6 class="fw-semibold">
                                                <i class="fas fa-bullseye text-primary me-2"></i>Start Strong
                                            </h6>
                                            <p class="small text-muted mb-0">Begin with an engaging introduction to hook your students.</p>
                                        </div>
                                        <div class="mb-3">
                                            <h6 class="fw-semibold">
                                                <i class="fas fa-video text-info me-2"></i>Mix Media
                                            </h6>
                                            <p class="small text-muted mb-0">Combine video lessons with text content for better engagement.</p>
                                        </div>
                                        <div class="mb-0">
                                            <h6 class="fw-semibold">
                                                <i class="fas fa-clock text-warning me-2"></i>Keep it Bite-sized
                                            </h6>
                                            <p class="small text-muted mb-0">Aim for 5-15 minute lessons to maintain attention.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Communication Tab -->
                    <div class="tab-pane <?= $activeTab === 'communication' ? 'show active' : '' ?>" id="communicationContent">
                        <div class="row">
                            <div class="col-lg-8">
                                <!-- Announcements -->
                                <div class="card border-0 shadow-sm mb-4">
                                    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="fw-bold mb-0">
                                                <i class="fas fa-bullhorn me-2"></i>Course Announcements
                                            </h5>
                                            <small class="text-muted">Keep your students informed and engaged</small>
                                        </div>
                                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addAnnouncementModal">
                                            <i class="fas fa-plus me-2"></i>New Announcement
                                        </button>
                                    </div>
                                    <div class="card-body">
                                        <?php if (empty($announcements)): ?>
                                            <div class="text-center py-4">
                                                <i class="fas fa-bullhorn fa-3x text-muted mb-3"></i>
                                                <h5 class="text-muted">No announcements yet</h5>
                                                <p class="text-muted">Keep your students updated with important information.</p>
                                            </div>
                                        <?php else: ?>
                                            <?php foreach ($announcements as $announcement): ?>
                                                <div class="card border-0 bg-light mb-3">
                                                    <div class="card-body">
                                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                                            <h6 class="fw-bold mb-0"><?= htmlspecialchars($announcement['title']) ?></h6>
                                                            <small class="text-muted"><?= date('M j, Y g:i A', strtotime($announcement['created_at'])) ?></small>
                                                        </div>
                                                        <p class="mb-0"><?= nl2br(htmlspecialchars($announcement['content'])) ?></p>
                                                        <div class="mt-2">
                                                            <small class="text-muted">
                                                                By <?= htmlspecialchars($announcement['instructor_name']) ?>
                                                            </small>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Communication Stats -->
                            <div class="col-lg-4">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-white border-0 py-3">
                                        <h5 class="fw-bold mb-0">
                                            <i class="fas fa-chart-line me-2"></i>Engagement Stats
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="text-center mb-4">
                                            <div class="h2 fw-bold text-primary"><?= count($announcements) ?></div>
                                            <small class="text-muted">Announcements Posted</small>
                                        </div>

                                        <div class="mb-3">
                                            <small class="text-muted d-block mb-1">Student Engagement</small>
                                            <div class="progress" style="height: 8px;">
                                                <div class="progress-bar bg-success" style="width: 65%"></div>
                                            </div>
                                            <small class="text-muted">65% of students active</small>
                                        </div>

                                        <div class="mb-0">
                                            <small class="text-muted d-block mb-1">Response Rate</small>
                                            <div class="progress" style="height: 8px;">
                                                <div class="progress-bar bg-info" style="width: 85%"></div>
                                            </div>
                                            <small class="text-muted">85% questions answered</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Settings Tab -->
                    <div class="tab-pane <?= $activeTab === 'settings' ? 'show active' : '' ?>" id="settingsContent">
                        <div class="row justify-content-center">
                            <div class="col-lg-8">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-white border-0 py-3">
                                        <h5 class="fw-bold mb-0">
                                            <i class="fas fa-cog me-2"></i>Course Settings
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <!-- Course Status -->
                                        <div class="mb-4">
                                            <label class="form-label fw-semibold">Course Status</label>
                                            <div class="alert alert-<?= $course['status'] === 'published' ? 'success' : 'warning' ?>">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <i class="fas fa-<?= $course['status'] === 'published' ? 'check-circle' : 'edit' ?> me-2"></i>
                                                        <strong>Currently <?= ucfirst($course['status']) ?></strong>
                                                    </div>
                                                    <?php if ($course['status'] !== 'published'): ?>
                                                        <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#publishModal">
                                                            Publish Course
                                                        </button>
                                                    <?php else: ?>
                                                        <form method="POST" class="d-inline">
                                                            <input type="hidden" name="status" value="draft">
                                                            <button type="submit" name="update_status" class="btn btn-warning btn-sm"
                                                                onclick="return confirm('Are you sure you want to unpublish this course?')">
                                                                Unpublish
                                                            </button>
                                                        </form>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Danger Zone -->
                                        <div class="border rounded p-4 bg-light">
                                            <h6 class="fw-bold text-danger mb-3">
                                                <i class="fas fa-exclamation-triangle me-2"></i>Danger Zone
                                            </h6>

                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h6 class="fw-semibold mb-1">Delete This Course</h6>
                                                    <p class="text-muted small mb-0">Once deleted, it cannot be recovered.</p>
                                                </div>
                                                <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                                    Delete Course
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Add Lesson Modal -->
<div class="modal fade" id="addLessonModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-plus-circle me-2"></i>Add New Lesson
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label for="lesson_title" class="form-label fw-semibold">Lesson Title *</label>
                            <input type="text" class="form-control" id="lesson_title" name="lesson_title" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="lesson_duration" class="form-label fw-semibold">Duration (minutes) *</label>
                            <input type="number" class="form-control" id="lesson_duration" name="lesson_duration" min="1" value="10" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="lesson_description" class="form-label fw-semibold">Short Description *</label>
                        <textarea class="form-control" id="lesson_description" name="lesson_description" rows="2"
                            placeholder="Brief description of what students will learn in this lesson..." required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="lesson_type" class="form-label fw-semibold">Lesson Type *</label>
                        <select class="form-select" id="lesson_type" name="lesson_type" required>
                            <option value="video">
                                <i class="fas fa-video me-2"></i>Video Lesson
                            </option>
                            <option value="text">
                                <i class="fas fa-file-text me-2"></i>Text Lesson
                            </option>
                            <option value="quiz">
                                <i class="fas fa-question-circle me-2"></i>Quiz
                            </option>
                            <option value="assignment">
                                <i class="fas fa-tasks me-2"></i>Assignment
                            </option>
                        </select>
                    </div>

                    <div class="mb-3 video-url-field">
                        <label for="video_url" class="form-label fw-semibold">
                            <i class="fas fa-link me-2"></i>Video URL
                        </label>
                        <input type="url" class="form-control" id="video_url" name="video_url"
                            placeholder="https://youtube.com/embed/... or https://vimeo.com/...">
                        <div class="form-text">
                            <i class="fas fa-info-circle me-1"></i>Paste YouTube or Vimeo embed URL
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="lesson_content" class="form-label fw-semibold">Lesson Content *</label>
                        <textarea class="form-control" id="lesson_content" name="lesson_content" rows="6"
                            placeholder="Detailed lesson content, instructions, or embed code..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="submit" name="add_lesson" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Add Lesson
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Lesson Modal -->
<div class="modal fade" id="editLessonModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-edit me-2"></i>Edit Lesson
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <input type="hidden" name="lesson_id" id="edit_lesson_id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label for="edit_lesson_title" class="form-label fw-semibold">Lesson Title *</label>
                            <input type="text" class="form-control" id="edit_lesson_title" name="edit_lesson_title" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="edit_lesson_duration" class="form-label fw-semibold">Duration (minutes) *</label>
                            <input type="number" class="form-control" id="edit_lesson_duration" name="edit_lesson_duration" min="1" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="edit_lesson_description" class="form-label fw-semibold">Short Description *</label>
                        <textarea class="form-control" id="edit_lesson_description" name="edit_lesson_description" rows="2" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="edit_lesson_type" class="form-label fw-semibold">Lesson Type *</label>
                        <select class="form-select" id="edit_lesson_type" name="edit_lesson_type" required>
                            <option value="video">
                                <i class="fas fa-video me-2"></i>Video Lesson
                            </option>
                            <option value="text">
                                <i class="fas fa-file-text me-2"></i>Text Lesson
                            </option>
                            <option value="quiz">
                                <i class="fas fa-question-circle me-2"></i>Quiz
                            </option>
                            <option value="assignment">
                                <i class="fas fa-tasks me-2"></i>Assignment
                            </option>
                        </select>
                    </div>

                    <div class="mb-3 edit-video-url-field">
                        <label for="edit_video_url" class="form-label fw-semibold">
                            <i class="fas fa-link me-2"></i>Video URL
                        </label>
                        <input type="url" class="form-control" id="edit_video_url" name="edit_video_url">
                        <div class="form-text">
                            <i class="fas fa-info-circle me-1"></i>Paste YouTube or Vimeo embed URL
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="edit_lesson_content" class="form-label fw-semibold">Lesson Content *</label>
                        <textarea class="form-control" id="edit_lesson_content" name="edit_lesson_content" rows="6" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="submit" name="update_lesson" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Update Lesson
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Announcement Modal -->
<div class="modal fade" id="addAnnouncementModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-bullhorn me-2"></i>New Announcement
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="announcement_title" class="form-label fw-semibold">Title *</label>
                        <input type="text" class="form-control" id="announcement_title" name="announcement_title" required>
                    </div>
                    <div class="mb-3">
                        <label for="announcement_content" class="form-label fw-semibold">Message *</label>
                        <textarea class="form-control" id="announcement_content" name="announcement_content" rows="4" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="submit" name="add_announcement" class="btn btn-primary">
                        <i class="fas fa-paper-plane me-2"></i>Publish Announcement
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Publish Confirmation Modal -->
<div class="modal fade" id="publishModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-rocket me-2"></i>Publish Course
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <p>Are you sure you want to publish "<strong><?= htmlspecialchars($course['title'] ?? '') ?></strong>"?</p>
                    <p class="text-muted small">Once published, your course will be available to all students on CodeMastery.</p>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="confirmPublish" required>
                        <label class="form-check-label" for="confirmPublish">
                            <i class="fas fa-check-circle me-1 text-success"></i>
                            I confirm that this course meets CodeMastery's quality standards
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <input type="hidden" name="status" value="published">
                    <button type="submit" name="update_status" class="btn btn-success" id="publishBtn" disabled>
                        <i class="fas fa-rocket me-2"></i>Publish Course
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>Delete Course
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <p>Are you sure you want to delete "<strong><?= htmlspecialchars($course['title'] ?? '') ?></strong>"?</p>
                    <p class="text-danger small">
                        <i class="fas fa-exclamation-circle me-1"></i>
                        This action cannot be undone. All course data, including student progress, will be permanently deleted.
                    </p>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="confirmDelete" required>
                        <label class="form-check-label" for="confirmDelete">
                            <i class="fas fa-check-circle me-1 text-danger"></i>
                            I understand this action is permanent and cannot be undone
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="submit" name="delete_course" class="btn btn-danger" id="deleteBtn" disabled>
                        <i class="fas fa-trash me-2"></i>Delete Course
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Mobile tab navigation
        const mobileTabSelector = document.getElementById('mobileTabSelector');
        if (mobileTabSelector) {
            mobileTabSelector.addEventListener('change', function() {
                const tab = this.value;
                window.location.href = `?course_id=<?= $courseId ?>&tab=${tab}`;
            });
        }

        // Desktop tab navigation
        const desktopTabs = document.querySelectorAll('button[data-tab]');
        desktopTabs.forEach(tab => {
            tab.addEventListener('click', function() {
                const tabName = this.getAttribute('data-tab');
                window.location.href = `?course_id=<?= $courseId ?>&tab=${tabName}`;
            });
        });

        // Character counter for short description
        const shortDesc = document.getElementById('short_description');
        const shortDescCount = document.getElementById('shortDescCount');
        if (shortDesc && shortDescCount) {
            shortDesc.addEventListener('input', function() {
                shortDescCount.textContent = this.value.length;
            });
        }

        // Lesson type toggle for add lesson modal
        const lessonType = document.getElementById('lesson_type');
        const videoUrlField = document.querySelector('.video-url-field');

        if (lessonType && videoUrlField) {
            function toggleVideoField() {
                videoUrlField.style.display = lessonType.value === 'video' ? 'block' : 'none';
            }

            lessonType.addEventListener('change', toggleVideoField);
            toggleVideoField(); // Initial call
        }

        // Lesson type toggle for edit lesson modal  
        const editLessonType = document.getElementById('edit_lesson_type');
        const editVideoUrlField = document.querySelector('.edit-video-url-field');

        if (editLessonType && editVideoUrlField) {
            function toggleEditVideoField() {
                editVideoUrlField.style.display = editLessonType.value === 'video' ? 'block' : 'none';
            }

            editLessonType.addEventListener('change', toggleEditVideoField);
            toggleEditVideoField(); // Initial call
        }

        // Drag and drop for curriculum
        const curriculumList = document.getElementById('curriculumList');
        const saveOrderBtn = document.getElementById('saveOrderBtn');

        if (curriculumList && typeof Sortable !== 'undefined') {
            new Sortable(curriculumList, {
                handle: '.drag-handle',
                animation: 150,
                onEnd: function() {
                    saveOrderBtn.style.display = 'block';

                    // Update lesson order hidden field
                    const lessonOrder = Array.from(document.querySelectorAll('.curriculum-item')).map(item => {
                        return item.getAttribute('data-lesson-id');
                    });
                    document.getElementById('lessonOrder').value = JSON.stringify(lessonOrder);
                }
            });
        }

        // Edit lesson functionality
        const editButtons = document.querySelectorAll('.edit-lesson');
        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                const lessonData = JSON.parse(this.getAttribute('data-lesson'));

                // Populate edit form
                document.getElementById('edit_lesson_id').value = lessonData.id;
                document.getElementById('edit_lesson_title').value = lessonData.title;
                document.getElementById('edit_lesson_duration').value = lessonData.duration;
                document.getElementById('edit_lesson_description').value = lessonData.description || '';
                document.getElementById('edit_lesson_type').value = lessonData.type;
                document.getElementById('edit_video_url').value = lessonData.video_url || '';
                document.getElementById('edit_lesson_content').value = lessonData.content || '';

                // Toggle video field based on type
                if (editLessonType && editVideoUrlField) {
                    editVideoUrlField.style.display = lessonData.type === 'video' ? 'block' : 'none';
                }

                // Show edit modal
                new bootstrap.Modal(document.getElementById('editLessonModal')).show();
            });
        });

        // Enable/disable publish button based on confirmation
        const confirmPublish = document.getElementById('confirmPublish');
        const publishBtn = document.getElementById('publishBtn');

        if (confirmPublish && publishBtn) {
            confirmPublish.addEventListener('change', function() {
                publishBtn.disabled = !this.checked;
            });
        }

        // Enable/disable delete button based on confirmation
        const confirmDelete = document.getElementById('confirmDelete');
        const deleteBtn = document.getElementById('deleteBtn');

        if (confirmDelete && deleteBtn) {
            confirmDelete.addEventListener('change', function() {
                deleteBtn.disabled = !this.checked;
            });
        }

        // Auto-resize textareas
        const textareas = document.querySelectorAll('textarea');
        textareas.forEach(textarea => {
            textarea.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = (this.scrollHeight) + 'px';
            });
            // Trigger initial resize
            textarea.style.height = 'auto';
            textarea.style.height = (textarea.scrollHeight) + 'px';
        });
    });
</script>


<script src="/assets/js/bootstrap.bundle.js"></script>