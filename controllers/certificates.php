<?php
require_once 'includes/auth-functions.php';
require_once 'includes/init.php';
requireAuth();

$user = getCurrentUser();

// Handle certificate actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['download_certificate'])) {
        $certificateId = $_POST['certificate_id'];
        downloadCertificate($certificateId, $user['id']);
        exit;
    } elseif (isset($_POST['share_certificate'])) {
        $certificateId = $_POST['certificate_id'];
        $platform = $_POST['platform'];
        if (shareCertificate($certificateId, $platform)) {
            $_SESSION['success'] = "Certificate shared successfully!";
        } else {
            $_SESSION['error'] = "Failed to share certificate.";
        }
    }
    
    header('Location: /certificates');
    exit;
}

// Get student certificates
$certificates = getStudentCertificates($user['id']);
$achievements = getStudentAchievements($user['id']);

$page_title = "My Certificates - CodeMastery";
$current_page = 'certificates';

require 'view/partial/nav.php';
require 'view/student/certificates.php';
require 'view/partial/footer.php';
?>