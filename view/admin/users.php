<div class="d-flex justify-content-between align-items-center mb-4 row">
    <div class="col-12">
        <h1 class="h3 fw-bold text-dark mb-1">User Management</h1>
        <p class="text-muted mb-0">Manage all users on the platform</p>
    </div>
    <div class="d-flex mt-2">
        <!-- Search Form -->
        <form method="GET" class="d-flex">
            <div class="input-group me-2">
                <input type="text" class="form-control" name="search" placeholder="Search users..." 
                       value="<?= htmlspecialchars($search) ?>">
                <button class="btn btn-outline-dark" type="submit">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </form>
        
        <!-- <div class="dropdown">
            <button class="btn btn-outline-dark dropdown-toggle" type="button" data-bs-toggle="dropdown">
                <i class="fas fa-filter me-2"></i>Filter
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="?">All Users</a></li>
                <li><a class="dropdown-item" href="?role=student">Students</a></li>
                <li><a class="dropdown-item" href="?role=instructor">Instructors</a></li>
                <li><a class="dropdown-item" href="?role=admin">Admins</a></li>
                <li><a class="dropdown-item" href="?status=suspended">Suspended</a></li>
            </ul>
        </div> -->
    </div>
</div>

<!-- Success/Error Messages -->
<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= $_SESSION['success'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= $_SESSION['error'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-muted mb-2">Total Users</h6>
                        <h3 class="fw-bold text-dark"><?= count($users) ?></h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-users fa-2x text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-muted mb-2">Students</h6>
                        <h3 class="fw-bold text-success"><?= count($students) ?></h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-user-graduate fa-2x text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-muted mb-2">Instructors</h6>
                        <h3 class="fw-bold text-info"><?= count($instructors) ?></h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-chalkboard-teacher fa-2x text-info"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-muted mb-2">Suspended</h6>
                        <h3 class="fw-bold text-danger"><?= count($suspended_users) ?></h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-user-slash fa-2x text-danger"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Users Table -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
        <h5 class="fw-bold mb-0">All Users</h5>
        <span class="text-muted"><?= count($users) ?> users found</span>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Joined</th>
                        <th>Courses</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <?php if ($user['id'] == $_SESSION['user']['id']) continue; // Skip current admin ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="<?= htmlspecialchars($user['avatar'] ?? '/assets/images/avatars/default.jpg') ?>" 
                                         alt="<?= htmlspecialchars($user['name']) ?>" 
                                         class="rounded-circle me-3" width="40" height="40" style="object-fit: cover;">
                                    <div>
                                        <div class="fw-semibold"><?= htmlspecialchars($user['name']) ?></div>
                                        <small class="text-muted">ID: <?= $user['id'] ?></small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <small><?= htmlspecialchars($user['email']) ?></small>
                            </td>
                            <td>
                                <span class="badge bg-<?= $user['role'] === 'admin' ? 'danger' : ($user['role'] === 'instructor' ? 'info' : 'success') ?>">
                                    <?= ucfirst($user['role']) ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-<?= $user['status'] === 'active' ? 'success' : 'danger' ?>">
                                    <?= ucfirst($user['status']) ?>
                                </span>
                            </td>
                            <td>
                                <small class="text-muted">
                                    <?= date('M j, Y', strtotime($user['created_at'])) ?>
                                </small>
                            </td>
                            <td>
                                <?php if ($user['role'] === 'instructor'): ?>
                                    <?php
                                    $instructor_courses = getCoursesByInstructor($user['id']);
                                    echo count($instructor_courses);
                                    ?>
                                <?php else: ?>
                                    <?php
                                    $student_enrollments = getStudentEnrollments($user['id']);
                                    echo count($student_enrollments);
                                    ?>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-dark dropdown-toggle" type="button" 
                                            data-bs-toggle="dropdown">
                                        Manage
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item" href="#" data-bs-toggle="modal" 
                                               data-bs-target="#roleModal<?= $user['id'] ?>">
                                                <i class="fas fa-user-cog me-2"></i>Change Role
                                            </a>
                                        </li>
                                        <?php if ($user['status'] === 'active'): ?>
                                            <li>
                                                <form method="POST" class="d-inline">
                                                    <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                                    <button type="submit" name="suspend_user" class="dropdown-item text-warning"
                                                            onclick="return confirm('Are you sure you want to suspend this user?')">
                                                        <i class="fas fa-user-slash me-2"></i>Suspend
                                                    </button>
                                                </form>
                                            </li>
                                        <?php else: ?>
                                            <li>
                                                <form method="POST" class="d-inline">
                                                    <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                                    <button type="submit" name="activate_user" class="dropdown-item text-success">
                                                        <i class="fas fa-user-check me-2"></i>Activate
                                                    </button>
                                                </form>
                                            </li>
                                        <?php endif; ?>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form method="POST" class="d-inline">
                                                <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                                <button type="submit" name="delete_user" class="dropdown-item text-danger"
                                                        onclick="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                                                    <i class="fas fa-trash me-2"></i>Delete User
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>

                                <!-- Role Change Modal -->
                                <div class="modal fade" id="roleModal<?= $user['id'] ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Change User Role</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form method="POST">
                                                <div class="modal-body">
                                                    <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                                    <div class="mb-3">
                                                        <label for="new_role" class="form-label">Select New Role</label>
                                                        <select class="form-select" id="new_role" name="new_role" required>
                                                            <option value="student" <?= $user['role'] === 'student' ? 'selected' : '' ?>>Student</option>
                                                            <option value="instructor" <?= $user['role'] === 'instructor' ? 'selected' : '' ?>>Instructor</option>
                                                            <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Administrator</option>
                                                        </select>
                                                    </div>
                                                    <div class="alert alert-info">
                                                        <small>
                                                            <i class="fas fa-info-circle me-2"></i>
                                                            Changing user role will affect their platform permissions and access.
                                                        </small>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" name="update_role" class="btn btn-primary">Update Role</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <?php if (empty($users)): ?>
            <div class="text-center py-5">
                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                <h5 class="fw-bold">No users found</h5>
                <p class="text-muted"><?= $search ? 'Try a different search term' : 'No users registered yet' ?></p>
            </div>
        <?php endif; ?>
    </div>
</div>