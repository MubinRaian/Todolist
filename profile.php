<?php
// views/user/profile.php
// User profile page

$pageTitle = 'Profile';
// Removed header include to prevent duplication
?>

<div class="row mb-4">
    <div class="col">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-user-circle"></i> My Profile
        </h1>
    </div>
</div>

<div class="row">
    <!-- Profile Information -->
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Profile Information</h6>
            </div>
            <div class="card-body">
                <form action="profile.php?action=update_profile" method="post">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username"
                            value="<?php echo $user['username']; ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email"
                            value="<?php echo $user['email']; ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Joined</label>
                        <p class="form-control-static"><?php echo formatDate($user['created_at'], 'F d, Y'); ?></p>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Profile
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Change Password -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Change Password</h6>
            </div>
            <div class="card-body">
                <form action="profile.php?action=change_password" method="post" id="changePasswordForm">
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Current Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="current_password" name="current_password"
                                required>
                            <button class="btn btn-outline-secondary toggle-password" type="button"
                                data-target="current_password">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="new_password" class="form-label">New Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="new_password" name="new_password" required>
                            <button class="btn btn-outline-secondary toggle-password" type="button"
                                data-target="new_password">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="form-text">Password must be at least 6 characters long.</div>
                    </div>

                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirm New Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password"
                                required>
                            <button class="btn btn-outline-secondary toggle-password" type="button"
                                data-target="confirm_password">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-key"></i> Change Password
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Theme Preferences -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Theme Preferences</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Current Theme</label>
                    <div class="d-flex align-items-center">
                        <div class="bg-<?php echo getUserTheme() === 'light' ? 'light' : 'dark'; ?> border p-3 me-3"
                            style="width: 100px;"></div>
                        <span><?php echo getUserTheme() === 'light' ? 'Light' : 'Dark'; ?> Mode</span>
                    </div>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="profile.php?action=toggle_theme" class="btn btn-primary">
                        <?php if (getUserTheme() === 'light'): ?>
                            <i class="fas fa-moon"></i> Switch to Dark Mode
                        <?php else: ?>
                            <i class="fas fa-sun"></i> Switch to Light Mode
                        <?php endif; ?>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Image and Stats -->
    <div class="col-lg-4">
        <!-- Profile Image -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Profile Image</h6>
            </div>
            <div class="card-body text-center">
                <div class="profile-image-container mb-3">
                    <?php if (!empty($user['profile_image'])): ?>
                        <img src="<?php echo $user['profile_image']; ?>" alt="Profile Image" class="profile-image">
                    <?php else: ?>
                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center h-100">
                            <span
                                class="text-white display-4"><?php echo strtoupper(substr($user['username'], 0, 1)); ?></span>
                        </div>
                    <?php endif; ?>
                </div>

                <form action="profile.php?action=upload_profile_image" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <input class="form-control" type="file" id="profile_image" name="profile_image"
                            accept="image/jpeg, image/png, image/gif">
                        <div class="form-text">Max file size: 2MB. Supported formats: JPG, PNG, GIF.</div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-upload"></i> Upload Image
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Task Statistics -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Task Statistics</h6>
                <a href="profile.php?action=statistics" class="btn btn-sm btn-primary">
                    <i class="fas fa-chart-bar"></i> Detailed Stats
                </a>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <h4 class="small font-weight-bold">Task Completion <span
                            class="float-end"><?php echo $taskStats['completion_rate']; ?>%</span></h4>
                    <div class="progress mb-4">
                        <div class="progress-bar bg-success" role="progressbar"
                            style="width: <?php echo $taskStats['completion_rate']; ?>%"
                            aria-valuenow="<?php echo $taskStats['completion_rate']; ?>" aria-valuemin="0"
                            aria-valuemax="100"></div>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="row text-center">
                        <div class="col-6 border-end">
                            <div class="h4 mb-0"><?php echo $taskStats['total']; ?></div>
                            <div class="small text-muted">Total Tasks</div>
                        </div>
                        <div class="col-6">
                            <div class="h4 mb-0"><?php echo $taskStats['completed']; ?></div>
                            <div class="small text-muted">Completed</div>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="row text-center">
                        <div class="col-6 border-end">
                            <div class="h4 mb-0"><?php echo $taskStats['pending']; ?></div>
                            <div class="small text-muted">Pending</div>
                        </div>
                        <div class="col-6">
                            <div class="h4 mb-0"><?php echo $taskStats['overdue']; ?></div>
                            <div class="small text-muted">Overdue</div>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <h6 class="font-weight-bold">Tasks by Priority</h6>
                    <div class="row">
                        <div class="col-4 text-center">
                            <span class="badge bg-danger d-block mb-1">High</span>
                            <div class="h5 mb-0"><?php echo $taskStats['priority']['high']; ?></div>
                        </div>
                        <div class="col-4 text-center">
                            <span class="badge bg-warning d-block mb-1">Medium</span>
                            <div class="h5 mb-0"><?php echo $taskStats['priority']['medium']; ?></div>
                        </div>
                        <div class="col-4 text-center">
                            <span class="badge bg-success d-block mb-1">Low</span>
                            <div class="h5 mb-0"><?php echo $taskStats['priority']['low']; ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Toggle password visibility
    document.querySelectorAll('.toggle-password').forEach(function (button) {
        button.addEventListener('click', function () {
            const targetId = this.getAttribute('data-target');
            const passwordInput = document.getElementById(targetId);
            const icon = this.querySelector('i');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });

    // Form validation
    document.getElementById('changePasswordForm').addEventListener('submit', function (event) {
        const newPassword = document.getElementById('new_password').value;
        const confirmPassword = document.getElementById('confirm_password').value;

        if (newPassword !== confirmPassword) {
            event.preventDefault();
            alert('New password and confirmation do not match!');
        }
    });
</script>

<?php
// Removed footer include to prevent duplication
?>