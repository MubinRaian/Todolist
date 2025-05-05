<?php
// controllers/TaskController.php
// Controller for task-related operations

require_once 'models/Task.php';

class TaskController
{
    // Display task list with filtering and pagination
    public function index()
    {
        // Check if user is logged in
        requireLogin();

        $userId = getCurrentUserId();

        // Get filters from request
        $filters = [
            'status' => $_GET['status'] ?? 'all',
            'priority' => $_GET['priority'] ?? 'all',
            'category_id' => $_GET['category_id'] ?? 0,
            'search' => $_GET['search'] ?? '',
            'date_from' => $_GET['date_from'] ?? '',
            'date_to' => $_GET['date_to'] ?? '',
            'sort_by' => $_GET['sort_by'] ?? 'due_date_asc'
        ];

        // Pagination
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $limit = 10; // Tasks per page

        $filters['page'] = $page;
        $filters['limit'] = $limit;

        // Get tasks
        $tasks = Task::getAllTasks($userId, $filters);

        // Get total tasks for pagination
        $totalTasks = Task::countTasks($userId, $filters);
        $totalPages = ceil($totalTasks / $limit);

        // Get user categories for filter dropdown
        $categories = getUserCategories($userId);

        // Include header, view, and footer
        include 'views/layouts/header.php';
        include 'views/tasks/index.php';
        include 'views/layouts/footer.php';
    }

    // Display create task form
    public function create()
    {
        // Check if user is logged in
        requireLogin();

        $userId = getCurrentUserId();

        // Get user categories for dropdown
        $categories = getUserCategories($userId);

        // Include header, view, and footer
        include 'views/layouts/header.php';
        include 'views/tasks/create.php';
        include 'views/layouts/footer.php';
    }

    // Process task creation
    public function store()
    {
        // Check if user is logged in
        requireLogin();

        $userId = getCurrentUserId();

        // Validate form data
        $title = cleanInput($_POST['title']);
        $description = cleanInput($_POST['description']);
        $dueDate = $_POST['due_date'];
        $priority = $_POST['priority'];
        $category_id = isset($_POST['category_id']) ? (int) $_POST['category_id'] : null;

        if (empty($title)) {
            setFlashMessage('danger', 'Task title is required');
            redirect('tasks/create.php');
            return;
        }

        // Create task data
        $taskData = [
            'user_id' => $userId,
            'title' => $title,
            'description' => $description,
            'due_date' => $dueDate,
            'priority' => $priority,
            'status' => 'pending',
            'category_id' => $category_id === 0 ? null : $category_id
        ];

        // Insert task
        $taskId = Task::createTask($taskData);

        if ($taskId) {
            // Record task creation in history
            Task::addTaskHistory($taskId, $userId, 'create', null, $taskData);

            setFlashMessage('success', 'Task created successfully');
            redirect('tasks/index.php');
        } else {
            setFlashMessage('danger', 'Failed to create task');
            redirect('tasks/create.php');
        }
    }

    // Display edit task form
    public function edit($taskId)
    {
        // Check if user is logged in
        requireLogin();

        $userId = getCurrentUserId();

        // Get task
        $task = Task::getTaskById($taskId, $userId);

        // Check if task exists and belongs to user
        if (!$task) {
            setFlashMessage('danger', 'Task not found');
            redirect('tasks/index.php');
            return;
        }

        // Get user categories for dropdown
        $categories = getUserCategories($userId);

        // Include header, view, and footer
        include 'views/layouts/header.php';
        include 'views/tasks/edit.php';
        include 'views/layouts/footer.php';
    }

    // Process task update
    public function update($taskId)
    {
        // Check if user is logged in
        requireLogin();

        $userId = getCurrentUserId();

        // Get task
        $task = Task::getTaskById($taskId, $userId);

        // Check if task exists and belongs to user
        if (!$task) {
            setFlashMessage('danger', 'Task not found');
            redirect('tasks/index.php');
            return;
        }

        // Validate form data
        $title = cleanInput($_POST['title']);
        $description = cleanInput($_POST['description']);
        $dueDate = $_POST['due_date'];
        $priority = $_POST['priority'];
        $status = $_POST['status'];
        $category_id = isset($_POST['category_id']) ? (int) $_POST['category_id'] : null;

        if (empty($title)) {
            setFlashMessage('danger', 'Task title is required');
            redirect("tasks/edit.php?id=$taskId");
            return;
        }

        // Save original task data for history
        $previousData = [
            'title' => $task['title'],
            'description' => $task['description'],
            'due_date' => $task['due_date'],
            'priority' => $task['priority'],
            'status' => $task['status'],
            'category_id' => $task['category_id']
        ];

        // Update task data
        $taskData = [
            'title' => $title,
            'description' => $description,
            'due_date' => $dueDate,
            'priority' => $priority,
            'status' => $status,
            'category_id' => $category_id === 0 ? null : $category_id
        ];

        // Update task
        $result = Task::updateTask($taskId, $userId, $taskData);

        if ($result) {
            // Record task update in history
            Task::addTaskHistory($taskId, $userId, 'update', $previousData, $taskData);

            setFlashMessage('success', 'Task updated successfully');
            redirect('tasks/index.php');
        } else {
            setFlashMessage('danger', 'Failed to update task');
            redirect("tasks/edit.php?id=$taskId");
        }
    }

