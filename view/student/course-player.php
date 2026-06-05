<?php
$page_title = $currentLesson ? $currentLesson['title'] . " - " . $courseArray['title'] : $courseArray['title'];
require 'view/partial/nav.php';
?>

<div class="container-fluid">
    <div class="row">
        <!-- Main Video Content -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="fw-bold mb-1"><?= htmlspecialchars($courseArray['title']) ?></h5>
                            <h2 class="h4 mb-0"><?= $currentLesson ? htmlspecialchars($currentLesson['title']) : 'No Lesson Selected' ?></h2>
                        </div>
                        <div class="d-flex gap-2">
                            <?php if ($enrollmentArray && $currentLesson): ?>
                                <form method="POST">
                                    <input type="hidden" name="mark_complete" value="1">
                                    <button type="submit" class="btn btn-<?= in_array($lessonId, $enrollmentArray['completed_lessons'] ?? []) ? 'success' : 'outline-success' ?>">
                                        <i class="fas fa-check me-2"></i>
                                        <?= in_array($lessonId, $enrollmentArray['completed_lessons'] ?? []) ? 'Completed' : 'Mark Complete' ?>
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    <?php if ($currentLesson): ?>
                        <?php if ($currentLesson['type'] === 'video' && !empty($currentLesson['video_url'])): ?>
                            <!-- Video Player -->
                            <div class="video-player-container">
                                <?php
                                $videoUrl = $currentLesson['video_url'];
                                $isYouTube = false;
                                $youTubeId = '';

                                if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $videoUrl, $matches)) {
                                    $isYouTube = true;
                                    $youTubeId = $matches[1];
                                } elseif (strpos($videoUrl, 'youtube.com/embed/') !== false) {
                                    $isYouTube = true;
                                    $youTubeId = substr($videoUrl, strrpos($videoUrl, '/') + 1);
                                }
                                ?>

                                <?php if ($isYouTube && $youTubeId): ?>
                                    <div class="ratio ratio-16x9">
                                        <iframe
                                            src="https://www.youtube.com/embed/<?= $youTubeId ?>?rel=0&modestbranding=1"
                                            frameborder="0"
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                            allowfullscreen
                                            class="w-100 h-100">
                                        </iframe>
                                    </div>
                                <?php elseif (strpos($videoUrl, 'vimeo.com') !== false): ?>
                                    <div class="ratio ratio-16x9">
                                        <iframe
                                            src="<?= htmlspecialchars($videoUrl) ?>"
                                            frameborder="0"
                                            allow="autoplay; fullscreen; picture-in-picture"
                                            allowfullscreen
                                            class="w-100 h-100">
                                        </iframe>
                                    </div>
                                <?php else: ?>
                                    <div class="ratio ratio-16x9">
                                        <video
                                            controls
                                            class="w-100 h-100"
                                            poster="<?= htmlspecialchars($courseArray['thumbnail'] ?? '') ?>">
                                            <source src="<?= htmlspecialchars($videoUrl) ?>" type="video/mp4">
                                            Your browser does not support the video tag.
                                        </video>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php elseif ($currentLesson['type'] === 'text'): ?>
                            <div class="p-4">
                                <div class="lesson-content">
                                    <?= nl2br(htmlspecialchars($currentLesson['content'] ?? 'No content available.')) ?>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="p-4 text-center">
                                <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Content Not Available</h5>
                                <p class="text-muted">This lesson type is not supported in the player.</p>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="p-4 text-center">
                            <i class="fas fa-graduation-cap fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No Lesson Selected</h5>
                            <p class="text-muted">Please select a lesson from the sidebar to begin.</p>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if ($currentLesson && !empty($currentLesson['description'])): ?>
                    <div class="card-footer bg-light border-0">
                        <h6 class="fw-bold mb-2">About this lesson:</h6>
                        <p class="mb-0 text-dark"><?= nl2br(htmlspecialchars($currentLesson['description'])) ?></p>
                    </div>
                <?php endif; ?>
            </div>

            <?php if ($currentLesson && !empty($currentLesson['resources'])): ?>
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="fw-bold mb-0">
                            <i class="fas fa-download me-2"></i>Lesson Resources
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group">
                            <?php foreach ($currentLesson['resources'] as $resource): ?>
                                <a href="<?= $resource['url'] ?>" download class="list-group-item list-group-item-action" target="_blank">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <i class="fas fa-file-<?= $resource['type'] ?? 'download' ?> me-2 text-primary"></i>
                                            <strong><?= htmlspecialchars($resource['name'] ?? 'Download') ?></strong>
                                        </div>
                                        <small class="text-muted"><?= formatFileSize($resource['size'] ?? 0) ?></small>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="fw-bold mb-0">
                        <i class="fas fa-sticky-note me-2"></i>My Notes
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <input type="hidden" name="save_note" value="1">
                        <input type="hidden" name="timestamp" value="00:00" id="noteTimestamp">
                        <div class="mb-3">
                            <label for="note_content" class="form-label fw-semibold">Add Note</label>
                            <textarea class="form-control" id="note_content" name="note_content" rows="4" placeholder="Take notes about this lesson..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Save Note
                        </button>
                    </form>

                    <?php if (!empty($notes)): ?>
                        <div class="mt-4">
                            <h6 class="fw-semibold mb-3">Your Notes</h6>
                            <?php foreach ($notes as $note): ?>
                                <div class="card bg-light mb-3">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <small class="text-muted">
                                                <?= date('M j, g:i A', strtotime($note['created_at'])) ?>
                                                <?php if ($note['timestamp'] !== '00:00'): ?>
                                                    • <span class="badge bg-secondary"><?= $note['timestamp'] ?></span>
                                                <?php endif; ?>
                                            </small>
                                            <form method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this note?');">
                                                <input type="hidden" name="delete_note" value="1">
                                                <input type="hidden" name="note_id" value="<?= $note['id'] ?>">
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                        <p class="mb-0"><?= nl2br(htmlspecialchars($note['content'])) ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-sticky-note fa-2x text-muted mb-3"></i>
                            <p class="text-muted mb-0">No notes yet. Add your first note above!</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Sidebar - Lesson Navigation -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm sticky-top" style="top: 100px;">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="fw-bold mb-0">Course Content</h5>
                    <div class="progress mt-2" style="height: 6px;">
                        <div class="progress-bar bg-success" style="width: <?= $enrollmentArray['progress'] ?? 0 ?>%"></div>
                    </div>
                    <small class="text-muted"><?= $enrollmentArray['progress'] ?? 0 ?>% Complete</small>
                </div>

                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <?php if (empty($lessons)): ?>
                            <div class="list-group-item text-center py-4">
                                <i class="fas fa-graduation-cap fa-2x text-muted mb-2"></i>
                                <p class="text-muted mb-0">No lessons available yet.</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($lessons as $index => $lesson): ?>
                                <a href="/course-player?course_id=<?= $courseId ?>&lesson_id=<?= $lesson['id'] ?>"
                                    class="list-group-item list-group-item-action border-0 py-3 <?= $lesson['id'] == $lessonId ? 'active' : '' ?>">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <div class="d-flex align-items-center mb-1">
                                                <?php if (in_array($lesson['id'], $enrollmentArray['completed_lessons'] ?? [])): ?>
                                                    <i class="fas fa-check-circle text-success me-2"></i>
                                                <?php else: ?>
                                                    <span class="text-muted me-2"><?= $index + 1 ?>.</span>
                                                <?php endif; ?>
                                                <span class="fw-semibold <?= $lesson['id'] == $lessonId ? 'text-white' : 'text-dark' ?>">
                                                    <?= htmlspecialchars($lesson['title']) ?>
                                                </span>
                                            </div>
                                            <div class="d-flex gap-2">
                                                <small class="<?= $lesson['id'] == $lessonId ? 'text-white-50' : 'text-muted' ?>">
                                                    <i class="fas fa-<?= $lesson['type'] === 'video' ? 'play-circle' : 'file-text' ?> me-1"></i>
                                                    <?= ucfirst($lesson['type']) ?>
                                                </small>
                                                <small class="<?= $lesson['id'] == $lessonId ? 'text-white-50' : 'text-muted' ?>">
                                                    <i class="fas fa-clock me-1"></i>
                                                    <?= formatDuration($lesson['duration'] ?? 0) ?>
                                                </small>
                                            </div>
                                        </div>
                                        <?php if ($lesson['id'] == $lessonId): ?>
                                            <i class="fas fa-play text-white ms-2"></i>
                                        <?php endif; ?>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if ($currentLesson): ?>
                    <div class="card-footer bg-white border-0 py-3">
                        <div class="d-flex justify-content-between">
                            <?php if ($prevLesson): ?>
                                <a href="/course-player?course_id=<?= $courseId ?>&lesson_id=<?= $prevLesson['id'] ?>" class="btn btn-outline-primary">
                                    <i class="fas fa-arrow-left me-2"></i>Previous
                                </a>
                            <?php else: ?>
                                <span></span>
                            <?php endif; ?>

                            <?php if ($nextLesson): ?>
                                <a href="/course-player?course_id=<?= $courseId ?>&lesson_id=<?= $nextLesson['id'] ?>" class="btn btn-primary">
                                    Next <i class="fas fa-arrow-right ms-2"></i>
                                </a>
                            <?php else: ?>
                                <a href="/course/<?= $courseId ?>" class="btn btn-success">
                                    <i class="fas fa-check me-2"></i>Finish Course
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const video = document.querySelector('video');
        if (video) {
            video.addEventListener('timeupdate', function() {
                const minutes = Math.floor(video.currentTime / 60);
                const seconds = Math.floor(video.currentTime % 60);
                const timestamp = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                document.getElementById('noteTimestamp').value = timestamp;
            });
        }

        const textareas = document.querySelectorAll('textarea');
        textareas.forEach(textarea => {
            textarea.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = (this.scrollHeight) + 'px';
            });
        });
    });
</script>