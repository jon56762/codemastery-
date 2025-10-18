<?php
function getFeaturedCourses() {
    $courses = json_decode(file_get_contents(DATA_PATH . 'courses.json'), true) ?? [];
    $featured = array_filter($courses, function($course) {
        return $course['featured'] ?? false;
    });
    return array_slice($featured, 0, 4);
}

function getAllCourses() {
    return json_decode(file_get_contents(DATA_PATH . 'courses.json'), true) ?? [];
}

function getCourseCategories() {
    $courses = getAllCourses();
    $categories = array_unique(array_column($courses, 'category'));
    return $categories;
}

function getCourseById($id) {
    $courses = getAllCourses();
    foreach ($courses as $course) {
        if ($course['id'] == $id) {
            return $course;
        }
    }
    return null;
}

function getTestimonials() {
    return json_decode(file_get_contents(DATA_PATH . 'testimonials.json'), true) ?? [];
}

function getPlatformStats() {
    $stats = json_decode(file_get_contents(DATA_PATH . 'stats.json'), true);
    if (!$stats) {
        $stats = [
            'students' => 12500,
            'courses' => 150,
            'rating' => 4.8,
            'instructors' => 45,
            'success_stories' => 2300
        ];
        file_put_contents(DATA_PATH . 'stats.json', json_encode($stats, JSON_PRETTY_PRINT));
    }
    return $stats;
}

function getKeyBenefits() {
    return [
        [
            'icon' => '<i class="fas fa-bullseye fa-3x text-primary"></i>',
            'title' => 'Project-Based Learning',
            'description' => 'Learn by building real-world projects that showcase your skills'
        ],
        [
            'icon' => '<i class="fas fa-bolt fa-3x text-primary"></i>',
            'title' => 'Self-Paced Learning',
            'description' => 'Study at your own pace with lifetime access to all course materials'
        ],
        [
            'icon' => '<i class="fas fa-chalkboard-teacher fa-3x text-primary"></i>',
            'title' => 'Expert Instructors',
            'description' => 'Learn from industry professionals with years of experience'
        ],
        [
            'icon' => '<i class="fas fa-trophy fa-3x text-primary"></i>',
            'title' => 'Career Support',
            'description' => 'Get help with job preparation and portfolio building'
        ]
    ];
}
?>