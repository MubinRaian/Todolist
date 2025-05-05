<?php
// calendar.php
// Calendar management page (in root directory)

// Include required files
require_once 'config/database.php';
require_once 'includes/functions.php';
require_once 'includes/session.php';
require_once 'models/Task.php';
require_once 'controllers/TaskController.php';

// Check if user is logged in
requireLogin();

// Initialize task controller
$taskController = new TaskController();

// Call the calendar method to display the appropriate calendar view
$taskController->calendar();
?>