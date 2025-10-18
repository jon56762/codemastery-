<?php

// File storage functions
function saveToFile($filename, $data) {
    $filePath = DATA_PATH . $filename;
    return file_put_contents($filePath, json_encode($data, JSON_PRETTY_PRINT));
}

function getFromFile($filename) {
    $filePath = DATA_PATH . $filename;
    if (!file_exists($filePath)) {
        // Create empty file if it doesn't exist
        saveToFile($filename, []);
        return [];
    }
    
    $content = file_get_contents($filePath);
    if (empty($content)) {
        return [];
    }
    
    return json_decode($content, true) ?? [];
}

// ==================== USER MANAGEMENT FUNCTIONS ====================

function getAllUsers() {
    return getFromFile('users.json');
}

function getUserById($id) {
    $users = getAllUsers();
    foreach ($users as $user) {
        if ($user['id'] == $id) {
            return $user;
        }
    }
    return null;
}

function getUserByEmail($email) {
    $users = getAllUsers();
    foreach ($users as $user) {
        if ($user['email'] === $email) {
            return $user;
        }
    }
    return null;
}

function createUser($userData) {
    $users = getAllUsers();
    
    // Check if email already exists
    foreach ($users as $user) {
        if ($user['email'] === $userData['email']) {
            return false;
        }
    }
    
    // Generate new ID
    $newId = 1;
    if (!empty($users)) {
        $ids = array_column($users, 'id');
        $newId = max($ids) + 1;
    }
    
    $user = [
        'id' => $newId,
        'name' => trim($userData['name']),
        'email' => trim($userData['email']),
        'password' => password_hash($userData['password'], PASSWORD_DEFAULT),
        'role' => $userData['role'] ?? 'student',
        'status' => 'active',
        'avatar' => $userData['avatar'] ?? '/assets/images/avatars/default.jpg',
        'bio' => $userData['bio'] ?? '',
        'skills' => $userData['skills'] ?? [],
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
    ];
    
    $users[] = $user;
    return saveToFile('users.json', $users) ? $user : false;
}

function updateUser($userId, $userData) {
    $users = getAllUsers();
    $updated = false;
    
    foreach ($users as &$user) {
        if ($user['id'] == $userId) {
            $user['name'] = $userData['name'] ?? $user['name'];
            $user['bio'] = $userData['bio'] ?? $user['bio'];
            $user['avatar'] = $userData['avatar'] ?? $user['avatar'];
            $user['skills'] = $userData['skills'] ?? $user['skills'];
            $user['updated_at'] = date('Y-m-d H:i:s');
            $updated = true;
            break;
        }
    }
    
    return $updated ? saveToFile('users.json', $users) : false;
}

// ==================== COURSE MANAGEMENT FUNCTIONS ====================

