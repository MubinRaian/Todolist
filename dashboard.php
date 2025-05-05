<?php
// views/dashboard.php
// Dashboard view shown after login

$pageTitle = 'Dashboard';
// Removed header include to prevent duplication
?>

<div class="row mb-4">
    <div class="col">
        <h1 class="h3 mb-2 text-gray-800 fw-bold">
            <i class="fas fa-tachometer-alt text-primary me-2"></i> Dashboard
        </h1>
        <p class="text-muted lead">Welcome back, <span class="fw-semibold"><?php echo getCurrentUsername(); ?></span>!
            Here's your task overview.</p>
    </div>
    <div class="col-auto d-flex align-items-center">
        <a href="tasks.php?action=create" class="btn btn-primary rounded-pill shadow-sm">
            <i class="fas fa-plus me-2"></i> New Task
        </a>
    </div>
</div>

<!-- Task Statistics Cards -->
<div class="row mb-4">
    <!-- Total Tasks Card -->
    <div class="col-md-6 col-lg-3 mb-4">
        <div class="card border-0 shadow-sm h-100 rounded-3 overflow-hidden">
            <div class="card-body position-relative">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <h6 class="text-uppercase text-primary fw-bold mb-0">Total Tasks</h6>
                    <div class="rounded-circle bg-primary bg-opacity-10 p-2">
                        <i class="fas fa-tasks fa-lg text-primary"></i>
                    </div>
                </div>
                <div class="d-flex align-items-end">
                    <h2 class="display-4 fw-bold mb-0 me-2"><?php echo $taskCounts['total']; ?></h2>
                    <p class="mb-0 text-muted">tasks</p>
                </div>
                <a href="tasks.php" class="stretched-link"></a>
            </div>
            <div class="card-footer bg-primary bg-opacity-10 py-2 border-0">
                <small class="text-primary"><i class="fas fa-info-circle me-1"></i> All your tasks</small>
            </div>
        </div>
    </div>

    <!-- Pending Tasks Card -->
    <div class="col-md-6 col-lg-3 mb-4">
        <div class="card border-0 shadow-sm h-100 rounded-3 overflow-hidden">
            <div class="card-body position-relative">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <h6 class="text-uppercase text-warning fw-bold mb-0">Pending Tasks</h6>
                    <div class="rounded-circle bg-warning bg-opacity-10 p-2">
                        <i class="fas fa-clipboard-list fa-lg text-warning"></i>
                    </div>
                </div>
                <div class="d-flex align-items-end">
                    <h2 class="display-4 fw-bold mb-0 me-2"><?php echo $taskCounts['pending']; ?></h2>
                    <p class="mb-0 text-muted">pending</p>
                </div>
                <a href="tasks.php?status=pending" class="stretched-link"></a>
            </div>
            <div class="card-footer bg-warning bg-opacity-10 py-2 border-0">
                <small class="text-warning"><i class="fas fa-clock me-1"></i> Awaiting completion</small>
            </div>
        </div>
    </div>

    <!-- Completed Tasks Card -->
    <div class="col-md-6 col-lg-3 mb-4">
        <div class="card border-0 shadow-sm h-100 rounded-3 overflow-hidden">
            <div class="card-body position-relative">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <h6 class="text-uppercase text-success fw-bold mb-0">Completed</h6>
                    <div class="rounded-circle bg-success bg-opacity-10 p-2">
                        <i class="fas fa-check-circle fa-lg text-success"></i>
                    </div>
                </div>
                <div class="d-flex align-items-end">
                    <h2 class="display-4 fw-bold mb-0 me-2"><?php echo $taskCounts['completed']; ?></h2>
                    <p class="mb-0 text-muted">done</p>
                </div>
                <a href="tasks.php?status=completed" class="stretched-link"></a>
            </div>
            <div class="card-footer bg-success bg-opacity-10 py-2 border-0">
                <small class="text-success"><i class="fas fa-trophy me-1"></i> Great job!</small>
            </div>
        </div>
    </div>

    <!-- Overdue Tasks Card -->
    <div class="col-md-6 col-lg-3 mb-4">
        <div class="card border-0 shadow-sm h-100 rounded-3 overflow-hidden">
            <div class="card-body position-relative">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <h6 class="text-uppercase text-danger fw-bold mb-0">Overdue</h6>
                    <div class="rounded-circle bg-danger bg-opacity-10 p-2">
                        <i class="fas fa-exclamation-circle fa-lg text-danger"></i>
                    </div>
                </div>
                <div class="d-flex align-items-end">
                    <h2 class="display-4 fw-bold mb-0 me-2"><?php echo $taskCounts['overdue']; ?></h2>
                    <p class="mb-0 text-muted">late</p>
                </div>
                <a href="tasks.php?status=pending&date_to=<?php echo date('Y-m-d', strtotime('-1 day')); ?>"
                    class="stretched-link"></a>
            </div>
            <div class="card-footer bg-danger bg-opacity-10 py-2 border-0">
                <small class="text-danger"><i class="fas fa-exclamation-triangle me-1"></i> Needs attention</small>
            </div>
        </div>
    </div>
