<?php
// auth.php
// Process authentication actions (login, register, logout, etc.)

// Include required files
require_once 'config/database.php';
require_once 'includes/functions.php';
require_once 'includes/session.php';
require_once 'controllers/AuthController.php';

// Create authentication controller
$authController = new AuthController();

// Process action based on query parameter
if (isset($_GET['action'])) {
    $action = $_GET['action'];

    switch ($action) {
        case 'login':
            // Process login form
            $authController->login();
            break;

        case 'register':
            // Process registration form
            $authController->register();
            break;

        case 'logout':
            // Process logout
            $authController->logout();
            break;

        case 'toggle_theme':
            // Toggle theme (dark/light mode)
            $authController->toggleTheme();
            break;

        default:
            // Invalid action
            setFlashMessage('danger', 'Invalid action');
            redirect('index.php');
    }
} else {
    // No action specified
    setFlashMessage('danger', 'No action specified');
    redirect('index.php');
}
?>