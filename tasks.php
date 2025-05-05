<?php
// tasks.php
// Task management page (in root directory)

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

// Process action based on URL parameter
$action = $_GET['action'] ?? 'index';

switch ($action) {
    case 'create':
        $taskController->create();
        break;

    case 'store':
        $taskController->store();
        break;

    case 'edit':
        $taskId = $_GET['id'] ?? 0;
        $taskController->edit($taskId);
        break;

    case 'update':
        $taskId = $_GET['id'] ?? 0;
        $taskController->update($taskId);
        break;

    case 'view':
        $taskId = $_GET['id'] ?? 0;
        $taskController->view($taskId);
        break;

    case 'delete':
        $taskId = $_GET['id'] ?? 0;
        $taskController->delete($taskId);
        break;

    case 'toggle':
        $taskId = $_GET['id'] ?? 0;
        $taskController->toggleStatus($taskId);
        break;

    case 'add_comment':
        $taskId = $_GET['id'] ?? 0;
        $taskController->addComment($taskId);
        break;

    case 'undo':
        $taskId = $_GET['id'] ?? 0;
        $taskController->undo($taskId);
        break;

    case 'update_priority':
        $taskId = $_GET['id'] ?? 0;
        $taskController->updatePriority($taskId);
        break;

    case 'bulk':
        $taskController->bulkAction();
        break;

    case 'statistics':
        $taskController->dashboard();
        break;

    default:
        $taskController->index();
        break;
}
?>