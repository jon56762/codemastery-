<div class="container-fluid py-4">
    <div class="row">
        <!-- Left Sidebar - Navigation -->
        <div class="col-lg-3">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <a href="#personal-info" class="list-group-item list-group-item-action border-0 active">
                            <i class="fas fa-user me-2"></i>Personal Information
                        </a>
                        <a href="#learning-goals" class="list-group-item list-group-item-action border-0">
                            <i class="fas fa-bullseye me-2"></i>Learning Goals
                        </a>
                        <a href="#notifications" class="list-group-item list-group-item-action border-0">
                            <i class="fas fa-bell me-2"></i>Notifications
                        </a>
                        <a href="#privacy" class="list-group-item list-group-item-action border-0">
                            <i class="fas fa-shield-alt me-2"></i>Privacy & Security
                        </a>
                        <a href="#achievements" class="list-group-item list-group-item-action border-0">
                            <i class="fas fa-trophy me-2"></i>Achievements
                        </a>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <img src="<?= getInstructorAvatar($user['name'], 80) ?>" 
                             alt="<?= htmlspecialchars($user['name']) ?>" 
                             class="rounded-circle" width="80" height="80">
                    </div>
                    <h6 class="fw-bold mb-1"><?= htmlspecialchars($user['name']) ?></h6>
                    <p class="text-muted small mb-3">Student since <?= date('M Y', strtotime($user['created_at'])) ?></p>
                    
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="h5 fw-bold text-dark mb-1"><?= count($enrollments) ?></div>
                            <small class="text-muted">Courses</small>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="h5 fw-bold text-success mb-1"><?= count($completedCourses) ?></div>
                            <small class="text-muted">Completed</small>
                        </div>
                    </div>
                    
                    <div class="progress mb-2" style="height: 6px;">
                        <div class="progress-bar bg-success" 
                             style="width: <?= count($enrollments) > 0 ? (count($completedCourses) / count($enrollments)) * 100 : 0 ?>%">
                        </div>
                    </div>
                    <small class="text-muted">
                        <?= count($completedCourses) ?> of <?= count($enrollments) ?> courses completed
                    </small>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-9">
            <!-- Personal Information -->
            <div class="card border-0 shadow-sm mb-4" id="personal-info">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="fw-bold mb-0">
                        <i class="fas fa-user me-2"></i>Personal Information
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label fw-semibold">Full Name</label>
                                <input type="text" class="form-control" id="name" name="name" 
                                       value="<?= htmlspecialchars($user['name'] ?? '') ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label fw-semibold">Email Address</label>
                                <input type="email" class="form-control" id="email" 
                                       value="<?= htmlspecialchars($user['email'] ?? '') ?>" disabled>
                                <small class="text-muted">Email cannot be changed</small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="bio" class="form-label fw-semibold">Bio</label>
                            <textarea class="form-control" id="bio" name="bio" rows="3" 
                                      placeholder="Tell us about yourself and your learning journey..."><?= htmlspecialchars($user['bio'] ?? '') ?></textarea>
                        </div>

                        <div class="mb-4">
                            <label for="skills" class="form-label fw-semibold">Skills & Interests</label>
                            <input type="text" class="form-control" id="skills" name="skills" 
                                   value="<?= htmlspecialchars(implode(', ', $user['skills'] ?? [])) ?>" 
                                   placeholder="e.g., JavaScript, Python, Web Development">
                            <small class="text-muted">Separate skills with commas</small>
                        </div>

                        <button type="submit" name="update_profile" class="btn btn-dark">
                            <i class="fas fa-save me-2"></i>Save Changes
                        </button>
                    </form>
                </div>
            </div>

            <!-- Learning Goals -->
            <div class="card border-0 shadow-sm mb-4" id="learning-goals">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="fw-bold mb-0">
                        <i class="fas fa-bullseye me-2"></i>Learning Goals
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label for="learning_goals" class="form-label fw-semibold">My Learning Goals</label>
                            <textarea class="form-control" id="learning_goals" name="learning_goals" rows="4"
                                      placeholder="What do you want to achieve with your learning? Set clear goals to stay motivated..."><?= htmlspecialchars($user['learning_goals'] ?? '') ?></textarea>
                        </div>
                        <button type="submit" name="update_profile" class="btn btn-dark">
                            <i class="fas fa-save me-2"></i>Save Goals
                        </button>
                    </form>

                    <!-- Goal Progress -->
                    <div class="mt-4 pt-3 border-top">
                        <h6 class="fw-semibold mb-3">Goal Progress</h6>
                        
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="small">Complete first course</span>
                                <span class="small fw-semibold">
                                    <?= count($completedCourses) >= 1 ? '✅ Completed' : '🟡 In Progress' ?>
                                </span>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar <?= count($completedCourses) >= 1 ? 'bg-success' : 'bg-warning' ?>" 
                                     style="width: <?= count($completedCourses) >= 1 ? 100 : 50 ?>%"></div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="small">Complete 3 courses</span>
                                <span class="small fw-semibold">
                                    <?= count($completedCourses) >= 3 ? '✅ Completed' : (count($completedCourses) . '/3') ?>
                                </span>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar <?= count($completedCourses) >= 3 ? 'bg-success' : 'bg-warning' ?>" 
                                     style="width: <?= (count($completedCourses) / 3) * 100 ?>%"></div>
                            </div>
                        </div>

                        <div class="mb-0">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="small">Spend 10 hours learning</span>
                                <span class="small fw-semibold">
                                    <?= $totalLearningTime >= 600 ? '✅ Completed' : (round($totalLearningTime / 60, 1) . '/10 hours') ?>
                                </span>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar <?= $totalLearningTime >= 600 ? 'bg-success' : 'bg-warning' ?>" 
                                     style="width: <?= min(($totalLearningTime / 600) * 100, 100) ?>%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notification Preferences -->
            <div class="card border-0 shadow-sm mb-4" id="notifications">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="fw-bold mb-0">
                        <i class="fas fa-bell me-2"></i>Notification Preferences
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="email_notifications" 
                                       name="email_notifications" checked>
                                <label class="form-check-label fw-semibold" for="email_notifications">
                                    Email Notifications
                                </label>
                                <div class="form-text">Receive important updates via email</div>
                            </div>

                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="course_updates" 
                                       name="course_updates" checked>
                                <label class="form-check-label fw-semibold" for="course_updates">
                                    Course Updates
                                </label>
                                <div class="form-text">Get notified when your courses are updated</div>
                            </div>

                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="newsletter" 
                                       name="newsletter" checked>
                                <label class="form-check-label fw-semibold" for="newsletter">
                                    Newsletter
                                </label>
                                <div class="form-text">Receive weekly learning tips and new course announcements</div>
                            </div>
                        </div>

                        <button type="submit" name="update_notifications" class="btn btn-dark">
                            <i class="fas fa-save me-2"></i>Save Preferences
                        </button>
                    </form>
                </div>
            </div>

            <!-- Privacy & Security -->
            <div class="card border-0 shadow-sm mb-4" id="privacy">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="fw-bold mb-0">
                        <i class="fas fa-shield-alt me-2"></i>Privacy & Security
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label for="profile_visibility" class="form-label fw-semibold">Profile Visibility</label>
                            <select class="form-select" id="profile_visibility" name="profile_visibility">
                                <option value="public">Public - Anyone can see my profile</option>
                                <option value="students">Only CodeMastery Students</option>
                                <option value="private">Private - Only I can see my profile</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="show_progress" 
                                       name="show_progress" checked>
                                <label class="form-check-label fw-semibold" for="show_progress">
                                    Show Learning Progress
                                </label>
                                <div class="form-text">Allow others to see my course progress and achievements</div>
                            </div>

                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="show_achievements" 
                                       name="show_achievements" checked>
                                <label class="form-check-label fw-semibold" for="show_achievements">
                                    Show Achievements
                                </label>
                                <div class="form-text">Display my earned achievements on my public profile</div>
                            </div>
                        </div>

                        <button type="submit" name="update_privacy" class="btn btn-dark">
                            <i class="fas fa-save me-2"></i>Save Privacy Settings
                        </button>
                    </form>

                    <!-- Security Actions -->
                    <div class="mt-4 pt-3 border-top">
                        <h6 class="fw-semibold mb-3">Security</h6>
                        <div class="d-flex gap-2">
                            <a href="#" class="btn btn-outline-dark btn-sm">
                                <i class="fas fa-key me-1"></i>Change Password
                            </a>
                            <a href="#" class="btn btn-outline-dark btn-sm">
                                <i class="fas fa-envelope me-1"></i>Update Email
                            </a>
                            <a href="#" class="btn btn-outline-danger btn-sm">
                                <i class="fas fa-user-slash me-1"></i>Delete Account
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Achievements -->
            <div class="card border-0 shadow-sm" id="achievements">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="fw-bold mb-0">
                        <i class="fas fa-trophy me-2"></i>Achievements
                    </h5>
                </div>
                <div class="card-body">
                    <?php $achievements = getStudentAchievements($userId); ?>
                    <?php if (empty($achievements)): ?>
                        <div class="text-center py-4">
                            <i class="fas fa-trophy fa-3x text-muted mb-3"></i>
                            <h6 class="fw-semibold">No achievements yet</h6>
                            <p class="text-muted small mb-0">Complete courses to earn achievements and badges!</p>
                        </div>
                    <?php else: ?>
                        <div class="row">
                            <?php foreach ($achievements as $achievement): ?>
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="card border-0 bg-light h-100">
                                        <div class="card-body text-center p-3">
                                            <div class="achievement-badge bg-warning rounded-circle d-inline-flex align-items-center justify-content-center mb-2"
                                                 style="width: 60px; height: 60px;">
                                                <i class="fas fa-<?= $achievement['icon'] ?> fa-lg text-white"></i>
                                            </div>
                                            <h6 class="fw-semibold mb-1"><?= htmlspecialchars($achievement['title']) ?></h6>
                                            <p class="text-muted small mb-2"><?= htmlspecialchars($achievement['description']) ?></p>
                                            <small class="text-muted">
                                                Earned <?= date('M j, Y', strtotime($achievement['earned_at'])) ?>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.list-group-item.active {
    background-color: #000 !important;
    border-color: #000 !important;
}

.achievement-badge {
    background: linear-gradient(135deg, #ffc107, #fd7e14);
}

.form-check-input:checked {
    background-color: #000;
    border-color: #000;
}

.card-header {
    border-bottom: 2px solid #000;
}

.progress {
    background-color: #f8f9fa;
}
</style>

<script>
// Smooth scrolling for navigation
document.addEventListener('DOMContentLoaded', function() {
    const navLinks = document.querySelectorAll('.list-group-item[href^="#"]');
    
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Remove active class from all links
            navLinks.forEach(l => l.classList.remove('active'));
            
            // Add active class to clicked link
            this.classList.add('active');
            
            // Scroll to target
            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);
            
            if (targetElement) {
                targetElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Update active link on scroll
    window.addEventListener('scroll', function() {
        const sections = document.querySelectorAll('.card[id]');
        const navLinks = document.querySelectorAll('.list-group-item[href^="#"]');
        
        let currentSection = '';
        
        sections.forEach(section => {
            const sectionTop = section.offsetTop - 100;
            if (window.scrollY >= sectionTop) {
                currentSection = '#' + section.getAttribute('id');
            }
        });
        
        navLinks.forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href') === currentSection) {
                link.classList.add('active');
            }
        });
    });

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