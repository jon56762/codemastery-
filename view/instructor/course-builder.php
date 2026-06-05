<?php
$page_title = $courseId ? "Edit Course - CodeMastery" : "Create New Course - CodeMastery";
require 'view/partial/instructor-header.php';
?>

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
                        <i class="fas fa-arrow-left me-2"></i>
                    </a>
                    <?php if ($courseId): ?>
                        <a href="/course/<?= $courseId ?>" class="btn btn-outline-primary" target="_blank">
                            <i class="fas fa-eye me-2"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Alert Messages -->
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <?= $_SESSION['error'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    <?= $_SESSION['success'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

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
                <!-- ==================== Basic Info Tab ==================== -->
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
                                            <label for="title" class="form-label fw-semibold">Course Title <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control form-control-lg" id="title" name="title"
                                                value="<?= htmlspecialchars($course['title'] ?? '') ?>"
                                                placeholder="e.g., Complete Web Development Bootcamp 2024" required>
                                        </div>

                                        <!-- Short Description -->
                                        <div class="mb-4">
                                            <label for="short_description" class="form-label fw-semibold">Short Description <span class="text-danger">*</span></label>
                                            <textarea class="form-control" id="short_description" name="short_description"
                                                rows="2" placeholder="Brief overview of your course (max 200 characters)..."
                                                maxlength="200" required><?= htmlspecialchars($course['short_description'] ?? '') ?></textarea>
                                            <div class="form-text">
                                                <span id="shortDescCount"><?= strlen($course['short_description'] ?? '') ?></span>/200 characters
                                            </div>
                                        </div>

                                        <!-- Full Description -->
                                        <div class="mb-4">
                                            <label for="description" class="form-label fw-semibold">Course Description <span class="text-danger">*</span></label>
                                            <textarea class="form-control" id="description" name="description"
                                                rows="6" placeholder="Detailed description of what students will learn..."
                                                required><?= htmlspecialchars($course['description'] ?? '') ?></textarea>
                                        </div>

                                        <!-- Category & Level -->
                                        <div class="row">
                                            <div class="col-md-6 mb-4">
                                                <label for="category" class="form-label fw-semibold">Category <span class="text-danger">*</span></label>
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
                                                <label for="level" class="form-label fw-semibold">Difficulty Level <span class="text-danger">*</span></label>
                                                <select class="form-select" id="level" name="level" required>
                                                    <option value="beginner" <?= ($course['level'] ?? '') === 'beginner' ? 'selected' : '' ?>>Beginner</option>
                                                    <option value="intermediate" <?= ($course['level'] ?? '') === 'intermediate' ? 'selected' : '' ?>>Intermediate</option>
                                                    <option value="advanced" <?= ($course['level'] ?? '') === 'advanced' ? 'selected' : '' ?>>Advanced</option>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Price -->
                                        <div class="mb-4">
                                            <label for="price" class="form-label fw-semibold">Course Price ($) <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text">$</span>
                                                <input type="number" class="form-control" id="price" name="price"
                                                    value="<?= $course['price'] ?? '49.99' ?>" min="0" step="0.01" required>
                                            </div>
                                            <small class="text-muted">Set to 0 to make this course free.</small>
                                        </div>

                                        <!-- Thumbnail -->
                                        <div class="mb-4">
                                            <label for="thumbnail" class="form-label fw-semibold">Course Thumbnail</label>
                                            <input type="url" class="form-control" id="thumbnail" name="thumbnail"
                                                value="<?= htmlspecialchars($course['thumbnail'] ?? '') ?>"
                                                placeholder="https://example.com/thumbnail.jpg">
                                            <?php if ($courseId && !empty($course['thumbnail'])): ?>
                                                <div class="mt-2">
                                                    <img src="<?= getCourseImage($course) ?>" alt="Course thumbnail" class="img-thumbnail" style="max-height: 200px;">
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
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
                                                        onclick="return confirm('Unpublish this course?')">
                                                        <i class="fas fa-pause me-2"></i>Unpublish
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

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
                                                    <div class="h4 fw-bold text-success mb-1"><?= $enrollmentCount ?></div>
                                                    <small class="text-muted">Students</small>
                                                </div>
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
                <!-- ==================== Curriculum Tab ==================== -->
                <div class="tab-pane <?= $activeTab === 'curriculum' ? 'show active' : '' ?>" id="curriculumContent">
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="fw-bold mb-0"><i class="fas fa-graduation-cap me-2"></i>Course Curriculum</h5>
                                        <small class="text-muted">Build your course content lesson by lesson</small>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge bg-primary fs-6"><?= count($course['curriculum'] ?? []) ?> lessons</span>
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addLessonModal">
                                            <i class="fas fa-plus me-2"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
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
                                            $sorted = $course['curriculum'];
                                            usort($sorted, function ($a, $b) {
                                                return ($a['order'] ?? 0) - ($b['order'] ?? 0);
                                            });
                                            foreach ($sorted as $index => $lesson):
                                            ?>
                                                <div class="card border-0 shadow-sm mb-3 curriculum-item" data-lesson-id="<?= $lesson['id'] ?>">
                                                    <div class="card-body">
                                                        <div class="d-flex align-items-center">
                                                            <div class="drag-handle me-3 text-muted cursor-grab">
                                                                <i class="fas fa-bars fa-lg"></i>
                                                            </div>
                                                            <div class="flex-grow-1">
                                                                <div class="d-flex justify-content-between align-items-start">
                                                                    <div>
                                                                        <h6 class="fw-bold mb-1"><?= ($index + 1) ?>. <?= htmlspecialchars($lesson['title']) ?></h6>
                                                                        <p class="text-muted small mb-1"><?= htmlspecialchars($lesson['description'] ?? '') ?></p>
                                                                        <span class="badge bg-<?=
                                                                            $lesson['type'] === 'video' ? 'primary' :
                                                                            ($lesson['type'] === 'reading' ? 'info' :
                                                                            ($lesson['type'] === 'quiz' ? 'warning' : 'success'))
                                                                        ?>">
                                                                            <i class="fas fa-<?=
                                                                                $lesson['type'] === 'video' ? 'play-circle' :
                                                                                ($lesson['type'] === 'reading' ? 'file-alt' :
                                                                                ($lesson['type'] === 'quiz' ? 'question-circle' : 'code'))
                                                                            ?> me-1"></i>
                                                                            <?= ucfirst($lesson['type']) ?>
                                                                        </span>
                                                                        <span class="text-muted small ms-2">
                                                                            <i class="fas fa-clock me-1"></i><?= formatDuration($lesson['duration'] ?? 0) ?>
                                                                        </span>
                                                                    </div>
                                                                    <div class="btn-group btn-group-sm">
                                                                        <button type="button" class="btn btn-outline-dark edit-lesson"
                                                                            data-lesson='<?= htmlspecialchars(json_encode($lesson)) ?>'>
                                                                            <i class="fas fa-edit"></i>
                                                                        </button>
                                                                        <form method="POST" class="d-inline"
                                                                            onsubmit="return confirm('Delete this lesson?')">
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

                        <div class="col-lg-4">
                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-header bg-white border-0 py-3">
                                    <h5 class="fw-bold mb-0"><i class="fas fa-chart-bar me-2"></i>Curriculum Stats</h5>
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
                                        <?php
                                        $types = array_count_values(array_column($course['curriculum'] ?? [], 'type'));
                                        foreach ($types as $type => $count):
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
                    </div>
                </div>

                <!-- ==================== Communication Tab ==================== -->
                <div class="tab-pane <?= $activeTab === 'communication' ? 'show active' : '' ?>" id="communicationContent">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                            <h5 class="fw-bold mb-0"><i class="fas fa-bullhorn me-2"></i>Announcements</h5>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAnnouncementModal">
                                <i class="fas fa-plus me-2"></i>New Announcement
                            </button>
                        </div>
                        <div class="card-body">
                            <?php if (empty($announcements)): ?>
                                <p class="text-muted">No announcements yet.</p>
                            <?php else: ?>
                                <?php foreach ($announcements as $ann): ?>
                                    <div class="mb-3 p-3 border rounded">
                                        <h6 class="fw-bold"><?= htmlspecialchars($ann->title) ?></h6>
                                        <p class="text-muted small mb-1"><?= htmlspecialchars($ann->content) ?></p>
                                        <small class="text-muted"><?= date('M j, Y', strtotime($ann->createdAt)) ?></small>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- ==================== Settings Tab ==================== -->
                <div class="tab-pane <?= $activeTab === 'settings' ? 'show active' : '' ?>" id="settingsContent">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center py-5">
                            <i class="fas fa-cog fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Course Settings</h5>
                            <p class="text-muted">Additional settings will be available here soon.</p>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- ===== MODALS ===== -->

<!-- Add Lesson Modal -->
<div class="modal fade" id="addLessonModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title fw-bold"><i class="fas fa-plus-circle me-2"></i>Add New Lesson</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label class="form-label fw-semibold">Lesson Title *</label>
                            <input type="text" class="form-control" name="lesson_title" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Duration (min) *</label>
                            <input type="number" class="form-control" name="lesson_duration" min="1" value="10" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Short Description *</label>
                        <textarea class="form-control" name="lesson_description" rows="2" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Lesson Type *</label>
                        <select class="form-select" name="lesson_type" id="add-lesson-type" required>
                            <option value="">Select Type</option>
                            <option value="video">Video</option>
                            <option value="reading">Reading Material</option>
                            <option value="quiz">Quiz</option>
                            <option value="exercise">Exercise</option>
                        </select>
                    </div>

                    <!-- Dynamic fields (Add modal) -->
                    <div class="add-video-url-field" style="display:none;">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Video URL</label>
                            <input type="url" class="form-control" name="video_url" placeholder="https://youtube.com/embed/...">
                        </div>
                    </div>
                    <div class="add-video-upload-field" style="display:none;">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Or Upload Video File</label>
                            <input type="file" class="form-control" name="video_upload" accept="video/*">
                            <div class="form-text">Supported formats: MP4, WebM, Ogg.</div>
                        </div>
                    </div>
                    <div class="add-reading-resources-field" style="display:none;">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Upload Resources (PDF, slides, etc.)</label>
                            <input type="file" class="form-control" name="reading_resources[]" multiple accept=".pdf,.ppt,.pptx,.doc,.docx,.zip">
                            <div class="form-text">Students can download these files.</div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Lesson Content *</label>
                        <textarea class="form-control" name="lesson_content" rows="6" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="add_lesson" class="btn btn-primary"><i class="fas fa-plus me-2"></i>Add Lesson</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Lesson Modal -->
<div class="modal fade" id="editLessonModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title fw-bold"><i class="fas fa-edit me-2"></i>Edit Lesson</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="lesson_id" id="edit_lesson_id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label class="form-label fw-semibold">Lesson Title *</label>
                            <input type="text" class="form-control" id="edit_lesson_title" name="edit_lesson_title" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Duration (min) *</label>
                            <input type="number" class="form-control" id="edit_lesson_duration" name="edit_lesson_duration" min="1" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Short Description *</label>
                        <textarea class="form-control" id="edit_lesson_description" name="edit_lesson_description" rows="2" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Lesson Type *</label>
                        <select class="form-select" id="edit-lesson-type" name="edit_lesson_type" required>
                            <option value="video">Video</option>
                            <option value="reading">Reading Material</option>
                            <option value="quiz">Quiz</option>
                            <option value="exercise">Exercise</option>
                        </select>
                    </div>

                    <!-- Dynamic fields (Edit modal) -->
                    <div class="edit-video-url-field" style="display:none;">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Video URL</label>
                            <input type="url" class="form-control" id="edit_video_url" name="edit_video_url">
                        </div>
                    </div>
                    <div class="edit-video-upload-field" style="display:none;">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Replace Video File</label>
                            <input type="file" class="form-control" name="video_upload" accept="video/*">
                            <div class="form-text">Leave empty to keep current video.</div>
                        </div>
                    </div>
                    <div class="edit-reading-resources-field" style="display:none;">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Replace Resources</label>
                            <input type="file" class="form-control" name="reading_resources[]" multiple accept=".pdf,.ppt,.pptx,.doc,.docx,.zip">
                            <div class="form-text">Leave empty to keep current resources.</div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Lesson Content *</label>
                        <textarea class="form-control" id="edit_lesson_content" name="edit_lesson_content" rows="6" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="update_lesson" class="btn btn-primary"><i class="fas fa-save me-2"></i>Update Lesson</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Announcement Modal -->
<div class="modal fade" id="addAnnouncementModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title fw-bold"><i class="fas fa-bullhorn me-2"></i>New Announcement</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Title *</label>
                        <input type="text" class="form-control" name="announcement_title" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Message *</label>
                        <textarea class="form-control" name="announcement_content" rows="4" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="add_announcement" class="btn btn-primary"><i class="fas fa-paper-plane me-2"></i>Publish</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Publish Modal -->
<div class="modal fade" id="publishModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title fw-bold"><i class="fas fa-rocket me-2"></i>Publish Course</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <p>Publish "<strong><?= htmlspecialchars($course['title'] ?? '') ?></strong>"?</p>
                    <p class="text-muted small">It will become available to all students.</p>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="confirmPublish" required>
                        <label class="form-check-label" for="confirmPublish">I confirm this course meets quality standards</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Cancel</button>
                    <input type="hidden" name="status" value="published">
                    <button type="submit" name="update_status" class="btn btn-success" id="publishBtn" disabled>
                        <i class="fas fa-rocket me-2"></i>Publish Course
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title fw-bold"><i class="fas fa-exclamation-triangle me-2"></i>Delete Course</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <p>Delete "<strong><?= htmlspecialchars($course['title'] ?? '') ?></strong>"?</p>
                    <p class="text-danger small">This action cannot be undone.</p>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="confirmDelete" required>
                        <label class="form-check-label" for="confirmDelete">I understand this is permanent</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="delete_course" class="btn btn-danger" id="deleteBtn" disabled>
                        <i class="fas fa-trash me-2"></i>Delete Course
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Tab navigation
    function switchTab(tab) {
        window.location.href = '?course_id=<?= $courseId ?>&tab=' + tab;
    }
    const mobileSelect = document.getElementById('mobileTabSelector');
    if (mobileSelect) mobileSelect.addEventListener('change', e => switchTab(e.target.value));
    document.querySelectorAll('button[data-tab]').forEach(btn => {
        btn.addEventListener('click', () => switchTab(btn.getAttribute('data-tab')));
    });

    // Dynamic fields for lesson type – ADD modal
    const addType = document.getElementById('add-lesson-type');
    const addFields = {
        videoUrl: document.querySelector('.add-video-url-field'),
        videoUpload: document.querySelector('.add-video-upload-field'),
        reading: document.querySelector('.add-reading-resources-field')
    };
    function updateAddFields() {
        const val = addType.value;
        addFields.videoUrl.style.display = val === 'video' ? 'block' : 'none';
        addFields.videoUpload.style.display = val === 'video' ? 'block' : 'none';
        addFields.reading.style.display = val === 'reading' ? 'block' : 'none';
    }
    addType.addEventListener('change', updateAddFields);
    updateAddFields(); // initial state

    // Dynamic fields for lesson type – EDIT modal
    const editType = document.getElementById('edit-lesson-type');
    const editFields = {
        videoUrl: document.querySelector('.edit-video-url-field'),
        videoUpload: document.querySelector('.edit-video-upload-field'),
        reading: document.querySelector('.edit-reading-resources-field')
    };
    function updateEditFields() {
        const val = editType.value;
        editFields.videoUrl.style.display = val === 'video' ? 'block' : 'none';
        editFields.videoUpload.style.display = val === 'video' ? 'block' : 'none';
        editFields.reading.style.display = val === 'reading' ? 'block' : 'none';
    }
    editType.addEventListener('change', updateEditFields);
    updateEditFields(); // initial state

    // Edit lesson button – populate form
    document.querySelectorAll('.edit-lesson').forEach(btn => {
        btn.addEventListener('click', function () {
            const lesson = JSON.parse(this.getAttribute('data-lesson'));
            document.getElementById('edit_lesson_id').value = lesson.id;
            document.getElementById('edit_lesson_title').value = lesson.title;
            document.getElementById('edit_lesson_duration').value = lesson.duration;
            document.getElementById('edit_lesson_description').value = lesson.description || '';
            document.getElementById('edit-lesson-type').value = lesson.type;
            document.getElementById('edit_video_url').value = lesson.video_url || '';
            document.getElementById('edit_lesson_content').value = lesson.content || '';

            // Trigger change to show/hide correct fields
            const evt = new Event('change');
            document.getElementById('edit-lesson-type').dispatchEvent(evt);

            new bootstrap.Modal(document.getElementById('editLessonModal')).show();
        });
    });

    // Confirmation toggles
    const confirmPublish = document.getElementById('confirmPublish');
    const publishBtn = document.getElementById('publishBtn');
    if (confirmPublish && publishBtn) confirmPublish.addEventListener('change', () => publishBtn.disabled = !confirmPublish.checked);

    const confirmDelete = document.getElementById('confirmDelete');
    const deleteBtn = document.getElementById('deleteBtn');
    if (confirmDelete && deleteBtn) confirmDelete.addEventListener('change', () => deleteBtn.disabled = !confirmDelete.checked);

    // Character counter
    const shortDesc = document.getElementById('short_description');
    const countSpan = document.getElementById('shortDescCount');
    if (shortDesc && countSpan) shortDesc.addEventListener('input', () => countSpan.textContent = shortDesc.value.length);
});
</script>

<?php require 'view/partial/footer.php'; ?>