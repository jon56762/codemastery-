<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'CodeMastery - Learn to Code'; ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="/assets/css/boostrap/bootstrap.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="/assets/css/font-awasome/css/all.css">
    <!-- CSS -->
    <link href="/assets/css/nav.css" rel="stylesheet">

</head>
<body>
    <?php
    // Auto-detect current page if not set
    if (!isset($current_page)) {
        $current_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $current_page = str_replace('/', '', $current_path);
        if (empty($current_page)) $current_page = 'home';
    }
    ?>
    
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="/">
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
                            <a class="nav-link <?= ($current_page === 'home' || $current_page === '') ? 'active' : '' ?>" 
                               href="/">
                               Home
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= ($current_page === 'courses') ? 'active' : '' ?>" 
                               href="/courses">
                               Courses
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= ($current_page === 'pricing') ? 'active' : '' ?>" 
                               href="/pricing">
                               Pricing
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= ($current_page === 'about') ? 'active' : '' ?>" 
                               href="/about">
                               About
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= ($current_page === 'blog') ? 'active' : '' ?>" 
                               href="/blog">
                               Blog
                            </a>
                        </li>
                        
                        <?php if (isset($_SESSION['user'])): ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="offcanvasNavbarDropdown" role="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-user me-1"></i>
                                    <?= htmlspecialchars($_SESSION['user']['name']) ?>
                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item <?= ($current_page === 'dashboard') ? 'active' : '' ?>" 
                                           href="/dashboard">
                                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <i class="fas fa-user me-2"></i>Profile
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item" href="/logout">
                                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        <?php else: ?>
                            <li class="nav-item">
                                <a href="/login" class="nav-link <?= ($current_page === 'login') ? 'active' : '' ?>">
                                    <i class="fas fa-sign-in-alt me-1"></i>Login
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/signup" class="btn btn-primary ms-2">
                                    Sign Up Free
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- Success/Error Messages -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show mb-0" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            <?= $_SESSION['success'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show mb-0" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            <?= $_SESSION['error'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="content-wrapper" style="padding-top: 80px;">