</div>

<!-- Progress Bar -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
            <div class="card-header bg-gradient-primary-to-secondary text-white py-3 border-0">
                <h5 class="m-0 fw-bold"><i class="fas fa-chart-line me-2"></i> Task Completion Progress</h5>
            </div>
            <div class="card-body py-4">
                <?php
                // Get completion metrics
                $completionPercentage = $taskStats['completion_percentage'];
                $totalTasks = $taskStats['total'];
                $completedTasks = $taskStats['completed'];
                $pendingTasks = $taskStats['pending'];
                $highPriorityTasks = $taskStats['high_priority'];

                // Determine status and color classes
                $progressClass = 'bg-danger';
                $statusText = 'Getting started';
                $statusIcon = 'fa-hourglass-start';

                if ($completionPercentage >= 75) {
                    $progressClass = 'bg-success';
                    $statusText = 'Excellent progress';
                    $statusIcon = 'fa-award';
                } else if ($completionPercentage >= 50) {
                    $progressClass = 'bg-info';
                    $statusText = 'Good progress';
                    $statusIcon = 'fa-thumbs-up';
                } else if ($completionPercentage >= 25) {
                    $progressClass = 'bg-warning';
                    $statusText = 'Making progress';
                    $statusIcon = 'fa-walking';
                }

                // Calculate optimal daily completion rate
                $activeTasksCount = $pendingTasks;
                $suggestedDailyTasks = max(1, ceil($activeTasksCount / 7)); // Spread over a week
                ?>

                <!-- Main progress bar -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h6 class="fw-bold text-uppercase small text-muted">Overall Completion</h6>
                            <div class="d-flex align-items-center">
                                <span class="fw-bold h3 mb-0 me-2"><?php echo $completionPercentage; ?>%</span>
                                <span class="badge <?php echo $progressClass; ?> d-flex align-items-center">
                                    <i class="fas <?php echo $statusIcon; ?> me-1"></i> <?php echo $statusText; ?>
                                </span>
                            </div>
                        </div>
                        <div class="progress rounded-pill" style="height: 25px;">
                            <div class="progress-bar <?php echo $progressClass; ?> progress-bar-striped progress-bar-animated"
                                role="progressbar" style="width: <?php echo $completionPercentage; ?>%"
                                aria-valuenow="<?php echo $completionPercentage; ?>" aria-valuemin="0"
                                aria-valuemax="100">
                                <span class="fw-bold"><?php echo $completedTasks; ?>/<?php echo $totalTasks; ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Task statistics cards -->
                <div class="row mb-4">
                    <div class="col-lg-3 col-md-6 mb-3 mb-lg-0">
                        <div class="card border-0 bg-light h-100">
                            <div class="card-body text-center">
                                <div class="circle-icon bg-primary mb-3">
                                    <i class="fas fa-tasks"></i>
                                </div>
                                <h3 class="fw-bold"><?php echo $totalTasks; ?></h3>
                                <p class="text-muted mb-0">Total Tasks</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3 mb-lg-0">
                        <div class="card border-0 bg-light h-100">
                            <div class="card-body text-center">
                                <div class="circle-icon bg-success mb-3">
                                    <i class="fas fa-check"></i>
                                </div>
                                <h3 class="fw-bold"><?php echo $completedTasks; ?></h3>
                                <p class="text-muted mb-0">Completed</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3 mb-lg-0">
                        <div class="card border-0 bg-light h-100">
                            <div class="card-body text-center">
                                <div class="circle-icon bg-warning mb-3">
                                    <i class="fas fa-hourglass-half"></i>
                                </div>
                                <h3 class="fw-bold"><?php echo $pendingTasks; ?></h3>
                                <p class="text-muted mb-0">Pending</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="card border-0 bg-light h-100">
                            <div class="card-body text-center">
                                <div class="circle-icon bg-danger mb-3">
                                    <i class="fas fa-exclamation"></i>
                                </div>
                                <h3 class="fw-bold"><?php echo $highPriorityTasks; ?></h3>
                                <p class="text-muted mb-0">High Priority</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Productivity insights -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="card border-0 bg-light h-100">
                            <div class="card-body">
                                <h6 class="fw-bold text-primary mb-3">
                                    <i class="fas fa-lightbulb me-2"></i>Productivity Insights
                                </h6>
                                <ul class="list-unstyled mb-0">
                                    <li class="mb-2">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        <span>Your completion rate is <?php echo $completionPercentage; ?>%</span>
                                    </li>
                                    <?php if ($totalTasks > 0): ?>
                                        <?php if ($completionPercentage >= 50): ?>
                                            <li class="mb-2">
                                                <i class="fas fa-star text-warning me-2"></i>
                                                <span>Great job! You've completed over half your tasks.</span>
                                            </li>
                                        <?php else: ?>
                                            <li class="mb-2">
                                                <i class="fas fa-info-circle text-info me-2"></i>
                                                <span>Try to complete <?php echo $suggestedDailyTasks; ?> tasks per day to
                                                    finish in a week.</span>
                                            </li>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    <?php if ($highPriorityTasks > 0): ?>
                                        <li>
                                            <i class="fas fa-exclamation-triangle text-danger me-2"></i>
                                            <span>You have <?php echo $highPriorityTasks; ?> high priority tasks that need
                                                attention.</span>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mt-3 mt-md-0">
                        <div class="card border-0 bg-light h-100">
                            <div class="card-body">
                                <h6 class="fw-bold text-primary mb-3">
                                    <i class="fas fa-rocket me-2"></i>Quick Actions
                                </h6>
                                <div class="d-grid gap-2">
                                    <a href="tasks.php?action=create" class="btn btn-primary">
                                        <i class="fas fa-plus me-2"></i>Add New Task
                                    </a>
                                    <a href="tasks.php?status=pending&priority=high" class="btn btn-outline-danger">
                                        <i class="fas fa-exclamation-circle me-2"></i>View High Priority Tasks
                                    </a>
                                    <a href="calendar.php" class="btn btn-outline-info">
                                        <i class="fas fa-calendar-alt me-2"></i>View Calendar
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Today's Tasks -->
    <div class="col-lg-6 mb-4">
        <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
            <div class="card-header bg-white border-bottom-0 py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-primary">
                        <i class="fas fa-calendar-day me-2"></i> Today's Tasks
                    </h5>
                    <div>
                        <button class="btn btn-sm btn-outline-primary me-2 task-sort-btn" data-sort="priority">
                            <i class="fas fa-sort me-1"></i> Sort by Priority
                        </button>
                        <a href="tasks.php?date_from=<?php echo date('Y-m-d'); ?>&date_to=<?php echo date('Y-m-d'); ?>"
                            class="btn btn-sm btn-outline-primary rounded-pill">
                            <i class="fas fa-external-link-alt me-1"></i> View All
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <?php if (empty($todayTasks)): ?>
                    <div class="p-4 text-center">
                        <div class="mb-3 text-primary" style="font-size: 4rem;">
                            <i class="fas fa-clipboard-check"></i>
                        </div>
                        <h6 class="text-muted">No tasks due today</h6>
                        <p class="small text-muted mb-0">Enjoy your free time or create a new task!</p>
                        <a href="tasks.php?action=create" class="btn btn-primary mt-3">
                            <i class="fas fa-plus me-1"></i> Create Task for Today
                        </a>
                    </div>
                <?php else: ?>
                    <div class="task-list-container">
                        <div class="list-group list-group-flush task-list" id="today-tasks-list">
                            <?php foreach ($todayTasks as $task): ?>
                                <div class="list-group-item list-group-item-action border-0 px-4 py-3 task-item"
                                    data-priority="<?php echo $task['priority']; ?>" data-id="<?php echo $task['id']; ?>">
                                    <div class="d-flex w-100 justify-content-between align-items-center">
                                        <div class="d-flex align-items-center flex-grow-1">
                                            <div class="form-check">
                                                <input class="form-check-input task-checkbox" type="checkbox"
                                                    id="task-<?php echo $task['id']; ?>" data-id="<?php echo $task['id']; ?>"
                                                    <?php echo $task['status'] === 'completed' ? 'checked' : ''; ?>>
                                            </div>
                                            <div class="ms-3 flex-grow-1">
                                                <h6
                                                    class="mb-1 task-title <?php echo $task['status'] === 'completed' ? 'text-decoration-line-through text-muted' : 'fw-semibold'; ?>">
                                                    <?php echo $task['title']; ?>
                                                </h6>
                                                <div class="d-flex align-items-center flex-wrap">
                                                    <small class="text-muted me-3">
                                                        <i class="fas fa-clock me-1"></i>
                                                        <?php echo date('g:i A', strtotime($task['due_date'])); ?>
                                                    </small>
                                                    <?php if (!empty($task['category_name'])): ?>
                                                        <span class="badge rounded-pill text-bg-light me-2"
                                                            style="background-color: <?php echo $task['category_color']; ?>!important; color: white!important;">
                                                            <i class="fas fa-tag me-1"></i><?php echo $task['category_name']; ?>
                                                        </span>
                                                    <?php endif; ?>
                                                    <span
                                                        class="badge rounded-pill priority-badge text-bg-<?php echo getPriorityClass($task['priority']); ?>">
                                                        <?php
                                                        $priorityIcon = '';
                                                        if ($task['priority'] === 'high') {
                                                            $priorityIcon = '<i class="fas fa-arrow-up me-1"></i>';
                                                        } else if ($task['priority'] === 'medium') {
                                                            $priorityIcon = '<i class="fas fa-equals me-1"></i>';
                                                        } else {
                                                            $priorityIcon = '<i class="fas fa-arrow-down me-1"></i>';
                                                        }
                                                        echo $priorityIcon . ucfirst($task['priority']);
                                                        ?>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="task-actions">
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-light dropdown-toggle" type="button"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li><a class="dropdown-item"
                                                            href="tasks.php?action=view&id=<?php echo $task['id']; ?>"><i
                                                                class="fas fa-eye me-2"></i>View</a></li>
                                                    <li><a class="dropdown-item"
                                                            href="tasks.php?action=edit&id=<?php echo $task['id']; ?>"><i
                                                                class="fas fa-edit me-2"></i>Edit</a></li>
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                    <li><a class="dropdown-item text-<?php echo $task['status'] === 'completed' ? 'warning' : 'success'; ?> toggle-status"
                                                            href="#" data-id="<?php echo $task['id']; ?>"
                                                            data-status="<?php echo $task['status']; ?>">
                                                            <?php if ($task['status'] === 'completed'): ?>
                                                                <i class="fas fa-undo me-2"></i>Mark as Pending
                                                            <?php else: ?>
                                                                <i class="fas fa-check me-2"></i>Mark as Completed
                                                            <?php endif; ?>
                                                        </a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="px-4 py-3 bg-light border-top d-flex justify-content-between align-items-center">
                            <span class="text-muted"><i class="fas fa-info-circle me-1"></i> <span
                                    id="today-tasks-count"><?php echo count($todayTasks); ?></span> tasks due today</span>
                            <a href="tasks.php?action=create" class="btn btn-sm btn-primary">
                                <i class="fas fa-plus me-1"></i> Add Task
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Overdue Tasks -->
    <div class="col-lg-6 mb-4">
        <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
            <div class="card-header bg-white border-bottom-0 py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-danger">
                        <i class="fas fa-exclamation-circle me-2"></i> Overdue Tasks
                    </h5>
                    <div>
                        <button class="btn btn-sm btn-outline-danger me-2 task-sort-btn" data-sort="due-date">
                            <i class="fas fa-sort me-1"></i> Sort by Date
                        </button>
                        <a href="tasks.php?status=pending&date_to=<?php echo date('Y-m-d', strtotime('-1 day')); ?>"
                            class="btn btn-sm btn-outline-danger rounded-pill">
                            <i class="fas fa-external-link-alt me-1"></i> View All
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <?php if (empty($overdueTasks)): ?>
                    <div class="p-4 text-center">
                        <div class="mb-3 text-success" style="font-size: 4rem;">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <h6 class="text-success">No overdue tasks</h6>
                        <p class="small text-muted mb-0">You're all caught up. Great job!</p>
                        <a href="calendar.php" class="btn btn-success mt-3">
                            <i class="fas fa-calendar me-1"></i> View Your Calendar
                        </a>
                    </div>
                <?php else: ?>
                    <div class="task-list-container">
                        <div class="list-group list-group-flush task-list" id="overdue-tasks-list">
                            <?php foreach ($overdueTasks as $task): ?>
                                <?php
                                $dueDate = new DateTime($task['due_date']);
                                $now = new DateTime();
                                $interval = $now->diff($dueDate);
                                $daysOverdue = $interval->days;

                                $overdueClass = 'text-danger';
                                $overdueDescription = '1 day overdue';

                                if ($daysOverdue > 1) {
                                    $overdueDescription = $daysOverdue . ' days overdue';
                                    if ($daysOverdue > 7) {
                                        $overdueClass = 'text-danger fw-bold';
                                    }
                                }
                                ?>
                                <div class="list-group-item list-group-item-action border-0 px-4 py-3 task-item <?php echo $daysOverdue > 7 ? 'bg-danger bg-opacity-10' : ''; ?>"
                                    data-priority="<?php echo $task['priority']; ?>"
                                    data-due-date="<?php echo $task['due_date']; ?>" data-id="<?php echo $task['id']; ?>">
                                    <div class="d-flex w-100 justify-content-between align-items-center">
                                        <div class="d-flex align-items-center flex-grow-1">
                                            <div class="form-check">
                                                <input class="form-check-input task-checkbox" type="checkbox"
                                                    id="task-overdue-<?php echo $task['id']; ?>"
                                                    data-id="<?php echo $task['id']; ?>" <?php echo $task['status'] === 'completed' ? 'checked' : ''; ?>>
                                            </div>
                                            <div class="ms-3 flex-grow-1">
                                                <div class="d-flex align-items-center mb-1">
                                                    <h6
                                                        class="mb-0 task-title <?php echo $task['status'] === 'completed' ? 'text-decoration-line-through text-muted' : 'fw-semibold'; ?>">
                                                        <?php echo $task['title']; ?>
                                                    </h6>
                                                    <span
                                                        class="badge bg-danger ms-2 <?php echo $daysOverdue > 7 ? 'bg-danger' : 'bg-opacity-75'; ?>">
                                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                                        <?php echo $overdueDescription; ?>
                                                    </span>
                                                </div>
                                                <div class="d-flex align-items-center flex-wrap">
                                                    <small class="<?php echo $overdueClass; ?> me-3">
                                                        <i class="fas fa-calendar-times me-1"></i> Due
                                                        <?php echo date('M d, Y', strtotime($task['due_date'])); ?>
                                                    </small>
                                                    <?php if (!empty($task['category_name'])): ?>
                                                        <span class="badge rounded-pill text-bg-light me-2"
                                                            style="background-color: <?php echo $task['category_color']; ?>!important; color: white!important;">
                                                            <i class="fas fa-tag me-1"></i><?php echo $task['category_name']; ?>
                                                        </span>
                                                    <?php endif; ?>
                                                    <span
                                                        class="badge rounded-pill priority-badge text-bg-<?php echo getPriorityClass($task['priority']); ?>">
                                                        <?php
                                                        $priorityIcon = '';
                                                        if ($task['priority'] === 'high') {
                                                            $priorityIcon = '<i class="fas fa-arrow-up me-1"></i>';
                                                        } else if ($task['priority'] === 'medium') {
                                                            $priorityIcon = '<i class="fas fa-equals me-1"></i>';
                                                        } else {
                                                            $priorityIcon = '<i class="fas fa-arrow-down me-1"></i>';
                                                        }
                                                        echo $priorityIcon . ucfirst($task['priority']);
                                                        ?>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="task-actions">
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-light dropdown-toggle" type="button"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li><a class="dropdown-item"
                                                            href="tasks.php?action=view&id=<?php echo $task['id']; ?>"><i
                                                                class="fas fa-eye me-2"></i>View</a></li>
                                                    <li><a class="dropdown-item"
                                                            href="tasks.php?action=edit&id=<?php echo $task['id']; ?>"><i
                                                                class="fas fa-edit me-2"></i>Edit</a></li>
                                                    <li><a class="dropdown-item update-due-date" href="#"
                                                            data-id="<?php echo $task['id']; ?>"><i
                                                                class="fas fa-calendar-plus me-2"></i>Update Due Date</a></li>
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                    <li><a class="dropdown-item text-<?php echo $task['status'] === 'completed' ? 'warning' : 'success'; ?> toggle-status"
                                                            href="#" data-id="<?php echo $task['id']; ?>"
                                                            data-status="<?php echo $task['status']; ?>">
                                                            <?php if ($task['status'] === 'completed'): ?>
                                                                <i class="fas fa-undo me-2"></i>Mark as Pending
                                                            <?php else: ?>
                                                                <i class="fas fa-check me-2"></i>Mark as Completed
                                                            <?php endif; ?>
                                                        </a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div
                            class="px-4 py-3 bg-danger bg-opacity-10 border-top d-flex justify-content-between align-items-center">
                            <span class="text-danger"><i class="fas fa-exclamation-circle me-1"></i> <span
                                    id="overdue-tasks-count"><?php echo count($overdueTasks); ?></span> overdue tasks need
                                attention</span>
                            <button class="btn btn-sm btn-danger complete-all-overdue">
                                <i class="fas fa-check-double me-1"></i> Complete All
                            </button>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Update Due Date Modal -->
