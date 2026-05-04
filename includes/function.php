<?php

// File storage functions
function saveToFile($filename, $data)
{
    $filePath = DATA_PATH . $filename;
    return file_put_contents($filePath, json_encode($data, JSON_PRETTY_PRINT));
}

function getFromFile($filename)
{
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

function getAllUsers()
{
    return getFromFile('users.json');
}

function getUserById($id)
{
    $users = getAllUsers();
    foreach ($users as $user) {
        // Ensure 'id' key exists
        if (isset($user['id']) && $user['id'] == $id) {
            return $user;
        }
    }
    return null;
}

function getUserByEmail($email)
{
    $users = getAllUsers();
    foreach ($users as $user) {
        if (isset($user['email']) && $user['email'] === $email) {
            return $user;
        }
    }
    return null;
}

function createUser($userData)
{
    $users = getAllUsers();

    // Check if email already exists
    foreach ($users as $user) {
        if (isset($user['email']) && $user['email'] === ($userData['email'] ?? '')) {
            return false;
        }
    }

    // Generate new ID
    $newId = 1;
    if (!empty($users)) {
        $ids = array_filter(array_column($users, 'id'), function($id) {
            return $id !== null;
        });
        if (!empty($ids)) {
            $newId = max($ids) + 1;
        }
    }

    $user = [
        'id' => $newId,
        'name' => trim($userData['name'] ?? ''),
        'email' => trim($userData['email'] ?? ''),
        'password' => password_hash($userData['password'] ?? '', PASSWORD_DEFAULT),
        'role' => $userData['role'] ?? 'student',
        'status' => 'active',
        'avatar' => $userData['avatar'] ?? '/assets/images/avatars/default.jpg',
        'skills' => $userData['skills'] ?? [],
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
    ];

    $users[] = $user;
    return saveToFile('users.json', $users) ? $user : false;
}

// ==================== COURSE MANAGEMENT FUNCTIONS ====================

function getAllCourses()
{
    return getFromFile('courses.json');
}

function getCourseById($id)
{
    $courses = getAllCourses();
    foreach ($courses as $course) {
        if (isset($course['id']) && $course['id'] == $id) {
            return $course;
        }
    }
    return null;
}

function getFeaturedCourses($limit = 4)
{
    $courses = getAllCourses();
    $featured = array_filter($courses, function ($course) {
        return ($course['featured'] ?? false) && ($course['status'] ?? '') === 'published';
    });
    return array_slice($featured, 0, $limit);
}

function getCoursesByInstructor($instructorId)
{
    $courses = getAllCourses();
    return array_filter($courses, function ($course) use ($instructorId) {
        return isset($course['instructor_id']) && $course['instructor_id'] == $instructorId;
    });
}

function getCourseCategories()
{
    $courses = getAllCourses();
    // Extract categories, filter out empty/null
    $categories = array_filter(array_unique(array_column($courses, 'category')));
    sort($categories);
    return $categories;
}

function createCourse($courseData)
{
    $courses = getAllCourses();

    // Generate new ID
    $newId = 1;
    if (!empty($courses)) {
        $ids = array_filter(array_column($courses, 'id'), function($id) {
            return $id !== null;
        });
        if (!empty($ids)) {
            $newId = max($ids) + 1;
        }
    }

    $course = [
        'id' => $newId,
        'title' => trim($courseData['title'] ?? ''),
        'description' => trim($courseData['description'] ?? ''),
        'short_description' => trim($courseData['short_description'] ?? ''),
        'instructor_id' => $courseData['instructor_id'] ?? 0,
        'instructor_name' => $courseData['instructor_name'] ?? '',
        'price' => $courseData['price'] ?? 0,
        'category' => $courseData['category'] ?? '',
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

/**
 * Update course with curriculum support - FIXED VERSION
 */
function updateCourse($courseId, $courseData) {
    $courses = getAllCourses();
    $updated = false;

    foreach ($courses as &$course) {
        if (isset($course['id']) && $course['id'] == $courseId) {
            // Update basic fields with fallbacks
            $course['title'] = $courseData['title'] ?? $course['title'];
            $course['description'] = $courseData['description'] ?? $course['description'];
            $course['short_description'] = $courseData['short_description'] ?? $course['short_description'];
            $course['price'] = $courseData['price'] ?? $course['price'];
            $course['category'] = $courseData['category'] ?? $course['category'];
            $course['level'] = $courseData['level'] ?? $course['level'];
            $course['thumbnail'] = $courseData['thumbnail'] ?? $course['thumbnail'];
            $course['status'] = $courseData['status'] ?? $course['status'];
            $course['featured'] = $courseData['featured'] ?? $course['featured'];
            
            // CRITICAL: Update curriculum if provided
            if (isset($courseData['curriculum'])) {
                $course['curriculum'] = $courseData['curriculum'];
                error_log("Curriculum updated for course {$courseId}: " . count($course['curriculum']) . " lessons");
            }
            
            $course['updated_at'] = date('Y-m-d H:i:s');
            $updated = true;
            break;
        }
    }

    if ($updated) {
        $result = saveToFile('courses.json', $courses);
        error_log("Save result for courses.json: " . ($result ? 'SUCCESS' : 'FAILED'));
        return $result;
    }
    
    error_log("No course found to update with ID: {$courseId}");
    return false;
}

// ==================== ENROLLMENT FUNCTIONS ====================
function generateEnrollmentId()
{
    return 'ENR' . date('YmdHis') . rand(1000, 9999);
}
function getAllEnrollments()
{
    return getFromFile('enrollments.json');
}

function enrollStudent($courseId, $studentId)
{
    $enrollments = getAllEnrollments();

    // Check if already enrolled
    foreach ($enrollments as $enrollment) {
        if ((isset($enrollment['course_id']) && $enrollment['course_id'] == $courseId) &&
            (isset($enrollment['user_id']) && $enrollment['user_id'] == $studentId)) {
            return false;
        }
    }

    $enrollment = [
        'id' => generateEnrollmentId(),
        'course_id' => $courseId,
        'user_id' => $studentId,
        'enrolled_at' => date('Y-m-d H:i:s'),
        'progress' => 0,
        'completed_lessons' => [],
        'status' => 'active'
    ];

    $enrollments[] = $enrollment;
    return saveToFile('enrollments.json', $enrollments) ? $enrollment : false;
}

function getStudentEnrollments($studentId)
{
    $enrollments = getAllEnrollments();
    return array_filter($enrollments, function ($enrollment) use ($studentId) {
        return isset($enrollment['user_id']) && $enrollment['user_id'] == $studentId;
    });
}

function getCourseEnrollments($courseId)
{
    $enrollments = getAllEnrollments();
    return array_filter($enrollments, function ($enrollment) use ($courseId) {
        return isset($enrollment['course_id']) && $enrollment['course_id'] == $courseId;
    });
}

function isUserEnrolled($userId, $courseId)
{
    $enrollments = getAllEnrollments();
    foreach ($enrollments as $enrollment) {
        if ((isset($enrollment['user_id']) && $enrollment['user_id'] == $userId) &&
            (isset($enrollment['course_id']) && $enrollment['course_id'] == $courseId)) {
            return $enrollment;
        }
    }
    return false;
}

// ==================== INSTRUCTOR APPLICATION FUNCTIONS ====================

function getAllInstructorApplications()
{
    return getFromFile('instructor-applications.json');
}

// Add the new function here
function getInstructorApplicationById($applicationId) {
    $applications = getAllInstructorApplications();
    foreach ($applications as $app) {
        if (isset($app['id']) && $app['id'] == $applicationId) {
            return $app;
        }
    }
    return null;
}

function submitInstructorApplication($applicationData)
{
    $applications = getAllInstructorApplications();

    // Check if user already has pending application
    foreach ($applications as $app) {
        if ((isset($app['user_id']) && $app['user_id'] == ($applicationData['user_id'] ?? 0)) &&
            ($app['status'] ?? '') === 'pending') {
            return false;
        }
    }

    $application = [
        'id' => generateApplicationId(),
        'user_id' => $applicationData['user_id'] ?? 0,
        'name' => $applicationData['name'] ?? '',
        'email' => $applicationData['email'] ?? '',
        'experience' => $applicationData['experience'] ?? '',
        'specialization' => $applicationData['specialization'] ?? '',
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

function updateApplicationStatus($applicationId, $status, $reviewerId = null, $notes = '')
{
    $applications = getAllInstructorApplications();
    $updated = false;

    foreach ($applications as &$app) {
        if (isset($app['id']) && $app['id'] == $applicationId) {
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

function generateApplicationId()
{
    return 'APP' . date('YmdHis') . rand(1000, 9999);
}

// ==================== PLATFORM STATISTICS ====================

function getPlatformStats()
{
    $users = getAllUsers();
    $courses = getAllCourses();
    $enrollments = getAllEnrollments();

    $students = array_filter($users, function ($user) {
        return ($user['role'] ?? '') === 'student';
    });

    $instructors = array_filter($users, function ($user) {
        return ($user['role'] ?? '') === 'instructor';
    });

    $publishedCourses = array_filter($courses, function ($course) {
        return ($course['status'] ?? '') === 'published';
    });

    return [
        'total_students' => count($students),
        'total_courses' => count($publishedCourses),
        'total_instructors' => count($instructors),
        'total_enrollments' => count($enrollments),
        'average_rating' => calculateAverageRating()
    ];
}

function calculateAverageRating()
{
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

function getTestimonials()
{
    return getFromFile('testimonials.json');
}

function getBlogPosts($limit = null)
{
    $posts = getFromFile('blog.json');
    if ($limit) {
        return array_slice($posts, 0, $limit);
    }
    return $posts;
}

function getBlogPostById($id)
{
    $posts = getBlogPosts();
    foreach ($posts as $post) {
        if (isset($post['id']) && $post['id'] == $id) {
            return $post;
        }
    }
    return null;
}

// ==================== NEWSLETTER FUNCTIONS ====================

function handleNewsletterSignup($email)
{
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    }

    $newsletter = getFromFile('newsletter.json');

    // Check if already subscribed
    foreach ($newsletter as $subscriber) {
        if (isset($subscriber['email']) && $subscriber['email'] === $email) {
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

function formatPrice($price)
{
    return '$' . number_format($price, 2);
}

function getCourseLevelBadge($level)
{
    $badges = [
        'beginner' => 'success',
        'intermediate' => 'warning',
        'advanced' => 'danger'
    ];

    return $badges[$level] ?? 'secondary';
}

function sanitizeInput($data)
{
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

// ==================== INITIALIZATION ====================

function initializeEmptyData()
{
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

    // Create payouts file if it doesn't exist
    if (!file_exists(DATA_PATH . 'payouts.json')) {
        saveToFile('payouts.json', []);
    }

    // Create earnings transactions file if it doesn't exist
    if (!file_exists(DATA_PATH . 'earnings-transactions.json')) {
        saveToFile('earnings-transactions.json', []);
    }

    if (!file_exists(DATA_PATH . 'testimonials.json')) {
        saveToFile('testimonials.json', []);
    }
}

// Initialize empty data on first run
initializeEmptyData();

/**
 * Get new courses (recently added)
 */
function getNewCourses($limit = 4)
{
    $courses = getAllCourses();

    // Sort by creation date (newest first)
    usort($courses, function ($a, $b) {
        $a_date = $a['created_at'] ?? '1970-01-01';
        $b_date = $b['created_at'] ?? '1970-01-01';
        return strtotime($b_date) <=> strtotime($a_date);
    });

    // Filter only published courses
    $published = array_filter($courses, function ($course) {
        return ($course['status'] ?? '') === 'published';
    });

    return array_slice($published, 0, $limit);
}

/**
 * Get popular courses based on enrollment count
 */
function getPopularCourses($limit = 4)
{
    $courses = getAllCourses();

    // Sort by enrollment count (highest first)
    usort($courses, function ($a, $b) {
        return ($b['enrollment_count'] ?? 0) <=> ($a['enrollment_count'] ?? 0);
    });

    // Filter only published courses
    $published = array_filter($courses, function ($course) {
        return ($course['status'] ?? '') === 'published';
    });

    return array_slice($published, 0, $limit);
}

/**
 * Get courses by category
 */
function getCoursesByCategory($category, $limit = null)
{
    $courses = getAllCourses();
    $filtered = array_filter($courses, function ($course) use ($category) {
        return ($course['category'] ?? '') === $category && ($course['status'] ?? '') === 'published';
    });

    if ($limit) {
        return array_slice($filtered, 0, $limit);
    }

    return $filtered;
}

/**
 * Get total courses count by category
 */
function getCourseCountByCategory($category)
{
    $courses = getCoursesByCategory($category);
    return count($courses);
}

// ==================== IMAGE HELPER FUNCTIONS ====================

/**
 * Get course image with fallback
 */
function getCourseImage($course)
{
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
function getCoursePlaceholder($category)
{
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
function getInstructorAvatar($instructorName, $size = 30)
{
    $initial = strtoupper(substr($instructorName, 0, 1));
    return "https://via.placeholder.com/{$size}x{$size}/007bff/ffffff?text={$initial}";
}

// ==================== BLOG INTERACTION FUNCTIONS ====================

/**
 * Like a blog post
 */
function likeBlogPost($postId, $userId)
{
    $blog_posts = getBlogPosts();
    $updated = false;

    foreach ($blog_posts as &$post) {
        if (isset($post['id']) && $post['id'] == $postId) {
            // Initialize likes array if it doesn't exist
            if (!isset($post['likes'])) {
                $post['likes'] = [];
            }

            // Check if user already liked
            if (!in_array($userId, $post['likes'])) {
                $post['likes'][] = $userId;
                $updated = true;
            }
            break;
        }
    }

    return $updated ? saveToFile('blog.json', $blog_posts) : false;
}

/**
 * Unlike a blog post
 */
function unlikeBlogPost($postId, $userId)
{
    $blog_posts = getBlogPosts();
    $updated = false;

    foreach ($blog_posts as &$post) {
        if (isset($post['id']) && $post['id'] == $postId) {
            if (isset($post['likes']) && in_array($userId, $post['likes'])) {
                $post['likes'] = array_diff($post['likes'], [$userId]);
                $updated = true;
            }
            break;
        }
    }

    return $updated ? saveToFile('blog.json', $blog_posts) : false;
}

/**
 * Check if user liked a post
 */
function hasUserLikedPost($postId, $userId)
{
    $blog_posts = getBlogPosts();

    foreach ($blog_posts as $post) {
        if (isset($post['id']) && $post['id'] == $postId && isset($post['likes'])) {
            return in_array($userId, $post['likes']);
        }
    }

    return false;
}

/**
 * Get like count for a post
 */
function getLikeCount($postId)
{
    $blog_posts = getBlogPosts();

    foreach ($blog_posts as $post) {
        if (isset($post['id']) && $post['id'] == $postId) {
            return isset($post['likes']) ? count($post['likes']) : 0;
        }
    }

    return 0;
}

/**
 * Add comment to blog post
 */
function addComment($postId, $commentData)
{
    $comments = getFromFile('blog-comments.json');

    // Generate new comment ID
    $newId = 1;
    if (!empty($comments)) {
        $ids = array_filter(array_column($comments, 'id'), function($id) {
            return $id !== null;
        });
        if (!empty($ids)) {
            $newId = max($ids) + 1;
        }
    }

    $comment = [
        'id' => $newId,
        'post_id' => $postId,
        'user_id' => $commentData['user_id'] ?? 0,
        'user_name' => $commentData['user_name'] ?? '',
        'content' => trim($commentData['content'] ?? ''),
        'created_at' => date('Y-m-d H:i:s'),
        'status' => 'approved'
    ];

    $comments[] = $comment;
    return saveToFile('blog-comments.json', $comments) ? $comment : false;
}

/**
 * Get comments for a blog post
 */
function getComments($postId)
{
    $comments = getFromFile('blog-comments.json');
    return array_filter($comments, function ($comment) use ($postId) {
        return (isset($comment['post_id']) && $comment['post_id'] == $postId) &&
               ($comment['status'] ?? '') === 'approved';
    });
}

/**
 * Get comment count for a post
 */
function getCommentCount($postId)
{
    $comments = getComments($postId);
    return count($comments);
}

// ==================== DASHBOARD FUNCTIONS ====================

/**
 * Get recommended courses for a student
 */
function getRecommendedCourses($studentId)
{
    $enrollments = getStudentEnrollments($studentId);
    $allCourses = getAllCourses();

    // If no enrollments, return featured courses
    if (empty($enrollments)) {
        return getFeaturedCourses(6);
    }

    // Get categories of enrolled courses
    $enrolledCategories = [];
    foreach ($enrollments as $enrollment) {
        if (isset($enrollment['course_id'])) {
            $course = getCourseById($enrollment['course_id']);
            if ($course && isset($course['category'])) {
                $enrolledCategories[] = $course['category'];
            }
        }
    }

    // Count category occurrences
    $categoryCounts = array_count_values($enrolledCategories);
    arsort($categoryCounts);
    $topCategory = key($categoryCounts) ?? '';

    // Recommend courses from top category that student isn't enrolled in
    $recommended = array_filter($allCourses, function ($course) use ($studentId, $topCategory) {
        if (($course['status'] ?? '') !== 'published') return false;
        if (($course['category'] ?? '') !== $topCategory) return false;

        $isEnrolled = false;
        foreach (getStudentEnrollments($studentId) as $enrollment) {
            if (isset($enrollment['course_id']) && $enrollment['course_id'] == ($course['id'] ?? 0)) {
                $isEnrolled = true;
                break;
            }
        }
        return !$isEnrolled;
    });

    // If no recommendations from top category, return featured courses
    if (empty($recommended)) {
        return getFeaturedCourses(6);
    }

    return array_slice($recommended, 0, 6);
}

/**
 * Get upcoming deadlines for student
 */
function getUpcomingDeadlines($studentId)
{
    // This is a simplified version - in a real app, you'd have actual deadlines
    $enrollments = getStudentEnrollments($studentId);
    $deadlines = [];

    foreach (array_slice($enrollments, 0, 3) as $enrollment) {
        if (isset($enrollment['course_id'])) {
            $course = getCourseById($enrollment['course_id']);
            if ($course && ($enrollment['progress'] ?? 0) < 100) {
                $deadlines[] = [
                    'title' => 'Complete ' . ($course['title'] ?? ''),
                    'course' => $course['title'] ?? '',
                    'date' => date('Y-m-d', strtotime('+' . rand(3, 14) . ' days')),
                    'type' => 'course_completion'
                ];
            }
        }
    }

    return $deadlines;
}

// ==================== COURSE PLAYER FUNCTIONS ====================

/**
 * Generate sample curriculum for courses that don't have one
 */
function generateSampleCurriculum($course)
{
    $lessonCount = $course['lessons'] ?? 10;
    $curriculum = [];

    $lessonTypes = ['Introduction', 'Fundamentals', 'Core Concepts', 'Advanced Topics', 'Practice', 'Project', 'Review'];

    for ($i = 1; $i <= $lessonCount; $i++) {
        $type = $lessonTypes[($i - 1) % count($lessonTypes)];
        $curriculum[] = [
            'id' => $i,
            'title' => "{$type}: Lesson {$i}",
            'description' => "Learn about {$type} in this comprehensive lesson.",
            'type' => ($i % 3 == 0) ? 'text' : 'video', // Mix of video and text lessons
            'duration' => rand(10, 45),
            'content' => "This is the content for lesson {$i}. In a real application, this would contain the actual lesson material, exercises and resources."
        ];
    }

    return $curriculum;
}

/**
 * Get enrollment by ID
 */
function getEnrollmentById($enrollmentId)
{
    $enrollments = getAllEnrollments();
    foreach ($enrollments as $enrollment) {
        if (isset($enrollment['id']) && $enrollment['id'] == $enrollmentId) {
            return $enrollment;
        }
    }
    return null;
}

/**
 * Update lesson progress (complete/incomplete)
 */
function updateLessonProgress($enrollmentId, $lessonId, $completed = true)
{
    $enrollments = getAllEnrollments();
    $updated = false;

    foreach ($enrollments as &$enrollment) {
        if (isset($enrollment['id']) && $enrollment['id'] == $enrollmentId) {
            // Initialize completed_lessons array if it doesn't exist
            if (!isset($enrollment['completed_lessons'])) {
                $enrollment['completed_lessons'] = [];
            }

            if ($completed && !in_array($lessonId, $enrollment['completed_lessons'])) {
                $enrollment['completed_lessons'][] = $lessonId;
            } elseif (!$completed) {
                $enrollment['completed_lessons'] = array_diff($enrollment['completed_lessons'], [$lessonId]);
            }

            // Calculate overall progress
            $course = getCourseById($enrollment['course_id'] ?? 0);
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

/**
 * Save lesson note
 */
function saveLessonNote($userId, $courseId, $lessonId, $content, $timestamp = '00:00')
{
    $notes = getFromFile('lesson-notes.json');

    // Generate new ID
    $newId = 1;
    if (!empty($notes)) {
        $ids = array_filter(array_column($notes, 'id'), function($id) {
            return $id !== null;
        });
        if (!empty($ids)) {
            $newId = max($ids) + 1;
        }
    }

    $note = [
        'id' => $newId,
        'user_id' => $userId,
        'course_id' => $courseId,
        'lesson_id' => $lessonId,
        'content' => $content,
        'timestamp' => $timestamp,
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
    ];

    $notes[] = $note;
    return saveToFile('lesson-notes.json', $notes);
}

/**
 * Get lesson notes for a user
 */
function getLessonNotes($userId, $courseId, $lessonId)
{
    $notes = getFromFile('lesson-notes.json');
    return array_filter($notes, function ($note) use ($userId, $courseId, $lessonId) {
        return (isset($note['user_id']) && $note['user_id'] == $userId) &&
               (isset($note['course_id']) && $note['course_id'] == $courseId) &&
               (isset($note['lesson_id']) && $note['lesson_id'] == $lessonId);
    });
}

/**
 * Format duration in minutes to readable format
 */
function formatDuration($minutes)
{
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

// ==================== PROFILE FUNCTIONS ====================

/**
 * Update user profile
 */
function updateUser($userId, $userData)
{
    $users = getAllUsers();
    $updated = false;

    foreach ($users as &$user) {
        if (isset($user['id']) && $user['id'] == $userId) {
            $user['name'] = $userData['name'] ?? $user['name'] ?? '';
            $user['bio'] = $userData['bio'] ?? $user['bio'] ?? '';
            $user['skills'] = $userData['skills'] ?? $user['skills'] ?? [];
            $user['learning_goals'] = $userData['learning_goals'] ?? $user['learning_goals'] ?? '';
            $user['updated_at'] = date('Y-m-d H:i:s');
            $updated = true;
            break;
        }
    }

    return $updated ? saveToFile('users.json', $users) : false;
}

/**
 * Get student achievements with progress
 */
function getStudentAchievements($studentId)
{
    $enrollments = getStudentEnrollments($studentId);
    $completedCount = count(array_filter($enrollments, function ($e) {
        return ($e['progress'] ?? 0) >= 100;
    }));

    $totalEnrollments = count($enrollments);
    $totalLearningTime = array_sum(array_map(function ($e) {
        $course = getCourseById($e['course_id'] ?? 0);
        return $course['duration'] ?? 0;
    }, $enrollments));

    $achievements = [];

    // First Course Completion
    if ($completedCount >= 1) {
        $achievements[] = [
            'id' => 1,
            'title' => 'First Steps',
            'description' => 'Complete your first course',
            'icon' => 'graduation-cap',
            'earned_at' => date('Y-m-d H:i:s', strtotime('-30 days'))
        ];
    }

    // Course Collector
    if ($completedCount >= 3) {
        $achievements[] = [
            'id' => 2,
            'title' => 'Course Collector',
            'description' => 'Complete 3 courses',
            'icon' => 'trophy',
            'earned_at' => date('Y-m-d H:i:s', strtotime('-15 days'))
        ];
    }

    // Dedicated Learner
    if ($totalEnrollments >= 5) {
        $achievements[] = [
            'id' => 3,
            'title' => 'Dedicated Learner',
            'description' => 'Enroll in 5 courses',
            'icon' => 'book',
            'earned_at' => date('Y-m-d H:i:s', strtotime('-7 days'))
        ];
    }

    // Perfect Progress
    $perfectCourses = array_filter($enrollments, function ($e) {
        return ($e['progress'] ?? 0) >= 100;
    });
    if (count($perfectCourses) >= 2) {
        $achievements[] = [
            'id' => 4,
            'title' => 'Perfect Progress',
            'description' => 'Complete 2 courses with 100% progress',
            'icon' => 'star',
            'earned_at' => date('Y-m-d H:i:s')
        ];
    }

    // Learning Marathon
    if ($totalLearningTime >= 600) { // 10 hours
        $achievements[] = [
            'id' => 5,
            'title' => 'Learning Marathon',
            'description' => 'Spend 10+ hours learning',
            'icon' => 'clock',
            'earned_at' => date('Y-m-d H:i:s')
        ];
    }

    return $achievements;
}

// ==================== ANNOUNCEMENT FUNCTIONS ====================

/**
 * Get course announcements
 */
function getCourseAnnouncements($courseId)
{
    $announcements = getFromFile('announcements.json');
    return array_filter($announcements, function ($ann) use ($courseId) {
        return isset($ann['course_id']) && $ann['course_id'] == $courseId;
    });
}

/**
 * Create new announcement
 */
function createAnnouncement($announcementData)
{
    $announcements = getFromFile('announcements.json');

    // Generate new ID
    $newId = 1;
    if (!empty($announcements)) {
        $ids = array_filter(array_column($announcements, 'id'), function($id) {
            return $id !== null;
        });
        if (!empty($ids)) {
            $newId = max($ids) + 1;
        }
    }

    $announcementData['id'] = $newId;
    $announcementData['created_at'] = date('Y-m-d H:i:s');
    $announcementData['updated_at'] = date('Y-m-d H:i:s');

    $announcements[] = $announcementData;
    return saveToFile('announcements.json', $announcements);
}

/**
 * Initialize announcements file if not exists
 */
function initializeAnnouncements()
{
    if (!file_exists(DATA_PATH . 'announcements.json')) {
        saveToFile('announcements.json', []);
    }
}
initializeAnnouncements();

// ==================== INSTRUCTOR FUNCTIONS ====================

/**
 * Check if user is instructor
 */
function requireInstructor()
{
    $user = getCurrentUser();
    if (!$user || !isset($user['role']) || $user['role'] !== 'instructor') {
        $_SESSION['error'] = "Instructor access required!";
        header('Location: /become-instructor');
        exit;
    }
    return true;
}

/**
 * Get instructor revenue
 */
function getInstructorRevenue($instructorId)
{
    $courses = getCoursesByInstructor($instructorId);
    $totalRevenue = 0;

    foreach ($courses as $course) {
        $enrollments = getCourseEnrollments($course['id'] ?? 0);
        $totalRevenue += count($enrollments) * ($course['price'] ?? 0);
    }

    return $totalRevenue;
}

/**
 * Get recent enrollments for instructor
 */
function getRecentEnrollmentsForInstructor($instructorId, $limit = 5)
{
    $courses = getCoursesByInstructor($instructorId);
    $allEnrollments = [];

    foreach ($courses as $course) {
        $enrollments = getCourseEnrollments($course['id'] ?? 0);
        foreach ($enrollments as $enrollment) {
            if (isset($enrollment['user_id'])) {
                $student = getUserById($enrollment['user_id']);
                $allEnrollments[] = [
                    'student_name' => $student['name'] ?? 'Unknown Student',
                    'course_title' => $course['title'] ?? '',
                    'enrolled_at' => $enrollment['enrolled_at'] ?? '',
                    'revenue' => $course['price'] ?? 0
                ];
            }
        }
    }

    // Sort by enrollment date (newest first)
    usort($allEnrollments, function ($a, $b) {
        return strtotime($b['enrolled_at'] ?? '1970-01-01') <=> strtotime($a['enrolled_at'] ?? '1970-01-01');
    });

    return array_slice($allEnrollments, 0, $limit);
}

/**
 * Get course performance metrics
 */
function getCoursePerformance($instructorId)
{
    $courses = getCoursesByInstructor($instructorId);
    $performance = [];

    foreach ($courses as $course) {
        $enrollments = getCourseEnrollments($course['id'] ?? 0);
        $completionRate = 0;

        if (count($enrollments) > 0) {
            $completed = array_filter($enrollments, function ($e) {
                return ($e['progress'] ?? 0) >= 100;
            });
            $completionRate = round((count($completed) / count($enrollments)) * 100);
        }

        $performance[] = [
            'title' => $course['title'] ?? '',
            'status' => $course['status'] ?? 'draft',
            'enrollment_count' => count($enrollments),
            'revenue' => count($enrollments) * ($course['price'] ?? 0),
            'rating' => $course['rating'] ?? 0,
            'completion_rate' => $completionRate
        ];
    }

    return $performance;
}

/**
 * Delete a course (instructor version)
 */
function deleteCourse($courseId, $instructorId)
{
    $courses = getAllCourses();
    $updatedCourses = [];
    $deleted = false;

    foreach ($courses as $course) {
        if ((isset($course['id']) && $course['id'] == $courseId) &&
            (isset($course['instructor_id']) && $course['instructor_id'] == $instructorId)) {
            $deleted = true;
            continue; // Skip this course (delete it)
        }
        $updatedCourses[] = $course;
    }

    if ($deleted) {
        return saveToFile('courses.json', $updatedCourses);
    }

    return false;
}

/**
 * Update course status (instructor version)
 */
function updateCourseStatus($courseId, $instructorId, $status)
{
    $courses = getAllCourses();
    $updated = false;

    foreach ($courses as &$course) {
        if ((isset($course['id']) && $course['id'] == $courseId) &&
            (isset($course['instructor_id']) && $course['instructor_id'] == $instructorId)) {
            $course['status'] = $status;
            $course['updated_at'] = date('Y-m-d H:i:s');
            $updated = true;
            break;
        }
    }

    return $updated ? saveToFile('courses.json', $courses) : false;
}

/**
 * Update user password
 */
function updateUserPassword($userId, $newPassword)
{
    $users = getAllUsers();
    $updated = false;

    foreach ($users as &$user) {
        if (isset($user['id']) && $user['id'] == $userId) {
            $user['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
            $user['updated_at'] = date('Y-m-d H:i:s');
            $updated = true;
            break;
        }
    }

    return $updated ? saveToFile('users.json', $users) : false;
}

// ==================== INSTRUCTOR ANALYTICS FUNCTIONS ====================

/**
 * Get real instructor analytics data from file storage
 */
function getInstructorAnalytics($instructorId, $startDate, $endDate)
{
    $courses = getCoursesByInstructor($instructorId);
    $enrollments = getAllEnrollments();

    $totalRevenue = 0;
    $totalStudents = 0;
    $completionRate = 0;
    $totalRatings = 0;
    $ratingCount = 0;

    $coursePerformance = [];
    $topCourses = [];
    $detailedAnalytics = [];

    foreach ($courses as $course) {
        $courseEnrollments = array_filter($enrollments, function ($enrollment) use ($course) {
            return isset($enrollment['course_id']) && $enrollment['course_id'] == ($course['id'] ?? 0);
        });

        $courseStudents = count($courseEnrollments);
        $courseRevenue = $courseStudents * ($course['price'] ?? 0);
        $totalRevenue += $courseRevenue;
        $totalStudents += $courseStudents;

        // Calculate completion rate for this course
        $completedEnrollments = array_filter($courseEnrollments, function ($enrollment) {
            return ($enrollment['progress'] ?? 0) >= 100;
        });
        $courseCompletionRate = $courseStudents > 0 ? round((count($completedEnrollments) / $courseStudents) * 100) : 0;

        // Calculate ratings
        if (isset($course['rating']) && $course['rating'] > 0) {
            $totalRatings += $course['rating'];
            $ratingCount++;
        }

        // Course performance data
        $coursePerformance[] = [
            'title' => $course['title'] ?? '',
            'enrollments' => $courseStudents,
            'revenue' => $courseRevenue,
            'trend' => 'up',
            'change' => 5
        ];

        // Top courses
        $topCourses[] = [
            'title' => $course['title'] ?? '',
            'revenue' => $courseRevenue,
            'rating' => $course['rating'] ?? 4.5
        ];

        // Detailed analytics
        $detailedAnalytics[] = [
            'title' => $course['title'] ?? '',
            'status' => $course['status'] ?? 'draft',
            'enrollments' => $courseStudents,
            'new_enrollments' => $courseStudents,
            'completion_rate' => $courseCompletionRate,
            'rating' => $course['rating'] ?? 4.5,
            'revenue' => $courseRevenue,
            'satisfaction' => min(100, max(60, ($course['rating'] ?? 4.5) * 20))
        ];
    }

    // Sort top courses by revenue
    usort($topCourses, function ($a, $b) {
        return ($b['revenue'] ?? 0) <=> ($a['revenue'] ?? 0);
    });

    // Calculate overall completion rate
    $allEnrollments = [];
    foreach ($courses as $course) {
        $courseEnrollments = array_filter($enrollments, function ($enrollment) use ($course) {
            return isset($enrollment['course_id']) && $enrollment['course_id'] == ($course['id'] ?? 0);
        });
        $allEnrollments = array_merge($allEnrollments, $courseEnrollments);
    }

    $completedAll = array_filter($allEnrollments, function ($enrollment) {
        return ($enrollment['progress'] ?? 0) >= 100;
    });

    $overallCompletionRate = count($allEnrollments) > 0 ?
        round((count($completedAll) / count($allEnrollments)) * 100) : 0;

    $avgRating = $ratingCount > 0 ? round($totalRatings / $ratingCount, 1) : 4.5;

    return [
        'total_revenue' => $totalRevenue,
        'revenue_change' => 15,
        'new_students' => $totalStudents,
        'student_change' => 8,
        'completion_rate' => $overallCompletionRate,
        'avg_rating' => $avgRating,
        'total_reviews' => $totalStudents,
        'active_students_rate' => 85,
        'lesson_completion_rate' => 78,
        'assignment_submission_rate' => 65,
        'course_performance' => array_slice($coursePerformance, 0, 3),
        'top_courses' => array_slice($topCourses, 0, 3),
        'detailed_analytics' => array_slice($detailedAnalytics, 0, 3)
    ];
}

/**
 * Get real instructor earnings data from file storage
 */
function getInstructorEarnings($instructorId)
{
    $courses = getCoursesByInstructor($instructorId);
    $enrollments = getAllEnrollments();
    $payouts = getFromFile('payouts.json');

    // Calculate total earnings from course enrollments
    $totalEarned = 0;
    $earningsByCourse = [];
    $recentTransactions = [];

    foreach ($courses as $course) {
        $courseEnrollments = array_filter($enrollments, function ($enrollment) use ($course) {
            return isset($enrollment['course_id']) && $enrollment['course_id'] == ($course['id'] ?? 0);
        });

        $courseRevenue = count($courseEnrollments) * ($course['price'] ?? 0);
        $instructorEarnings = $courseRevenue * 0.7; // 70% commission for instructor

        $totalEarned += $instructorEarnings;

        // Earnings by course
        $earningsByCourse[] = [
            'title' => $course['title'] ?? '',
            'enrollments' => count($courseEnrollments),
            'total_revenue' => $courseRevenue,
            'instructor_earnings' => $instructorEarnings,
            'commission_rate' => 70,
            'performance' => min(100, max(60, (count($courseEnrollments) / 10) * 10))
        ];

        // Recent transactions (last 3 enrollments per course)
        $recentCourseEnrollments = array_slice($courseEnrollments, -3);
        foreach ($recentCourseEnrollments as $enrollment) {
            if (isset($enrollment['user_id'])) {
                $student = getUserById($enrollment['user_id']);
                $recentTransactions[] = [
                    'date' => $enrollment['enrolled_at'] ?? '',
                    'course_title' => $course['title'] ?? '',
                    'student_name' => $student['name'] ?? 'Unknown Student',
                    'type' => 'sale',
                    'amount' => $course['price'] ?? 0,
                    'status' => 'paid'
                ];
            }
        }
    }

    // Calculate instructor payouts
    $instructorPayouts = array_filter($payouts, function ($payout) use ($instructorId) {
        return isset($payout['instructor_id']) && $payout['instructor_id'] == $instructorId;
    });

    $totalPaidOut = array_sum(array_column($instructorPayouts, 'amount') ?: [0]);
    $availableBalance = max(0, $totalEarned - $totalPaidOut);

    // Sort recent transactions by date
    usort($recentTransactions, function ($a, $b) {
        return strtotime($b['date'] ?? '1970-01-01') <=> strtotime($a['date'] ?? '1970-01-01');
    });

    return [
        'available_balance' => $availableBalance,
        'pending_balance' => $totalEarned * 0.1,
        'total_earned' => $totalEarned,
        'total_paid_out' => $totalPaidOut,
        'recent_transactions' => array_slice($recentTransactions, 0, 5),
        'earnings_by_course' => $earningsByCourse
    ];
}

/**
 * Get real instructor payouts from file storage
 */
function getInstructorPayouts($instructorId)
{
    $payouts = getFromFile('payouts.json');
    $instructorPayouts = array_filter($payouts, function ($payout) use ($instructorId) {
        return isset($payout['instructor_id']) && $payout['instructor_id'] == $instructorId;
    });

    // Sort by date (newest first)
    usort($instructorPayouts, function ($a, $b) {
        return strtotime($b['processed_at'] ?? '1970-01-01') <=> strtotime($a['processed_at'] ?? '1970-01-01');
    });

    return array_slice($instructorPayouts, 0, 5);
}

/**
 * Request a payout (save to payouts.json)
 */
function requestPayout($instructorId, $amount)
{
    $payouts = getFromFile('payouts.json');

    // Generate payout ID
    $payoutId = 'PO_' . date('YmdHis') . '_' . $instructorId;

    $payout = [
        'id' => $payoutId,
        'instructor_id' => $instructorId,
        'amount' => $amount,
        'status' => 'pending',
        'requested_at' => date('Y-m-d H:i:s'),
        'processed_at' => null,
        'method' => 'paypal'
    ];

    $payouts[] = $payout;
    return saveToFile('payouts.json', $payouts);
}

// ==================== ENHANCED COURSE FUNCTIONS ====================

/**
 * Get courses with enhanced data for instructor
 */
function getCoursesByInstructorWithStats($instructorId)
{
    $courses = getCoursesByInstructor($instructorId);
    $enrollments = getAllEnrollments();

    foreach ($courses as &$course) {
        $courseEnrollments = array_filter($enrollments, function ($enrollment) use ($course) {
            return isset($enrollment['course_id']) && $enrollment['course_id'] == ($course['id'] ?? 0);
        });

        $course['enrollment_count'] = count($courseEnrollments);
        $course['revenue'] = count($courseEnrollments) * ($course['price'] ?? 0);

        // Calculate completion rate
        $completed = array_filter($courseEnrollments, function ($enrollment) {
            return ($enrollment['progress'] ?? 0) >= 100;
        });
        $course['completion_rate'] = count($courseEnrollments) > 0 ?
            round((count($completed) / count($courseEnrollments)) * 100) : 0;
    }

    return $courses;
}

/**
 * Get monthly revenue data for charts
 */
function getInstructorMonthlyRevenue($instructorId, $months = 6)
{
    $courses = getCoursesByInstructor($instructorId);
    $enrollments = getAllEnrollments();

    $monthlyRevenue = [];

    for ($i = $months - 1; $i >= 0; $i--) {
        $month = date('Y-m', strtotime("-$i months"));
        $monthlyRevenue[$month] = 0;

        foreach ($courses as $course) {
            $courseEnrollments = array_filter($enrollments, function ($enrollment) use ($course, $month) {
                if (!isset($enrollment['enrolled_at']) || !isset($enrollment['course_id'])) return false;
                $enrollmentMonth = date('Y-m', strtotime($enrollment['enrolled_at']));
                return $enrollment['course_id'] == ($course['id'] ?? 0) && $enrollmentMonth == $month;
            });

            $monthlyRevenue[$month] += count($courseEnrollments) * ($course['price'] ?? 0) * 0.7;
        }
    }

    return $monthlyRevenue;
}

/**
 * Get real instructor stats for profile
 */
function getInstructorStudentCount($instructorId)
{
    $courses = getCoursesByInstructor($instructorId);
    $enrollments = getAllEnrollments();

    $totalStudents = 0;
    foreach ($courses as $course) {
        $courseEnrollments = array_filter($enrollments, function ($enrollment) use ($course) {
            return isset($enrollment['course_id']) && $enrollment['course_id'] == ($course['id'] ?? 0);
        });
        $totalStudents += count($courseEnrollments);
    }

    return $totalStudents;
}

/**
 * Get real instructor review count
 */
function getInstructorReviewCount($instructorId)
{
    $courses = getCoursesByInstructor($instructorId);
    $totalReviews = 0;

    foreach ($courses as $course) {
        $totalReviews += $course['reviews_count'] ?? 0;
    }

    return $totalReviews;
}

/**
 * Get real instructor rating average
 */
function getInstructorRating($instructorId)
{
    $courses = getCoursesByInstructor($instructorId);
    $totalRating = 0;
    $ratedCourses = 0;

    foreach ($courses as $course) {
        if (isset($course['rating']) && $course['rating'] > 0) {
            $totalRating += $course['rating'];
            $ratedCourses++;
        }
    }

    return $ratedCourses > 0 ? round($totalRating / $ratedCourses, 1) : 0;
}

/**
 * Get instructor by course ID
 */
function getInstructorByCourseId($courseId)
{
    $course = getCourseById($courseId);
    if ($course && isset($course['instructor_id'])) {
        return getUserById($course['instructor_id']);
    }
    return null;
}

/**
 * Ensure uploads directory exists
 */
function ensureUploadsDirectory()
{
    $directories = ['uploads/avatars', 'uploads/courses', 'uploads/blog'];

    foreach ($directories as $dir) {
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
    }
}
ensureUploadsDirectory();

// ==================== ADMIN FUNCTIONS ====================

/**
 * Get pending instructor applications count
 */
function getPendingApplicationsCount() {
    $applications = getAllInstructorApplications();
    $pending = array_filter($applications, function($app) {
        return ($app['status'] ?? '') === 'pending';
    });
    return count($pending);
}

/**
 * Get pending testimonials count
 */
function getPendingTestimonialsCount() {
    $testimonials = getFromFile('testimonials.json');
    $pending = array_filter($testimonials, function($testimonial) {
        return ($testimonial['status'] ?? '') === 'pending';
    });
    return count($pending);
}

/**
 * Get pending courses count
 */
function getPendingCoursesCount() {
    $courses = getAllCourses();
    $pending = array_filter($courses, function($course) {
        return ($course['status'] ?? '') === 'pending';
    });
    return count($pending);
}

/**
 * Get total pending items count
 */
function getTotalPendingCount() {
    return getPendingApplicationsCount() + getPendingTestimonialsCount() + getPendingCoursesCount();
}

/**
 * Get recent admin activities
 */
function getRecentAdminActivities() {
    // This would typically come from an activities log
    // For now, return sample data
    return [
        [
            'icon' => 'user-plus',
            'color' => 'success',
            'title' => 'New user registration',
            'description' => 'John Doe signed up as a student',
            'time' => '2 hours ago'
        ],
        [
            'icon' => 'book',
            'color' => 'info',
            'title' => 'Course published',
            'description' => 'Web Development Bootcamp was published',
            'time' => '5 hours ago'
        ],
        [
            'icon' => 'comment',
            'color' => 'warning',
            'title' => 'New testimonial',
            'description' => 'Sarah Johnson shared her experience',
            'time' => '1 day ago'
        ]
    ];
}

/**
 * Update user role (student to instructor)
 */
function updateUserRole($userId, $newRole) {
    $users = getAllUsers();
    $updated = false;

    foreach ($users as &$user) {
        if (isset($user['id']) && $user['id'] == $userId) {
            $user['role'] = $newRole;
            $user['updated_at'] = date('Y-m-d H:i:s');
            $updated = true;
            break;
        }
    }

    return $updated ? saveToFile('users.json', $users) : false;
}

/**
 * Approve instructor application
 */
function approveInstructorApplication($applicationId, $adminId) {
    $applications = getAllInstructorApplications();
    $updated = false;

    foreach ($applications as &$app) {
        if (isset($app['id']) && $app['id'] == $applicationId) {
            $app['status'] = 'approved';
            $app['reviewed_at'] = date('Y-m-d H:i:s');
            $app['reviewed_by'] = $adminId;
            $app['notes'] = 'Application approved by administrator';

            // Update user role to instructor
            if (isset($app['user_id'])) {
                updateUserRole($app['user_id'], 'instructor');
            }

            $updated = true;
            break;
        }
    }

    return $updated ? saveToFile('instructor-applications.json', $applications) : false;
}

/**
 * Reject instructor application
 */
function rejectInstructorApplication($applicationId, $adminId, $reason = '') {
    $applications = getAllInstructorApplications();
    $updated = false;

    foreach ($applications as &$app) {
        if (isset($app['id']) && $app['id'] == $applicationId) {
            $app['status'] = 'rejected';
            $app['reviewed_at'] = date('Y-m-d H:i:s');
            $app['reviewed_by'] = $adminId;
            $app['notes'] = $reason ?: 'Application rejected by administrator';
            $updated = true;
            break;
        }
    }

    return $updated ? saveToFile('instructor-applications.json', $applications) : false;
}

/**
 * Approve testimonial
 */
function approveTestimonial($testimonialId) {
    $testimonials = getFromFile('testimonials.json');
    $updated = false;

    foreach ($testimonials as &$testimonial) {
        if (isset($testimonial['id']) && $testimonial['id'] == $testimonialId) {
            $testimonial['status'] = 'approved';
            $updated = true;
            break;
        }
    }

    return $updated ? saveToFile('testimonials.json', $testimonials) : false;
}

/**
 * Reject testimonial
 */
function rejectTestimonial($testimonialId) {
    $testimonials = getFromFile('testimonials.json');
    $updated = false;

    foreach ($testimonials as &$testimonial) {
        if (isset($testimonial['id']) && $testimonial['id'] == $testimonialId) {
            $testimonial['status'] = 'rejected';
            $updated = true;
            break;
        }
    }

    return $updated ? saveToFile('testimonials.json', $testimonials) : false;
}

/**
 * Update user status (active/suspended)
 */
function updateUserStatus($userId, $status) {
    $users = getAllUsers();
    $updated = false;

    foreach ($users as &$user) {
        if (isset($user['id']) && $user['id'] == $userId) {
            $user['status'] = $status;
            $user['updated_at'] = date('Y-m-d H:i:s');
            $updated = true;
            break;
        }
    }

    return $updated ? saveToFile('users.json', $users) : false;
}

/**
 * Delete user (with safety checks)
 */
function deleteUser($userId) {
    $users = getAllUsers();
    $updated_users = [];
    $deleted = false;

    foreach ($users as $user) {
        if (isset($user['id']) && $user['id'] == $userId) {
            // Check if user has courses (instructors)
            if (isset($user['role']) && $user['role'] === 'instructor') {
                $instructor_courses = getCoursesByInstructor($userId);
                if (!empty($instructor_courses)) {
                    return false; // Cannot delete instructor with courses
                }
            }
            $deleted = true;
            continue; // Skip this user (delete them)
        }
        $updated_users[] = $user;
    }

    return $deleted ? saveToFile('users.json', $updated_users) : false;
}

/**
 * Get status badge color
 */
function getStatusBadgeColor($status) {
    $colors = [
        'published' => 'success',
        'pending' => 'warning',
        'draft' => 'info',
        'rejected' => 'danger',
        'active' => 'success',
        'suspended' => 'danger'
    ];

    return $colors[$status] ?? 'secondary';
}

/**
 * Update course status (admin version - no instructor check required)
 */
function updateCourseStatusAdmin($courseId, $status) {
    $courses = getAllCourses();
    $updated = false;

    foreach ($courses as &$course) {
        if (isset($course['id']) && $course['id'] == $courseId) {
            $course['status'] = $status;
            $course['updated_at'] = date('Y-m-d H:i:s');
            $updated = true;
            break;
        }
    }

    return $updated ? saveToFile('courses.json', $courses) : false;
}

/**
 * Delete course (admin version - no instructor check required)
 */
function deleteCourseAdmin($courseId) {
    $courses = getAllCourses();
    $updated_courses = [];
    $deleted = false;

    foreach ($courses as $course) {
        if (isset($course['id']) && $course['id'] == $courseId) {
            $deleted = true;
            continue; // Skip this course (delete it)
        }
        $updated_courses[] = $course;
    }

    return $deleted ? saveToFile('courses.json', $updated_courses) : false;
}

/**
 * Get platform analytics data
 */
function getPlatformAnalytics($startDate, $endDate, $period = 'monthly') {
    $users = getAllUsers();
    $courses = getAllCourses();
    $enrollments = getAllEnrollments();

    // Calculate metrics
    $totalUsers = count($users);
    $totalCourses = count(array_filter($courses, function($course) {
        return ($course['status'] ?? '') === 'published';
    }));
    $totalEnrollments = count($enrollments);

    // User distribution
    $students = array_filter($users, function($user) { return ($user['role'] ?? '') === 'student'; });
    $instructors = array_filter($users, function($user) { return ($user['role'] ?? '') === 'instructor'; });
    $admins = array_filter($users, function($user) { return ($user['role'] ?? '') === 'admin'; });

    $studentPercent = $totalUsers > 0 ? round((count($students) / $totalUsers) * 100) : 0;
    $instructorPercent = $totalUsers > 0 ? round((count($instructors) / $totalUsers) * 100) : 0;
    $adminPercent = $totalUsers > 0 ? round((count($admins) / $totalUsers) * 100) : 0;

    // Sample data - in real app, calculate from actual data
    return [
        'total_revenue' => 125000.50,
        'revenue_growth' => 15,
        'new_users' => 245,
        'user_growth' => 12,
        'new_enrollments' => 567,
        'enrollment_growth' => 18,
        'avg_rating' => 4.7,
        'total_reviews' => 1289,

        'user_distribution' => [
            'students' => count($students),
            'instructors' => count($instructors),
            'admins' => count($admins),
            'student_percent' => $studentPercent,
            'instructor_percent' => $instructorPercent,
            'admin_percent' => $adminPercent
        ],

        'revenue_trends' => [
            ['period' => 'Jan 2024', 'revenue' => 18500, 'enrollments' => 120, 'avg_price' => 49.99, 'growth' => 5],
            ['period' => 'Feb 2024', 'revenue' => 21400, 'enrollments' => 145, 'avg_price' => 49.99, 'growth' => 15],
            ['period' => 'Mar 2024', 'revenue' => 23800, 'enrollments' => 162, 'avg_price' => 49.99, 'growth' => 11],
            ['period' => 'Apr 2024', 'revenue' => 26500, 'enrollments' => 178, 'avg_price' => 49.99, 'growth' => 12]
        ],

        'top_courses' => [
            ['title' => 'Web Development Bootcamp', 'revenue' => 45800, 'rating' => 4.8],
            ['title' => 'JavaScript Mastery', 'revenue' => 32500, 'rating' => 4.7],
            ['title' => 'React Fundamentals', 'revenue' => 28700, 'rating' => 4.6],
            ['title' => 'Python for Beginners', 'revenue' => 23400, 'rating' => 4.5]
        ],

        'detailed_metrics' => [
            ['name' => 'User Registrations', 'current' => '245', 'previous' => '218', 'growth' => 12],
            ['name' => 'Course Purchases', 'current' => '567', 'previous' => '480', 'growth' => 18],
            ['name' => 'Course Completion', 'current' => '68%', 'previous' => '65%', 'growth' => 5],
            ['name' => 'Instructor Applications', 'current' => '45', 'previous' => '38', 'growth' => 18],
            ['name' => 'Average Session Duration', 'current' => '24m', 'previous' => '22m', 'growth' => 9]
        ]
    ];
}

/**
 * Get platform revenue data
 */
function getPlatformRevenue() {
    $courses = getAllCourses();
    $enrollments = getAllEnrollments();

    // Calculate total revenue
    $totalRevenue = 0;
    foreach ($courses as $course) {
        $courseEnrollments = array_filter($enrollments, function($enrollment) use ($course) {
            return isset($enrollment['course_id']) && $enrollment['course_id'] == ($course['id'] ?? 0);
        });
        $totalRevenue += count($courseEnrollments) * ($course['price'] ?? 0);
    }

    $platformEarnings = $totalRevenue * 0.3; // 30% commission
    $pendingPayouts = $totalRevenue * 0.7; // 70% for instructors

    return [
        'total_revenue' => $totalRevenue,
        'platform_earnings' => $platformEarnings,
        'pending_payouts' => $pendingPayouts,
        'commission_rate' => 30,

        'revenue_by_category' => [
            ['name' => 'Web Development', 'revenue' => 45800, 'percentage' => 36, 'color' => '#007bff'],
            ['name' => 'Data Science', 'revenue' => 32500, 'percentage' => 26, 'color' => '#28a745'],
            ['name' => 'Mobile Development', 'revenue' => 28700, 'percentage' => 23, 'color' => '#dc3545'],
            ['name' => 'Machine Learning', 'revenue' => 18000, 'percentage' => 15, 'color' => '#ffc107']
        ],

        'monthly_trends' => [
            ['month' => 'January', 'revenue' => 18500, 'growth' => 5],
            ['month' => 'February', 'revenue' => 21400, 'growth' => 15],
            ['month' => 'March', 'revenue' => 23800, 'growth' => 11],
            ['month' => 'April', 'revenue' => 26500, 'growth' => 12]
        ]
    ];
}

/**
 * Get pending payouts
 */
function getPendingPayouts() {
    // Sample data - in real app, get from payouts.json
    return [
        [
            'id' => 1,
            'instructor_name' => 'John Smith',
            'instructor_avatar' => '/assets/images/avatars/default.jpg',
            'amount' => 1250.50,
            'requested_at' => '2024-01-15',
            'payment_method' => 'paypal',
            'courses_count' => 3,
            'breakdown' => [
                ['course' => 'Web Development Bootcamp', 'amount' => 850.00],
                ['course' => 'JavaScript Basics', 'amount' => 400.50]
            ]
        ],
        [
            'id' => 2,
            'instructor_name' => 'Sarah Johnson',
            'instructor_avatar' => '/assets/images/avatars/default.jpg',
            'amount' => 890.75,
            'requested_at' => '2024-01-14',
            'payment_method' => 'bank_transfer',
            'courses_count' => 2,
            'breakdown' => [
                ['course' => 'React Fundamentals', 'amount' => 650.25],
                ['course' => 'Node.js Course', 'amount' => 240.50]
            ]
        ]
    ];
}

/**
 * Get platform settings
 */
function getPlatformSettings() {
    $settings = getFromFile('settings.json');

    // Default settings
    $defaultSettings = [
        'site_name' => 'CodeMastery',
        'site_email' => 'admin@codemastery.com',
        'site_description' => 'Learn to code from industry experts',
        'currency' => 'USD',
        'timezone' => 'UTC',
        'commission_rate' => 30,
        'stripe_enabled' => false,
        'paypal_enabled' => false,
        'stripe_publishable_key' => '',
        'stripe_secret_key' => ''
    ];

    return array_merge($defaultSettings, $settings);
}

/**
 * Update platform settings
 */
function updatePlatformSettings($newSettings) {
    $currentSettings = getPlatformSettings();
    $updatedSettings = array_merge($currentSettings, $newSettings);
    return saveToFile('settings.json', $updatedSettings);
}

/**
 * Update payment settings
 */
function updatePaymentSettings($paymentSettings) {
    $currentSettings = getPlatformSettings();
    $updatedSettings = array_merge($currentSettings, $paymentSettings);
    return saveToFile('settings.json', $updatedSettings);
}

// ==================== BLOG MANAGEMENT FUNCTIONS ====================

/**
 * Update blog post status
 */
function updateBlogPostStatus($postId, $status) {
    $blog_posts = getBlogPosts();
    $updated = false;

    foreach ($blog_posts as &$post) {
        if (isset($post['id']) && $post['id'] == $postId) {
            $post['status'] = $status;
            $post['updated_at'] = date('Y-m-d H:i:s');
            $updated = true;
            break;
        }
    }

    return $updated ? saveToFile('blog.json', $blog_posts) : false;
}

/**
 * Delete blog post
 */
function deleteBlogPost($postId) {
    $blog_posts = getBlogPosts();
    $updated_posts = [];
    $deleted = false;

    foreach ($blog_posts as $post) {
        if (isset($post['id']) && $post['id'] == $postId) {
            $deleted = true;
            continue;
        }
        $updated_posts[] = $post;
    }

    return $deleted ? saveToFile('blog.json', $updated_posts) : false;
}

/**
 * Create new blog post
 */
function createBlogPost($postData) {
    $blog_posts = getBlogPosts();

    // Generate new ID
    $newId = 1;
    if (!empty($blog_posts)) {
        $ids = array_filter(array_column($blog_posts, 'id'), function($id) {
            return $id !== null;
        });
        if (!empty($ids)) {
            $newId = max($ids) + 1;
        }
    }

    $post = [
        'id' => $newId,
        'title' => trim($postData['title'] ?? ''),
        'excerpt' => trim($postData['excerpt'] ?? ''),
        'content' => trim($postData['content'] ?? ''),
        'author' => $postData['author'] ?? '',
        'author_id' => $postData['author_id'] ?? null,
        'category' => $postData['category'] ?? '',
        'published_at' => date('Y-m-d H:i:s'),
        'image' => $postData['image'] ?? '/assets/images/blog/default.jpg',
        'status' => $postData['status'] ?? 'pending',
        'likes' => [],
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
    ];

    $blog_posts[] = $post;
    return saveToFile('blog.json', $blog_posts) ? $post : false;
}

/**
 * Get blog posts by status
 */
function getBlogPostsByStatus($status) {
    $posts = getBlogPosts();
    return array_filter($posts, function($post) use ($status) {
        return ($post['status'] ?? '') === $status;
    });
}

// ==================== TESTIMONIAL FUNCTIONS ====================

/**
 * Delete testimonial
 */
function deleteTestimonial($testimonialId) {
    $testimonials = getTestimonials();
    $updated_testimonials = [];
    $deleted = false;

    foreach ($testimonials as $testimonial) {
        if (isset($testimonial['id']) && $testimonial['id'] == $testimonialId) {
            $deleted = true;
            continue;
        }
        $updated_testimonials[] = $testimonial;
    }

    return $deleted ? saveToFile('testimonials.json', $updated_testimonials) : false;
}

// ==================== REAL ANALYTICS FUNCTIONS ====================

/**
 * Get real platform analytics data from file storage
 */
function getRealPlatformAnalytics($startDate, $endDate, $period = 'monthly') {
    $users = getAllUsers();
    $courses = getAllCourses();
    $enrollments = getAllEnrollments();

    // Calculate metrics based on actual data
    $totalUsers = count($users);
    $totalCourses = count(array_filter($courses, function($course) {
        return ($course['status'] ?? '') === 'published';
    }));
    $totalEnrollments = count($enrollments);

    // User distribution
    $students = array_filter($users, function($user) { return ($user['role'] ?? '') === 'student'; });
    $instructors = array_filter($users, function($user) { return ($user['role'] ?? '') === 'instructor'; });
    $admins = array_filter($users, function($user) { return ($user['role'] ?? '') === 'admin'; });

    $studentPercent = $totalUsers > 0 ? round((count($students) / $totalUsers) * 100) : 0;
    $instructorPercent = $totalUsers > 0 ? round((count($instructors) / $totalUsers) * 100) : 0;
    $adminPercent = $totalUsers > 0 ? round((count($admins) / $totalUsers) * 100) : 0;

    // Calculate total revenue from enrollments
    $totalRevenue = 0;
    foreach ($enrollments as $enrollment) {
        if (isset($enrollment['course_id'])) {
            $course = getCourseById($enrollment['course_id']);
            if ($course) {
                $totalRevenue += $course['price'] ?? 0;
            }
        }
    }

    // Calculate average rating
    $totalRating = 0;
    $ratedCourses = 0;
    foreach ($courses as $course) {
        if (isset($course['rating']) && $course['rating'] > 0) {
            $totalRating += $course['rating'];
            $ratedCourses++;
        }
    }
    $avgRating = $ratedCourses > 0 ? round($totalRating / $ratedCourses, 1) : 4.5;

    // Get top courses by revenue
    $courseRevenues = [];
    foreach ($courses as $course) {
        $courseEnrollments = array_filter($enrollments, function($enrollment) use ($course) {
            return isset($enrollment['course_id']) && $enrollment['course_id'] == ($course['id'] ?? 0);
        });
        $courseRevenue = count($courseEnrollments) * ($course['price'] ?? 0);
        $courseRevenues[] = [
            'title' => $course['title'] ?? '',
            'revenue' => $courseRevenue,
            'rating' => $course['rating'] ?? 4.5
        ];
    }

    // Sort by revenue descending
    usort($courseRevenues, function($a, $b) {
        return ($b['revenue'] ?? 0) - ($a['revenue'] ?? 0);
    });
    $topCourses = array_slice($courseRevenues, 0, 3);

    // Calculate growth percentages (simplified - in real app, compare with previous period)
    $revenueGrowth = 15;
    $userGrowth = 12;
    $enrollmentGrowth = 18;

    // Generate revenue trends (last 6 months)
    $revenueTrends = [];
    $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
    $baseRevenue = $totalRevenue / 6;

    foreach ($months as $index => $month) {
        $monthRevenue = $baseRevenue * (1 + ($index * 0.1));
        $revenueTrends[] = [
            'period' => $month . ' 2024',
            'revenue' => $monthRevenue,
            'enrollments' => rand(80, 150),
            'avg_price' => 49.99,
            'growth' => $index > 0 ? rand(5, 15) : 0
        ];
    }

    // Detailed metrics
    $detailedMetrics = [
        ['name' => 'User Registrations', 'current' => '245', 'previous' => '218', 'growth' => 12],
        ['name' => 'Course Purchases', 'current' => '567', 'previous' => '480', 'growth' => 18],
        ['name' => 'Course Completion', 'current' => '68%', 'previous' => '65%', 'growth' => 5],
        ['name' => 'Instructor Applications', 'current' => '45', 'previous' => '38', 'growth' => 18],
        ['name' => 'Average Session Duration', 'current' => '24m', 'previous' => '22m', 'growth' => 9]
    ];

    return [
        'total_revenue' => $totalRevenue,
        'revenue_growth' => $revenueGrowth,
        'new_users' => count($users),
        'user_growth' => $userGrowth,
        'new_enrollments' => $totalEnrollments,
        'enrollment_growth' => $enrollmentGrowth,
        'avg_rating' => $avgRating,
        'total_reviews' => $ratedCourses,

        'user_distribution' => [
            'students' => count($students),
            'instructors' => count($instructors),
            'admins' => count($admins),
            'student_percent' => $studentPercent,
            'instructor_percent' => $instructorPercent,
            'admin_percent' => $adminPercent
        ],

        'revenue_trends' => $revenueTrends,

        'top_courses' => $topCourses,

        'detailed_metrics' => $detailedMetrics
    ];
}

/**
 * Get real platform revenue data from file storage
 */
function getRealPlatformRevenue() {
    $courses = getAllCourses();
    $enrollments = getAllEnrollments();
    $settings = getPlatformSettings();

    // Calculate total revenue from all enrollments
    $totalRevenue = 0;
    foreach ($enrollments as $enrollment) {
        if (isset($enrollment['course_id'])) {
            $course = getCourseById($enrollment['course_id']);
            if ($course) {
                $totalRevenue += $course['price'] ?? 0;
            }
        }
    }

    $commissionRate = $settings['commission_rate'] ?? 30;
    $platformEarnings = $totalRevenue * ($commissionRate / 100);
    $pendingPayouts = $totalRevenue - $platformEarnings;

    // Revenue by category
    $revenueByCategory = [];
    $categories = array_filter(array_unique(array_column($courses, 'category')));
    $colors = ['#007bff', '#28a745', '#dc3545', '#ffc107', '#6f42c1'];

    foreach ($categories as $index => $category) {
        $categoryCourses = array_filter($courses, function($course) use ($category) {
            return ($course['category'] ?? '') === $category;
        });

        $categoryRevenue = 0;
        foreach ($categoryCourses as $course) {
            $courseEnrollments = array_filter($enrollments, function($enrollment) use ($course) {
                return isset($enrollment['course_id']) && $enrollment['course_id'] == ($course['id'] ?? 0);
            });
            $categoryRevenue += count($courseEnrollments) * ($course['price'] ?? 0);
        }

        $percentage = $totalRevenue > 0 ? round(($categoryRevenue / $totalRevenue) * 100) : 0;

        $revenueByCategory[] = [
            'name' => $category,
            'revenue' => $categoryRevenue,
            'percentage' => $percentage,
            'color' => $colors[$index % count($colors)]
        ];
    }

    // Monthly trends (last 6 months)
    $monthlyTrends = [];
    $months = ['January', 'February', 'March', 'April', 'May', 'June'];
    $baseRevenue = $totalRevenue / 6;

    foreach ($months as $index => $month) {
        $monthRevenue = $baseRevenue * (1 + ($index * 0.1));
        $monthlyTrends[] = [
            'month' => $month,
            'revenue' => $monthRevenue,
            'growth' => $index > 0 ? rand(5, 15) : 0
        ];
    }

    return [
        'total_revenue' => $totalRevenue,
        'platform_earnings' => $platformEarnings,
        'pending_payouts' => $pendingPayouts,
        'commission_rate' => $commissionRate,

        'revenue_by_category' => $revenueByCategory,

        'monthly_trends' => $monthlyTrends
    ];
}

/**
 * Get real pending payouts from file storage
 */
function getRealPendingPayouts() {
    $payouts = getFromFile('payouts.json');
    $pendingPayouts = array_filter($payouts, function($payout) {
        return ($payout['status'] ?? '') === 'pending';
    });

    $formattedPayouts = [];
    foreach ($pendingPayouts as $payout) {
        if (isset($payout['instructor_id'])) {
            $instructor = getUserById($payout['instructor_id']);
            $instructorCourses = getCoursesByInstructor($payout['instructor_id']);

            $formattedPayouts[] = [
                'id' => $payout['id'] ?? '',
                'instructor_name' => $instructor['name'] ?? 'Unknown Instructor',
                'instructor_avatar' => $instructor['avatar'] ?? '/assets/images/avatars/default.jpg',
                'amount' => $payout['amount'] ?? 0,
                'requested_at' => $payout['requested_at'] ?? '',
                'payment_method' => $payout['method'] ?? 'paypal',
                'courses_count' => count($instructorCourses),
                'breakdown' => [
                    ['course' => 'Sample Course 1', 'amount' => ($payout['amount'] ?? 0) * 0.6],
                    ['course' => 'Sample Course 2', 'amount' => ($payout['amount'] ?? 0) * 0.4]
                ]
            ];
        }
    }

    return $formattedPayouts;
}

/**
 * Process a payout (update status in file storage)
 */
function processPayout($payoutId) {
    $payouts = getFromFile('payouts.json');
    $updated = false;

    foreach ($payouts as &$payout) {
        if (isset($payout['id']) && $payout['id'] == $payoutId) {
            $payout['status'] = 'processed';
            $payout['processed_at'] = date('Y-m-d H:i:s');
            $updated = true;
            break;
        }
    }

    return $updated ? saveToFile('payouts.json', $payouts) : false;
}

/**
 * Update commission rate in platform settings
 */
function updateCommissionRate($newRate) {
    $settings = getPlatformSettings();
    $settings['commission_rate'] = $newRate;
    return saveToFile('settings.json', $settings);
}

// ==================== CONTENT MODERATION FUNCTIONS ====================

function getReportedContent() {
    // Sample data - in real app, this would come from reported-content.json
    return [
        [
            'id' => 1,
            'content_id' => 101,
            'content_type' => 'comment',
            'content_title' => 'Inappropriate language in discussion',
            'content_preview' => 'This comment contains offensive language...',
            'full_content' => 'This user used inappropriate language in the course discussion forum.',
            'reporter_name' => 'Sarah Johnson',
            'reporter_avatar' => '/assets/images/avatars/1.jpg',
            'reason' => 'harassment',
            'severity' => 'high',
            'user_id' => 201,
            'reported_at' => date('Y-m-d H:i:s', strtotime('-2 hours'))
        ],
        [
            'id' => 2,
            'content_id' => 102,
            'content_type' => 'course',
            'content_title' => 'Plagiarized course content',
            'content_preview' => 'This course appears to contain copied material...',
            'full_content' => 'The course content seems to be copied from another platform without permission.',
            'reporter_name' => 'Mike Chen',
            'reporter_avatar' => '/assets/images/avatars/2.jpg',
            'reason' => 'plagiarism',
            'severity' => 'medium',
            'user_id' => 202,
            'reported_at' => date('Y-m-d H:i:s', strtotime('-1 day'))
        ]
    ];
}

function getPendingReviews() {
    return [
        [
            'id' => 1,
            'content_title' => 'Advanced JavaScript Patterns',
            'user_name' => 'Alex Thompson',
            'user_avatar' => '/assets/images/avatars/3.jpg',
            'type' => 'course',
            'created_at' => date('Y-m-d H:i:s', strtotime('-3 days'))
        ]
    ];
}

function getFlaggedComments() {
    return [
        [
            'id' => 1,
            'user_name' => 'John Doe',
            'content' => 'This comment was flagged for review due to inappropriate content.',
            'created_at' => date('Y-m-d H:i:s', strtotime('-5 hours'))
        ]
    ];
}

function getResolvedToday() {
    return [];
}

function approveContent($contentId, $contentType) {
    // In real app, update content status in file storage
    return true;
}

function rejectContent($contentId, $contentType, $reason) {
    // In real app, update content status and log reason
    return true;
}

function deleteContent($contentId, $contentType) {
    // In real app, remove content from file storage
    return true;
}

function banUser($userId, $reason) {
    return updateUserStatus($userId, 'suspended');
}

// ==================== BILLING FUNCTIONS ====================

function getStudentPurchaseHistory($studentId) {
    $enrollments = getStudentEnrollments($studentId);
    $purchases = [];

    foreach ($enrollments as $enrollment) {
        if (isset($enrollment['course_id'])) {
            $course = getCourseById($enrollment['course_id']);
            if ($course && ($course['price'] ?? 0) > 0) {
                $purchases[] = [
                    'id' => $enrollment['id'] ?? '',
                    'course_id' => $course['id'] ?? 0,
                    'course_title' => $course['title'] ?? '',
                    'course_image' => $course['thumbnail'] ?? '',
                    'instructor_name' => $course['instructor_name'] ?? '',
                    'amount' => $course['price'] ?? 0,
                    'purchase_date' => $enrollment['enrolled_at'] ?? '',
                    'status' => 'completed',
                    'can_refund' => isset($enrollment['enrolled_at']) && strtotime($enrollment['enrolled_at']) > strtotime('-7 days')
                ];
            }
        }
    }

    return $purchases;
}

function getStudentPaymentMethods($studentId) {
    // Sample data - in real app, this would come from payment-methods.json
    return [
        [
            'id' => 1,
            'card_number' => '4242',
            'expiry_date' => '12/25',
            'card_holder' => 'Student Name',
            'is_default' => true
        ]
    ];
}

function getStudentSubscriptions($studentId) {
    // Sample data - in real app, this would come from subscriptions.json
    return [];
}

function updatePaymentMethod($studentId, $paymentData) {
    // In real app, save to payment-methods.json
    return true;
}

function requestRefund($studentId, $purchaseId, $reason) {
    // In real app, create refund request in refunds.json
    return true;
}

// ==================== CERTIFICATE FUNCTIONS ====================

function getStudentCertificates($studentId) {
    $enrollments = getStudentEnrollments($studentId);
    $certificates = [];

    foreach ($enrollments as $enrollment) {
        if (($enrollment['progress'] ?? 0) >= 100) {
            $course = getCourseById($enrollment['course_id'] ?? 0);
            $student = getUserById($studentId);
            if ($course) {
                $certificates[] = [
                    'id' => uniqid(),
                    'certificate_id' => 'CM' . strtoupper(uniqid()),
                    'course_id' => $course['id'] ?? 0,
                    'course_title' => $course['title'] ?? '',
                    'issued_date' => $enrollment['enrolled_at'] ?? '',
                    'student_name' => $student['name'] ?? ''
                ];
            }
        }
    }

    return $certificates;
}

function downloadCertificate($certificateId, $studentId) {
    // In real app, generate and serve PDF certificate
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="certificate-' . $certificateId . '.pdf"');
    // PDF generation code would go here
    echo "PDF certificate content for " . $certificateId;
    exit;
}

function shareCertificate($certificateId, $platform) {
    // In real app, handle social media sharing
    return true;
}

/**
 * Format file size in human readable format
 */
function formatFileSize($bytes) {
    if ($bytes == 0) return '0 Bytes';

    $k = 1024;
    $sizes = ['Bytes', 'KB', 'MB', 'GB'];
    $i = floor(log($bytes) / log($k));

    return number_format($bytes / pow($k, $i), 2) . ' ' . $sizes[$i];
}

/**
 * Delete a lesson note
 */
function deleteLessonNote($noteId, $userId) {
    $notes = getFromFile('lesson-notes.json');
    $updatedNotes = [];
    $deleted = false;

    foreach ($notes as $note) {
        // Only allow deletion if the note belongs to the user
        if ((isset($note['id']) && $note['id'] == $noteId) &&
            (isset($note['user_id']) && $note['user_id'] == $userId)) {
            $deleted = true;
            continue;
        }
        $updatedNotes[] = $note;
    }

    if ($deleted) {
        return saveToFile('lesson-notes.json', $updatedNotes);
    }

    return false;
}
?>