    // Process task deletion
    public function delete($taskId)
    {
        // Check if user is logged in
        requireLogin();

        $userId = getCurrentUserId();

        // Get task for history
        $task = Task::getTaskById($taskId, $userId);

        if (!$task) {
            setFlashMessage('danger', 'Task not found');
            redirect('tasks/index.php');
            return;
        }

        // Delete task
        $result = Task::deleteTask($taskId, $userId);

        if ($result) {
            // Record task deletion in history
            Task::addTaskHistory($taskId, $userId, 'delete', $task, null);

            setFlashMessage('success', 'Task deleted successfully');
        } else {
            setFlashMessage('danger', 'Failed to delete task');
        }

        redirect('tasks/index.php');
    }

    // Add a comment to a task
    public function addComment($taskId)
    {
        // Check if user is logged in
        requireLogin();

        $userId = getCurrentUserId();

        // Get task
        $task = Task::getTaskById($taskId, $userId);

        // Check if task exists and belongs to user
        if (!$task) {
            setFlashMessage('danger', 'Task not found');
            redirect('tasks/index.php');
            return;
        }

        // Validate comment
        $comment = cleanInput($_POST['comment']);

        if (empty($comment)) {
            setFlashMessage('danger', 'Comment cannot be empty');
            redirect("tasks.php?action=view&id=$taskId");
            return;
        }

        // Add comment
        $result = Task::addComment($taskId, $userId, $comment);

        if ($result) {
            setFlashMessage('success', 'Comment added successfully');
        } else {
            setFlashMessage('danger', 'Failed to add comment');
        }

        redirect("tasks.php?action=view&id=$taskId");
    }

    // Toggle task status (complete/pending)
    public function toggleStatus($taskId)
    {
        // Check if user is logged in
        requireLogin();

        $userId = getCurrentUserId();

        // Get task for history
        $task = Task::getTaskById($taskId, $userId);

        if (!$task) {
            setFlashMessage('danger', 'Task not found');
            redirect('tasks/index.php');
            return;
        }

        // Previous status
        $previousStatus = ['status' => $task['status']];

        // New status
        $newStatus = ['status' => $task['status'] === 'completed' ? 'pending' : 'completed'];

        // Toggle task status
        $result = Task::toggleTaskStatus($taskId, $userId);

        if ($result) {
            // Record status change in history
            Task::addTaskHistory($taskId, $userId, 'status_change', $previousStatus, $newStatus);

            setFlashMessage('success', 'Task status updated');
        } else {
            setFlashMessage('danger', 'Failed to update task status');
        }

        // Redirect back to referring page or index
        $referer = $_SERVER['HTTP_REFERER'] ?? 'tasks/index.php';
        redirect($referer);
    }

    // Undo last task action
    public function undo($taskId)
    {
        // Check if user is logged in
        requireLogin();

        $userId = getCurrentUserId();

        $result = Task::undoLastAction($taskId, $userId);

        if ($result) {
            setFlashMessage('success', 'Action undone successfully');
        } else {
            setFlashMessage('warning', 'Nothing to undo');
        }

        // Redirect back to referring page or task view
        $referer = $_SERVER['HTTP_REFERER'] ?? "tasks.php?action=view&id=$taskId";
        redirect($referer);
    }

    // Update task priority via AJAX (for drag and drop)
    public function updatePriority($taskId)
    {
        // Check if user is logged in
        requireLogin();

        $userId = getCurrentUserId();

        // Set content type header for AJAX response
        header('Content-Type: application/json');

        // Get priority from request
        $priority = $_GET['priority'] ?? null;

        if (!in_array($priority, ['low', 'medium', 'high'])) {
            echo json_encode([
                'success' => false,
                'message' => 'Invalid priority value'
            ]);
            exit;
        }

        // Get task for history
        $task = Task::getTaskById($taskId, $userId);

        if (!$task) {
            echo json_encode([
                'success' => false,
                'message' => 'Task not found'
            ]);
            exit;
        }

        // Previous priority
        $previousData = ['priority' => $task['priority']];

        // New priority
        $newData = ['priority' => $priority];

        // Update task priority
        $result = Task::updateTask($taskId, $userId, ['priority' => $priority]);

        if ($result) {
            // Record priority change in history
            Task::addTaskHistory($taskId, $userId, 'update', $previousData, $newData);

            echo json_encode([
                'success' => true,
                'message' => "Task priority updated to $priority"
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Failed to update task priority'
            ]);
        }
        exit;
    }

