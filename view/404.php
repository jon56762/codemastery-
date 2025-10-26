<?php
http_response_code(404);
$page_title = "Page Not Found - CodeMastery";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?></title>
    <link href="/assets/css/boostrap/bootstrap.css" rel="stylesheet">
    <link href="/assets/css/font-awasome/css/all.css" rel="stylesheet">
    <style>
        .error-page {
            min-height: 100vh;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .error-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
            max-width: 500px;
            width: 90%;
        }
        .error-icon {
            font-size: 6rem;
            color: #6c757d;
            margin-bottom: 2rem;
        }
        .error-animation {
            animation: bounce 2s infinite;
        }
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {transform: translateY(0);}
            40% {transform: translateY(-30px);}
            60% {transform: translateY(-15px);}
        }
    </style>
</head>
<body>
    <div class="error-page">
        <div class="error-card text-center p-5">
            <div class="error-icon error-animation">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            
            <h1 class="display-4 fw-bold text-dark mb-3">404</h1>
            <h2 class="h3 text-muted mb-4">Page Not Found</h2>
            
            <p class="text-muted mb-4">
                Oops! The page you're looking for seems to have wandered off into the digital wilderness. 
                Don't worry, even the best explorers sometimes take wrong turns.
            </p>
            
            <div class="row mb-4">
                <div class="col-12">
                    <div class="alert alert-info">
                        <i class="fas fa-lightbulb me-2"></i>
                        <strong>Quick Tips:</strong> Check the URL for typos or use the navigation menu to find your way.
                    </div>
                </div>
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                <a href="/" class="btn btn-primary btn-lg">
                    <i class="fas fa-home me-2"></i>Back to Home
                </a>
                <a href="/courses" class="btn btn-outline-primary btn-lg">
                    <i class="fas fa-book me-2"></i>Browse Courses
                </a>
            </div>

            <div class="mt-4">
                <small class="text-muted">
                    Need help? <a href="/contact" class="text-decoration-none">Contact our support team</a>
                </small>
            </div>

            <!-- Fun illustration -->
            <div class="mt-4">
                <div class="d-flex justify-content-center">
                    <div class="text-muted">
                        <i class="fas fa-binoculars fa-2x me-3"></i>
                        <i class="fas fa-map-signs fa-2x me-3"></i>
                        <i class="fas fa-compass fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="/assets/js/bootstrap.bundle.js"></script>
</body>
</html>