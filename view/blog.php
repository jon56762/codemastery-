<?php
// Get blog posts and platform stats
$blog_posts = getBlogPosts();
$platformStats = getPlatformStats();
?>

<div class="container py-5">
    <!-- Header -->
    <div class="row mb-5">
        <div class="col-12 text-center">
            <h1 class="fw-bold mb-3">CodeMastery Blog</h1>
            <p class="lead text-muted">Tips, tutorials, and insights from our community of <?= number_format($platformStats['total_instructors']) ?>+ instructors</p>
        </div>
    </div>

    <div class="row">
        <!-- Blog Posts -->
        <div class="col-lg-8">
            <?php if (empty($blog_posts)): ?>
                <div class="text-center py-5">
                    <i class="fas fa-newspaper fa-3x text-muted mb-3"></i>
                    <h4 class="fw-bold">No blog posts yet</h4>
                    <p class="text-muted">Check back soon for updates and articles.</p>
                </div>
            <?php else: ?>
                <?php foreach ($blog_posts as $post): ?>
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="row g-0">
                            <div class="col-md-4">
                                <img src="<?= htmlspecialchars($post['image']) ?>" 
                                     class="img-fluid rounded-start h-100" 
                                     alt="<?= htmlspecialchars($post['title']) ?>"
                                     style="object-fit: cover;">
                            </div>
                            <div class="col-md-8">
                                <div class="card-body p-4">
                                    <span class="badge bg-light text-dark mb-2"><?= htmlspecialchars($post['category']) ?></span>
                                    <h3 class="card-title fw-bold">
                                        <a href="/blog/<?= $post['id'] ?>" class="text-dark text-decoration-none">
                                            <?= htmlspecialchars($post['title']) ?>
                                        </a>
                                    </h3>
                                    <p class="card-text text-muted"><?= htmlspecialchars($post['excerpt']) ?></p>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <small class="text-muted">
                                                <i class="fas fa-user me-1"></i><?= htmlspecialchars($post['author']) ?>
                                            </small>
                                            <small class="text-muted ms-3">
                                                <i class="fas fa-calendar me-1"></i>
                                                <?= date('F j, Y', strtotime($post['published_at'])) ?>
                                            </small>
                                        </div>
                                        <a href="/blog/<?= $post['id'] ?>" class="btn btn-outline-dark btn-sm">
                                            Read More <i class="fas fa-arrow-right ms-1"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- About Blog -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">About Our Blog</h5>
                    <p class="text-muted">Learn from industry experts, get coding tips, and stay updated with the latest trends in technology and education.</p>
                    <div class="d-flex justify-content-between text-center mt-4">
                        <div>
                            <div class="h6 fw-bold mb-0"><?= count($blog_posts) ?></div>
                            <small class="text-muted">Articles</small>
                        </div>
                        <div>
                            <div class="h6 fw-bold mb-0"><?= number_format($platformStats['total_instructors']) ?></div>
                            <small class="text-muted">Expert Writers</small>
                        </div>
                        <div>
                            <div class="h6 fw-bold mb-0"><?= number_format($platformStats['total_students']) ?></div>
                            <small class="text-muted">Readers</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Categories -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">Categories</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <a href="#" class="text-decoration-none text-muted">
                                <i class="fas fa-code me-2"></i>Web Development
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="#" class="text-decoration-none text-muted">
                                <i class="fas fa-chart-bar me-2"></i>Data Science
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="#" class="text-decoration-none text-muted">
                                <i class="fas fa-mobile-alt me-2"></i>Mobile Development
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="#" class="text-decoration-none text-muted">
                                <i class="fas fa-robot me-2"></i>Machine Learning
                            </a>
                        </li>
                        <li>
                            <a href="#" class="text-decoration-none text-muted">
                                <i class="fas fa-graduation-cap me-2"></i>Learning Tips
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Newsletter -->
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">Stay Updated</h5>
                    <p class="text-muted small mb-3">Get the latest articles and coding tips delivered to your inbox.</p>
                    <form method="POST" action="">
                        <div class="mb-3">
                            <input type="email" class="form-control" placeholder="Enter your email" required>
                        </div>
                        <button type="submit" name="newsletter_signup" class="btn btn-dark w-100">
                            <i class="fas fa-envelope me-2"></i>Subscribe
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>