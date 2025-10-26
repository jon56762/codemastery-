<div class="container-fluid mt-5 py-4">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 fw-bold mb-1">Instructor Profile</h1>
                    <p class="text-muted mb-0">Manage your public profile and account settings</p>
                </div>
                <a href="/instructor-profile?preview=true" class="btn btn-outline-primary" target="_blank">
                    <i class="fas fa-eye me-2"></i>Preview Public Profile
                </a>
            </div>

            <!-- Success/Error Messages -->
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= $_SESSION['success'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= $_SESSION['error'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <div class="row">
                <!-- Profile Information -->
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="fw-bold mb-0">
                                <i class="fas fa-user-edit me-2"></i>Profile Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <form method="POST">
                                <input type="hidden" name="update_profile" value="1">
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label fw-semibold">Full Name *</label>
                                        <input type="text" class="form-control" id="name" name="name"
                                               value="<?= htmlspecialchars($user['name'] ?? '') ?>" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label fw-semibold">Email Address</label>
                                        <input type="email" class="form-control" id="email" 
                                               value="<?= htmlspecialchars($user['email'] ?? '') ?>" disabled>
                                        <div class="form-text">Email cannot be changed</div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="bio" class="form-label fw-semibold">Bio *</label>
                                    <textarea class="form-control" id="bio" name="bio" rows="4" 
                                              placeholder="Tell students about your experience, expertise, and teaching style..." required><?= htmlspecialchars($user['bio'] ?? '') ?></textarea>
                                    <div class="form-text">This appears on your public instructor profile</div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="specialization" class="form-label fw-semibold">Specialization</label>
                                        <select class="form-select" id="specialization" name="specialization">
                                            <option value="">Select your specialization</option>
                                            <option value="Web Development" <?= ($user['specialization'] ?? '') === 'Web Development' ? 'selected' : '' ?>>Web Development</option>
                                            <option value="Data Science" <?= ($user['specialization'] ?? '') === 'Data Science' ? 'selected' : '' ?>>Data Science</option>
                                            <option value="Mobile Development" <?= ($user['specialization'] ?? '') === 'Mobile Development' ? 'selected' : '' ?>>Mobile Development</option>
                                            <option value="Machine Learning" <?= ($user['specialization'] ?? '') === 'Machine Learning' ? 'selected' : '' ?>>Machine Learning</option>
                                            <option value="UI/UX Design" <?= ($user['specialization'] ?? '') === 'UI/UX Design' ? 'selected' : '' ?>>UI/UX Design</option>
                                            <option value="Digital Marketing" <?= ($user['specialization'] ?? '') === 'Digital Marketing' ? 'selected' : '' ?>>Digital Marketing</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="experience" class="form-label fw-semibold">Years of Experience</label>
                                        <select class="form-select" id="experience" name="experience">
                                            <option value="">Select experience</option>
                                            <option value="1-2 years" <?= ($user['experience'] ?? '') === '1-2 years' ? 'selected' : '' ?>>1-2 years</option>
                                            <option value="3-5 years" <?= ($user['experience'] ?? '') === '3-5 years' ? 'selected' : '' ?>>3-5 years</option>
                                            <option value="5-10 years" <?= ($user['experience'] ?? '') === '5-10 years' ? 'selected' : '' ?>>5-10 years</option>
                                            <option value="10+ years" <?= ($user['experience'] ?? '') === '10+ years' ? 'selected' : '' ?>>10+ years</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Social Links</label>
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <i class="fas fa-globe"></i>
                                                </span>
                                                <input type="url" class="form-control" name="website" 
                                                       placeholder="Website" value="<?= htmlspecialchars($user['website'] ?? '') ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <i class="fab fa-linkedin text-primary"></i>
                                                </span>
                                                <input type="url" class="form-control" name="linkedin" 
                                                       placeholder="LinkedIn Profile" value="<?= htmlspecialchars($user['linkedin'] ?? '') ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <i class="fab fa-twitter text-info"></i>
                                                </span>
                                                <input type="url" class="form-control" name="twitter" 
                                                       placeholder="Twitter Profile" value="<?= htmlspecialchars($user['twitter'] ?? '') ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <i class="fab fa-youtube text-danger"></i>
                                                </span>
                                                <input type="url" class="form-control" name="youtube" 
                                                       placeholder="YouTube Channel" value="<?= htmlspecialchars($user['youtube'] ?? '') ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Save Changes
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Change Password -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="fw-bold mb-0">
                                <i class="fas fa-lock me-2"></i>Change Password
                            </h5>
                        </div>
                        <div class="card-body">
                            <form method="POST">
                                <input type="hidden" name="change_password" value="1">
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="current_password" class="form-label fw-semibold">Current Password *</label>
                                        <input type="password" class="form-control" id="current_password" name="current_password" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="new_password" class="form-label fw-semibold">New Password *</label>
                                        <input type="password" class="form-control" id="new_password" name="new_password" required>
                                        <div class="form-text">Minimum 6 characters</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="confirm_password" class="form-label fw-semibold">Confirm New Password *</label>
                                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-warning">
                                        <i class="fas fa-key me-2"></i>Change Password
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Profile Preview & Stats -->
                <div class="col-lg-4">
                    <!-- Profile Preview -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="fw-bold mb-0">Profile Preview</h5>
                        </div>
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <img src="<?= $user['avatar'] ?? '/assets/images/avatars/default.jpg' ?>" 
                                     class="rounded-circle" width="120" height="120" style="object-fit: cover;">
                            </div>
                            <h5 class="fw-bold"><?= htmlspecialchars($user['name'] ?? 'Instructor') ?></h5>
                            <p class="text-muted mb-2">Instructor</p>
                            
                            <?php if (!empty($user['specialization'])): ?>
                                <span class="badge bg-primary mb-2"><?= htmlspecialchars($user['specialization']) ?></span>
                            <?php endif; ?>
                            
                            <?php if (!empty($user['experience'])): ?>
                                <p class="small text-muted mb-3"><?= htmlspecialchars($user['experience']) ?> experience</p>
                            <?php endif; ?>
                            
                            <div class="d-flex justify-content-center gap-3 mb-3">
                                <?php if (!empty($user['website'])): ?>
                                    <a href="<?= htmlspecialchars($user['website']) ?>" class="text-muted" target="_blank">
                                        <i class="fas fa-globe fa-lg"></i>
                                    </a>
                                <?php endif; ?>
                                <?php if (!empty($user['linkedin'])): ?>
                                    <a href="<?= htmlspecialchars($user['linkedin']) ?>" class="text-primary" target="_blank">
                                        <i class="fab fa-linkedin fa-lg"></i>
                                    </a>
                                <?php endif; ?>
                                <?php if (!empty($user['twitter'])): ?>
                                    <a href="<?= htmlspecialchars($user['twitter']) ?>" class="text-info" target="_blank">
                                        <i class="fab fa-twitter fa-lg"></i>
                                    </a>
                                <?php endif; ?>
                                <?php if (!empty($user['youtube'])): ?>
                                    <a href="<?= htmlspecialchars($user['youtube']) ?>" class="text-danger" target="_blank">
                                        <i class="fab fa-youtube fa-lg"></i>
                                    </a>
                                <?php endif; ?>
                            </div>

                            <div class="text-start">
                                <h6 class="fw-semibold mb-2">About</h6>
                                <p class="small text-muted">
                                    <?= !empty($user['bio']) ? nl2br(htmlspecialchars($user['bio'])) : 'No bio added yet.' ?>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Instructor Stats -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="fw-bold mb-0">Teaching Stats</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span>Total Courses</span>
                                <strong class="text-primary"><?= count(getCoursesByInstructor($user['id'])) ?></strong>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span>Total Students</span>
                                <strong class="text-success"><?= getInstructorStudentCount($user['id']) ?></strong>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span>Total Reviews</span>
                                <strong class="text-warning"><?= getInstructorReviewCount($user['id']) ?></strong>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span>Average Rating</span>
                                <strong class="text-info"><?= getInstructorRating($user['id']) ?>/5</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border-radius: 12px;
}

.input-group-text {
    background-color: #f8f9fa;
}

.badge {
    font-size: 0.8rem;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Password confirmation validation
    const newPassword = document.getElementById('new_password');
    const confirmPassword = document.getElementById('confirm_password');
    
    function validatePassword() {
        if (newPassword.value !== confirmPassword.value) {
            confirmPassword.setCustomValidity("Passwords do not match");
        } else {
            confirmPassword.setCustomValidity("");
        }
    }
    
    newPassword.addEventListener('input', validatePassword);
    confirmPassword.addEventListener('input', validatePassword);
});
</script>