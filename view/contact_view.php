<?php
// Get platform statistics
$platformStats = getPlatformStats();
?>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <!-- Header -->
            <div class="text-center mb-5">
                <h1 class="fw-bold mb-3">Contact Us</h1>
                <p class="lead text-muted">Join our community of <?= number_format($platformStats['total_students']) ?>+ students and <?= number_format($platformStats['total_instructors']) ?>+ instructors</p>
            </div>

            <div class="row">
                <!-- Contact Form -->
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <form method="POST">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label fw-semibold">Full Name</label>
                                        <input type="text" class="form-control" id="name" name="name" 
                                               value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label fw-semibold">Email Address</label>
                                        <input type="email" class="form-control" id="email" name="email" 
                                               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="subject" class="form-label fw-semibold">Subject</label>
                                    <select class="form-select" id="subject" name="subject" required>
                                        <option value="">Select a subject</option>
                                        <option value="General Inquiry" <?= ($_POST['subject'] ?? '') === 'General Inquiry' ? 'selected' : '' ?>>General Inquiry</option>
                                        <option value="Technical Support" <?= ($_POST['subject'] ?? '') === 'Technical Support' ? 'selected' : '' ?>>Technical Support</option>
                                        <option value="Billing Issue" <?= ($_POST['subject'] ?? '') === 'Billing Issue' ? 'selected' : '' ?>>Billing Issue</option>
                                        <option value="Course Feedback" <?= ($_POST['subject'] ?? '') === 'Course Feedback' ? 'selected' : '' ?>>Course Feedback</option>
                                        <option value="Instructor Application" <?= ($_POST['subject'] ?? '') === 'Instructor Application' ? 'selected' : '' ?>>Instructor Application</option>
                                        <option value="Other" <?= ($_POST['subject'] ?? '') === 'Other' ? 'selected' : '' ?>>Other</option>
                                    </select>
                                </div>
                                
                                <div class="mb-4">
                                    <label for="message" class="form-label fw-semibold">Message</label>
                                    <textarea class="form-control" id="message" name="message" rows="5" 
                                              placeholder="Tell us how we can help you..." required><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
                                </div>
                                
                                <button type="submit" name="contact" class="btn btn-dark btn-lg w-100">
                                    <i class="fas fa-paper-plane me-2"></i>Send Message
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Contact Info -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-4">Get in Touch</h5>
                            
                            <div class="d-flex align-items-start mb-4">
                                <div class="me-3 text-dark">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div>
                                    <h6 class="fw-semibold mb-1">Email</h6>
                                    <p class="text-muted mb-0">support@codemastery.com</p>
                                </div>
                            </div>
                            
                            <div class="d-flex align-items-start mb-4">
                                <div class="me-3 text-dark">
                                    <i class="fas fa-phone"></i>
                                </div>
                                <div>
                                    <h6 class="fw-semibold mb-1">Phone</h6>
                                    <p class="text-muted mb-0">+1 (555) 123-4567</p>
                                </div>
                            </div>
                            
                            <div class="d-flex align-items-start mb-4">
                                <div class="me-3 text-dark">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div>
                                    <h6 class="fw-semibold mb-1">Response Time</h6>
                                    <p class="text-muted mb-0">Within 24 hours</p>
                                </div>
                            </div>
                            
                            <div class="d-flex align-items-start">
                                <div class="me-3 text-dark">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div>
                                    <h6 class="fw-semibold mb-1">Location</h6>
                                    <p class="text-muted mb-0">San Francisco, CA</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Platform Stats -->
                    <div class="card border-0 shadow-sm mt-4">
                        <div class="card-body p-4">
                            <h6 class="fw-bold mb-3">Our Community</h6>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Students:</span>
                                <span class="fw-semibold"><?= number_format($platformStats['total_students']) ?></span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Courses:</span>
                                <span class="fw-semibold"><?= number_format($platformStats['total_courses']) ?></span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Instructors:</span>
                                <span class="fw-semibold"><?= number_format($platformStats['total_instructors']) ?></span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Enrollments:</span>
                                <span class="fw-semibold"><?= number_format($platformStats['total_enrollments']) ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>