<link rel="stylesheet" href="/assets/css/become-instructor.css">
<div class="container py-5 mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Header Section -->
            <div class="text-center mb-5">
                <h1 class="fw-bold mb-3">Become a CodeMastery Instructor</h1>
                <p class="lead text-muted mb-4">Share your knowledge, inspire learners, and earn money teaching what you love.</p>
                
                <?php if ($pendingApplication): ?>
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <i class="fas fa-clock me-2"></i>
                        <strong>Application Pending:</strong> Your instructor application is under review. We'll notify you once it's processed.
                        <br><small class="text-muted">Submitted on: <?= date('M j, Y', strtotime($pendingApplication['submitted_at'])) ?></small>
                    </div>
                <?php endif; ?>
            </div>

            <div class="row">
                <!-- Benefits Section -->
                <div class="col-lg-5 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <h4 class="fw-bold mb-4">Why Teach on CodeMastery?</h4>
                            
                            <div class="d-flex align-items-start mb-4">
                                <div class="bg-primary text-white rounded-circle p-2 me-3">
                                    <i class="fas fa-dollar-sign fa-lg"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">Earn Money</h6>
                                    <p class="text-muted small mb-0">Earn 70% commission on every course sale. Get paid monthly.</p>
                                </div>
                            </div>
                            
                            <div class="d-flex align-items-start mb-4">
                                <div class="bg-success text-white rounded-circle p-2 me-3">
                                    <i class="fas fa-users fa-lg"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">Reach Thousands</h6>
                                    <p class="text-muted small mb-0">Teach students from around the world on our growing platform.</p>
                                </div>
                            </div>
                            
                            <div class="d-flex align-items-start mb-4">
                                <div class="bg-warning text-white rounded-circle p-2 me-3">
                                    <i class="fas fa-tools fa-lg"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">Powerful Tools</h6>
                                    <p class="text-muted small mb-0">Use our easy course builder, analytics, and marketing tools.</p>
                                </div>
                            </div>
                            
                            <div class="d-flex align-items-start mb-4">
                                <div class="bg-info text-white rounded-circle p-2 me-3">
                                    <i class="fas fa-chart-line fa-lg"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">Grow Your Brand</h6>
                                    <p class="text-muted small mb-0">Build your reputation as an expert in your field.</p>
                                </div>
                            </div>
                            
                            <div class="d-flex align-items-start">
                                <div class="bg-dark text-white rounded-circle p-2 me-3">
                                    <i class="fas fa-life-ring fa-lg"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">Full Support</h6>
                                    <p class="text-muted small mb-0">Get help from our instructor support team whenever you need it.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Application Form -->
                <div class="col-lg-7">
                    <?php if (!$pendingApplication): ?>
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0 py-3">
                            <h4 class="fw-bold mb-0">Instructor Application</h4>
                            <p class="text-muted mb-0">Tell us about your experience and expertise</p>
                        </div>
                        <div class="card-body p-4">
                            <form method="POST">
                                <!-- Experience -->
                                <div class="mb-4">
                                    <label for="experience" class="form-label fw-semibold">
                                        Teaching Experience <span class="text-danger">*</span>
                                    </label>
                                    <textarea class="form-control" id="experience" name="experience" rows="4" 
                                              placeholder="Describe your teaching experience, including any previous courses, workshops, or mentoring experience..."
                                              required><?= $_POST['experience'] ?? '' ?></textarea>
                                    <div class="form-text">Tell us about your experience teaching or mentoring others.</div>
                                </div>

                                <!-- Specialization -->
                                <div class="mb-4">
                                    <label for="specialization" class="form-label fw-semibold">
                                        Area of Specialization <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="specialization" name="specialization"
                                           value="<?= $_POST['specialization'] ?? '' ?>" 
                                           placeholder="e.g., Web Development, Data Science, Mobile App Development" required>
                                    <div class="form-text">What subjects are you most qualified to teach?</div>
                                </div>

                                <!-- Portfolio -->
                                <div class="mb-4">
                                    <label for="portfolio" class="form-label fw-semibold">Portfolio/Website</label>
                                    <input type="url" class="form-control" id="portfolio" name="portfolio"
                                           value="<?= $_POST['portfolio'] ?? '' ?>" 
                                           placeholder="https://yourportfolio.com">
                                    <div class="form-text">Link to your portfolio, GitHub, or personal website.</div>
                                </div>

                                <!-- LinkedIn -->
                                <div class="mb-4">
                                    <label for="linkedin" class="form-label fw-semibold">LinkedIn Profile</label>
                                    <input type="url" class="form-control" id="linkedin" name="linkedin"
                                           value="<?= $_POST['linkedin'] ?? '' ?>" 
                                           placeholder="https://linkedin.com/in/yourprofile">
                                    <div class="form-text">Your LinkedIn profile URL (optional but recommended).</div>
                                </div>

                                <!-- Additional Message -->
                                <div class="mb-4">
                                    <label for="message" class="form-label fw-semibold">Additional Information</label>
                                    <textarea class="form-control" id="message" name="message" rows="3"
                                              placeholder="Anything else you'd like to share about why you want to become an instructor..."><?= $_POST['message'] ?? '' ?></textarea>
                                </div>

                                <!-- Agreement -->
                                <div class="mb-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="agree_terms" name="agree_terms" required>
                                        <label class="form-check-label" for="agree_terms">
                                            I agree to the <a href="#" class="text-decoration-none">Instructor Terms</a> and 
                                            <a href="#" class="text-decoration-none">Privacy Policy</a>
                                        </label>
                                    </div>
                                </div>

                                <button type="submit" name="submit_application" class="btn btn-primary btn-lg w-100 py-3">
                                    <i class="fas fa-paper-plane me-2"></i>Submit Application
                                </button>
                            </form>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center py-5">
                            <i class="fas fa-clock fa-4x text-warning mb-4"></i>
                            <h4 class="fw-bold mb-3">Application Under Review</h4>
                            <p class="text-muted mb-4">
                                Your instructor application is currently being reviewed by our team. 
                                We typically process applications within 3-5 business days.
                            </p>
                            <div class="row text-start">
                                <div class="col-md-6 mb-3">
                                    <strong>Submitted:</strong><br>
                                    <span class="text-muted"><?= date('F j, Y', strtotime($pendingApplication['submitted_at'])) ?></span>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <strong>Specialization:</strong><br>
                                    <span class="text-muted"><?= htmlspecialchars($pendingApplication['specialization']) ?></span>
                                </div>
                            </div>
                            <a href="/dashboard" class="btn btn-outline-dark mt-3">
                                <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                            </a>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- FAQ Section -->
            <div class="row mt-5">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0 py-3">
                            <h4 class="fw-bold mb-0">Frequently Asked Questions</h4>
                        </div>
                        <div class="card-body p-4">
                            <div class="accordion" id="instructorFAQ">
                                <div class="accordion-item border-0">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                            How much can I earn as an instructor?
                                        </button>
                                    </h2>
                                    <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#instructorFAQ">
                                        <div class="accordion-body text-muted">
                                            Instructors earn 70% commission on every course sale. Top instructors on our platform earn over $10,000 per month. Your earnings depend on course quality, marketing efforts, and student demand.
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="accordion-item border-0">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                            What are the requirements to become an instructor?
                                        </button>
                                    </h2>
                                    <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#instructorFAQ">
                                        <div class="accordion-body text-muted">
                                            We look for instructors with real-world experience in their field, good communication skills, and a passion for teaching. You don't need formal teaching experience, but you should be able to create high-quality, engaging content.
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="accordion-item border-0">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                            How long does the application process take?
                                        </button>
                                    </h2>
                                    <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#instructorFAQ">
                                        <div class="accordion-body text-muted">
                                            We typically review applications within 3-5 business days. If approved, you'll gain immediate access to our course creation tools and can start building your first course right away.
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
</div>