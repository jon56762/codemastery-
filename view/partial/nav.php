<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'CodeMastery - Learn to Code'; ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #ffffff;
            color: #000000;
            line-height: 1.6;
        }
        
        .navbar {
            background-color: #ffffff !important;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-bottom: 1px solid #e5e5e5;
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: #000000 !important;
        }
        
        .nav-link {
            font-weight: 500;
            color: #000000 !important;
            padding: 0.75rem 1rem;
            border-radius: 6px;
            transition: all 0.3s ease;
        }
        
        .nav-link:hover {
            background-color: #f8f9fa;
            color: #000000 !important;
        }
        
        .btn-primary {
            background-color: #000000;
            border: 2px solid #000000;
            color: #ffffff;
            padding: 0.5rem 1.5rem;
            font-weight: 600;
            border-radius: 6px;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background-color: #333333;
            border-color: #333333;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        
        .btn-outline-light {
            border: 2px solid #000000;
            color: #000000;
            background-color: transparent;
            padding: 0.5rem 1.5rem;
            font-weight: 600;
            border-radius: 6px;
            transition: all 0.3s ease;
        }
        
        .btn-outline-light:hover {
            background-color: #000000;
            color: #ffffff;
            transform: translateY(-2px);
        }

        /* Offcanvas Styles */
        .offcanvas-header {
            border-bottom: 1px solid #e5e5e5;
            padding: 1.5rem;
        }

        .offcanvas-title {
            font-weight: 700;
            font-size: 1.5rem;
            color: #000000;
        }

        .offcanvas-body {
            padding: 1.5rem;
        }

        .offcanvas-body .nav-link {
            padding: 0.75rem 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .offcanvas-body .nav-link:last-child {
            border-bottom: none;
        }

        .offcanvas-body .navbar-nav {
            gap: 0.5rem;
        }

        .navbar-toggler {
            border: 2px solid #000000;
            padding: 0.5rem 0.75rem;
            border-radius: 6px;
        }

        .navbar-toggler:focus {
            box-shadow: none;
        }

        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%280, 0, 0, 0.8%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        .alert {
            border: none;
            border-radius: 8px;
            font-weight: 500;
        }

        /* Ensure content doesn't get hidden behind fixed navbar */
        .content-wrapper {
            padding-top: 80px;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-light fixed-top shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= BASE_PATH ?>/">
                <i class="fas fa-graduation-cap me-2"></i>CodeMastery
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasNavbarLabel">
                        <i class="fas fa-graduation-cap me-2"></i>CodeMastery
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
                </div>
                <div class="offcanvas-body">
                    <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                        <li class="nav-item">
                            <a class="nav-link" href="<?= BASE_PATH ?>/">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= BASE_PATH ?>/courses">Courses</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= BASE_PATH ?>/pricing">Pricing</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= BASE_PATH ?>/about">About</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= BASE_PATH ?>/blog">Blog</a>
                        </li>
                        
                        <?php if (isset($_SESSION['user'])): ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="offcanvasNavbarDropdown" role="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-user me-1"></i>
                                    <?= htmlspecialchars($_SESSION['user']['name'] ?? 'User') ?>
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="<?= BASE_PATH ?>/dashboard"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a></li>
                                    <li><a class="dropdown-item" href="<?= BASE_PATH ?>/profile"><i class="fas fa-user me-2"></i>Profile</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="<?= BASE_PATH ?>/logout"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                                </ul>
                            </li>
                        <?php else: ?>
                            <li class="nav-item">
                                <a href="<?= BASE_PATH ?>/login" class="nav-link">
                                    <i class="fas fa-sign-in-alt me-1"></i>Login
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= BASE_PATH ?>/signup" class="btn btn-primary w-100 mt-2">
                                    Sign Up Free
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- Success Messages -->
    <?php if (isset($_SESSION['newsletter_success'])): ?>
        <div class="alert alert-success alert-dismissible fade show mb-0" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            <?= $_SESSION['newsletter_success'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['newsletter_success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['signup_success'])): ?>
        <div class="alert alert-success alert-dismissible fade show mb-0" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            <?= $_SESSION['signup_success'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['signup_success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['contact_success'])): ?>
        <div class="alert alert-success alert-dismissible fade show mb-0" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            <?= $_SESSION['contact_success'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['contact_success']); ?>
    <?php endif; ?>

    <div class="content-wrapper">