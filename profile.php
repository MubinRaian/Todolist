<?php
// profile.php
// User profile page (in root directory)

// Include required files
require_once 'config/database.php';
require_once 'includes/functions.php';
require_once 'includes/session.php';
require_once 'models/User.php';
require_once 'controllers/UserController.php';

// Check if user is logged in
requireLogin();

// Initialize user controller
$userController = new UserController();

// Process action based on URL parameter
$action = $_GET['action'] ?? 'profile';

switch ($action) {
    case 'update_profile':
        $userController->updateProfile();
        break;

    case 'change_password':
        $userController->changePassword();
        break;

    case 'upload_profile_image':
        $userController->uploadProfileImage();
        break;

    case 'toggle_theme':
        $userController->toggleTheme();
        break;

    case 'statistics':
        $userController->statistics();
        break;

    default:
        $userController->profile();
        break;
}
?>