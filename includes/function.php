<?php
// File storage functions
function saveToFile($filename, $data) {
    $filePath = DATA_PATH . $filename;
    return file_put_contents($filePath, json_encode($data, JSON_PRETTY_PRINT));
}

function getFromFile($filename) {
    $filePath = DATA_PATH . $filename;
    if (!file_exists($filePath)) {
        return [];
    }
    return json_decode(file_get_contents($filePath), true) ?? [];
}

// Platform statistics
function getPlatformStats() {
    return [
        'total_students' => 12500,
        'total_courses' => 150,
        'total_instructors' => 45,
        'total_enrollments' => 28000,
        'average_rating' => 4.8
    ];
}

// Newsletter subscription
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
        'subscribed_at' => date('Y-m-d H:i:s')
    ];
    
    $newsletter[] = $subscriber;
    $success = saveToFile('newsletter.json', $newsletter);
    
    if ($success) {
        $_SESSION['success'] = "Thanks for subscribing! We'll keep you updated.";
    }
    
    return $success;
}

// Initialize sample data
function initializeSampleData() {
    // Sample testimonials
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
                'avatar' => '/assets/images/avatars/3.jpg',
                'text' => 'As a complete beginner, I found the community support incredible. The instructors are always available to help.',
                'rating' => 4
            ]
        ];
        saveToFile('testimonials.json', $testimonials);
    }
    
    // Sample admin user
    if (empty(getFromFile('users.json'))) {
        $users = [
            [
                'id' => 1,
                'name' => 'Admin User',
                'email' => 'admin@codemastery.com',
                'password' => password_hash('admin123', PASSWORD_DEFAULT),
                'role' => 'admin',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s')
            ]
        ];
        saveToFile('users.json', $users);
    }
}

// Initialize data on first run
initializeSampleData();
?>