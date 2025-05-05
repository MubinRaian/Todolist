<?php
// views/auth/login.php
// Login form view

$pageTitle = 'Login';
include 'views/layouts/header.php';
?>

<div class="auth-page">
    <div class="container h-100">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-8 col-lg-6 col-xl-5">
                <div class="text-center mb-4">
                    <i class="fas fa-tasks fa-3x text-primary"></i>
                    <h2 class="mt-3 text-white">Task Manager</h2>
                    <p class="text-white-50">Log in to manage your tasks efficiently</p>
                </div>

                <div class="card shadow-lg border-0 rounded-lg">
                    <div class="card-body p-4 p-lg-5">
                        <h4 class="card-title text-center mb-4 fw-bold">Welcome Back</h4>

                        <form action="auth.php?action=login" method="post" class="needs-validation" novalidate>
                            <div class="mb-3">
                                <label for="email" class="form-label small fw-medium">Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i
                                            class="fas fa-envelope text-primary"></i></span>
                                    <input type="email" class="form-control border-start-0 bg-light" id="email"
                                        name="email" placeholder="name@example.com" required>
                                </div>
                                <div class="invalid-feedback small">Please enter your email.</div>
                            </div>

                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <label for="password" class="form-label small fw-medium">Password</label>
                                    <a href="#" class="small text-decoration-none text-primary">Forgot password?</a>
                                </div>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i
                                            class="fas fa-lock text-primary"></i></span>
                                    <input type="password" class="form-control border-start-0 bg-light" id="password"
                                        name="password" placeholder="Enter your password" required>
                                    <button class="btn btn-light border border-start-0" type="button"
                                        id="togglePassword">
                                        <i class="fas fa-eye text-muted small"></i>
                                    </button>
                                </div>
                                <div class="invalid-feedback small">Please enter your password.</div>
                            </div>

                            <div class="mb-4 form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                <label class="form-check-label small" for="remember">Remember me</label>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary py-2">
                                    Sign In <i class="fas fa-sign-in-alt ms-2"></i>
                                </button>
                            </div>

                            <div class="text-center mt-4">
                                <p class="mb-0 small">Don't have an account? <a href="register.php"
                                        class="text-decoration-none fw-medium">Create an account</a></p>
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

<style>
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
</script>

<?php include 'views/layouts/footer.php'; ?>