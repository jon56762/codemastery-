<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?></title>
    <link href="/assets/css/boostrap/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/font-awasome/css/all.css">
    <style>
        .course-player-container {
            height: 100vh;
            padding-top: 56px; /* Account for fixed navbar */
        }
        
        .sidebar {
            height: calc(100vh - 56px);
            overflow-y: auto;
            transition: transform 0.3s ease;
        }
        
        .main-content {
            height: calc(100vh - 56px);
            overflow-y: auto;
        }
        
        @media (max-width: 991.98px) {
            .sidebar {
                position: fixed;
                top: 56px;
                left: 0;
                width: 300px;
                transform: translateX(-100%);
                z-index: 1040;
                background: white;
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .sidebar-overlay {
                position: fixed;
                top: 56px;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0,0,0,0.5);
                z-index: 1039;
                display: none;
            }
            
            .sidebar-overlay.show {
                display: block;
            }
        }
        
        .video-container {
            background: #000;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .lesson-content {
            line-height: 1.7;
        }
        
        .notes-list {
            max-height: 300px;
            overflow-y: auto;
        }
        
        .progress {
            height: 6px;
        }
    </style>
</head>
<body>
    <!-- Mobile Sidebar Toggle -->
    <div class="d-lg-none fixed-top" style="top: 56px; z-index: 1038;">
        <div class="bg-light border-bottom p-2">
            <button class="btn btn-outline-dark btn-sm" id="sidebarToggle">
                <i class="fas fa-bars me-1"></i> Lessons
            </button>
            <span class="ms-2 fw-semibold"><?= htmlspecialchars($currentLesson['title']) ?></span>
        </div>
    </div>

    <!-- Sidebar Overlay for Mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="course-player-container">
        <div class="row g-0 h-100">
            <!-- Lesson Navigation Sidebar -->
            <div class="col-lg-3 col-xl-2 sidebar bg-light border-end" id="sidebar">
                <div class="d-flex flex-column h-100">
                    <!-- Course Header -->
                    <div class="p-3 border-bottom bg-white">
                        <div class="d-flex align-items-center">
                            <a href="/course/<?= $course['id'] ?>" class="btn btn-sm btn-outline-dark me-2">
                                <i class="fas fa-arrow-left"></i>
                            </a>
                            <div class="flex-grow-1">
                                <h6 class="fw-bold mb-0 text-truncate"><?= htmlspecialchars($course['title']) ?></h6>
                                <small class="text-muted"><?= count($course['curriculum']) ?> lessons</small>
                            </div>
                        </div>
                        
                        <!-- Progress -->
                        <div class="mt-3">
                            <div class="d-flex justify-content-between mb-1">
                                <small class="text-muted">Course Progress</small>
                                <small class="fw-semibold"><?= $enrollment['progress'] ?>%</small>
                            </div>
                            <div class="progress">
                                <div class="progress-bar bg-success" style="width: <?= $enrollment['progress'] ?>%"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Curriculum -->
                    <div class="flex-grow-1 overflow-auto">
                        <div class="p-3">
                            <h6 class="fw-bold mb-3">Course Content</h6>
                            <div class="list-group list-group-flush">
                                <?php foreach ($course['curriculum'] as $index => $lesson): 
                                    $isCompleted = in_array($lesson['id'], $enrollment['completed_lessons']);
                                    $isCurrent = $lesson['id'] == $lessonId;
                                ?>
                                    <a href="/course-player?course_id=<?= $course['id'] ?>&lesson_id=<?= $lesson['id'] ?>" 
                                       class="list-group-item list-group-item-action border-0 py-3 <?= $isCurrent ? 'bg-primary text-white' : '' ?>">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                <?php if ($isCompleted): ?>
                                                    <i class="fas fa-check-circle text-success"></i>
                                                <?php else: ?>
                                                    <i class="far fa-circle text-muted"></i>
                                                <?php endif; ?>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="fw-semibold small"><?= ($index + 1) . '. ' . htmlspecialchars($lesson['title']) ?></div>
                                                <small class="<?= $isCurrent ? 'text-white-50' : 'text-muted' ?>">
                                                    <?= $lesson['duration'] ?? '5 min' ?>
                                                    <?php if ($lesson['type'] ?? 'video' === 'video'): ?>
                                                        • <i class="fas fa-play-circle"></i>
                                                    <?php else: ?>
                                                        • <i class="fas fa-file-text"></i>
                                                    <?php endif; ?>
                                                </small>
                                            </div>
                                            <?php if ($isCurrent): ?>
                                                <i class="fas fa-play text-white-50"></i>
                                            <?php endif; ?>
                                        </div>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="col-lg-9 col-xl-10 main-content">
                <div class="d-flex flex-column h-100">
                    <!-- Lesson Header -->
                    <div class="border-bottom bg-white p-3">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div class="flex-grow-1">
                                <h4 class="fw-bold mb-1"><?= htmlspecialchars($currentLesson['title']) ?></h4>
                                <p class="text-muted mb-0 d-none d-md-block"><?= htmlspecialchars($currentLesson['description'] ?? '') ?></p>
                            </div>
                            <div class="d-flex gap-2 flex-wrap">
                                <!-- Mark Complete Button -->
                                <form method="POST" class="d-inline">
                                    <input type="hidden" name="lesson_id" value="<?= $currentLesson['id'] ?>">
                                    <?php if ($isLessonCompleted): ?>
                                        <button type="submit" name="mark_complete" value="incomplete" 
                                                class="btn btn-success btn-sm">
                                            <i class="fas fa-check-circle me-1"></i>Completed
                                        </button>
                                    <?php else: ?>
                                        <button type="submit" name="mark_complete" value="complete" 
                                                class="btn btn-outline-dark btn-sm">
                                            <i class="far fa-circle me-1"></i>Mark Complete
                                        </button>
                                    <?php endif; ?>
                                </form>
                                
                                <!-- Navigation Buttons -->
                                <div class="btn-group">
                                    <?php if ($prevLesson): ?>
                                        <a href="/course-player?course_id=<?= $course['id'] ?>&lesson_id=<?= $prevLesson['id'] ?>" 
                                           class="btn btn-outline-dark btn-sm">
                                            <i class="fas fa-chevron-left"></i> 
                                            <span class="d-none d-sm-inline">Previous</span>
                                        </a>
                                    <?php endif; ?>
                                    
                                    <?php if ($nextLesson): ?>
                                        <a href="/course-player?course_id=<?= $course['id'] ?>&lesson_id=<?= $nextLesson['id'] ?>" 
                                           class="btn btn-dark btn-sm">
                                            <span class="d-none d-sm-inline">Next</span>
                                            <i class="fas fa-chevron-right"></i>
                                        </a>
                                    <?php else: ?>
                                        <a href="/course/<?= $course['id'] ?>" 
                                           class="btn btn-success btn-sm">
                                            <span class="d-none d-sm-inline">Finish Course</span>
                                            <i class="fas fa-flag-checkered"></i>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Video Player and Content -->
                    <div class="flex-grow-1 overflow-auto">
                        <div class="row h-100">
                            <!-- Video/Content Area -->
                            <div class="col-xl-8 border-end">
                                <div class="p-3 p-md-4">
                                    <?php if ($currentLesson['type'] === 'video'): ?>
                                        <!-- Video Player -->
                                        <div class="video-container ratio ratio-16x9 mb-4">
                                            <div class="d-flex align-items-center justify-content-center h-100 text-white">
                                                <div class="text-center">
                                                    <i class="fas fa-play-circle fa-4x mb-3"></i>
                                                    <h5>Video Lesson</h5>
                                                    <p class="mb-0"><?= htmlspecialchars($currentLesson['title']) ?></p>
                                                    <small class="text-white-50">Duration: <?= $currentLesson['duration'] ?? '10:00' ?></small>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Video Controls -->
                                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
                                            <div class="btn-group">
                                                <button class="btn btn-outline-dark btn-sm">
                                                    <i class="fas fa-backward"></i> 
                                                    <span class="d-none d-sm-inline">10s</span>
                                                </button>
                                                <button class="btn btn-outline-dark btn-sm">
                                                    <i class="fas fa-forward"></i> 
                                                    <span class="d-none d-sm-inline">10s</span>
                                                </button>
                                            </div>
                                            <div class="d-flex gap-2">
                                                <button class="btn btn-outline-dark btn-sm" id="fullscreenBtn">
                                                    <i class="fas fa-expand"></i> 
                                                    <span class="d-none d-sm-inline">Fullscreen</span>
                                                </button>
                                                <button class="btn btn-outline-dark btn-sm">
                                                    <i class="fas fa-cog"></i> 
                                                    <span class="d-none d-sm-inline">Settings</span>
                                                </button>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <!-- Text Content -->
                                        <div class="card border-0 bg-light">
                                            <div class="card-body">
                                                <h5 class="card-title fw-bold"><?= htmlspecialchars($currentLesson['title']) ?></h5>
                                                <div class="lesson-content">
                                                    <?= nl2br(htmlspecialchars($currentLesson['content'] ?? 'This lesson contains reading material and exercises.')) ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Lesson Description -->
                                    <div class="mt-4">
                                        <h6 class="fw-bold mb-3">About this lesson</h6>
                                        <p class="text-muted"><?= htmlspecialchars($currentLesson['description'] ?? 'Learn essential concepts and techniques in this comprehensive lesson.') ?></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Notes and Discussion Sidebar -->
                            <div class="col-xl-4">
                                <div class="p-3 p-md-4">
                                    <!-- Notes Section -->
                                    <div class="mb-5">
                                        <h6 class="fw-bold mb-3">
                                            <i class="fas fa-sticky-note me-2"></i>My Notes
                                        </h6>
                                        
                                        <!-- Add Note Form -->
                                        <form method="POST" class="mb-3">
                                            <div class="mb-2">
                                                <textarea class="form-control form-control-sm" name="note_content" 
                                                          rows="3" placeholder="Add a note at this point in the lesson..."></textarea>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <small class="text-muted">Timestamp: <span id="currentTimestamp">00:00</span></small>
                                                <button type="submit" name="save_note" class="btn btn-dark btn-sm">
                                                    <i class="fas fa-save me-1"></i>Save
                                                </button>
                                            </div>
                                            <input type="hidden" name="note_timestamp" id="noteTimestamp" value="00:00">
                                        </form>

                                        <!-- Existing Notes -->
                                        <div class="notes-list">
                                            <?php if (empty($lessonNotes)): ?>
                                                <div class="text-center py-3">
                                                    <i class="fas fa-sticky-note fa-2x text-muted mb-2"></i>
                                                    <p class="text-muted small mb-0">No notes yet. Add your first note!</p>
                                                </div>
                                            <?php else: ?>
                                                <?php foreach ($lessonNotes as $note): ?>
                                                    <div class="card border-0 bg-light mb-2">
                                                        <div class="card-body p-3">
                                                            <div class="d-flex justify-content-between align-items-start mb-1">
                                                                <small class="text-muted"><?= $note['timestamp'] ?></small>
                                                                <form method="POST" class="d-inline" onsubmit="return confirm('Delete this note?')">
                                                                    <input type="hidden" name="delete_note" value="<?= $note['id'] ?>">
                                                                    <button type="submit" class="btn btn-sm btn-outline-danger p-1">
                                                                        <i class="fas fa-trash fa-xs"></i>
                                                                    </button>
                                                                </form>
                                                            </div>
                                                            <p class="small mb-0"><?= nl2br(htmlspecialchars($note['content'])) ?></p>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <!-- Discussion Section -->
                                    <div>
                                        <h6 class="fw-bold mb-3">
                                            <i class="fas fa-comments me-2"></i>Lesson Discussion
                                        </h6>
                                        <div class="text-center py-4">
                                            <i class="fas fa-comments fa-2x text-muted mb-2"></i>
                                            <p class="text-muted small mb-2">Discussion feature coming soon!</p>
                                            <small class="text-muted">Ask questions and share insights with other students.</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Mobile sidebar toggle
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('show');
                    sidebarOverlay.classList.toggle('show');
                });
            }
            
            if (sidebarOverlay) {
                sidebarOverlay.addEventListener('click', function() {
                    sidebar.classList.remove('show');
                    sidebarOverlay.classList.remove('show');
                });
            }
            
            // Fullscreen functionality
            const fullscreenBtn = document.getElementById('fullscreenBtn');
            if (fullscreenBtn) {
                fullscreenBtn.addEventListener('click', function() {
                    const elem = document.documentElement;
                    if (elem.requestFullscreen) {
                        elem.requestFullscreen();
                    }
                });
            }
            
            // Note timestamp simulation
            const noteTextarea = document.querySelector('textarea[name="note_content"]');
            const timestampInput = document.getElementById('noteTimestamp');
            const timestampDisplay = document.getElementById('currentTimestamp');
            
            if (noteTextarea) {
                noteTextarea.addEventListener('focus', function() {
                    // Simulate current video time
                    const minutes = Math.floor(Math.random() * 10);
                    const seconds = Math.floor(Math.random() * 60);
                    const timestamp = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                    
                    timestampInput.value = timestamp;
                    timestampDisplay.textContent = timestamp;
                });
            }
            
            // Auto-resize textareas
            const textareas = document.querySelectorAll('textarea');
            textareas.forEach(textarea => {
                textarea.addEventListener('input', function() {
                    this.style.height = 'auto';
                    this.style.height = (this.scrollHeight) + 'px';
                });
            });
        });
    </script>
</body>
</html>