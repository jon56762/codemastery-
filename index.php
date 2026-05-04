<?php

require 'functions.php';

require 'routes.php';

require 'controllers/main-controllers.php';

route('/', MainController::class,'index');

route('/courses', MainController::class, 'courses');

route('/course', MainController::class, 'course');

route('/blog-creation', MainController::class,'blogCreation');

route('/about', MainController::class,'about');

route('/contact', MainController::class, 'contact');

route('/pricing', MainController::class,'pricing');

route('/blog', MainController::class,'blog');

route('/login', MainController::class, 'login');

route('/signup', MainController::class, 'signup');

route('/dashboard', MainController::class, 'dashboard');

route('/logout', MainController::class, 'logout');

route('/become-instructor', MainController::class, 'becomeInstructor');

route('/instructor-dashboard', MainController::class,'instructorDashboard');

route('/instructor-courses', MainController::class,'instructorCourses');

route('/course-builder', MainController::class,'courseBuilder');

route('/profile', MainController::class, 'profile');

route('/my-courses', MainController::class, 'myCourses');

route('/process-login', MainController::class, 'processLogin');

route('/process-signup', MainController::class, 'processSignup');

route('/instructor-analytics', MainController::class,'instructorAnalytics');

route('/instructor-earnings', MainController::class,'instructorEarnings');

route('/testimonials', MainController::class,'testimonials');

route('/testimonial-submit', MainController::class, 'testimonialSubmit');

route('/instructor-profile', MainController::class,'instructorProfile');

route('/course-player', MainController::class,'coursePlayer');

route('/admin', MainController::class, 'admin');

route('/admin-analytics', MainController::class, 'adminAnalytics');

route('/admin-revenue', MainController::class, 'adminRevenue');

route('/admin-settings', MainController::class, 'adminSettings');

route('/admin-courses', MainController::class, 'adminCourses');

route('/admin-users', MainController::class, 'adminUsers');

route('/admin-instructor-applications', MainController::class, 'adminInstructorApplication');

route('/admin-blog', MainController::class, 'adminBlog');

route('/admin-testimonials', MainController::class, 'adminTestimonials');

route('/admin-moderation', MainController::class, 'adminModeration');

route('/billing', MainController::class, 'billing');

route ('/certificates', MainController::class, 'certificate');
run();