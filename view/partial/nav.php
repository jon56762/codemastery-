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
    <nav class="navbar navbar-expand-lg navbar-light fixed-top bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/">
                <i class="fas fa-graduation-cap me-2"></i>CodeMastery
            </a>
            <div class="d-flex align-items-center gap-2 ms-auto order-lg-last">
                <?php if (isset($_SESSION['user'])): ?>
                    <a href="/notifications" class="text-decoration-none text-dark position-relative me-2">
                        <i class="fas fa-bell fs-5"></i>
                        <?php
                        // Count unread notifications for the logged-in user
                        $unreadCount = 0;
                        if (isset($_SESSION['user'])) {
                            $notifications = getFromFile('notifications.json');
                            $unreadCount = count(array_filter($notifications, function ($n) {
                                return isset($n['user_id']) && $n['user_id'] == $_SESSION['user']['id'] && empty($n['read']);
                            }));
                        }
                        if ($unreadCount > 0): ?>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size:0.65rem;">
                                <?= $unreadCount ?>
                            </span>
                        <?php endif; ?>
                    </a>
                <?php endif; ?>

                <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>


            <div class="collapse navbar-collapse">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link <?= ($current_page === 'home' || $current_page === '') ? 'active' : '' ?>" href="/">
                            <i class="fas fa-home me-1"></i>Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= ($current_page === 'courses') ? 'active' : '' ?>" href="/courses">
                            <i class="fas fa-book me-1"></i>Courses
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= ($current_page === 'pricing') ? 'active' : '' ?>" href="/pricing">
                            <i class="fas fa-tag me-1"></i>Pricing
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= ($current_page === 'testimonials') ? 'active' : '' ?>" href="/testimonials">
                            <i class="fas fa-comments me-1"></i>Testimonials
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= ($current_page === 'about') ? 'active' : '' ?>" href="/about">
                            <i class="fas fa-info-circle me-1"></i>About
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= ($current_page === 'contact') ? 'active' : '' ?>" href="/contact">
                            <i class="fas fa-envelope me-1"></i>Contact
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= ($current_page === 'blog') ? 'active' : '' ?>" href="/blog">
                            <i class="fas fa-newspaper me-1"></i>Blog
                        </a>
                    </li>
                </ul>

                <?php if (isset($_SESSION['user'])): ?>
                    <!-- User Dropdown -->
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                                <img src="<?= htmlspecialchars($_SESSION['user']['avatar'] ?? '/assets/images/avatars/default.png') ?>"
                                    alt="<?= htmlspecialchars($_SESSION['user']['name']) ?>"
                                    class="rounded-circle me-2" width="32" height="32" style="object-fit: cover;">
                                <span><?= htmlspecialchars($_SESSION['user']['name']) ?></span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <!-- Admin Access -->
                                <?php if ($_SESSION['user']['role'] === 'admin'): ?>
                                    <li>
                                        <a class="dropdown-item text-danger fw-semibold" href="/admin">
                                            <i class="fas fa-crown me-2"></i>Admin Panel
                                        </a>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                <?php endif; ?>

                                <!-- Instructor Access -->
                                <?php if ($_SESSION['user']['role'] === 'instructor'): ?>
                                    <li>
                                        <a class="dropdown-item text-success fw-semibold" href="/instructor-dashboard">
                                            <i class="fas fa-chalkboard-teacher me-2"></i>Instructor Dashboard
                                        </a>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                <?php endif; ?>

                                <!-- Common User Links -->
                                <li>
                                    <a class="dropdown-item <?= ($current_page === 'dashboard') ? 'active' : '' ?>" href="/dashboard">
                                        <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item <?= ($current_page === 'my-courses') ? 'active' : '' ?>" href="/my-courses">
                                        <i class="fas fa-book me-2"></i>My Courses
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item <?= ($current_page === 'profile') ? 'active' : '' ?>" href="/profile">
                                        <i class="fas fa-user me-2"></i>Profile
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item <?= ($current_page === 'billing') ? 'active' : '' ?>" href="/billing">
                                        <i class="fas fa-credit-card me-2"></i>Billing
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item <?= ($current_page === 'certificates') ? 'active' : '' ?>" href="/certificates">
                                        <i class="fas fa-award me-2"></i>Certificates
                                    </a>
                                </li>

                                <!-- Become Instructor for Students -->
                                <?php if ($_SESSION['user']['role'] === 'student'): ?>
                                    <li>
                                        <a class="dropdown-item text-info" href="/become-instructor">
                                            <i class="fas fa-chalkboard-teacher me-2"></i>Become Instructor
                                        </a>
                                    </li>
                                <?php endif; ?>

                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item text-danger" href="/logout">
                                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                <?php else: ?>
                    <!-- Guest User Links -->
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a href="/login" class="nav-link <?= ($current_page === 'login') ? 'active' : '' ?>">
                                <i class="fas fa-sign-in-alt me-1"></i>Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/signup" class="btn btn-primary ms-2">
                                <i class="fas fa-user-plus me-1"></i>Sign Up Free
                            </a>
                        </li>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Offcanvas Navigation -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
        <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-title fw-bold" id="offcanvasNavbarLabel">
                <i class="fas fa-graduation-cap me-2"></i>CodeMastery
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <ul class="navbar-nav justify-content-end flex-grow-1">
                <?php if (isset($_SESSION['user'])): ?>
                    <!-- User Info at Top -->
                    <div class="d-flex align-items-center mb-4 p-3 bg-light rounded">
                        <img src="<?= htmlspecialchars($_SESSION['user']['avatar'] ?? '/assets/images/avatars/default.png') ?>"
                            alt="<?= htmlspecialchars($_SESSION['user']['name']) ?>"
                            class="rounded-circle me-3" width="48" height="48" style="object-fit: cover;">
                        <div>
                            <h6 class="fw-bold mb-0"><?= htmlspecialchars($_SESSION['user']['name']) ?></h6>
                            <small class="text-muted"><?= ucfirst($_SESSION['user']['role']) ?></small>
                        </div>
                    </div>
                <?php endif; ?>
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page === 'home' || $current_page === '') ? 'active' : '' ?>" href="/">
                        <i class="fas fa-home me-2"></i>Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page === 'courses') ? 'active' : '' ?>" href="/courses">
                        <i class="fas fa-book me-2"></i>Courses
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page === 'pricing') ? 'active' : '' ?>" href="/pricing">
                        <i class="fas fa-tag me-2"></i>Pricing
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page === 'testimonials') ? 'active' : '' ?>" href="/testimonials">
                        <i class="fas fa-comments me-2"></i>Testimonials
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page === 'about') ? 'active' : '' ?>" href="/about">
                        <i class="fas fa-info-circle me-2"></i>About
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page === 'contact') ? 'active' : '' ?>" href="/contact">
                        <i class="fas fa-envelope me-2"></i>Contact
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page === 'blog') ? 'active' : '' ?>" href="/blog">
                        <i class="fas fa-newspaper me-2"></i>Blog
                    </a>
                </li>

                <?php if (isset($_SESSION['user'])): ?>
                    <!-- User Section in Offcanvas -->
                    <li class="nav-item mt-4 pt-3 border-top">

                        <!-- Admin Access -->
                        <?php if ($_SESSION['user']['role'] === 'admin'): ?>
                            <a class="btn btn-danger w-100 mb-2 fw-semibold" href="/admin">
                                <i class="fas fa-crown me-2"></i>Admin Panel
                            </a>
                        <?php endif; ?>

                        <!-- Instructor Access -->
                        <?php if ($_SESSION['user']['role'] === 'instructor'): ?>
                            <a class="btn btn-success w-100 mb-2 fw-semibold" href="/instructor-dashboard">
                                <i class="fas fa-chalkboard-teacher me-2"></i>Instructor Dashboard
                            </a>
                        <?php endif; ?>

                        <!-- Common User Links -->
                        <div class="list-group list-group-flush">
                            <a class="list-group-item list-group-item-action border-0 <?= ($current_page === 'dashboard') ? 'active' : '' ?>" href="/dashboard">
                                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                            </a>
                            <a class="list-group-item list-group-item-action border-0 <?= ($current_page === 'my-courses') ? 'active' : '' ?>" href="/my-courses">
                                <i class="fas fa-book me-2"></i>My Courses
                            </a>
                            <a class="list-group-item list-group-item-action border-0 <?= ($current_page === 'profile') ? 'active' : '' ?>" href="/profile">
                                <i class="fas fa-user me-2"></i>Profile
                            </a>
                            <a class="list-group-item list-group-item-action border-0 <?= ($current_page === 'billing') ? 'active' : '' ?>" href="/billing">
                                <i class="fas fa-credit-card me-2"></i>Billing
                            </a>
                            <a class="list-group-item list-group-item-action border-0 <?= ($current_page === 'certificates') ? 'active' : '' ?>" href="/certificates">
                                <i class="fas fa-award me-2"></i>Certificates
                            </a>

                            <!-- Become Instructor for Students -->
                            <?php if ($_SESSION['user']['role'] === 'student'): ?>
                                <a class="list-group-item list-group-item-action border-0 text-info" href="/become-instructor">
                                    <i class="fas fa-chalkboard-teacher me-2"></i>Become Instructor
                                </a>
                            <?php endif; ?>
                        </div>

                        <a class="btn btn-outline-danger w-100 mt-3" href="/logout">
                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                        </a>
                    </li>
                <?php else: ?>
                    <!-- Guest User Links in Offcanvas -->
                    <li class="nav-item mt-4 pt-3 border-top">
                        <a href="/login" class="btn btn-outline-primary w-100 mb-2">
                            <i class="fas fa-sign-in-alt me-2"></i>Login
                        </a>
                        <a href="/signup" class="btn btn-primary w-100">
                            <i class="fas fa-user-plus me-2"></i>Sign Up Free
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>

    <!-- Success/Error Messages -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show mb-0" role="alert" style="margin-top: 80px;">
            <i class="fas fa-check-circle me-2"></i>
            <?= $_SESSION['success'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show mb-0" role="alert" style="margin-top: 80px;">
            <i class="fas fa-exclamation-circle me-2"></i>
            <?= $_SESSION['error'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="content-wrapper" style="padding-top: 80px;">

        <script>
            // Auto-dismiss alerts after 5 seconds
            document.addEventListener('DOMContentLoaded', function() {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => {
                    setTimeout(() => {
                        if (alert.classList.contains('show')) {
                            const bsAlert = new bootstrap.Alert(alert);
                            bsAlert.close();
                        }
                    }, 5000);
                });
            });

            // Close offcanvas when clicking on links
            document.addEventListener('DOMContentLoaded', function() {
                const offcanvas = document.getElementById('offcanvasNavbar');
                const offcanvasInstance = bootstrap.Offcanvas.getInstance(offcanvas);
                const links = offcanvas.querySelectorAll('a.nav-link, a.btn');

                links.forEach(link => {
                    link.addEventListener('click', function() {
                        if (offcanvasInstance) {
                            offcanvasInstance.hide();
                        }
                    });
                });
            });
        </script>