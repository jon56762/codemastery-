<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'Instructor Dashboard - CodeMastery'; ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="/assets/css/boostrap/bootstrap.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="/assets/css/font-awasome/css/all.css">
    <!-- Custom CSS -->
    <link href="/assets/css/instructor.css" rel="stylesheet">
</head>
<body>
    <!-- Instructor Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top instructor-nav">
        <div class="container-fluid">
            <a class="navbar-brand" href="/instructor-dashboard">
                <i class="fas fa-chalkboard-teacher me-2"></i>Instructor Dashboard
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#instructorNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="offcanvas offcanvas-end bg-dark text-white" tabindex="-1" id="instructorNavbar">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title">
                        <i class="fas fa-chalkboard-teacher me-2"></i>Instructor Panel
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
                </div>
                <div class="offcanvas-body">
                    <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                        <li class="nav-item">
                            <a class="nav-link <?= ($current_page ?? '') === 'instructor-dashboard' ? 'active' : '' ?>" 
                               href="/instructor-dashboard">
                                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= ($current_page ?? '') === 'instructor-courses' ? 'active' : '' ?>" 
                               href="/instructor-courses">
                                <i class="fas fa-book me-2"></i>My Courses
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= ($current_page ?? '') === 'course-builder' ? 'active' : '' ?>" 
                               href="/course-builder">
                                <i class="fas fa-plus-circle me-2"></i>Create Course
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="fas fa-chart-bar me-2"></i>Analytics
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="fas fa-dollar-sign me-2"></i>Earnings
                            </a>
                        </li>
                        
                        <!-- User Dropdown -->
                        <li class="nav-item dropdown mt-2">
                            <a class="nav-link dropdown-toggle" href="#" id="instructorDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user me-1"></i>
                                <?= htmlspecialchars($user['name']) ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-dark">
                                <li>
                                    <a class="dropdown-item" href="/instructor-dashboard">
                                        <i class="fas fa-tachometer-alt me-2"></i>Instructor Dashboard
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="/dashboard">
                                        <i class="fas fa-user-graduate me-2"></i>Student View
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="/profile">
                                        <i class="fas fa-cog me-2"></i>Settings
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="/logout">
                                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                                    </a>
                                </li>
                            </ul>
                        </li>
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

    <div class="content-wrapper instructor-content">
 <script src="/assets/js/bootstrap.bundle.js"></script>       