function getAllCourses() {
    return getFromFile('courses.json');
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

function getFeaturedCourses($limit = 4) {
    $courses = getAllCourses();
    $featured = array_filter($courses, function($course) {
        return ($course['featured'] ?? false) && ($course['status'] === 'published');
    });
    return array_slice($featured, 0, $limit);
}

function getCoursesByInstructor($instructorId) {
    $courses = getAllCourses();
    return array_filter($courses, function($course) use ($instructorId) {
        return $course['instructor_id'] == $instructorId;
    });
}

function getCourseCategories() {
    $courses = getAllCourses();
    $categories = array_unique(array_column($courses, 'category'));
    sort($categories);
    return $categories;
}

function createCourse($courseData) {
    $courses = getAllCourses();
    
    // Generate new ID
    $newId = 1;
    if (!empty($courses)) {
        $ids = array_column($courses, 'id');
        $newId = max($ids) + 1;
    }
    
    $course = [
        'id' => $newId,
        'title' => trim($courseData['title']),
        'description' => trim($courseData['description']),
        'short_description' => trim($courseData['short_description'] ?? ''),
        'instructor_id' => $courseData['instructor_id'],
        'instructor_name' => $courseData['instructor_name'],
        'price' => $courseData['price'] ?? 0,
        'category' => $courseData['category'],
        'level' => $courseData['level'] ?? 'beginner',
        'duration' => $courseData['duration'] ?? 0,
        'lessons' => $courseData['lessons'] ?? 0,
        'thumbnail' => $courseData['thumbnail'] ?? '/assets/images/courses/default.jpg',
        'promo_video' => $courseData['promo_video'] ?? '',
        'curriculum' => $courseData['curriculum'] ?? [],
        'resources' => $courseData['resources'] ?? [],
        'status' => $courseData['status'] ?? 'draft',
        'featured' => $courseData['featured'] ?? false,
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
    ];
    
    $courses[] = $course;
    return saveToFile('courses.json', $courses) ? $course : false;
}

function updateCourse($courseId, $courseData) {
    $courses = getAllCourses();
    $updated = false;
    
    foreach ($courses as &$course) {
        if ($course['id'] == $courseId) {
            $course['title'] = $courseData['title'] ?? $course['title'];
            $course['description'] = $courseData['description'] ?? $course['description'];
            $course['price'] = $courseData['price'] ?? $course['price'];
            $course['category'] = $courseData['category'] ?? $course['category'];
            $course['level'] = $courseData['level'] ?? $course['level'];
            $course['thumbnail'] = $courseData['thumbnail'] ?? $course['thumbnail'];
            $course['status'] = $courseData['status'] ?? $course['status'];
            $course['featured'] = $courseData['featured'] ?? $course['featured'];
            $course['updated_at'] = date('Y-m-d H:i:s');
            $updated = true;
            break;
        }
    }
    
    return $updated ? saveToFile('courses.json', $courses) : false;
}

// ==================== ENROLLMENT FUNCTIONS ====================

function getAllEnrollments() {
    return getFromFile('enrollments.json');
}

function enrollStudent($courseId, $studentId) {
    $enrollments = getAllEnrollments();
    
    // Check if already enrolled
    foreach ($enrollments as $enrollment) {
        if ($enrollment['course_id'] == $courseId && $enrollment['student_id'] == $studentId) {
            return false;
        }
    }
    
    $enrollment = [
        'id' => generateEnrollmentId(),
        'course_id' => $courseId,
        'student_id' => $studentId,
        'enrolled_at' => date('Y-m-d H:i:s'),
        'progress' => 0,
        'completed_lessons' => [],
        'status' => 'active'
    ];
    
    $enrollments[] = $enrollment;
    return saveToFile('enrollments.json', $enrollments) ? $enrollment : false;
}

function getStudentEnrollments($studentId) {
    $enrollments = getAllEnrollments();
    return array_filter($enrollments, function($enrollment) use ($studentId) {
        return $enrollment['student_id'] == $studentId;
    });
}

function getCourseEnrollments($courseId) {
    $enrollments = getAllEnrollments();
    return array_filter($enrollments, function($enrollment) use ($courseId) {
        return $enrollment['course_id'] == $courseId;
    });
}

function updateLessonProgress($enrollmentId, $lessonId, $completed = true) {
    $enrollments = getAllEnrollments();
    $updated = false;
    
    foreach ($enrollments as &$enrollment) {
        if ($enrollment['id'] == $enrollmentId) {
            if ($completed && !in_array($lessonId, $enrollment['completed_lessons'])) {
                $enrollment['completed_lessons'][] = $lessonId;
            } elseif (!$completed) {
                $enrollment['completed_lessons'] = array_diff($enrollment['completed_lessons'], [$lessonId]);
            }
            
            // Calculate progress percentage
            $course = getCourseById($enrollment['course_id']);
            $totalLessons = count($course['curriculum'] ?? []);
            if ($totalLessons > 0) {
                $enrollment['progress'] = round((count($enrollment['completed_lessons']) / $totalLessons) * 100);
            }
            
            $updated = true;
            break;
        }
    }
    
    return $updated ? saveToFile('enrollments.json', $enrollments) : false;
}

function generateEnrollmentId() {
    return 'ENR' . date('YmdHis') . rand(1000, 9999);
}

// ==================== INSTRUCTOR APPLICATION FUNCTIONS ====================

function getAllInstructorApplications() {
    return getFromFile('instructor-applications.json');
}

function submitInstructorApplication($applicationData) {
    $applications = getAllInstructorApplications();
    
    // Check if user already has pending application
    foreach ($applications as $app) {
        if ($app['user_id'] == $applicationData['user_id'] && $app['status'] == 'pending') {
            return false;
        }
    }
    
    $application = [
        'id' => generateApplicationId(),
        'user_id' => $applicationData['user_id'],
        'name' => $applicationData['name'],
        'email' => $applicationData['email'],
        'experience' => $applicationData['experience'],
        'specialization' => $applicationData['specialization'],
        'portfolio' => $applicationData['portfolio'] ?? '',
        'linkedin' => $applicationData['linkedin'] ?? '',
        'status' => 'pending',
        'submitted_at' => date('Y-m-d H:i:s'),
        'reviewed_at' => null,
        'reviewed_by' => null,
        'notes' => ''
    ];
    
    $applications[] = $application;
    return saveToFile('instructor-applications.json', $applications) ? $application : false;
}

function updateApplicationStatus($applicationId, $status, $reviewerId = null, $notes = '') {
    $applications = getAllInstructorApplications();
    $updated = false;
    
    foreach ($applications as &$app) {
        if ($app['id'] == $applicationId) {
            $app['status'] = $status;
            $app['reviewed_at'] = date('Y-m-d H:i:s');
            $app['reviewed_by'] = $reviewerId;
            $app['notes'] = $notes;
            $updated = true;
            break;
        }
    }
    
    return $updated ? saveToFile('instructor-applications.json', $applications) : false;
}

function generateApplicationId() {
    return 'APP' . date('YmdHis') . rand(1000, 9999);
}

// ==================== PLATFORM STATISTICS ====================

function getPlatformStats() {
    $users = getAllUsers();
    $courses = getAllCourses();
    $enrollments = getAllEnrollments();
    
    $students = array_filter($users, function($user) {
        return $user['role'] === 'student';
    });
    
    $instructors = array_filter($users, function($user) {
        return $user['role'] === 'instructor';
    });
    
    $publishedCourses = array_filter($courses, function($course) {
        return $course['status'] === 'published';
    });
    
    return [
        'total_students' => count($students),
        'total_courses' => count($publishedCourses),
        'total_instructors' => count($instructors),
        'total_enrollments' => count($enrollments),
        'average_rating' => calculateAverageRating()
    ];
}

function calculateAverageRating() {
    $courses = getAllCourses();
    $totalRating = 0;
    $ratedCourses = 0;
    
    foreach ($courses as $course) {
        if (isset($course['rating']) && $course['rating'] > 0) {
            $totalRating += $course['rating'];
            $ratedCourses++;
        }
    }
    
    return $ratedCourses > 0 ? round($totalRating / $ratedCourses, 1) : 4.5;
}

// ==================== TESTIMONIALS & CONTENT ====================

function getTestimonials() {
    return getFromFile('testimonials.json');
}

function getBlogPosts($limit = null) {
    $posts = getFromFile('blog.json');
    if ($limit) {
        return array_slice($posts, 0, $limit);
    }
    return $posts;
}

function getBlogPostById($id) {
    $posts = getBlogPosts();
    foreach ($posts as $post) {
        if ($post['id'] == $id) {
            return $post;
        }
    }
    return null;
}

// ==================== NEWSLETTER FUNCTIONS ====================

function handleNewsletterSignup($email) {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    }
    
    $newsletter = getFromFile('newsletter.json');
    
    // Check if already subscribed
    foreach ($newsletter as $subscriber) {
        if ($subscriber['email'] === $email) {
            return false;
        }
    }
    
    $subscriber = [
        'email' => $email,
        'subscribed_at' => date('Y-m-d H:i:s'),
        'active' => true
    ];
    
    $newsletter[] = $subscriber;
    $success = saveToFile('newsletter.json', $newsletter);
    
    if ($success) {
        $_SESSION['success'] = "Thanks for subscribing! We'll keep you updated.";
    }
    
    return $success;
}

