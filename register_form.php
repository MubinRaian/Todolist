<?php
// views/auth/register.php
// Registration form view

$pageTitle = 'Register';
include 'views/layouts/header.php';
?>

<div class="auth-page">
    <div class="container h-100">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-8 col-lg-6 col-xl-5">
                <div class="text-center mb-4">
                    <i class="fas fa-tasks fa-3x text-primary"></i>
                    <h2 class="mt-3 text-white">Task Manager</h2>
                    <p class="text-white-50">Create an account to start managing your tasks</p>
                </div>

                <div class="card shadow-lg border-0 rounded-lg">
                    <div class="card-body p-4 p-lg-5">
                        <h4 class="card-title text-center mb-4 fw-bold">Create Account</h4>

                        <form action="auth.php?action=register" method="post" id="registerForm" class="needs-validation"
                            novalidate>
                            <div class="mb-3">
                                <label for="username" class="form-label small fw-medium">Username</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i
                                            class="fas fa-user text-primary"></i></span>
                                    <input type="text" class="form-control border-start-0 bg-light" id="username"
                                        name="username" placeholder="Choose a username" required>
                                </div>
                                <div class="invalid-feedback small">Please choose a username.</div>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label small fw-medium">Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i
                                            class="fas fa-envelope text-primary"></i></span>
                                    <input type="email" class="form-control border-start-0 bg-light" id="email"
                                        name="email" placeholder="name@example.com" required>
                                </div>
                                <div class="invalid-feedback small">Please enter a valid email address.</div>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label small fw-medium">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i
                                            class="fas fa-lock text-primary"></i></span>
                                    <input type="password" class="form-control border-start-0 bg-light" id="password"
                                        name="password" placeholder="Create a password" required>
                                    <button class="btn btn-light border border-start-0" type="button"
                                        id="togglePassword">
                                        <i class="fas fa-eye text-muted small"></i>
                                    </button>
                                </div>
                                <div class="form-text small">Password must be at least 6 characters long.</div>
                                <div class="invalid-feedback small">Please create a password.</div>
                            </div>

                            <div class="mb-3">
                                <label for="confirm_password" class="form-label small fw-medium">Confirm
                                    Password</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i
                                            class="fas fa-lock text-primary"></i></span>
                                    <input type="password" class="form-control border-start-0 bg-light"
                                        id="confirm_password" name="confirm_password"
                                        placeholder="Confirm your password" required>
                                </div>
                                <div class="invalid-feedback small">Please confirm your password.</div>
                            </div>

                            <div class="mb-4 form-check">
                                <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                                <label class="form-check-label small" for="terms">I agree to the <a href="#"
                                        data-bs-toggle="modal" data-bs-target="#termsModal"
                                        class="text-decoration-none">Terms</a> and <a href="#" data-bs-toggle="modal"
                                        data-bs-target="#privacyModal" class="text-decoration-none">Privacy
                                        Policy</a></label>
                                <div class="invalid-feedback small">You must agree to our terms and policies.</div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary py-2">
                                    Create Account <i class="fas fa-user-plus ms-2"></i>
                                </button>
                            </div>

                            <div class="text-center mt-4">
                                <p class="mb-0 small">Already have an account? <a href="login.php"
                                        class="text-decoration-none fw-medium">Sign in</a></p>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <p class="mb-0 small text-white-50">© <?php echo date('Y'); ?> Task Manager. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Terms of Service Modal -->
<div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary-to-secondary text-white">
                <h5 class="modal-title" id="termsModalLabel">Terms of Service</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6 class="text-primary">1. Acceptance of Terms</h6>
                <p>By accessing and using Task Manager, you agree to be bound by these Terms of Service.</p>

                <h6 class="text-primary">2. User Accounts</h6>
                <p>To use Task Manager, you must create an account with a valid email address and secure password. You
                    are responsible for maintaining the confidentiality of your account information.</p>

                <h6 class="text-primary">3. User Conduct</h6>
                <p>You agree not to use Task Manager for any illegal or unauthorized purpose.</p>

                <h6 class="text-primary">4. Data Security</h6>
                <p>We implement reasonable security measures to protect your data.</p>

                <h6 class="text-primary">5. Changes to Terms</h6>
                <p>We reserve the right to modify these terms at any time.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">I Understand</button>
            </div>
        </div>
    </div>
</div>

<!-- Privacy Policy Modal -->
<div class="modal fade" id="privacyModal" tabindex="-1" aria-labelledby="privacyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary-to-secondary text-white">
                <h5 class="modal-title" id="privacyModalLabel">Privacy Policy</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6 class="text-primary">1. Information We Collect</h6>
                <p>We collect information that you provide when you register and use Task Manager.</p>

                <h6 class="text-primary">2. How We Use Your Information</h6>
                <p>We use your information to provide and improve Task Manager services.</p>

                <h6 class="text-primary">3. Data Storage</h6>
                <p>Your data is stored securely on our servers.</p>

                <h6 class="text-primary">4. Cookies</h6>
                <p>We use cookies to enhance your user experience.</p>

                <h6 class="text-primary">5. Third-Party Services</h6>
                <p>We may use third-party services to help us operate Task Manager.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">I Understand</button>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-gradient-primary-to-secondary {
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
    }

    .auth-page {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        min-height: 100vh;
        display: flex;
        align-items: center;
        position: relative;
    }

    .auth-page::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        background-image: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23ffffff' fill-opacity='0.05' fill-rule='evenodd'/%3E%3C/svg%3E");
        opacity: 0.8;
    }

    .card {
        border: none;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }

    .form-control:focus,
    .input-group-text:focus,
    .form-check-input:focus {
        box-shadow: 0 0 0 0.25rem rgba(78, 115, 223, 0.25);
        border-color: #bac8f3;
    }

    .container {
        position: relative;
        z-index: 10;
    }

    .btn-primary {
        background: linear-gradient(to right, #4e73df, #224abe);
        border: none;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background: linear-gradient(to right, #224abe, #1e3c72);
        transform: translateY(-1px);
    }
</style>

<script>
    // Toggle password visibility
    document.getElementById('togglePassword').addEventListener('click', function () {
        const passwordInput = document.getElementById('password');
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

    // Form validation
    (function () {
        'use strict';
        const forms = document.querySelectorAll('.needs-validation');
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    })();

    // Password match validation
    document.getElementById('registerForm').addEventListener('submit', function (event) {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirm_password').value;

        if (password !== confirmPassword) {
            event.preventDefault();
            const confirmPasswordInput = document.getElementById('confirm_password');
            confirmPasswordInput.setCustomValidity('Passwords do not match!');
            confirmPasswordInput.reportValidity();
        }
    });

    // Clear custom validation when typing
    document.getElementById('confirm_password').addEventListener('input', function () {
        this.setCustomValidity('');
    });
</script>

<?php include 'views/layouts/footer.php'; ?>