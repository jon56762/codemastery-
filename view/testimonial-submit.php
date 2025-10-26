<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Header -->
            <div class="text-center mb-5">
                <h1 class="fw-bold mb-3">Share Your Experience</h1>
                <p class="text-muted">Help others discover CodeMastery by sharing your learning journey</p>
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

            <!-- Testimonial Form -->
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <!-- User Info -->
                    <div class="d-flex align-items-center mb-4 p-3 bg-light rounded">
                        <img src="<?= htmlspecialchars($user['avatar'] ?? '/assets/images/avatars/default.jpg') ?>" 
                             alt="<?= htmlspecialchars($user['name']) ?>" 
                             class="rounded-circle me-3" width="60" height="60" style="object-fit: cover;">
                        <div>
                            <h5 class="fw-bold mb-1"><?= htmlspecialchars($user['name']) ?></h5>
                            <p class="text-muted mb-0"><?= ucfirst($user['role']) ?> • Member since <?= date('F Y', strtotime($user['created_at'] ?? 'now')) ?></p>
                        </div>
                    </div>

                    <form method="POST">
                        <!-- Rating -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">How would you rate your experience? *</label>
                            <div class="rating-stars mb-2">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <input type="radio" id="star<?= $i ?>" name="rating" value="<?= $i ?>" 
                                           class="d-none" <?= $i == 5 ? 'checked' : '' ?>>
                                    <label for="star<?= $i ?>" class="star-label">
                                        <i class="far fa-star"></i>
                                    </label>
                                <?php endfor; ?>
                            </div>
                            <div class="rating-labels">
                                <small class="text-muted">1 - Poor</small>
                                <small class="text-muted">5 - Excellent</small>
                            </div>
                        </div>

                        <!-- Current Role -->
                        <div class="mb-4">
                            <label for="role" class="form-label fw-semibold">Your Current Role *</label>
                            <input type="text" class="form-control" id="role" name="role" 
                                   value="<?= htmlspecialchars($_POST['role'] ?? '') ?>" 
                                   placeholder="e.g., Frontend Developer, Data Scientist, Student" required>
                            <div class="form-text">This will be displayed with your testimonial</div>
                        </div>

                        <!-- Testimonial Text -->
                        <div class="mb-4">
                            <label for="testimonial_text" class="form-label fw-semibold">Your Experience *</label>
                            <textarea class="form-control" id="testimonial_text" name="testimonial_text" 
                                      rows="6" placeholder="Share your learning journey, how CodeMastery helped you, what you achieved..." 
                                      required><?= htmlspecialchars($_POST['testimonial_text'] ?? '') ?></textarea>
                            <div class="form-text">
                                <span id="char_count">0</span>/500 characters
                            </div>
                        </div>

                        <!-- Guidelines -->
                        <div class="alert alert-info">
                            <h6 class="fw-semibold mb-2">
                                <i class="fas fa-info-circle me-2"></i>Testimonial Guidelines
                            </h6>
                            <ul class="mb-0 small">
                                <li>Share your genuine experience</li>
                                <li>Mention specific skills you learned</li>
                                <li>Talk about your achievements or career progress</li>
                                <li>Keep it authentic and helpful for others</li>
                            </ul>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-3">
                            <button type="submit" name="submit_testimonial" class="btn btn-primary btn-lg flex-grow-1">
                                <i class="fas fa-paper-plane me-2"></i>Submit Testimonial
                            </button>
                            <a href="/testimonials" class="btn btn-outline-dark btn-lg">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- User Stats -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">Your Learning Journey</h5>
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="h4 fw-bold text-primary mb-1">
                                <?= count(getStudentEnrollments($user['id'])) ?>
                            </div>
                            <small class="text-muted">Courses Enrolled</small>
                        </div>
                        <div class="col-4">
                            <div class="h4 fw-bold text-success mb-1">
                                <?= count(array_filter(getStudentEnrollments($user['id']), function($e) { return $e['progress'] >= 100; })) ?>
                            </div>
                            <small class="text-muted">Courses Completed</small>
                        </div>
                        <div class="col-4">
                            <div class="h4 fw-bold text-warning mb-1">
                                <?= count(getStudentAchievements($user['id'])) ?>
                            </div>
                            <small class="text-muted">Achievements</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.rating-stars {
    display: flex;
    gap: 5px;
}

.star-label {
    font-size: 2rem;
    color: #ddd;
    cursor: pointer;
    transition: color 0.2s;
}

.star-label:hover,
.rating-stars input:checked ~ .star-label {
    color: #ffc107;
}

.rating-stars input:checked + .star-label {
    color: #ffc107;
}

.rating-labels {
    display: flex;
    justify-content: space-between;
    margin-top: 5px;
}

.card {
    border-radius: 12px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Character count for testimonial text
    const textarea = document.getElementById('testimonial_text');
    const charCount = document.getElementById('char_count');
    
    textarea.addEventListener('input', function() {
        const count = this.value.length;
        charCount.textContent = count;
        
        if (count > 500) {
            charCount.classList.add('text-danger');
        } else {
            charCount.classList.remove('text-danger');
        }
    });
    
    // Initialize character count
    charCount.textContent = textarea.value.length;
    
    // Star rating interaction
    const stars = document.querySelectorAll('.star-label');
    stars.forEach((star, index) => {
        star.addEventListener('click', function() {
            // Update all stars up to the clicked one
            stars.forEach((s, i) => {
                if (i <= index) {
                    s.innerHTML = '<i class="fas fa-star"></i>';
                } else {
                    s.innerHTML = '<i class="far fa-star"></i>';
                }
            });
        });
    });
    
    // Initialize stars based on checked radio
    const checkedStar = document.querySelector('input[name="rating"]:checked');
    if (checkedStar) {
        const index = parseInt(checkedStar.value) - 1;
        stars.forEach((s, i) => {
            if (i <= index) {
                s.innerHTML = '<i class="fas fa-star"></i>';
            }
        });
    }
});
</script>