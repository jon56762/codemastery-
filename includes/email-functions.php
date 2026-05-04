<?php

require_once __DIR__ . '/../vendor/autoload.php';

// Manually include PHPMailer from local folder
require_once __DIR__ . '/../PHPMailer/src/Exception.php';
require_once __DIR__ . '/../PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


function sendEmailNotification($to, $subject, $message, $isHTML = true) {
    $mail = new PHPMailer(true);
    $settings = getPlatformSettings();
    
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = $settings['smtp_host'] ?? 'smtp-codemastery.alwaysdata.net';
        $mail->SMTPAuth = true;
        $mail->Username = $settings['smtp_username'] ?? 'codemastery';
        $mail->Password = $settings['smtp_password'] ?? 'succ00$$';
        $mail->SMTPSecure = $settings['smtp_encryption'] ?? PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = $settings['smtp_port'] ?? 587;

        // Recipients
        $mail->setFrom($settings['from_email'] ?? 'noreply@codemastery.com', $settings['from_name'] ?? 'CodeMastery');
        $mail->addAddress($to);
        
        // Content
        $mail->isHTML($isHTML);
        $mail->Subject = $subject;
        $mail->Body = $message;
        
        if (!$isHTML) {
            $mail->AltBody = strip_tags($message);
        }

        $mail->send();
        
        // Log the email
        logEmailNotification($to, $subject, 'sent');
        return true;
        
    } catch (Exception $e) {
        // Log the error
        logEmailNotification($to, $subject, 'failed', $mail->ErrorInfo);
        error_log("Email sending failed: " . $mail->ErrorInfo);
        return false;
    }
}

function logEmailNotification($to, $subject, $status, $error = '') {
    $emails = getFromFile('email-notifications.json');
    
    $email = [
        'id' => count($emails) + 1,
        'to' => $to,
        'subject' => $subject,
        'status' => $status,
        'error' => $error,
        'sent_at' => date('Y-m-d H:i:s')
    ];
    
    $emails[] = $email;
    saveToFile('email-notifications.json', $emails);
}

