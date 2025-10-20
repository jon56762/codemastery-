<?php
// Get platform statistics
$platformStats = getPlatformStats();
?>

<div class="container py-5">
    <!-- Header -->
    <div class="row mb-5">
        <div class="col-12 text-center">
            <h1 class="fw-bold mb-3">About CodeMastery</h1>
            <p class="lead text-muted">Empowering developers worldwide through quality education</p>
        </div>
    </div>

    <!-- Mission Section -->
    <div class="row mb-5">
        <div class="col-lg-6">
            <h2 class="fw-bold mb-4">Our Mission</h2>
            <p class="mb-4">At CodeMastery, we believe that anyone can learn to code and build amazing things. Our mission is to make quality programming education accessible to everyone, regardless of their background or experience level.</p>
            <p>We're building a community where learners can grow, instructors can share their knowledge, and everyone can advance their careers in technology.</p>
        </div>
        <div class="col-lg-6">
            <img src="/assets/images/womanbackground.jpg" alt="Our Mission" class="img-fluid rounded shadow">
        </div>
    </div>

    <!-- Dynamic Stats -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="bg-light rounded p-5 text-center">
                <div class="row">
                    <div class="col-md-3 col-6 mb-4">
                        <div class="h1 fw-bold text-dark"><?= number_format($platformStats['total_students']) ?>+</div>
                        <div class="text-muted">Students</div>
                    </div>
                    <div class="col-md-3 col-6 mb-4">
                        <div class="h1 fw-bold text-dark"><?= number_format($platformStats['total_courses']) ?>+</div>
                        <div class="text-muted">Courses</div>
                    </div>
                    <div class="col-md-3 col-6 mb-4">
                        <div class="h1 fw-bold text-dark"><?= number_format($platformStats['total_instructors']) ?>+</div>
                        <div class="text-muted">Instructors</div>
                    </div>
                    <div class="col-md-3 col-6 mb-4">
                        <div class="h1 fw-bold text-dark"><?= number_format($platformStats['total_enrollments']) ?>+</div>
                        <div class="text-muted">Enrollments</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Values -->
    <div class="row mb-5">
        <div class="col-12 text-center mb-5">
            <h2 class="fw-bold">Our Values</h2>
        </div>
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="text-center p-4">
                <div class="mb-3">
                    <i class="fas fa-graduation-cap fa-2x text-dark"></i>
                </div>
                <h5 class="fw-bold">Quality Education</h5>
                <p class="text-muted">We maintain high standards for all our courses and instructors.</p>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="text-center p-4">
                <div class="mb-3">
                    <i class="fas fa-hand-holding-heart fa-2x text-dark"></i>
                </div>
                <h5 class="fw-bold">Student Success</h5>
                <p class="text-muted">Your learning journey and career growth are our top priority.</p>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="text-center p-4">
                <div class="mb-3">
                    <i class="fas fa-users fa-2x text-dark"></i>
                </div>
                <h5 class="fw-bold">Community</h5>
                <p class="text-muted">We foster a supportive community of learners and mentors.</p>
            </div>
        </div>
    </div>
</div>