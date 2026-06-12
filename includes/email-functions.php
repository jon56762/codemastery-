<?php

require_once __DIR__ . '/../vendor/autoload.php';

require_once __DIR__ . '/../PHPMailer/src/Exception.php';
require_once __DIR__ . '/../PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../PHPMailer/src/SMTP.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$environment = 'production';

class EmailService
{
    private $mail;
    private $devMode;
    private $settings;


    public function __construct()
    {
        global $environment;
        $this->devMode = ($environment === 'development');

        if (!$this->devMode) {
            $this->initMailer();
        }

        $this->loadSettings();
        $this->initMailer();
    }

    private function loadSettings()
    {
        if (function_exists('getPlatformSettings')) {
            $this->settings = getPlatformSettings();
        } else {
            $configFile = __DIR__ . '/../config/email.php';
            if (file_exists($configFile)) {
                $this->settings = require $configFile;
            } else {
                // Default settings (update with your own)
                $this->settings = [
                    'smtp_host'      => 'smtp-codemastery.alwaysdata.net',
                    'smtp_username'  => 'codemastery@alwaysdata.net',
                    'smtp_password'  => 'succ00$$',
                    'smtp_port'      => 587,
                    'smtp_encryption'=> 'tls',
                    'contact_email'  => 'admin@codemastery.com',
                    'site_name'      => 'CodeMastery'
                ];
            }
        }
    }

  
    private function initMailer()
    {
        $this->mail = new PHPMailer(true);
        try {
            $this->mail->isSMTP();
            $this->mail->Host       = $this->settings['smtp_host'] ?? 'smtp-codemastery.alwaysdata.net';
            $this->mail->SMTPAuth   = true;
            $this->mail->Username   = $this->settings['smtp_username'] ?? 'codemastery@alwaysdata.net';
            $this->mail->Password   = $this->settings['smtp_password'] ?? 'succ00$$';
            $this->mail->SMTPSecure = $this->settings['smtp_encryption'] ?? PHPMailer::ENCRYPTION_STARTTLS;
            $this->mail->Port       = $this->settings['smtp_port'] ?? 587;
            
            $this->mail->setFrom($this->settings['smtp_username'] ?? 'codemastery@alwaysdata.net', $this->settings['site_name'] ?? 'CodeMastery');
        } catch (Exception $e) {
            error_log("Mailer initialization failed: " . $e->getMessage());
        }
    }

    /**
     * Send an email
     * @param string $to Recipient email
     * @param string $subject Email subject
     * @param string $body HTML body
     * @param bool $isHTML Whether body is HTML
     * @return bool
     */
    public function send($to, $subject, $body, $isHTML = true)
    {
        try {
            $this->mail->clearAddresses();
            $this->mail->addAddress($to);
            $this->mail->Subject = $subject;
            $this->mail->isHTML($isHTML);
            $this->mail->Body = $body;
            if (!$isHTML) {
                $this->mail->AltBody = strip_tags($body);
            }

            $result = $this->mail->send();
            $this->logNotification($to, $subject, 'sent');
            return $result;
        } catch (Exception $e) {
            $this->logNotification($to, $subject, 'failed', $this->mail->ErrorInfo);
            error_log("Email failed to {$to}: " . $this->mail->ErrorInfo);
            return false;
        }
    }

    private function logNotification($to, $subject, $status, $error = '')
    {
        // Database logging (preferred)
        $db = Database::getConnection();
        if ($db) {
            $stmt = $db->prepare("INSERT INTO email_logs (recipient, subject, status, error_message, created_at) VALUES (?, ?, ?, ?, NOW())");
            if ($stmt) {
                $stmt->bind_param("ssss", $to, $subject, $status, $error);
                $stmt->execute();
                $stmt->close();
                return;
            }
        }
        
        // Fallback: file logging
        $logFile = __DIR__ . '/../logs/email.log';
        $logDir = dirname($logFile);
        if (!is_dir($logDir)) mkdir($logDir, 0777, true);
        $log = "[" . date('Y-m-d H:i:s') . "] $status | To: $to | Subject: $subject | Error: $error" . PHP_EOL;
        file_put_contents($logFile, $log, FILE_APPEND);
    }

