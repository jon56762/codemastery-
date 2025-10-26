<link rel="stylesheet" href="/assets/css/signup.css">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-sm">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <h2 class="fw-bold">Join CodeMastery</h2>
                        <p class="text-muted">Create your free student account and start learning</p>
                    </div>

                    <!-- Add enctype for file uploads -->
                    <form method="POST" action="/process-signup" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-user"></i>
                                </span>
                                <input type="text" class="form-control" id="name" name="name" 
                                       value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" 
                                       placeholder="Enter your full name" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-envelope"></i>
                                </span>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" 
                                       placeholder="Enter your email" required>
                            </div>
                        </div>

                        <!-- Profile Picture Upload -->
                        <div class="mb-3">
                            <label for="profile_picture" class="form-label">Profile Picture (Optional)</label>
                            <input type="file" class="form-control" id="profile_picture" name="profile_picture" 
                                   accept="image/*">
                            <div class="form-text">Supported formats: JPG, PNG, GIF. Max size: 2MB</div>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input type="password" class="form-control" id="password" name="password" 
                                       placeholder="Create a password (min. 6 characters)" required>
                            </div>
                            <div class="form-text">Must be at least 6 characters long</div>
                        </div>

                        <div class="mb-4">
                            <label for="confirm_password" class="form-label">Confirm Password</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" 
                                       placeholder="Confirm your password" required>
                            </div>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                            <label class="form-check-label" for="terms">
                                I agree to the <a href="#" class="text-decoration-none">Terms of Service</a> and <a href="#" class="text-decoration-none">Privacy Policy</a>
                            </label>
                        </div>

                        <button type="submit" name="register" class="btn btn-primary w-100 py-2 mb-3">
                            <i class="fas fa-user-plus me-2"></i>Create Student Account
                        </button>

                        <div class="text-center">
                            <p class="text-muted mb-0">
                                By creating an account, you agree to our learning community guidelines.
                            </p>
                        </div>
                    </form>

                    <hr class="my-4">

                    <div class="text-center">
                        <p class="text-muted mb-0">Already have an account?</p>
                        <a href="/login" class="btn btn-outline-dark mt-2">
                            Sign In Instead
                        </a>
                    </div>
                </div>
            </div>

            <!-- Instructor Application Info -->
            <div class="card mt-4">
                <div class="card-body">
                    <h6 class="card-title">Want to Teach?</h6>
                    <div class="mb-0">
                        <p class="small text-muted mb-2">
                            Interested in becoming an instructor? After creating your student account, 
                            you can apply to become an instructor through our application process.
                        </p>
                        <a href="/become-instructor" class="btn btn-outline-success btn-sm">
                            Learn About Instructor Opportunities
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Password confirmation validation
document.addEventListener('DOMContentLoaded', function() {
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('confirm_password');
    
    function validatePassword() {
        if (password.value !== confirmPassword.value) {
            confirmPassword.setCustomValidity("Passwords do not match");
        } else {
            confirmPassword.setCustomValidity("");
        }
    }
    
    password.addEventListener('input', validatePassword);
    confirmPassword.addEventListener('input', validatePassword);
});
</script>