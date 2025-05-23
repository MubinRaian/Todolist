<?php
// login.php
// Login page (in root directory)

// Include required files
require_once 'config/database.php';
require_once 'includes/functions.php';
require_once 'includes/session.php';
require_once 'controllers/AuthController.php';

// Set page title
$pageTitle = 'Login';

// If user is already logged in, redirect to dashboard
if (isLoggedIn()) {
    redirect('index.php');
}

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process login form
    $authController = new AuthController();
    $authController->login();
} else {
    // Display login form - don't include header/footer as the form already includes them
    include 'views/auth/login_form.php';
}
?>