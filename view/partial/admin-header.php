<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?? 'Admin Panel - CodeMastery' ?></title>
    <link href="/assets/css/boostrap/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/font-awasome/css/all.css">
    <link rel="stylesheet" href="/assets/css/admin.css">
</head>
<body>
    <!-- Top Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top admin-navbar">
        <div class="container-fluid">
            <button class="btn btn-outline-secondary me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#adminOffcanvas">
                <i class="fas fa-bars"></i>
            </button>
            <a class="navbar-brand fw-bold" href="/admin">
                <i class="fas fa-crown me-2"></i>CodeMastery <small class="text-muted">Admin</small>
            </a>

            <div class="ms-auto d-flex align-items-center gap-3">
                <!-- Notification Bell -->
                <?php
                $pendingApps = count(array_filter(InstructorApplication::getAll(), fn($a) => $a->status === 'pending'));
                $pendingTestimonials = count(array_filter(Testimonial::getAll(), fn($t) => $t->status === 'pending'));
                $pendingCourses = count(array_filter(Course::getAll(), fn($c) => $c->status === 'pending'));
                $pendingBlogs = count(BlogPost::getByStatus('pending'));
                $totalPending = $pendingApps + $pendingTestimonials + $pendingCourses + $pendingBlogs;
                ?>
                <div class="dropdown">
                    <a class="text-dark position-relative" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-bell fs-5"></i>
                        <?php if ($totalPending > 0): ?>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                <?= $totalPending ?>
                            </span>
                        <?php endif; ?>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end">
                        <h6 class="dropdown-header">Pending Items</h6>
                        <?php if ($pendingApps > 0): ?>
                            <a class="dropdown-item" href="/admin-instructor-applications">
                                Instructor Applications: <?= $pendingApps ?>
                            </a>
                        <?php endif; ?>
                        <?php if ($pendingTestimonials > 0): ?>
                            <a class="dropdown-item" href="/admin-testimonials">
                                Testimonials: <?= $pendingTestimonials ?>
                            </a>
                        <?php endif; ?>
                        <?php if ($pendingCourses > 0): ?>
                            <a class="dropdown-item" href="/admin-courses">
                                Courses: <?= $pendingCourses ?>
                            </a>
                        <?php endif; ?>
                        <?php if ($pendingBlogs > 0): ?>
                            <a class="dropdown-item" href="/admin-blog">
                                Blog Posts: <?= $pendingBlogs ?>
                            </a>
                        <?php endif; ?>
                        <?php if ($totalPending === 0): ?>
                            <span class="dropdown-item text-muted">No pending items</span>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- User dropdown -->
                <div class="dropdown">
                    <a class="d-flex align-items-center text-decoration-none text-dark" href="#" role="button" data-bs-toggle="dropdown">
                        <img src="<?= $_SESSION['user']['avatar'] ?? '/assets/images/avatars/default.png' ?>" 
                             alt="Admin" class="rounded-circle me-2" width="32" height="32" style="object-fit: cover;">
                        <span><?= htmlspecialchars($_SESSION['user']['name']) ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="/dashboard"><i class="fas fa-user me-2"></i>View Site</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="/logout"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- Offcanvas Sidebar -->
    <div class="offcanvas offcanvas-start admin-offcanvas" tabindex="-1" id="adminOffcanvas">
        <div class="offcanvas-header border-bottom border-secondary">
            <h5 class="offcanvas-title fw-bold"><i class="fas fa-crown me-2"></i>Admin Panel</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
            <!-- Admin info -->
            <div class="d-flex align-items-center mb-4 p-3 bg-dark rounded">
                <img src="<?= $_SESSION['user']['avatar'] ?? '/assets/images/avatars/default.png' ?>" 
                     class="rounded-circle me-3" width="48" height="48" style="object-fit: cover;">
                <div>
                    <h6 class="fw-bold mb-0 text-white"><?= htmlspecialchars($_SESSION['user']['name']) ?></h6>
                    <small class="text-muted">Administrator</small>
                </div>
            </div>

            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page ?? '') === 'admin-dashboard' ? 'active' : '' ?>" href="/admin">
                        <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                    </a>
                </li>

                <!-- User Management Accordion -->
                <li class="nav-item">
                    <div class="accordion" id="adminAccordion">
                        <div class="accordion-item border-0 bg-transparent">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#userMenu">
                                    <i class="fas fa-users me-2"></i>User Management
                                </button>
                            </h2>
                            <div id="userMenu" class="accordion-collapse collapse <?= in_array($current_page ?? '', ['admin-users', 'admin-instructor-applications']) ? 'show' : '' ?>">
                                <div class="accordion-body p-0">
                                    <a class="nav-link <?= ($current_page ?? '') === 'admin-users' ? 'active' : '' ?>" href="/admin-users">All Users</a>
                                    <a class="nav-link <?= ($current_page ?? '') === 'admin-instructor-applications' ? 'active' : '' ?>" href="/admin-instructor-applications">
                                        Instructor Applications
                                        <?php if ($pendingApps > 0): ?>
                                            <span class="badge bg-danger ms-1"><?= $pendingApps ?></span>
                                        <?php endif; ?>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Content Management Accordion -->
                        <div class="accordion-item border-0 bg-transparent">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#contentMenu">
                                    <i class="fas fa-file-alt me-2"></i>Content Management
                                </button>
                            </h2>
                            <div id="contentMenu" class="accordion-collapse collapse <?= in_array($current_page ?? '', ['admin-courses', 'admin-testimonials', 'admin-blog']) ? 'show' : '' ?>">
                                <div class="accordion-body p-0">
                                    <a class="nav-link <?= ($current_page ?? '') === 'admin-courses' ? 'active' : '' ?>" href="/admin-courses">
                                        Course Moderation
                                        <?php if ($pendingCourses > 0): ?>
                                            <span class="badge bg-danger ms-1"><?= $pendingCourses ?></span>
                                        <?php endif; ?>
                                    </a>
                                    <a class="nav-link <?= ($current_page ?? '') === 'admin-testimonials' ? 'active' : '' ?>" href="/admin-testimonials">
                                        Testimonials
                                        <?php if ($pendingTestimonials > 0): ?>
                                            <span class="badge bg-danger ms-1"><?= $pendingTestimonials ?></span>
                                        <?php endif; ?>
                                    </a>
                                    <a class="nav-link <?= ($current_page ?? '') === 'admin-blog' ? 'active' : '' ?>" href="/admin-blog">
                                        Blog Posts
                                        <?php if ($pendingBlogs > 0): ?>
                                            <span class="badge bg-danger ms-1"><?= $pendingBlogs ?></span>
                                        <?php endif; ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?= ($current_page ?? '') === 'admin-analytics' ? 'active' : '' ?>" href="/admin-analytics">
                        <i class="fas fa-chart-bar me-2"></i>Platform Analytics
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page ?? '') === 'admin-revenue' ? 'active' : '' ?>" href="/admin-revenue">
                        <i class="fas fa-money-bill me-2"></i>Revenue Management
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page ?? '') === 'admin-settings' ? 'active' : '' ?>" href="/admin-settings">
                        <i class="fas fa-cog me-2"></i>System Settings
                    </a>
                </li>
            </ul>

            <div class="mt-4">
                <a href="/dashboard" class="btn btn-outline-dark w-100 mb-2"><i class="fas fa-user me-1"></i>Back to Site</a>
                <a href="/logout" class="btn btn-outline-danger w-100"><i class="fas fa-sign-out-alt me-1"></i>Logout</a>
            </div>
        </div>
    </div>

    <!-- Main content area -->
    <div class="container-fluid" style="padding-top: 70px;">
        <script src="/assets/js/bootstrap.bundle.js"></script>