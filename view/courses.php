<link rel="stylesheet" href="/assets/css/courses.css">
<div class="container-fluid py-4">
    <div class="row">
        <!-- Sidebar Filters -->
        <div class="col-lg-3 col-md-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title fw-bold mb-4">Filters</h5>
                    
                    <!-- Search -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Search</label>
                        <form method="GET" class="d-flex">
                            <input type="text" name="search" class="form-control" placeholder="Search courses..." 
                                   value="<?= htmlspecialchars($search) ?>">
                            <button type="submit" class="btn btn-dark ms-2">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                    </div>

                    <!-- Category Filter -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Category</label>
                        <select name="category" class="form-select" onchange="this.form.submit()" form="filter-form">
                            <option value="">All Categories</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= htmlspecialchars($cat) ?>" 
                                    <?= $category === $cat ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cat) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Level Filter -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Level</label>
                        <select name="level" class="form-select" onchange="this.form.submit()" form="filter-form">
                            <option value="">All Levels</option>
                            <?php foreach ($levels as $lvl): ?>
                                <option value="<?= $lvl ?>" <?= $level === $lvl ? 'selected' : '' ?>>
                                    <?= ucfirst($lvl) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Price Filter -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Price</label>
                        <select name="price" class="form-select" onchange="this.form.submit()" form="filter-form">
                            <option value="">All Prices</option>
                            <option value="free" <?= $price === 'free' ? 'selected' : '' ?>>Free</option>
                            <option value="paid" <?= $price === 'paid' ? 'selected' : '' ?>>Paid</option>
                        </select>
                    </div>

                    <!-- Sort Options -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Sort By</label>
                        <select name="sort" class="form-select" onchange="this.form.submit()" form="filter-form">
                            <option value="newest" <?= $sort === 'newest' ? 'selected' : '' ?>>Newest</option>
                            <option value="popular" <?= $sort === 'popular' ? 'selected' : '' ?>>Most Popular</option>
                            <option value="rating" <?= $sort === 'rating' ? 'selected' : '' ?>>Highest Rated</option>
                            <option value="price_low" <?= $sort === 'price_low' ? 'selected' : '' ?>>Price: Low to High</option>
                            <option value="price_high" <?= $sort === 'price_high' ? 'selected' : '' ?>>Price: High to Low</option>
                        </select>
                    </div>

                    <!-- Clear Filters -->
                    <a href="/courses" class="btn btn-outline-dark w-100">Clear All Filters</a>

                    <!-- Hidden form for filter submissions -->
                    <form id="filter-form" method="GET" class="d-none">
                        <input type="text" name="search" value="<?= htmlspecialchars($search) ?>">
                    </form>
                </div>
            </div>

            <!-- Featured Courses Sidebar -->
            <?php if (!empty($featured_courses)): ?>
                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-body">
                        <h6 class="card-title fw-bold mb-3">Featured Courses</h6>
                        <?php foreach ($featured_courses as $featured): ?>
                            <div class="mb-3 pb-3 border-bottom">
                                <div class="d-flex align-items-start">
                                    <img src="<?= htmlspecialchars($featured['thumbnail']) ?>" 
                                         alt="<?= htmlspecialchars($featured['title']) ?>" 
                                         class="rounded me-3" width="60" height="60" style="object-fit: cover;">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 fw-semibold small">
                                            <a href="/course/<?= $featured['id'] ?>" class="text-dark text-decoration-none">
                                                <?= htmlspecialchars($featured['title']) ?>
                                            </a>
                                        </h6>
                                        <div class="text-muted small"><?= htmlspecialchars($featured['instructor_name']) ?></div>
                                        <div class="text-dark fw-bold small"><?= $featured['price'] > 0 ? '$' . $featured['price'] : 'Free' ?></div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Main Content -->
        <div class="col-lg-9 col-md-8">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 fw-bold mb-1">All Courses</h1>
                    <p class="text-muted mb-0">
                        <?= count($filtered_courses) ?> course<?= count($filtered_courses) !== 1 ? 's' : '' ?> found
                        <?php if ($search): ?>
                            for "<?= htmlspecialchars($search) ?>"
                        <?php endif; ?>
                    </p>
                </div>
                <div class="d-none d-md-block">
                    <div class="btn-group">
                        <button type="button" class="btn btn-outline-dark active" onclick="setView('grid')">
                            <i class="fas fa-th"></i>
                        </button>
                        <button type="button" class="btn btn-outline-dark" onclick="setView('list')">
                            <i class="fas fa-list"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Courses Grid -->
            <?php if (empty($filtered_courses)): ?>
                <div class="text-center py-5">
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <h4 class="fw-bold">No courses found</h4>
                    <p class="text-muted mb-4">Try adjusting your search or filters</p>
                    <a href="/courses" class="btn btn-dark">Clear Filters</a>
                </div>
            <?php else: ?>
                <div class="row" id="courses-grid">
                    <?php foreach ($filtered_courses as $course): ?>
                        <div class="col-xl-4 col-lg-6 col-md-6 mb-4">
                            <div class="card course-card h-100 border-0 shadow-sm">
                                <div class="position-relative">
                                    <img src="<?= htmlspecialchars($course['thumbnail']) ?>" 
                                         class="card-img-top" 
                                         alt="<?= htmlspecialchars($course['title']) ?>"
                                         style="height: 200px; object-fit: cover;">
                                    <?php if ($course['featured']): ?>
                                        <span class="position-absolute top-0 start-0 m-2 badge bg-dark">
                                            <i class="fas fa-star me-1"></i>Featured
                                        </span>
                                    <?php endif; ?>
                                    <div class="position-absolute top-0 end-0 m-2">
                                        <span class="badge bg-<?= getCourseLevelBadge($course['level']) ?>">
                                            <?= ucfirst($course['level']) ?>
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="card-body d-flex flex-column">
                                    <div class="mb-2">
                                        <span class="badge bg-light text-dark border">
                                            <?= htmlspecialchars($course['category']) ?>
                                        </span>
                                    </div>
                                    
                                    <h5 class="card-title fw-bold">
                                        <a href="/course/<?= $course['id'] ?>" class="text-dark text-decoration-none">
                                            <?= htmlspecialchars($course['title']) ?>
                                        </a>
                                    </h5>
                                    
                                    <p class="card-text text-muted small flex-grow-1">
                                        <?= htmlspecialchars($course['short_description'] ?? substr($course['description'], 0, 100) . '...') ?>
                                    </p>
                                    
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="text-warning small">
                                            <?= str_repeat('<i class="fas fa-star"></i>', floor($course['rating'] ?? 4)) ?>
                                            <?= ($course['rating'] ?? 4) - floor($course['rating'] ?? 4) >= 0.5 ? '<i class="fas fa-star-half-alt"></i>' : '' ?>
                                            <span class="text-muted ms-1">(<?= $course['rating'] ?? '4.0' ?>)</span>
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between align-items-center mt-auto">
                                        <div class="d-flex align-items-center">
                                            <img src="https://via.placeholder.com/30x30/007bff/ffffff?text=<?= substr($course['instructor_name'], 0, 1) ?>" 
                                                 alt="<?= htmlspecialchars($course['instructor_name']) ?>" 
                                                 class="rounded-circle me-2" width="30" height="30">
                                            <small class="text-muted"><?= htmlspecialchars($course['instructor_name']) ?></small>
                                        </div>
                                        <div class="text-end">
                                            <div class="fw-bold text-dark h5 mb-0">
                                                <?= $course['price'] > 0 ? '$' . $course['price'] : 'Free' ?>
                                            </div>
                                            <small class="text-muted">
                                                <?= $course['lessons'] ?> lessons • <?= formatDuration($course['duration']) ?>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="card-footer bg-transparent border-0 pt-0">
                                    <a href="/course/<?= $course['id'] ?>" class="btn btn-dark w-100">
                                        <i class="fas fa-eye me-2"></i>View Course
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function setView(view) {
    const grid = document.getElementById('courses-grid');
    const buttons = document.querySelectorAll('.btn-group .btn');
    
    buttons.forEach(btn => btn.classList.remove('active'));
    event.target.classList.add('active');
    
    if (view === 'list') {
        grid.classList.remove('row-cols-1', 'row-cols-md-2', 'row-cols-lg-3');
        grid.classList.add('row-cols-1');
        document.querySelectorAll('.course-card').forEach(card => {
            card.classList.add('flex-row');
            card.querySelector('.card-img-top').style.width = '200px';
            card.querySelector('.card-img-top').style.height = 'auto';
        });
    } else {
        grid.classList.remove('row-cols-1');
        grid.classList.add('row-cols-1', 'row-cols-md-2', 'row-cols-lg-3');
        document.querySelectorAll('.course-card').forEach(card => {
            card.classList.remove('flex-row');
            card.querySelector('.card-img-top').style.width = '100%';
            card.querySelector('.card-img-top').style.height = '200px';
        });
    }
}

// Auto-submit search when typing stops
let searchTimeout;
document.querySelector('input[name="search"]').addEventListener('input', function() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        document.querySelector('form').submit();
    }, 500);
});
</script>