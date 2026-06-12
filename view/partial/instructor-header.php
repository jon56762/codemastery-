<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'Instructor Studio - CodeMastery'; ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="/assets/css/boostrap/bootstrap.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="/assets/css/font-awasome/css/all.css">
    <!-- Custom Instructor CSS -->
    <link href="/assets/css/instructor.css" rel="stylesheet">
</head>
<body>
    <!-- Instructor Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top instructor-nav">
        <div class="container-fluid">
            <!-- Left side: Back to site + Brand -->
            <div class="d-flex align-items-center">
                <a href="/" class="back-to-site me-3 ">
                    <i class="fas fa-arrow-left me-1"></i>
                </a>
                <a class="navbar-brand fw-bold" href="/instructor-dashboard">
                    <i class="fas fa-graduation-cap me-2"></i>CodeMastery
                    <small class="d-block text-muted" style="font-size: 0.7rem;">Instructor Studio</small>
                </a>
            </div>

            <!-- Mobile toggle + Create button -->
            <div class="d-flex align-items-center gap-2 ms-auto order-lg-last">
                <a href="/course-builder" class="btn-create d-none d-lg-inline-block">
                    <i class="fas fa-plus-circle me-1"></i>Create Course
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#instructorOffcanvas">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>

            <!-- Desktop Nav Links -->
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center">
                    <li class="nav-item">
                        <a class="nav-link <?= ($current_page ?? '') === 'instructor-dashboard' ? 'active' : '' ?>" href="/instructor-dashboard">
                            <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= ($current_page ?? '') === 'instructor-courses' ? 'active' : '' ?>" href="/instructor-courses">
                            <i class="fas fa-book me-1"></i>My Courses
                        </a>
                    </li>
		<li class="nav-item">
                    <a class="nav-link <?= ($current_page ?? '') === 'blog-creation' ? 'active' : '' ?>" href="/blog-creation">
                        <i class="fas fa-newspaper me-1"></i>Blogging
                    </a>
                </li>

                    <li class="nav-item">
                        <a class="nav-link <?= ($current_page ?? '') === 'instructor-analytics' ? 'active' : '' ?>" href="/instructor-analytics">
                            <i class="fas fa-chart-bar me-1"></i>Analytics
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= ($current_page ?? '') === 'instructor-earnings' ? 'active' : '' ?>" href="/instructor-earnings">
                            <i class="fas fa-dollar-sign me-1"></i>Earnings
                        </a>
                    </li>
                    <!-- User Dropdown -->
                    <li class="nav-item dropdown ms-3">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                            <img src="<?= htmlspecialchars($user['avatar'] ?? '/assets/images/avatars/default.png') ?>" 
                                 alt="<?= htmlspecialchars($user['name']) ?>" 
                                 class="rounded-circle me-2" width="32" height="32" style="object-fit: cover;">
                            <span><?= htmlspecialchars($user['name']) ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="/instructor-profile">
                                    <i class="fas fa-cog me-2"></i>Settings
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="/dashboard">
                                    <i class="fas fa-user-graduate me-2"></i>Student View
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-danger" href="/logout">
                                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Offcanvas for Mobile -->
    <div class="offcanvas offcanvas-end instructor-offcanvas" tabindex="-1" id="instructorOffcanvas">
        <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-title fw-bold">
                <i class="fas fa-graduation-cap me-2"></i>Instructor Studio
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
            <!-- User info -->
            <div class="d-flex align-items-center mb-4 p-3 bg-light rounded">
                <img src="<?= htmlspecialchars($user['avatar'] ?? '/assets/images/avatars/default.png') ?>" 
                     alt="<?= htmlspecialchars($user['name']) ?>" 
                     class="rounded-circle me-3" width="48" height="48" style="object-fit: cover;">
                <div>
                    <h6 class="fw-bold mb-0"><?= htmlspecialchars($user['name']) ?></h6>
                    <small class="text-muted">Instructor</small>
                </div>
            </div>

            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page ?? '') === 'instructor-dashboard' ? 'active' : '' ?>" href="/instructor-dashboard">
                        <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page ?? '') === 'instructor-courses' ? 'active' : '' ?>" href="/instructor-courses">
                        <i class="fas fa-book me-2"></i>My Courses
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page ?? '') === 'blog-creation' ? 'active' : '' ?>" href="/blog-creation">
                        <i class="fas fa-newspaper me-1"></i>Blogging
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page ?? '') === 'course-builder' ? 'active' : '' ?>" href="/course-builder">
                        <i class="fas fa-plus-circle me-2"></i>Create Course
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page ?? '') === 'instructor-analytics' ? 'active' : '' ?>" href="/instructor-analytics">
                        <i class="fas fa-chart-bar me-2"></i>Analytics
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page ?? '') === 'instructor-earnings' ? 'active' : '' ?>" href="/instructor-earnings">
                        <i class="fas fa-dollar-sign me-2"></i>Earnings
                    </a>
                </li>
                <li class="nav-item mt-3">
                    <a class="nav-link" href="/dashboard">
                        <i class="fas fa-user-graduate me-2"></i>Switch to Student View
                    </a>
                </li>
                <li class="nav-item mt-3">
                    <a class="btn btn-outline-danger w-100" href="/logout">
                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                    </a>
                </li>
            </ul>
        </div>
    </div>

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

    <div class="content-wrapper instructor-content">
<script src="../partial/js/bootstrap.bundle.js"></script>
        