// ==================== UTILITY FUNCTIONS ====================

function formatPrice($price) {
    return '$' . number_format($price, 2);
}

function getCourseLevelBadge($level) {
    $badges = [
        'beginner' => 'success',
        'intermediate' => 'warning',
        'advanced' => 'danger'
    ];
    
    return $badges[$level] ?? 'secondary';
}

/**
 * Format duration in minutes to readable format
 */
function formatDuration($minutes) {
    if ($minutes < 60) {
        return $minutes . ' min';
    } else {
        $hours = floor($minutes / 60);
        $mins = $minutes % 60;
        if ($mins > 0) {
            return $hours . 'h ' . $mins . 'm';
        } else {
            return $hours . 'h';
        }
    }
}

function sanitizeInput($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

// ==================== INITIALIZATION ====================

function initializeEmptyData() {
    // Only create testimonials if they don't exist
    if (empty(getFromFile('testimonials.json'))) {
        $testimonials = [
            [
                'id' => 1,
                'name' => 'Alex Johnson',
                'role' => 'Full Stack Developer',
                'avatar' => '/assets/images/avatars/1.jpg',
                'text' => 'This platform helped me transition from retail to a developer role in 6 months. The project-based approach was exactly what I needed.',
                'rating' => 5
            ],
            [
                'id' => 2,
                'name' => 'Maria Garcia',
                'role' => 'Frontend Developer',
                'avatar' => '/assets/images/avatars/2.jpg',
                'text' => 'The courses are well-structured and the instructors are amazing. I doubled my salary after completing the JavaScript course.',
                'rating' => 5
            ],
            [
                'id' => 3,
                'name' => 'David Kim',
                'role' => 'Backend Developer',
                'avatar' => '/assets/images/woman1.jpg',
                'text' => 'As a complete beginner, I found the community support incredible. The instructors are always available to help.',
                'rating' => 4
            ]
        ];
        saveToFile('testimonials.json', $testimonials);
    }
    
    // Create sample blog posts if empty
    if (empty(getFromFile('blog.json'))) {
        $blogPosts = [
            [
                'id' => 1,
                'title' => 'Getting Started with Web Development in 2024',
                'excerpt' => 'Learn the essential skills and tools you need to start your web development journey this year.',
                'content' => 'Full blog post content here...',
                'author' => 'Sarah Wilson',
                'category' => 'Web Development',
                'published_at' => date('Y-m-d H:i:s', strtotime('-5 days')),
                'image' => '/assets/images/blog/web-dev.jpg',
                'status' => 'published'
            ],
            [
                'id' => 2,
                'title' => 'Why Project-Based Learning is More Effective',
                'excerpt' => 'Discover how building real projects can accelerate your learning and career growth.',
                'content' => 'Full blog post content here...',
                'author' => 'Mike Chen',
                'category' => 'Learning',
                'published_at' => date('Y-m-d H:i:s', strtotime('-10 days')),
                'image' => '/assets/images/blog/learning.jpg',
                'status' => 'published'
            ]
        ];
        saveToFile('blog.json', $blogPosts);
    }
    
    // Create empty files if they don't exist
    $files = [
        'users.json', 
        'courses.json', 
        'enrollments.json', 
        'instructor-applications.json', 
        'newsletter.json',
        'settings.json'
    ];
    
    foreach ($files as $file) {
        if (!file_exists(DATA_PATH . $file)) {
            saveToFile($file, []);
        }
    }
}

// Initialize empty data on first run
initializeEmptyData();

/**
 * Get new courses (recently added)
 */
function getNewCourses($limit = 4) {
    $courses = getAllCourses();
    
    // Sort by creation date (newest first)
    usort($courses, function($a, $b) {
        return strtotime($b['created_at']) <=> strtotime($a['created_at']);
    });
    
    // Filter only published courses
    $published = array_filter($courses, function($course) {
        return $course['status'] === 'published';
    });
    
    return array_slice($published, 0, $limit);
}

/**
 * Get popular courses based on enrollment count
 */
function getPopularCourses($limit = 4) {
    $courses = getAllCourses();
    
    // Sort by enrollment count (highest first)
    usort($courses, function($a, $b) {
        return ($b['enrollment_count'] ?? 0) <=> ($a['enrollment_count'] ?? 0);
    });
    
    // Filter only published courses
    $published = array_filter($courses, function($course) {
        return $course['status'] === 'published';
    });
    
    return array_slice($published, 0, $limit);
}

/**
 * Get courses by category
 */
function getCoursesByCategory($category, $limit = null) {
    $courses = getAllCourses();
    $filtered = array_filter($courses, function($course) use ($category) {
        return $course['category'] === $category && $course['status'] === 'published';
    });
    
    if ($limit) {
        return array_slice($filtered, 0, $limit);
    }
    
    return $filtered;
}

/**
 * Get total courses count by category
 */
function getCourseCountByCategory($category) {
    $courses = getCoursesByCategory($category);
    return count($courses);
}

// ==================== IMAGE HELPER FUNCTIONS ====================

/**
 * Get course image with fallback
 */
function getCourseImage($course) {
    $thumbnail = $course['thumbnail'] ?? '';
    
    // If thumbnail exists and is not empty, use it
    if (!empty($thumbnail)) {
        return $thumbnail;
    }
    
    // Fallback to placeholder based on category
    return getCoursePlaceholder($course['category'] ?? 'default');
}

/**
 * Get placeholder image for courses
 */
function getCoursePlaceholder($category) {
    $colors = [
        'Web Development' => '007bff',
        'Data Science' => '28a745', 
        'Mobile Development' => 'dc3545',
        'Machine Learning' => '6f42c1',
        'Programming' => 'fd7e14',
        'default' => '6c757d'
    ];
    
    $color = $colors[$category] ?? $colors['default'];
    $text = urlencode($category);
    
    return "https://via.placeholder.com/600x400/{$color}/ffffff?text={$text}";
}

/**
 * Get instructor avatar
 */
function getInstructorAvatar($instructorName, $size = 30) {
    $initial = strtoupper(substr($instructorName, 0, 1));
    return "https://via.placeholder.com/{$size}x{$size}/007bff/ffffff?text={$initial}";
}
?>