    public function sendWelcomeEmail($userEmail, $userName, $role = 'student')
    {
        $roleText = $role === 'instructor' ? 'instructor' : 'student';
        $subject = "Welcome to CodeMastery - Get Started as a {$roleText}";
        $body = $this->getWelcomeTemplate($userName, $roleText);
        return $this->send($userEmail, $subject, $body);
    }

    public function sendInstructorApplicationApprovalEmail($userEmail, $userName)
    {
        $subject = "Your Instructor Application Has Been Approved - CodeMastery";
        $body = $this->getInstructorApprovalTemplate($userName);
        return $this->send($userEmail, $subject, $body);
    }

    public function sendInstructorApplicationRejectionEmail($userEmail, $userName, $reason = '')
    {
        $subject = "Update on Your Instructor Application - CodeMastery";
        $body = $this->getInstructorRejectionTemplate($userName, $reason);
        return $this->send($userEmail, $subject, $body);
    }

    public function sendCourseApprovalEmail($userEmail, $userName, $courseTitle, $courseId)
    {
        $subject = "Your Course Has Been Approved - CodeMastery";
        $body = $this->getCourseApprovalTemplate($userName, $courseTitle, $courseId);
        return $this->send($userEmail, $subject, $body);
    }

    public function sendBlogPostApprovalEmail($userEmail, $userName, $postTitle, $postId)
    {
        $subject = "Your Blog Post Has Been Published - CodeMastery";
        $body = $this->getBlogApprovalTemplate($userName, $postTitle, $postId);
        return $this->send($userEmail, $subject, $body);
    }

    public function sendBlogPostRejectionEmail($userEmail, $userName, $postTitle, $reason = '')
    {
        $subject = "Update on Your Blog Post Submission - CodeMastery";
        $body = $this->getBlogRejectionTemplate($userName, $postTitle, $reason);
        return $this->send($userEmail, $subject, $body);
    }

    public function sendNewUserRegistrationNotification($userEmail, $userName, $role = 'student')
    {
        $adminEmail = $this->settings['contact_email'] ?? 'admin@codemastery.com';
        $subject = "New User Registration - CodeMastery";
        $body = $this->getNewUserNotificationTemplate($userName, $userEmail, $role);
        return $this->send($adminEmail, $subject, $body);
    }

    public function testEmailConfiguration($testEmail)
    {
        $subject = "CodeMastery - Email Configuration Test";
        $body = $this->getTestEmailTemplate();
        return $this->send($testEmail, $subject, $body);
    }

