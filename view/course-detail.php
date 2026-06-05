<div class="container py-5">
    <div class="row">
        <div class="col-lg-8">
            <h1 class="fw-bold mb-3"><?= htmlspecialchars($course['title']) ?></h1>
            
            <div class="mb-4">
                <img src="<?= getCourseImage($course) ?>" class="img-fluid rounded" alt="<?= htmlspecialchars($course['title']) ?>">
            </div>
            
            <div class="mb-4">
                <h5>Description</h5>
                <p><?= nl2br(htmlspecialchars($course['description'])) ?></p>
            </div>
            
            <div class="mb-4">
                <h5>Course Content</h5>
                <p><?= count($course['curriculum'] ?? []) ?> lessons</p>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h3 class="text-primary">$<?= number_format($course['price'], 2) ?></h3>
                    
                    <?php if (!$isEnrolled): ?>
                        <form method="POST">
                            <button type="submit" name="enroll" class="btn btn-primary w-100 mt-3">
                                Enroll Now
                            </button>
                        </form>
                    <?php else: ?>
                        <a href="/course-player?course_id=<?= $course['id'] ?>" class="btn btn-success w-100 mt-3">
                            Continue Learning
                        </a>
                    <?php endif; ?>
                    
                    <hr>
                    <p><strong>Category:</strong> <?= htmlspecialchars($course['category']) ?></p>
                    <p><strong>Level:</strong> <?= ucfirst($course['level']) ?></p>
                    <p><strong>Instructor:</strong> <?= htmlspecialchars($course['instructor_name']) ?></p>
                </div>
            </div>
        </div>
    </div>
    
    <?php if (!empty($relatedArray)): ?>
    <div class="mt-5">
        <h4>Related Courses</h4>
        <div class="row">
            <?php foreach ($relatedArray as $rel): ?>
                <div class="col-md-4">
                    <div class="card mb-3">
                        <img src="<?= getCourseImage($rel) ?>" class="card-img-top" style="height: 150px; object-fit: cover;">
                        <div class="card-body">
                            <h6><?= htmlspecialchars($rel['title']) ?></h6>
                            <a href="/course/<?= $rel['id'] ?>" class="btn btn-sm btn-outline-primary">View</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</div>