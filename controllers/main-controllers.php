<?php

require_once __DIR__ . '/../includes/init.php';
class MainController
{
     public function index()
    {
        $popular_courses = array_map(fn($c) => $c->toArray(), Course::getFeatured(3));
        $new_courses = array_map(fn($c) => $c->toArray(), Course::getPublished());
        $featured_courses = $popular_courses;
        $testimonials = array_map(fn($t) => $t->toArray(), Testimonial::getApproved());
        $platformStats = getPlatformStats();   // now available

        view('index');
    }

    public function courses()
    {
        view('courses');
    }

    public function course()
    {
        view('course');
    }

    public function blogCreation()
    {
        view('blog-creation');
    }

    public function about()
    {
        view('about');
    }

    public function contact()
    {
        view('contact');
    }

    public function pricing()
    {
        view('pricing');
    }

    public function blog()
    {
        view('blog');
    }

    public function login()
    {
        view('login');
    }

    public function signup()
    {
        view('signup');
    }

    public function dashboard()
    {
        view('dashboard');
    }

    public function logout()
    {
        view('logout');
    }

    public function becomeInstructor()
    {
        view('become-instructor');
    }

    public function instructorDashboard()
    {
        view('instructor-dashboard');
    }

    public function notifications()
    {
        view('notifications');
    }

    public function instructorCourses()
    {
        view('instructor-courses');
    }

    public function courseBuilder()
    {
        view('course-builder');
    }

    public function profile()
    {
        view('student-profile');
    }

    public function myCourses()
    {
        view('my-courses');
    }

    public function processLogin()
    {
        view('process-login');
    }

    public function processSignup()
    {
        view('process-signup');
    }

    public function instructorAnalytics()
    {
        view('instructor-analytics');
    }

    public function instructorEarnings()
    {
        view('instructor-earnings');
    }

    public function testimonials()
    {
        view('testimonials');
    }

    public function testimonialSubmit()
    {
        view('testimonial-submit');
    }

    public function instructorProfile()
    {
        view('instructor-profile');
    }

    public function coursePlayer()
    {
        view('course-player');
    }

    public function admin()
    {
        view('admin');
    }

    public function adminAnalytics()
    {
        view('admin-analytics');
    }

    public function adminRevenue()
    {
        view('admin-revenue');
    }

    public function adminSettings()
    {
        view('admin-settings');
    }

    public function adminCourses()
    {
        view('admin-courses');
    }

    public function adminUsers()
    {
        view('admin-users');
    }

    public function adminInstructorApplication()
    {
        view('admin-instructor-applications');
    }

    public function adminBlog()
    {
        view('admin-blog');
    }

    public function adminTestimonials()
    {
        view('admin-testimonials');
    }

    public function adminModeration()
    {
        view('admin-moderation');
    }

    public function billing()
    {
        view('billing');
    }

    public function certificate()
    {
        view('certificates');
    }
}
