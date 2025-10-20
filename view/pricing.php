<?php
$pricing_plans = [
    'student' => [
        'name' => 'Student',
        'price' => 'Free',
        'period' => 'forever',
        'features' => [
            'Access to free courses',
            'Community support',
            'Basic learning resources',
            'Progress tracking',
            'Certificate of completion (for free courses)'
        ],
        'button_text' => 'Get Started Free',
        'button_class' => 'btn-outline-dark'
    ],
    'pro' => [
        'name' => 'Pro Learner',
        'price' => '$29',
        'period' => 'per month',
        'popular' => true,
        'features' => [
            'All free course features',
            'Access to all premium courses',
            'Downloadable resources',
            'Project files & code',
            'Priority support',
            'Professional certificates',
            'Career guidance'
        ],
        'button_text' => 'Start Learning',
        'button_class' => 'btn-dark'
    ],
    'team' => [
        'name' => 'Team',
        'price' => '$99',
        'period' => 'per month',
        'features' => [
            'All Pro features',
            'Up to 5 team members',
            'Progress analytics',
            'Team management dashboard',
            'Dedicated account manager',
            'Custom learning paths',
            'SLA support'
        ],
        'button_text' => 'Contact Sales',
        'button_class' => 'btn-outline-dark'
    ]
];
?>

<div class="container py-5">
    <!-- Header -->
    <div class="row mb-5">
        <div class="col-12 text-center">
            <h1 class="fw-bold mb-3">Simple, Transparent Pricing</h1>
            <p class="lead text-muted">Join <?= number_format($platformStats['total_students']) ?>+ students learning on CodeMastery</p>
        </div>
    </div>

    <!-- Pricing Cards -->
    <div class="row justify-content-center">
        <?php foreach ($pricing_plans as $key => $plan): ?>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100 border-0 shadow-sm <?= $plan['popular'] ?? false ? 'border-dark' : '' ?>">
                    <?php if ($plan['popular'] ?? false): ?>
                        <div class="card-header bg-dark text-white text-center py-3">
                            <span class="fw-bold">MOST POPULAR</span>
                        </div>
                    <?php endif; ?>
                    
                    <div class="card-body p-4 d-flex flex-column">
                        <div class="text-center mb-4">
                            <h3 class="fw-bold"><?= $plan['name'] ?></h3>
                            <div class="my-3">
                                <span class="h1 fw-bold"><?= $plan['price'] ?></span>
                                <?php if ($plan['period'] !== 'forever'): ?>
                                    <span class="text-muted">/<?= $plan['period'] ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <ul class="list-unstyled mb-4 flex-grow-1">
                            <?php foreach ($plan['features'] as $feature): ?>
                                <li class="mb-3">
                                    <i class="fas fa-check text-success me-2"></i>
                                    <?= $feature ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        
                        <div class="text-center mt-auto">
                            <a href="/signup" class="btn <?= $plan['button_class'] ?> btn-lg w-100">
                                <?= $plan['button_text'] ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Platform Stats -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="bg-light rounded p-5 text-center">
                <h3 class="fw-bold mb-4">Join Our Growing Community</h3>
                <div class="row">
                    <div class="col-md-3 col-6 mb-3">
                        <div class="h2 fw-bold text-dark"><?= number_format($platformStats['total_students']) ?>+</div>
                        <div class="text-muted">Happy Students</div>
                    </div>
                    <div class="col-md-3 col-6 mb-3">
                        <div class="h2 fw-bold text-dark"><?= number_format($platformStats['total_courses']) ?>+</div>
                        <div class="text-muted">Quality Courses</div>
                    </div>
                    <div class="col-md-3 col-6 mb-3">
                        <div class="h2 fw-bold text-dark"><?= number_format($platformStats['total_instructors']) ?>+</div>
                        <div class="text-muted">Expert Instructors</div>
                    </div>
                    <div class="col-md-3 col-6 mb-3">
                        <div class="h2 fw-bold text-dark"><?= number_format($platformStats['total_enrollments']) ?>+</div>
                        <div class="text-muted">Course Enrollments</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>