    private function getWelcomeTemplate($userName, $roleText)
    {
        return <<<HTML
        <!DOCTYPE html>
        <html>
        <head><style>body{font-family:Arial;line-height:1.6}.header{background:#000;color:#fff;padding:20px;text-align:center}.content{background:#f9f9f9;padding:30px}.button{background:#000;color:#fff;padding:12px 30px;text-decoration:none;border-radius:5px}</style></head>
        <body>
        <div class='header'><h1>Welcome to CodeMastery!</h1></div>
        <div class='content'>
            <h2>Hello, {$userName}!</h2>
            <p>Welcome to CodeMastery! Your account has been created as a <strong>{$roleText}</strong>.</p>
            <p><a href='/dashboard' class='button'>Go to Dashboard</a></p>
            <p>Happy learning!<br><strong>The CodeMastery Team</strong></p>
        </div>
        </body>
        </html>
        HTML;
    }

    private function getInstructorApprovalTemplate($userName)
    {
        return <<<HTML
        <!DOCTYPE html>
        <html>
        <head><style>body{font-family:Arial;line-height:1.6}.header{background:#000;color:#fff;padding:20px}.button{background:#000;color:#fff;padding:10px 20px;text-decoration:none}</style></head>
        <body>
        <div class='header'><h1>🎉 Instructor Application Approved!</h1></div>
        <div class='content'>
            <h2>Congratulations, {$userName}!</h2>
            <p>You can now create courses and share your knowledge.</p>
            <p><a href='/instructor-dashboard' class='button'>Go to Instructor Dashboard</a></p>
            <p>Best,<br>The CodeMastery Team</p>
        </div>
        </body>
        </html>
        HTML;
    }

    private function getInstructorRejectionTemplate($userName, $reason)
    {
        $reasonHtml = $reason ? "<p><strong>Feedback:</strong> {$reason}</p>" : "";
        return <<<HTML
        <!DOCTYPE html>
        <html>
        <head><style>body{font-family:Arial;line-height:1.6}.header{background:#6c757d;color:#fff;padding:20px}</style></head>
        <body>
        <div class='header'><h1>Instructor Application Update</h1></div>
        <div class='content'>
            <h2>Dear {$userName},</h2>
            <p>We appreciate your interest, but we cannot approve your application at this time.</p>
            {$reasonHtml}
            <p>We encourage you to reapply in the future.</p>
            <p>Best regards,<br>The CodeMastery Team</p>
        </div>
        </body>
        </html>
        HTML;
    }

    private function getCourseApprovalTemplate($userName, $courseTitle, $courseId)
    {
        return <<<HTML
        <!DOCTYPE html>
        <html>
        <head><style>body{font-family:Arial;line-height:1.6}.header{background:#007bff;color:#fff;padding:20px}.button{background:#007bff;color:#fff;padding:10px 20px;text-decoration:none}</style></head>
        <body>
        <div class='header'><h1>🎓 Course Published!</h1></div>
        <div class='content'>
            <h2>Congratulations, {$userName}!</h2>
            <p>Your course "<strong>{$courseTitle}</strong>" is now live.</p>
            <p><a href='/course/{$courseId}' class='button'>View Course</a></p>
            <p>Best,<br>The CodeMastery Team</p>
        </div>
        </body>
        </html>
        HTML;
    }

    private function getBlogApprovalTemplate($userName, $postTitle, $postId)
    {
        return <<<HTML
        <!DOCTYPE html>
        <html>
        <head><style>body{font-family:Arial;line-height:1.6}.header{background:#28a745;color:#fff;padding:20px}.button{background:#28a745;color:#fff;padding:10px 20px}</style></head>
        <body>
        <div class='header'><h1>📝 Blog Post Published</h1></div>
        <div class='content'>
            <h2>Great news, {$userName}!</h2>
            <p>Your post "<strong>{$postTitle}</strong>" is now live.</p>
            <p><a href='/blog/{$postId}' class='button'>Read Post</a></p>
            <p>Thank you for contributing!<br>The CodeMastery Team</p>
        </div>
        </body>
        </html>
        HTML;
    }

    private function getBlogRejectionTemplate($userName, $postTitle, $reason)
    {
        $reasonHtml = $reason ? "<p><strong>Reason:</strong> {$reason}</p>" : "";
        return <<<HTML
        <!DOCTYPE html>
        <html>
        <head><style>body{font-family:Arial;line-height:1.6}.header{background:#dc3545;color:#fff;padding:20px}</style></head>
        <body>
        <div class='header'><h1>Blog Post Submission Update</h1></div>
        <div class='content'>
            <h2>Dear {$userName},</h2>
            <p>We cannot publish your post "<strong>{$postTitle}</strong>" at this time.</p>
            {$reasonHtml}
            <p>Please revise and resubmit.</p>
            <p>Regards,<br>The Editorial Team</p>
        </div>
        </body>
        </html>
        HTML;
    }

    private function getNewUserNotificationTemplate($userName, $userEmail, $role)
    {
        return <<<HTML
        <!DOCTYPE html>
        <html>
        <head><style>body{font-family:Arial;line-height:1.6}.header{background:#17a2b8;color:#fff;padding:20px}</style></head>
        <body>
        <div class='header'><h1>New User Registration</h1></div>
        <div class='content'>
            <p><strong>Name:</strong> {$userName}<br>
            <strong>Email:</strong> {$userEmail}<br>
            <strong>Role:</strong> {$role}<br>
            <strong>Registered:</strong> " . date('Y-m-d H:i:s') . "</p>
            <p><a href='/admin/users'>View in Admin Panel</a></p>
        </div>
        </body>
        </html>
        HTML;
    }

    private function getTestEmailTemplate()
    {
        return <<<HTML
        <!DOCTYPE html>
        <html>
        <head><style>body{font-family:Arial;line-height:1.6}.header{background:#28a745;color:#fff;padding:20px}</style></head>
        <body>
        <div class='header'><h1>✅ Email Test Successful</h1></div>
        <div class='content'>
            <p>This is a test email from your CodeMastery platform.</p>
            <p>If you're reading this, your email configuration is working!</p>
            <p><strong>Timestamp:</strong> " . date('Y-m-d H:i:s') . "</p>
        </div>
        </body>
        </html>
        HTML;
    }
}

// Create a global instance for easy access (optional)
$emailService = new EmailService();

if (!function_exists('sendEmailNotification')) {
    function sendEmailNotification($to, $subject, $message, $isHTML = true) {
        global $emailService;
        return $emailService->send($to, $subject, $message, $isHTML);
    }
}
if (!function_exists('sendWelcomeEmail')) {
    function sendWelcomeEmail($userEmail, $userName, $role = 'student') {
        global $emailService;
        return $emailService->sendWelcomeEmail($userEmail, $userName, $role);
    }
}
if (!function_exists('sendInstructorApplicationApprovalEmail')) {
    function sendInstructorApplicationApprovalEmail($userEmail, $userName) {
        global $emailService;
        return $emailService->sendInstructorApplicationApprovalEmail($userEmail, $userName);
    }
}
if (!function_exists('sendInstructorApplicationRejectionEmail')) {
    function sendInstructorApplicationRejectionEmail($userEmail, $userName, $reason = '') {
        global $emailService;
        return $emailService->sendInstructorApplicationRejectionEmail($userEmail, $userName, $reason);
    }
}
if (!function_exists('sendCourseApprovalEmail')) {
    function sendCourseApprovalEmail($userEmail, $userName, $courseTitle, $courseId) {
        global $emailService;
        return $emailService->sendCourseApprovalEmail($userEmail, $userName, $courseTitle, $courseId);
    }
}
if (!function_exists('sendBlogPostApprovalEmail')) {
    function sendBlogPostApprovalEmail($userEmail, $userName, $postTitle, $postId) {
        global $emailService;
        return $emailService->sendBlogPostApprovalEmail($userEmail, $userName, $postTitle, $postId);
    }
}
if (!function_exists('sendBlogPostRejectionEmail')) {
    function sendBlogPostRejectionEmail($userEmail, $userName, $postTitle, $reason = '') {
        global $emailService;
        return $emailService->sendBlogPostRejectionEmail($userEmail, $userName, $postTitle, $reason);
    }
}
if (!function_exists('sendNewUserRegistrationNotification')) {
    function sendNewUserRegistrationNotification($userEmail, $userName, $role = 'student') {
        global $emailService;
        return $emailService->sendNewUserRegistrationNotification($userEmail, $userName, $role);
    }
}
if (!function_exists('testEmailConfiguration')) {
    function testEmailConfiguration($testEmail) {
        global $emailService;
        return $emailService->testEmailConfiguration($testEmail);
    }
}