function sendInstructorApplicationApprovalEmail($userEmail, $userName) {
    $subject = "Your Instructor Application Has Been Approved - CodeMastery";
    $message = "
    <!DOCTYPE html>
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: #000000; color: #ffffff; padding: 30px; text-align: center; }
            .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 8px 8px; }
            .footer { background: #eeeeee; padding: 20px; text-align: center; font-size: 12px; color: #666; }
            .button { background: #000000; color: #ffffff; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block; margin: 10px 0; }
            .steps { background: #ffffff; padding: 20px; border-radius: 5px; margin: 20px 0; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h1>🎉 Welcome to CodeMastery Instructors! 🎉</h1>
            </div>
            <div class='content'>
                <h2>Congratulations, {$userName}!</h2>
                <p>We're thrilled to inform you that your instructor application has been approved!</p>
                
                <div class='steps'>
                    <h3>🚀 Get Started as an Instructor:</h3>
                    <ol>
                        <li><strong>Log in</strong> to your CodeMastery account</li>
                        <li>Access your <strong>Instructor Dashboard</strong></li>
                        <li>Create your first course using our <strong>Course Builder</strong></li>
                        <li>Set up your <strong>Instructor Profile</strong> to attract students</li>
                        <li>Start sharing your knowledge and earning!</li>
                    </ol>
                </div>
                
                <p><strong>Quick Links:</strong></p>
                <p>
                    <a href='/instructor-dashboard' class='button'>Go to Instructor Dashboard</a>
                </p>
                
                <p><strong>Need Help?</strong><br>
                Check out our instructor resources or contact our support team if you have any questions.</p>
                
                <p>We're excited to see the courses you'll create and the students you'll inspire!</p>
                
                <p>Best regards,<br>
                <strong>The CodeMastery Team</strong></p>
            </div>
            <div class='footer'>
                <p>This email was sent from CodeMastery Learning Platform<br>
                © 2024 CodeMastery. All rights reserved.</p>
            </div>
        </div>
    </body>
    </html>
    ";
    
    return sendEmailNotification($userEmail, $subject, $message);
}

function sendNewUserRegistrationNotification($userEmail, $userName, $role = 'student') {
    $settings = getPlatformSettings();
    $adminEmail = $settings['contact_email'] ?? 'admin@codemastery.com';
    
    $subject = "New User Registration - CodeMastery";
    $message = "
    <!DOCTYPE html>
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: #17a2b8; color: #fff; padding: 20px; text-align: center; }
            .content { background: #f9f9f9; padding: 20px; }
            .user-info { background: #fff; padding: 15px; border-radius: 5px; margin: 10px 0; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h1>📈 New User Registration</h1>
            </div>
            <div class='content'>
                <p>A new user has registered on CodeMastery:</p>
                
                <div class='user-info'>
                    <strong>Name:</strong> {$userName}<br>
                    <strong>Email:</strong> {$userEmail}<br>
                    <strong>Role:</strong> {$role}<br>
                    <strong>Registration Date:</strong> " . date('Y-m-d H:i:s') . "
                </div>
                
                <p>You can view user details in the admin panel.</p>
                
                <p><a href='/admin/users' style='background: #17a2b8; color: #fff; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>View in Admin Panel</a></p>
            </div>
        </div>
    </body>
    </html>
    ";
    
    return sendEmailNotification($adminEmail, $subject, $message);
}

function sendInstructorApplicationRejectionEmail($userEmail, $userName, $reason = '') {
    $reasonText = $reason ? "<p><strong>Feedback from our team:</strong><br>{$reason}</p>" : "";
    
    $subject = "Update on Your Instructor Application - CodeMastery";
    $message = "
    <!DOCTYPE html>
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: #6c757d; color: #ffffff; padding: 30px; text-align: center; }
            .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 8px 8px; }
            .footer { background: #eeeeee; padding: 20px; text-align: center; font-size: 12px; color: #666; }
            .improvement { background: #fff3cd; padding: 20px; border-radius: 5px; margin: 20px 0; border-left: 4px solid #ffc107; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h1>CodeMastery Instructor Application</h1>
            </div>
            <div class='content'>
                <h2>Dear {$userName},</h2>
                <p>Thank you for your interest in becoming an instructor on CodeMastery. We appreciate the time and effort you put into your application.</p>
                
                <p>After careful review, we're unable to approve your instructor application at this time.</p>
                
                {$reasonText}
                
                <div class='improvement'>
                    <h3>💡 Ways to Strengthen Your Application:</h3>
                    <ul>
                        <li>Gain more practical experience in your field</li>
                        <li>Build a portfolio of your work or projects</li>
                        <li>Consider creating sample course content</li>
                        <li>Gather teaching experience through workshops or mentoring</li>
                        <li>Reapply in 3-6 months with additional experience</li>
                    </ul>
                </div>
                
                <p>We encourage you to continue developing your skills and reapply in the future. Our requirements and opportunities may also evolve, so we hope you'll consider applying again.</p>
                
                <p>If you have any questions about this decision or would like more specific feedback, please don't hesitate to contact our instructor support team.</p>
                
                <p>Thank you for your understanding and interest in CodeMastery.</p>
                
                <p>Best regards,<br>
                <strong>The CodeMastery Team</strong></p>
            </div>
            <div class='footer'>
                <p>This email was sent from CodeMastery Learning Platform<br>
                © 2024 CodeMastery. All rights reserved.</p>
            </div>
        </div>
    </body>
    </html>
    ";
    
    return sendEmailNotification($userEmail, $subject, $message);
}

function sendBlogPostApprovalEmail($userEmail, $userName, $postTitle, $postId) {
    $subject = "Your Blog Post Has Been Published - CodeMastery";
    $message = "
    <!DOCTYPE html>
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: #28a745; color: #ffffff; padding: 30px; text-align: center; }
            .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 8px 8px; }
            .footer { background: #eeeeee; padding: 20px; text-align: center; font-size: 12px; color: #666; }
            .button { background: #28a745; color: #ffffff; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block; margin: 10px 0; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h1>📝 Blog Post Published!</h1>
            </div>
            <div class='content'>
                <h2>Great News, {$userName}!</h2>
                <p>Your blog post <strong>\"{$postTitle}\"</strong> has been approved and published on CodeMastery!</p>
                
                <p>It's now live and visible to all our users. You can share it with your network and engage with readers through comments.</p>
                
                <p><strong>View your published post:</strong></p>
                <p>
                    <a href='/blog/{$postId}' class='button'>Read Your Published Post</a>
                </p>
                
                <p><strong>What's Next?</strong></p>
                <ul>
                    <li>Share your post on social media</li>
                    <li>Respond to reader comments</li>
                    <li>Consider writing a follow-up post</li>
                    <li>Check out analytics on your post's performance</li>
                </ul>
                
                <p>Thank you for contributing valuable content to the CodeMastery community!</p>
                
                <p>Best regards,<br>
                <strong>The CodeMastery Team</strong></p>
            </div>
            <div class='footer'>
                <p>This email was sent from CodeMastery Learning Platform<br>
                © 2024 CodeMastery. All rights reserved.</p>
            </div>
        </div>
    </body>
    </html>
    ";
    
    return sendEmailNotification($userEmail, $subject, $message);
}

function sendBlogPostRejectionEmail($userEmail, $userName, $postTitle, $reason = '') {
    $reasonText = $reason ? "<div style='background: #f8d7da; padding: 15px; border-radius: 5px; margin: 15px 0; border-left: 4px solid #dc3545;'><strong>Feedback from our editors:</strong><br>{$reason}</div>" : "";
    
    $subject = "Update on Your Blog Post Submission - CodeMastery";
    $message = "
    <!DOCTYPE html>
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: #dc3545; color: #ffffff; padding: 30px; text-align: center; }
            .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 8px 8px; }
            .footer { background: #eeeeee; padding: 20px; text-align: center; font-size: 12px; color: #666; }
            .improvement { background: #fff3cd; padding: 20px; border-radius: 5px; margin: 20px 0; border-left: 4px solid #ffc107; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h1>Blog Post Submission Update</h1>
            </div>
            <div class='content'>
                <h2>Dear {$userName},</h2>
                <p>Thank you for submitting your blog post <strong>\"{$postTitle}\"</strong> to CodeMastery.</p>
                
                <p>After careful review by our editorial team, we're unable to publish your post at this time.</p>
                
                {$reasonText}
                
                <div class='improvement'>
                    <h3>📝 Tips for Improving Your Submission:</h3>
                    <ul>
                        <li>Review our content guidelines and style guide</li>
                        <li>Ensure your content is original and provides unique value</li>
                        <li>Check for grammatical errors and improve readability</li>
                        <li>Add more practical examples or case studies</li>
                        <li>Consider the needs and interests of our audience</li>
                        <li>Resubmit after making revisions</li>
                    </ul>
                </div>
                
                <p>We encourage you to revise your post based on the feedback above and resubmit it for review.</p>
                
                <p>If you have any questions about our content guidelines or need clarification on the feedback, please contact our editorial team.</p>
                
                <p>We appreciate your contribution and hope to see more of your work in the future.</p>
                
                <p>Best regards,<br>
                <strong>The CodeMastery Editorial Team</strong></p>
            </div>
            <div class='footer'>
                <p>This email was sent from CodeMastery Learning Platform<br>
                © 2024 CodeMastery. All rights reserved.</p>
            </div>
        </div>
    </body>
    </html>
    ";
    
    return sendEmailNotification($userEmail, $subject, $message);
}

function sendCourseApprovalEmail($userEmail, $userName, $courseTitle, $courseId) {
    $subject = "Your Course Has Been Approved - CodeMastery";
    $message = "
    <!DOCTYPE html>
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: #007bff; color: #ffffff; padding: 30px; text-align: center; }
            .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 8px 8px; }
            .footer { background: #eeeeee; padding: 20px; text-align: center; font-size: 12px; color: #666; }
            .button { background: #007bff; color: #ffffff; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block; margin: 10px 0; }
            .celebrate { background: #d4edda; padding: 20px; border-radius: 5px; margin: 20px 0; border-left: 4px solid #28a745; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h1>🎓 Course Published Successfully!</h1>
            </div>
            <div class='content'>
                <h2>Congratulations, {$userName}!</h2>
                
                <div class='celebrate'>
                    <h3>Your course <strong>\"{$courseTitle}\"</strong> has been approved and is now live on CodeMastery!</h3>
                </div>
                
                <p>Students can now discover and enroll in your course. This is an exciting milestone in your instructor journey!</p>
                
                <p><strong>View your published course:</strong></p>
                <p>
                    <a href='/course/{$courseId}' class='button'>See Your Live Course</a>
                </p>
                
                <p><strong>Next Steps to Maximize Your Success:</strong></p>
                <ul>
                    <li>Share your course on social media and with your network</li>
                    <li>Promote your course through your website or blog</li>
                    <li>Engage with students through course discussions</li>
                    <li>Monitor your course analytics in the instructor dashboard</li>
                    <li>Consider creating promotional materials</li>
                </ul>
                
                <p><strong>Need help promoting your course?</strong><br>
                Check out our instructor resources for marketing tips and best practices.</p>
                
                <p>We're excited to see the impact your course will have on students!</p>
                
                <p>Best regards,<br>
                <strong>The CodeMastery Team</strong></p>
            </div>
            <div class='footer'>
                <p>This email was sent from CodeMastery Learning Platform<br>
                © 2024 CodeMastery. All rights reserved.</p>
            </div>
        </div>
    </body>
    </html>
    ";
    
    return sendEmailNotification($userEmail, $subject, $message);
}

function sendWelcomeEmail($userEmail, $userName, $role = 'student') {
    $roleText = $role === 'instructor' ? 'instructor' : 'student';
    $subject = "Welcome to CodeMastery - Get Started as a {$roleText}";
    
    $message = "
    <!DOCTYPE html>
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: #000000; color: #ffffff; padding: 30px; text-align: center; }
            .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 8px 8px; }
            .footer { background: #eeeeee; padding: 20px; text-align: center; font-size: 12px; color: #666; }
            .button { background: #000000; color: #ffffff; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block; margin: 10px 0; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h1>Welcome to CodeMastery!</h1>
            </div>
            <div class='content'>
                <h2>Hello, {$userName}!</h2>
                <p>Welcome to CodeMastery! We're excited to have you as part of our learning community.</p>
                
                <p><strong>Your account type:</strong> {$roleText}</p>
                
                <p><strong>Get Started:</strong></p>
                <p>
                    <a href='/dashboard' class='button'>Go to Your Dashboard</a>
                </p>
                
                <p>If you have any questions or need assistance, don't hesitate to contact our support team.</p>
                
                <p>Happy learning!<br>
                <strong>The CodeMastery Team</strong></p>
            </div>
            <div class='footer'>
                <p>This email was sent from CodeMastery Learning Platform<br>
                © 2024 CodeMastery. All rights reserved.</p>
            </div>
        </div>
    </body>
    </html>
    ";
    
    return sendEmailNotification($userEmail, $subject, $message);
}

// Test email configuration
function testEmailConfiguration($testEmail) {
    $subject = "CodeMastery - Email Configuration Test";
    $message = "
    <!DOCTYPE html>
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: #28a745; color: #fff; padding: 20px; text-align: center; }
            .content { background: #f9f9f9; padding: 20px; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h1>✅ Email Test Successful</h1>
            </div>
            <div class='content'>
                <p>This is a test email from your CodeMastery platform.</p>
                <p>If you're reading this, your email configuration is working correctly!</p>
                <p><strong>Timestamp:</strong> " . date('Y-m-d H:i:s') . "</p>
            </div>
        </div>
    </body>
    </html>
    ";
    
    return sendEmailNotification($testEmail, $subject, $message);
}
?>