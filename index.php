<?php
// index.php
// Main entry point for the ToDoList application

// Include required files
require_once 'config/database.php';
require_once 'includes/functions.php';
require_once 'includes/session.php';
require_once 'models/User.php';
require_once 'models/Task.php';
require_once 'controllers/TaskController.php';
require_once 'controllers/AuthController.php';

// Check if user is logged in
if (isLoggedIn()) {
    // Show dashboard for logged-in users
    $taskController = new TaskController();
    $taskController->dashboard();
} else {
    // Redirect to login page for non-logged-in users
    redirect('login.php');
}
?>