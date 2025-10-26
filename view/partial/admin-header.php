<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?? 'Admin Panel - CodeMastery' ?></title>
    <link href="/assets/css/boostrap/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/font-awasome/css/all.css">
    <link rel="stylesheet" href="/assets/css/admin.css">
    <style>
        
    </style>
</head>

<body>
    <div class="admin-container">
        <!-- Desktop Sidebar -->
        <nav id="sidebar" class="bg-dark text-white">
            <div class="sidebar-header p-3 border-bottom border-secondary">
                <h4 class="fw-bold mb-0">
                    <i class="fas fa-crown me-2"></i>CodeMastery Admin
                </h4>
            </div>

            <ul class="list-unstyled components p-3">
                <li class="nav-item">
                    <a class="nav-link <?= $current_page === 'admin-dashboard' ? 'active' : '' ?>" href="/admin">
                        <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                    </a>
                </li>

                <!-- User Management -->
                <li class="nav-item">
                    <a class="nav-link dropdown-toggle" href="#userSubmenu" data-bs-toggle="collapse">
                        <i class="fas fa-users me-2"></i>User Management
                    </a>
                    <ul class="collapse list-unstyled <?= in_array($current_page, ['admin-users', 'admin-instructor-applications']) ? 'show' : '' ?>" id="userSubmenu">
                        <li class="nav-item">
                            <a class="nav-link <?= $current_page === 'admin-users' ? 'active' : '' ?>" href="/admin-users">
                                All Users
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $current_page === 'admin-instructor-applications' ? 'active' : '' ?>" href="/admin-instructor-applications">
                                Instructor Applications
                                <?php if (getPendingApplicationsCount() > 0): ?>
                                    <span class="badge bg-danger ms-2"><?= getPendingApplicationsCount() ?></span>
                                <?php endif; ?>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Content Management -->
                <li class="nav-item">
                    <a class="nav-link dropdown-toggle" href="#contentSubmenu" data-bs-toggle="collapse">
                        <i class="fas fa-file-alt me-2"></i>Content Management
                    </a>
                    <ul class="collapse list-unstyled <?= in_array($current_page, ['admin-courses', 'admin-testimonials', 'admin-blog', 'admin-moderation']) ? 'show' : '' ?>" id="contentSubmenu">
                        <li class="nav-item">
                            <a class="nav-link <?= $current_page === 'admin-courses' ? 'active' : '' ?>" href="/admin-courses">
                                Course Moderation
                                <?php if (getPendingCoursesCount() > 0): ?>
                                    <span class="badge bg-warning ms-2"><?= getPendingCoursesCount() ?></span>
                                <?php endif; ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $current_page === 'admin-testimonials' ? 'active' : '' ?>" href="/admin-testimonials">
                                Testimonials
                                <?php if (getPendingTestimonialsCount() > 0): ?>
                                    <span class="badge bg-warning ms-2"><?= getPendingTestimonialsCount() ?></span>
                                <?php endif; ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $current_page === 'admin-blog' ? 'active' : '' ?>" href="/admin-blog">
                                Blog Posts
                                <?php if (count(getBlogPostsByStatus('pending')) > 0): ?>
                                    <span class="badge bg-warning ms-2"><?= count(getBlogPostsByStatus('pending')) ?></span>
                                <?php endif; ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $current_page === 'admin-moderation' ? 'active' : '' ?>" href="/admin-moderation">
                                Content Moderation
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Analytics & Revenue -->
                <li class="nav-item">
                    <a class="nav-link <?= $current_page === 'admin-analytics' ? 'active' : '' ?>" href="/admin-analytics">
                        <i class="fas fa-chart-bar me-2"></i>Platform Analytics
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $current_page === 'admin-revenue' ? 'active' : '' ?>" href="/admin-revenue">
                        <i class="fas fa-money-bill me-2"></i>Revenue Management
                    </a>
                </li>

                <!-- System -->
                <li class="nav-item">
                    <a class="nav-link <?= $current_page === 'admin-settings' ? 'active' : '' ?>" href="/admin-settings">
                        <i class="fas fa-cog me-2"></i>System Settings
                    </a>
                </li>
            </ul>

            <div class="sidebar-footer p-3 border-top border-secondary">
                <div class="d-flex align-items-center mb-3">
                    <img src="<?= $_SESSION['user']['avatar'] ?? '/assets/images/avatars/default.jpg' ?>"
                        class="rounded-circle me-2" width="40" height="40" style="object-fit: cover;">
                    <div class="flex-grow-1">
                        <div class="fw-semibold"><?= htmlspecialchars($_SESSION['user']['name']) ?></div>
                        <small class="text-muted">Administrator</small>
                    </div>
                </div>
                <div class="d-grid gap-2">
                    <a href="/dashboard" class="btn btn-outline-light btn-sm">
                        <i class="fas fa-user me-1"></i>Back to Dashboard
                    </a>
                    <a href="/logout" class="btn btn-outline-light btn-sm">
                        <i class="fas fa-sign-out-alt me-1"></i>Logout
                    </a>
                </div>
            </div>
        </nav>

        <!-- Mobile Offcanvas Sidebar -->
        <div class="offcanvas offcanvas-start d-lg-none" tabindex="-1" id="mobileSidebar">
            <div class="offcanvas-header bg-dark text-white border-bottom border-secondary">
                <h5 class="offcanvas-title fw-bold">
                    <i class="fas fa-crown me-2"></i>Admin Panel
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
            </div>
            <div class="offcanvas-body bg-dark text-white p-0">
                <ul class="list-unstyled components p-3">
                    <li class="nav-item">
                        <a class="nav-link <?= $current_page === 'admin-dashboard' ? 'active' : '' ?>" href="/admin">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </a>
                    </li>

                    <!-- User Management -->
                    <li class="nav-item">
                        <a class="nav-link dropdown-toggle" href="#mobileUserSubmenu" data-bs-toggle="collapse">
                            <i class="fas fa-users me-2"></i>User Management
                        </a>
                        <ul class="collapse list-unstyled <?= in_array($current_page, ['admin-users', 'admin-instructor-applications']) ? 'show' : '' ?>" id="mobileUserSubmenu">
                            <li class="nav-item">
                                <a class="nav-link <?= $current_page === 'admin-users' ? 'active' : '' ?>" href="/admin-users">
                                    All Users
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= $current_page === 'admin-instructor-applications' ? 'active' : '' ?>" href="/admin-instructor-applications">
                                    Instructor Applications
                                    <?php if (getPendingApplicationsCount() > 0): ?>
                                        <span class="badge bg-danger ms-2"><?= getPendingApplicationsCount() ?></span>
                                    <?php endif; ?>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Content Management -->
                    <li class="nav-item">
                        <a class="nav-link dropdown-toggle" href="#mobileContentSubmenu" data-bs-toggle="collapse">
                            <i class="fas fa-file-alt me-2"></i>Content Management
                        </a>
                        <ul class="collapse list-unstyled <?= in_array($current_page, ['admin-courses', 'admin-testimonials', 'admin-blog', 'admin-moderation']) ? 'show' : '' ?>" id="mobileContentSubmenu">
                            <li class="nav-item">
                                <a class="nav-link <?= $current_page === 'admin-courses' ? 'active' : '' ?>" href="/admin-courses">
                                    Course Moderation
                                    <?php if (getPendingCoursesCount() > 0): ?>
                                        <span class="badge bg-warning ms-2"><?= getPendingCoursesCount() ?></span>
                                    <?php endif; ?>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= $current_page === 'admin-testimonials' ? 'active' : '' ?>" href="/admin-testimonials">
                                    Testimonials
                                    <?php if (getPendingTestimonialsCount() > 0): ?>
                                        <span class="badge bg-warning ms-2"><?= getPendingTestimonialsCount() ?></span>
                                    <?php endif; ?>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= $current_page === 'admin-blog' ? 'active' : '' ?>" href="/admin-blog">
                                    Blog Posts
                                    <?php if (count(getBlogPostsByStatus('pending')) > 0): ?>
                                        <span class="badge bg-warning ms-2"><?= count(getBlogPostsByStatus('pending')) ?></span>
                                    <?php endif; ?>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= $current_page === 'admin-moderation' ? 'active' : '' ?>" href="/admin-moderation">
                                    Content Moderation
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Analytics & Revenue -->
                    <li class="nav-item">
                        <a class="nav-link <?= $current_page === 'admin-analytics' ? 'active' : '' ?>" href="/admin-analytics">
                            <i class="fas fa-chart-bar me-2"></i>Platform Analytics
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $current_page === 'admin-revenue' ? 'active' : '' ?>" href="/admin-revenue">
                            <i class="fas fa-money-bill me-2"></i>Revenue Management
                        </a>
                    </li>

                    <!-- System -->
                    <li class="nav-item">
                        <a class="nav-link <?= $current_page === 'admin-settings' ? 'active' : '' ?>" href="/admin-settings">
                            <i class="fas fa-cog me-2"></i>System Settings
                        </a>
                    </li>
                </ul>

                <div class="sidebar-footer p-3 border-top border-secondary mt-auto">
                    <div class="d-flex align-items-center mb-3">
                        <img src="<?= $_SESSION['user']['avatar'] ?? '/assets/images/avatars/default.jpg' ?>"
                            class="rounded-circle me-2" width="40" height="40" style="object-fit: cover;">
                        <div class="flex-grow-1">
                            <div class="fw-semibold"><?= htmlspecialchars($_SESSION['user']['name']) ?></div>
                            <small class="text-muted">Administrator</small>
                        </div>
                    </div>
                    <div class="d-grid gap-2">
                        <a href="/dashboard" class="btn btn-outline-light btn-sm">
                            <i class="fas fa-user me-1"></i>Back to Dashboard
                        </a>
                        <a href="/logout" class="btn btn-outline-light btn-sm">
                            <i class="fas fa-sign-out-alt me-1"></i>Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Page Content -->
        <div id="content">
            <!-- Top Navbar -->
            <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom sticky-top">
                <div class="container-fluid">
                    <!-- Mobile Toggle Button -->
                    <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <!-- Desktop Toggle Button (Hidden on mobile) -->
                    <button class="navbar-toggler d-none d-lg-block" type="button" id="sidebarToggle">
                        <i class="fas fa-bars"></i>
                    </button>

                    <div class="navbar-nav ms-auto">
                        <div class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-bell"></i>
                                <?php if (getTotalPendingCount() > 0): ?>
                                    <span class="badge bg-danger"><?= getTotalPendingCount() ?></span>
                                <?php endif; ?>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <h6 class="dropdown-header">Notifications</h6>
                                <?php if (getPendingApplicationsCount() > 0): ?>
                                    <a class="dropdown-item" href="/admin-instructor-applications">
                                        <i class="fas fa-user-plus text-warning me-2"></i>
                                        <?= getPendingApplicationsCount() ?> pending instructor applications
                                    </a>
                                <?php endif; ?>
                                <?php if (getPendingTestimonialsCount() > 0): ?>
                                    <a class="dropdown-item" href="/admin-testimonials">
                                        <i class="fas fa-comment text-info me-2"></i>
                                        <?= getPendingTestimonialsCount() ?> pending testimonials
                                    </a>
                                <?php endif; ?>
                                <?php if (getPendingCoursesCount() > 0): ?>
                                    <a class="dropdown-item" href="/admin-courses">
                                        <i class="fas fa-book text-success me-2"></i>
                                        <?= getPendingCoursesCount() ?> pending courses
                                    </a>
                                <?php endif; ?>
                                <?php if (count(getBlogPostsByStatus('pending')) > 0): ?>
                                    <a class="dropdown-item" href="/admin-blog">
                                        <i class="fas fa-blog text-primary me-2"></i>
                                        <?= count(getBlogPostsByStatus('pending')) ?> pending blog posts
                                    </a>
                                <?php endif; ?>
                                <?php if (getTotalPendingCount() === 0): ?>
                                    <span class="dropdown-item text-muted">No pending items</span>
                                <?php endif; ?>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item text-center small" href="/admin">View All</a>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Main Content -->
            <div class="container-fluid py-4">

    <script>
    // Toggle sidebar on desktop
    document.addEventListener('DOMContentLoaded', function() {
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');
        const content = document.getElementById('content');

        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('active');
                content.classList.toggle('active');
            });
        }

        // Auto-close mobile offcanvas when clicking links
        const mobileOffcanvas = document.getElementById('mobileSidebar');
        if (mobileOffcanvas) {
            const links = mobileOffcanvas.querySelectorAll('a.nav-link');
            links.forEach(link => {
                link.addEventListener('click', function() {
                    const offcanvasInstance = bootstrap.Offcanvas.getInstance(mobileOffcanvas);
                    if (offcanvasInstance) {
                        offcanvasInstance.hide();
                    }
                });
            });
        }

        // Auto-dismiss alerts after 5 seconds
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
    </script>