    // Display task details
    public function view($taskId)
    {
        // Check if user is logged in
        requireLogin();

        $userId = getCurrentUserId();

        // Get task
        $task = Task::getTaskById($taskId, $userId);

        // Check if task exists and belongs to user
        if (!$task) {
            setFlashMessage('danger', 'Task not found');
            redirect('tasks/index.php');
            return;
        }

        // Include header, view, and footer
        include 'views/layouts/header.php';
        include 'views/tasks/view.php';
        include 'views/layouts/footer.php';
    }

    // Display dashboard
    public function dashboard()
    {
        // Check if user is logged in
        requireLogin();

        $userId = getCurrentUserId();

        // Get task counts
        $taskCounts = User::getUserTaskCounts($userId);

        // Get tasks due today
        $todayTasks = Task::getTasksDueToday($userId);

        // Get overdue tasks
        $overdueTasks = Task::getOverdueTasks($userId);

        // Get upcoming tasks
        $upcomingTasks = Task::getUpcomingTasks($userId);

        // Get task statistics
        $taskStats = Task::getTaskStatistics($userId);

        // Include header, view, and footer
        include 'views/layouts/header.php';
        include 'views/dashboard.php';
        include 'views/layouts/footer.php';
    }

    // Display calendar view
    public function calendar()
    {
        // Check if user is logged in
        requireLogin();

        $userId = getCurrentUserId();

        // Get view type (day, week, month, year)
        $viewType = $_GET['view'] ?? 'month';

        // Get current date
        $currentDate = $_GET['date'] ?? date('Y-m-d');

        // Calculate date range based on view type
        switch ($viewType) {
            case 'day':
                $startDate = $currentDate . ' 00:00:00';
                $endDate = $currentDate . ' 23:59:59';
                break;

            case 'week':
                // Get Monday of the week
                $monday = date('Y-m-d', strtotime('monday this week', strtotime($currentDate)));
                $startDate = $monday . ' 00:00:00';
                $endDate = date('Y-m-d', strtotime($monday . ' +6 days')) . ' 23:59:59';
                break;

            case 'month':
                // Get first and last day of month
                $firstDay = date('Y-m-01', strtotime($currentDate));
                $lastDay = date('Y-m-t', strtotime($currentDate));
                $startDate = $firstDay . ' 00:00:00';
                $endDate = $lastDay . ' 23:59:59';
                break;

            case 'year':
                // Get first and last day of year
                $year = date('Y', strtotime($currentDate));
                $startDate = $year . '-01-01 00:00:00';
                $endDate = $year . '-12-31 23:59:59';
                break;
        }

        // Get tasks for the selected period
        $tasks = Task::getTasksForCalendar($userId, $startDate, $endDate);

        // Include header, appropriate view based on view type, and footer
        include 'views/layouts/header.php';
        include "views/calendar/$viewType.php";
        include 'views/layouts/footer.php';
    }

    // Process bulk actions on multiple tasks
    public function bulkAction()
    {
        // Check if user is logged in
        requireLogin();

        $userId = getCurrentUserId();

        // Get selected task IDs and action type
        $taskIds = isset($_POST['task_ids']) ? $_POST['task_ids'] : [];
        $actionType = isset($_POST['action_type']) ? $_POST['action_type'] : '';

        if (empty($taskIds) || empty($actionType)) {
            setFlashMessage('warning', 'No tasks selected or invalid action');
            redirect('tasks.php');
            return;
        }

        $successCount = 0;

        foreach ($taskIds as $taskId) {
            // Verify task belongs to user
            $task = Task::getTaskById($taskId, $userId);

            if (!$task) {
                continue;
            }

            switch ($actionType) {
                case 'complete':
                    if ($task['status'] !== 'completed') {
                        // Save original status for history
                        $previousData = ['status' => $task['status']];
                        $newData = ['status' => 'completed'];

                        // Update task
                        Task::updateTask($taskId, $userId, ['status' => 'completed']);

                        // Record in history
                        Task::addTaskHistory($taskId, $userId, 'status_change', $previousData, $newData);

                        $successCount++;
                    }
                    break;

                case 'pending':
                    if ($task['status'] !== 'pending') {
                        // Save original status for history
                        $previousData = ['status' => $task['status']];
                        $newData = ['status' => 'pending'];

                        // Update task
                        Task::updateTask($taskId, $userId, ['status' => 'pending']);

                        // Record in history
                        Task::addTaskHistory($taskId, $userId, 'status_change', $previousData, $newData);

                        $successCount++;
                    }
                    break;

                case 'delete':
                    // Record before deleting
                    Task::addTaskHistory($taskId, $userId, 'delete', $task, null);

                    // Delete task
                    Task::deleteTask($taskId, $userId);
                    $successCount++;
                    break;
            }
        }

        // Set flash message based on action and success count
        if ($successCount > 0) {
            $actionMsg = $actionType === 'complete' ? 'marked as completed' :
                ($actionType === 'pending' ? 'marked as pending' : 'deleted');

            setFlashMessage('success', "$successCount task(s) successfully $actionMsg");
        } else {
            setFlashMessage('warning', 'No tasks were modified');
        }

        redirect('tasks.php');
    }
}