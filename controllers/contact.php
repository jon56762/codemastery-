<?php
require_once 'includes/function.php';

$page_title = "Contact Us - CodeMastery";
$current_page = 'contact';

// Get platform statistics for the contact page
$platformStats = getPlatformStats();

// Handle contact form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contact'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);
    
    // Basic validation
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $_SESSION['error'] = "Please fill in all fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Please enter a valid email address.";
    } else {
        // Save contact message to data file
        $contacts = getFromFile('contacts.json');
        
        $contact = [
            'id' => count($contacts) + 1,
            'name' => $name,
            'email' => $email,
            'subject' => $subject,
            'message' => $message,
            'submitted_at' => date('Y-m-d H:i:s'),
            'status' => 'new'
        ];
        
        $contacts[] = $contact;
        saveToFile('contacts.json', $contacts);
        
        $_SESSION['success'] = "Thank you for your message! We'll get back to you within 24 hours.";
        
        // Clear form
        unset($_POST);
    }
}

require 'view/partial/nav.php';
require 'view/contact_view.php';
require 'view/partial/footer.php';
?>