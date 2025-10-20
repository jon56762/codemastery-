<link rel="stylesheet" href="/assets/css/signup.css">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-sm">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <h2 class="fw-bold">Join CodeMastery</h2>
                        <p class="text-muted">Create your free account and start learning</p>
                    </div>

                    <form method="POST" action="/process-signup">
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

                        <!-- Role Selection -->
                        <div class="mb-3">
                            <label class="form-label">I want to join as:</label>
                            <div class="d-grid gap-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="role" id="role_student" value="student" checked>
                                    <label class="form-check-label" for="role_student">
                                        <i class="fas fa-user-graduate me-2"></i>
                                        <strong>Student</strong> - Learn from expert instructors
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="role" id="role_instructor" value="instructor">
                                    <label class="form-check-label" for="role_instructor">
                                        <i class="fas fa-chalkboard-teacher me-2"></i>
                                        <strong>Instructor</strong> - Teach and earn money
                                    </label>
                                </div>
                            </div>
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
                            <i class="fas fa-user-plus me-2"></i>Create Account
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

            <!-- Role Information -->
            <div class="card mt-4">
                <div class="card-body">
                    <h6 class="card-title">About Roles</h6>
                    <div class="mb-3">
                        <strong><i class="fas fa-user-graduate me-2 text-primary"></i>Student</strong>
                        <p class="small text-muted mb-2">Access courses, track progress, earn certificates, and join our learning community.</p>
                    </div>
                    <div class="mb-0">
                        <strong><i class="fas fa-chalkboard-teacher me-2 text-success"></i>Instructor</strong>
                        <p class="small text-muted mb-0">Create and sell courses, earn revenue, and share your expertise with students worldwide.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>