<div class="modal fade" id="updateDueDateModal" tabindex="-1" aria-labelledby="updateDueDateModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="updateDueDateModalLabel"><i class="fas fa-calendar-alt me-2"></i>Update Due
                    Date</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="updateDueDateForm">
                    <input type="hidden" id="update-task-id" name="task_id" value="">
                    <div class="mb-3">
                        <label for="new-due-date" class="form-label">New Due Date</label>
                        <input type="date" class="form-control" id="new-due-date" name="due_date"
                            value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="new-due-time" class="form-label">New Due Time</label>
                        <input type="time" class="form-control" id="new-due-time" name="due_time" value="12:00"
                            required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveDueDate">
                    <i class="fas fa-save me-1"></i> Save Changes
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Task checkbox event handler
    document.addEventListener('DOMContentLoaded', function () {
        // Get all task checkboxes
        const taskCheckboxes = document.querySelectorAll('.task-checkbox');

        // Add change event listener to each checkbox
        taskCheckboxes.forEach(function (checkbox) {
            checkbox.addEventListener('change', function () {
                const taskId = this.dataset.id;
                const isCompleted = this.checked;

                // Get the task title element
                const taskTitleEl = this.closest('.list-group-item').querySelector('.task-title');

                // Update UI immediately
                if (isCompleted) {
                    taskTitleEl.classList.add('text-decoration-line-through', 'text-muted');
                } else {
                    taskTitleEl.classList.remove('text-decoration-line-through', 'text-muted');
                }

                // Send AJAX request to update task status
                fetch('tasks.php?action=toggle&id=' + taskId, {
                    method: 'POST'
                })
                    .then(response => response.text())
                    .then(data => {
                        // Refresh the dashboard after a short delay
                        setTimeout(() => {
                            location.reload();
                        }, 800);
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        // Revert UI changes if request failed
                        this.checked = !isCompleted;
                        if (isCompleted) {
                            taskTitleEl.classList.remove('text-decoration-line-through', 'text-muted');
                        } else {
                            taskTitleEl.classList.add('text-decoration-line-through', 'text-muted');
                        }
                        alert('Failed to update task status. Please try again.');
                    });
            });
        });

        // Toggle status from dropdown
        document.querySelectorAll('.toggle-status').forEach(function (link) {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                const taskId = this.dataset.id;
                const currentStatus = this.dataset.status;

                // Send AJAX request to toggle task status
                fetch('tasks.php?action=toggle&id=' + taskId, {
                    method: 'POST'
                })
                    .then(response => response.text())
                    .then(data => {
                        // Refresh the dashboard after a short delay
                        setTimeout(() => {
                            location.reload();
                        }, 800);
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Failed to update task status. Please try again.');
                    });
            });
        });

        // Complete all overdue tasks
        const completeAllButton = document.querySelector('.complete-all-overdue');
        if (completeAllButton) {
            completeAllButton.addEventListener('click', function () {
                if (!confirm('Are you sure you want to mark all overdue tasks as completed?')) {
                    return;
                }

                const taskItems = document.querySelectorAll('#overdue-tasks-list .task-item');
                let completedCount = 0;
                let totalTasks = taskItems.length;

                taskItems.forEach(function (taskItem) {
                    const taskId = taskItem.dataset.id;
                    const checkbox = taskItem.querySelector('.task-checkbox');

                    if (!checkbox.checked) {
                        // Send AJAX request to update task status
                        fetch('tasks.php?action=toggle&id=' + taskId, {
                            method: 'POST'
                        })
                            .then(response => response.text())
                            .then(data => {
                                completedCount++;
                                if (completedCount === totalTasks) {
                                    // Refresh the dashboard after all tasks are completed
                                    location.reload();
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                            });
                    } else {
                        completedCount++;
                    }
                });
            });
        }

        // Update due date functionality
        document.querySelectorAll('.update-due-date').forEach(function (link) {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                const taskId = this.dataset.id;
                document.getElementById('update-task-id').value = taskId;

                // Show the modal
                const dueDateModal = new bootstrap.Modal(document.getElementById('updateDueDateModal'));
                dueDateModal.show();
            });
        });

        // Save due date changes
        document.getElementById('saveDueDate').addEventListener('click', function () {
            const form = document.getElementById('updateDueDateForm');
            const taskId = document.getElementById('update-task-id').value;
            const newDate = document.getElementById('new-due-date').value;
            const newTime = document.getElementById('new-due-time').value;
            const newDueDate = newDate + ' ' + newTime;

            // Validate form
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            // Send AJAX request to update due date
            fetch('tasks.php?action=update', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `id=${taskId}&due_date=${newDueDate}`
            })
                .then(response => response.text())
                .then(data => {
                    // Close the modal
                    const dueDateModal = bootstrap.Modal.getInstance(document.getElementById('updateDueDateModal'));
                    dueDateModal.hide();

                    // Refresh the dashboard
                    location.reload();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to update due date. Please try again.');
                });
        });

        // Sort functionality
        document.querySelectorAll('.task-sort-btn').forEach(function (button) {
            button.addEventListener('click', function () {
                const sortBy = this.dataset.sort;
                const listId = this.closest('.card').querySelector('.task-list').id;
                const taskList = document.getElementById(listId);
                const tasks = Array.from(taskList.querySelectorAll('.task-item'));

                // Sort tasks
                tasks.sort(function (a, b) {
                    if (sortBy === 'priority') {
                        const priorityOrder = { 'high': 0, 'medium': 1, 'low': 2 };
                        return priorityOrder[a.dataset.priority] - priorityOrder[b.dataset.priority];
                    } else if (sortBy === 'due-date') {
                        return new Date(a.dataset.dueDate) - new Date(b.dataset.dueDate);
                    }
                    return 0;
                });

                // Re-append tasks in sorted order
                tasks.forEach(task => taskList.appendChild(task));
            });
        });
    });
</script>

<style>
    .circle-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        color: white;
        font-size: 24px;
    }

    .task-list-container {
        max-height: 400px;
        overflow-y: auto;
    }

    .task-actions .dropdown-toggle::after {
        display: none;
    }

    .priority-badge {
        font-size: 0.75rem;
    }
</style>

<?php
// Removed footer include to prevent duplication
?>