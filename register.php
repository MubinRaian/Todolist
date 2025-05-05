<?php
// register.php
// Registration page (in root directory)

// Include required files
require_once 'config/database.php';
require_once 'includes/functions.php';
require_once 'includes/session.php';
require_once 'controllers/AuthController.php';

// Set page title
$pageTitle = 'Register';

// If user is already logged in, redirect to dashboard
if (isLoggedIn()) {
    redirect('index.php');
}

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process registration form
    $authController = new AuthController();
    $authController->register();
} else {
    // Display registration form - don't include header/footer if the form already includes them
    include 'views/auth/register_form.php';
}
?>