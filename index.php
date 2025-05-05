<?php
// views/tasks/index.php
// Task list view with filtering and sorting

$pageTitle = 'My Tasks';
// Removed header include to prevent duplication

?>

<style>
    /* My Tasks page specific styles */
    .page-header {
        background-color: #f8f9fc;
        padding: 1.5rem;
        border-radius: 10px;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        border-left: 4px solid var(--primary-color);
    }

    [data-bs-theme="dark"] .page-header {
        background-color: #2d3748;
    }

    .task-card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 0.15rem 1.75rem rgba(33, 40, 50, 0.15);
        margin-bottom: 1.5rem;
        overflow: hidden;
    }

    .card-header {
        background: linear-gradient(45deg, #4e73df, #224abe);
        color: white;
        padding: 1rem 1.5rem;
        font-weight: 600;
        border: none;
    }

    .card-body {
        padding: 1.5rem;
    }

    .filter-form {
        background-color: rgba(78, 115, 223, 0.03);
        border-radius: 8px;
        padding: 1.25rem;
    }

    [data-bs-theme="dark"] .filter-form {
        background-color: rgba(78, 115, 223, 0.08);
    }

    .form-label {
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #555;
    }

    [data-bs-theme="dark"] .form-label {
        color: #ccd;
    }

    .form-control,
    .form-select {
        padding: 0.7rem 1rem;
        border-radius: 6px;
        transition: all 0.2s ease;
    }

    .btn-primary,
    .btn-secondary {
        padding: 0.6rem 1.25rem;
        font-weight: 600;
        border-radius: 6px;
        transition: all 0.2s ease;
    }

    .btn-primary {
        background: linear-gradient(45deg, #4e73df, #224abe);
        border: none;
    }

    .btn-primary:hover {
        background: linear-gradient(45deg, #4668c5, #1d3d9e);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .btn-secondary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .btn-outline-primary {
        border-color: #4e73df;
        color: #4e73df;
        font-weight: 500;
    }

    .btn-outline-primary:hover {
        background-color: #4e73df;
        color: white;
    }

    .task-table {
        border-collapse: separate;
        border-spacing: 0;
    }

    .task-table th {
        background-color: rgba(78, 115, 223, 0.08);
        color: #4e73df;
        font-weight: 600;
        padding: 1rem 0.75rem;
        border-bottom: 2px solid #e3e6f0;
    }

    [data-bs-theme="dark"] .task-table th {
        background-color: rgba(78, 115, 223, 0.15);
        border-bottom: 2px solid #1d3d9e;
        color: #fff;
    }

    .task-table td {
        padding: 1rem 0.75rem;
        vertical-align: middle;
    }

    .task-table tr {
        transition: all 0.2s ease;
    }

    .task-table tr:hover {
        background-color: rgba(78, 115, 223, 0.03);
    }

    [data-bs-theme="dark"] .task-table tr:hover {
        background-color: rgba(78, 115, 223, 0.08);
    }

    .task-title {
        font-weight: 600;
        color: #4e73df;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .task-title:hover {
        color: #224abe;
    }

    [data-bs-theme="dark"] .task-title {
        color: #6e93ff;
    }

    [data-bs-theme="dark"] .task-title:hover {
        color: #a6baff;
    }

    .badge {
        font-weight: 600;
        padding: 0.5em 0.8em;
        border-radius: 6px;
    }

    .badge-priority {
        display: inline-block;
        padding: 0.4em 0.8em;
        border-radius: 6px;
        font-weight: 600;
    }

    .action-buttons .btn {
        padding: 0.4rem 0.6rem;
        margin: 0 0.1rem;
    }

    .action-buttons .btn:hover {
        transform: translateY(-2px);
    }

    .table-success {
        background-color: rgba(28, 200, 138, 0.1) !important;
    }

    [data-bs-theme="dark"] .table-success {
        background-color: rgba(28, 200, 138, 0.15) !important;
    }

    .dropdown-menu {
        border: none;
        border-radius: 8px;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        overflow: hidden;
    }

    .dropdown-item {
        padding: 0.7rem 1.5rem;
        transition: all 0.2s ease;
    }

    .dropdown-item:hover {
        background-color: rgba(78, 115, 223, 0.1);
        padding-left: 1.8rem;
    }

    .dropdown-item i {
        width: 1.25rem;
        margin-right: 0.5rem;
        text-align: center;
    }

    .pagination {
        margin-bottom: 0;
    }

    .page-link {
        border: none;
        padding: 0.5rem 0.75rem;
        margin: 0 0.2rem;
        border-radius: 5px;
        color: #4e73df;
        transition: all 0.2s ease;
    }

    .page-link:hover {
        background-color: #4e73df;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.15);
    }

    .page-item.active .page-link {
        background-color: #4e73df;
        border-color: #4e73df;
        font-weight: 600;
    }
</style>

<!-- Page Header -->
<div class="page-header d-flex justify-content-between align-items-center">
    <h1 class="h3 mb-0 text-gray-800">
        <i class="fas fa-tasks me-2"></i> My Tasks
    </h1>
    <a href="tasks.php?action=create" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> New Task
    </a>
</div>

<!-- Filter and Search -->
<div class="card task-card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold"><i class="fas fa-filter me-2"></i>Filter Tasks</h6>
        <button class="btn btn-sm btn-light" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse"
            aria-expanded="true" aria-controls="filterCollapse">
            <i class="fas fa-chevron-down"></i>
        </button>
    </div>
    <div class="card-body collapse show" id="filterCollapse">
        <form method="get" action="tasks.php" class="filter-form">
            <div class="row">
                <!-- Status Filter -->
                <div class="col-md-2 mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="all" <?php echo $filters['status'] === 'all' ? 'selected' : ''; ?>>All</option>
                        <option value="pending" <?php echo $filters['status'] === 'pending' ? 'selected' : ''; ?>>Pending
                        </option>
                        <option value="completed" <?php echo $filters['status'] === 'completed' ? 'selected' : ''; ?>>
                            Completed</option>
                    </select>
                </div>

                <!-- Priority Filter -->
                <div class="col-md-2 mb-3">
                    <label for="priority" class="form-label">Priority</label>
                    <select class="form-select" id="priority" name="priority">
                        <option value="all" <?php echo $filters['priority'] === 'all' ? 'selected' : ''; ?>>All</option>
                        <option value="high" <?php echo $filters['priority'] === 'high' ? 'selected' : ''; ?>>High
                        </option>
                        <option value="medium" <?php echo $filters['priority'] === 'medium' ? 'selected' : ''; ?>>Medium
                        </option>
                        <option value="low" <?php echo $filters['priority'] === 'low' ? 'selected' : ''; ?>>Low</option>
                    </select>
                </div>

                <!-- Category Filter -->
                <div class="col-md-2 mb-3">
                    <label for="category_id" class="form-label">Category</label>
                    <select class="form-select" id="category_id" name="category_id">
                        <option value="0" <?php echo $filters['category_id'] == 0 ? 'selected' : ''; ?>>All</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo $category['id']; ?>" <?php echo $filters['category_id'] == $category['id'] ? 'selected' : ''; ?>
                                style="background-color: <?php echo $category['color']; ?>; color: white;">
                                <?php echo $category['name']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Sort By -->
                <div class="col-md-2 mb-3">
                    <label for="sort_by" class="form-label">Sort By</label>
                    <select class="form-select" id="sort_by" name="sort_by">
                        <option value="due_date_asc" <?php echo $filters['sort_by'] === 'due_date_asc' ? 'selected' : ''; ?>>Due Date (Asc)</option>
                        <option value="due_date_desc" <?php echo $filters['sort_by'] === 'due_date_desc' ? 'selected' : ''; ?>>Due Date (Desc)</option>
                        <option value="priority" <?php echo $filters['sort_by'] === 'priority' ? 'selected' : ''; ?>>
                            Priority</option>
                        <option value="title" <?php echo $filters['sort_by'] === 'title' ? 'selected' : ''; ?>>Title
                        </option>
                        <option value="created_asc" <?php echo $filters['sort_by'] === 'created_asc' ? 'selected' : ''; ?>>Created (Asc)</option>
                        <option value="created_desc" <?php echo $filters['sort_by'] === 'created_desc' ? 'selected' : ''; ?>>Created (Desc)</option>
                    </select>
                </div>

                <!-- Date Range From -->
                <div class="col-md-2 mb-3">
                    <label for="date_from" class="form-label">From Date</label>
                    <input type="text" class="form-control date-range-from" id="date_from" name="date_from"
                        value="<?php echo $filters['date_from']; ?>" placeholder="From">
                </div>

                <!-- Date Range To -->
                <div class="col-md-2 mb-3">
                    <label for="date_to" class="form-label">To Date</label>
                    <input type="text" class="form-control date-range-to" id="date_to" name="date_to"
                        value="<?php echo $filters['date_to']; ?>" placeholder="To">
                </div>

                <!-- Search -->
                <div class="col-md-8 mb-3">
                    <label for="search" class="form-label">Search</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" class="form-control" id="search" name="search"
                            value="<?php echo $filters['search']; ?>" placeholder="Search tasks...">
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="col-md-4 mb-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-filter me-1"></i> Apply Filters
                    </button>
                    <a href="tasks.php" class="btn btn-secondary">
                        <i class="fas fa-sync me-1"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Task List -->
<div class="card task-card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold"><i class="fas fa-list me-2"></i>Task List</h6>
        <div class="dropdown">
            <button class="btn btn-sm btn-outline-light dropdown-toggle" type="button" id="bulkActionsDropdown"
                data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-cog me-1"></i> Bulk Actions
            </button>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="bulkActionsDropdown">
                <li><a class="dropdown-item bulk-action" href="#" data-action="complete"><i class="fas fa-check"></i>
                        Mark as Completed</a></li>
                <li><a class="dropdown-item bulk-action" href="#" data-action="pending"><i class="fas fa-hourglass"></i>
                        Mark as Pending</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item bulk-action text-danger" href="#" data-action="delete"><i
                            class="fas fa-trash"></i> Delete Selected</a></li>
            </ul>
        </div>
    </div>
    <div class="card-body">
        <?php if (empty($tasks)): ?>
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i> No tasks found with the current filters. <a href="tasks.php">Clear
                    filters</a> or <a href="tasks.php?action=create">create a new task</a>.
            </div>
        <?php else: ?>
            <form id="bulkActionsForm" action="tasks.php?action=bulk" method="post">
                <div class="table-responsive">
                    <table class="table task-table mb-0">
                        <thead>
                            <tr>
                                <th width="30">
                                    <input type="checkbox" class="form-check-input" id="selectAll">
                                </th>
                                <th width="40"></th>
                                <th>Task</th>
                                <th width="180">Due Date</th>
                                <th width="120">Priority</th>
                                <th width="120">Category</th>
                                <th width="120">Status</th>
                                <th width="180">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tasks as $task): ?>
                                <tr class="<?php echo $task['status'] === 'completed' ? 'table-success' : ''; ?>"
                                    data-task-id="<?php echo $task['id']; ?>">
                                    <td>
                                        <input type="checkbox" class="form-check-input task-select" name="task_ids[]"
                                            value="<?php echo $task['id']; ?>" form="bulkActionsForm">
                                    </td>
                                    <td class="drag-handle text-center" style="cursor: move">
                                        <i class="fas fa-grip-vertical text-muted"></i>
                                    </td>
                                    <td>
                                        <a href="tasks.php?action=view&id=<?php echo $task['id']; ?>" class="task-title">
                                            <?php echo htmlspecialchars($task['title']); ?>
                                        </a>
                                        <?php if (!empty($task['description'])): ?>
                                            <small class="d-block text-muted mt-1">
                                                <?php echo mb_strimwidth(htmlspecialchars($task['description']), 0, 60, '...'); ?>
                                            </small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($task['due_date'])): ?>
                                            <?php
                                            $dueDate = new DateTime($task['due_date']);
                                            $today = new DateTime();
                                            $tomorrow = new DateTime('tomorrow');
                                            $yesterday = new DateTime('yesterday');

                                            if ($dueDate->format('Y-m-d') === $today->format('Y-m-d')) {
                                                echo '<span class="badge bg-primary"><i class="fas fa-clock me-1"></i>Today</span> ' . $dueDate->format('g:i A');
                                            } else if ($dueDate->format('Y-m-d') === $tomorrow->format('Y-m-d')) {
                                                echo '<span class="badge bg-info"><i class="fas fa-calendar-day me-1"></i>Tomorrow</span> ' . $dueDate->format('g:i A');
                                            } else if ($dueDate->format('Y-m-d') === $yesterday->format('Y-m-d')) {
                                                echo '<span class="badge bg-danger"><i class="fas fa-exclamation-circle me-1"></i>Yesterday</span> ' . $dueDate->format('g:i A');
                                            } else if ($dueDate < $today) {
                                                echo '<span class="badge bg-danger"><i class="fas fa-exclamation-circle me-1"></i>' . $dueDate->format('M d') . '</span> ' . $dueDate->format('g:i A');
                                            } else {
                                                echo '<span class="badge bg-secondary"><i class="fas fa-calendar me-1"></i>' . $dueDate->format('M d') . '</span> ' . $dueDate->format('g:i A');
                                            }
                                            ?>
                                        <?php else: ?>
                                            <span class="text-muted"><i class="fas fa-minus"></i> No due date</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php
                                        $priorityClass = getPriorityClass($task['priority']);
                                        $priorityIcon = '';

                                        if ($task['priority'] === 'high') {
                                            $priorityIcon = '<i class="fas fa-arrow-up me-1"></i>';
                                        } else if ($task['priority'] === 'medium') {
                                            $priorityIcon = '<i class="fas fa-equals me-1"></i>';
                                        } else {
                                            $priorityIcon = '<i class="fas fa-arrow-down me-1"></i>';
                                        }
                                        ?>
                                        <span class="badge-priority bg-<?php echo $priorityClass; ?>">
                                            <?php echo $priorityIcon . ucfirst($task['priority']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if (!empty($task['category_name'])): ?>
                                            <span class="badge" style="background-color: <?php echo $task['category_color']; ?>">
                                                <i class="fas fa-tag me-1"></i><?php echo $task['category_name']; ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="text-muted"><i class="fas fa-minus"></i> None</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="tasks.php?action=toggle&id=<?php echo $task['id']; ?>" class="toggle-status"
                                            data-id="<?php echo $task['id']; ?>">
                                            <?php if ($task['status'] === 'completed'): ?>
                                                <span class="badge bg-success"><i class="fas fa-check me-1"></i>Completed</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning"><i class="fas fa-hourglass me-1"></i>Pending</span>
                                            <?php endif; ?>
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <div class="action-buttons">
                                            <a href="tasks.php?action=view&id=<?php echo $task['id']; ?>"
                                                class="btn btn-sm btn-info" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="tasks.php?action=edit&id=<?php echo $task['id']; ?>"
                                                class="btn btn-sm btn-primary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="tasks.php?action=delete&id=<?php echo $task['id']; ?>"
                                                class="btn btn-sm btn-danger delete-task" title="Delete"
                                                data-id="<?php echo $task['id']; ?>">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </form>

            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
                <div class="d-flex justify-content-center mt-4">
                    <?php
                    $urlPattern = "tasks.php?page=%d";
                    // Add any active filters to the pagination URLs
                    foreach ($filters as $key => $value) {
                        if ($key !== 'page' && $key !== 'limit' && !empty($value)) {
                            $urlPattern .= "&{$key}=" . urlencode($value);
                        }
                    }

                    echo generatePagination($page, $totalPages, $urlPattern);
                    ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<!-- Delete Task Confirmation Modal -->
<div class="modal fade" id="deleteTaskModal" tabindex="-1" aria-labelledby="deleteTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteTaskModalLabel"><i
                        class="fas fa-exclamation-triangle me-2"></i>Confirm Delete Task</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this task? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <a href="#" class="btn btn-danger" id="confirmDeleteTask"><i class="fas fa-trash me-1"></i>Delete</a>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Actions Confirmation Modal -->
<div class="modal fade" id="bulkActionModal" tabindex="-1" aria-labelledby="bulkActionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="bulkActionModalLabel"><i class="fas fa-cog me-2"></i>Confirm Bulk Action
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="bulkActionMessage">Are you sure you want to perform this action on the selected tasks?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmBulkAction"><i
                        class="fas fa-check me-1"></i>Confirm</button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for Drag and Drop functionality -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        // Task deletion confirmation
        document.querySelectorAll('.delete-task').forEach(function (button) {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                const taskId = this.dataset.id;
                const deleteModal = new bootstrap.Modal(document.getElementById('deleteTaskModal'));
                const confirmDelete = document.getElementById('confirmDeleteTask');

                confirmDelete.href = `tasks.php?action=delete&id=${taskId}`;
                deleteModal.show();
            });
        });

        // Select all tasks
        document.getElementById('selectAll')?.addEventListener('change', function () {
            const isChecked = this.checked;
            document.querySelectorAll('.task-select').forEach(function (checkbox) {
                checkbox.checked = isChecked;
            });
        });

        // Bulk actions
        document.querySelectorAll('.bulk-action').forEach(function (action) {
            action.addEventListener('click', function (e) {
                e.preventDefault();

                const selectedTasks = document.querySelectorAll('.task-select:checked');

                if (selectedTasks.length === 0) {
                    alert('Please select at least one task.');
                    return;
                }

                const actionType = this.dataset.action;
                const form = document.getElementById('bulkActionsForm');
                const bulkActionModal = new bootstrap.Modal(document.getElementById('bulkActionModal'));
                const confirmBulkAction = document.getElementById('confirmBulkAction');
                const bulkActionMessage = document.getElementById('bulkActionMessage');

                // Set action type
                const actionInput = document.createElement('input');
                actionInput.type = 'hidden';
                actionInput.name = 'action_type';
                actionInput.value = actionType;

                // Remove any existing action_type input
                const existingAction = form.querySelector('input[name="action_type"]');
                if (existingAction) {
                    form.removeChild(existingAction);
                }

                form.appendChild(actionInput);

                // Set modal message based on action
                if (actionType === 'complete') {
                    bulkActionMessage.innerHTML = `Are you sure you want to mark <strong>${selectedTasks.length}</strong> selected task(s) as completed?`;
                    confirmBulkAction.className = 'btn btn-success';
                    confirmBulkAction.innerHTML = '<i class="fas fa-check me-1"></i>Mark as Completed';
                } else if (actionType === 'pending') {
                    bulkActionMessage.innerHTML = `Are you sure you want to mark <strong>${selectedTasks.length}</strong> selected task(s) as pending?`;
                    confirmBulkAction.className = 'btn btn-warning';
                    confirmBulkAction.innerHTML = '<i class="fas fa-hourglass me-1"></i>Mark as Pending';
                } else if (actionType === 'delete') {
                    bulkActionMessage.innerHTML = `Are you sure you want to delete <strong>${selectedTasks.length}</strong> selected task(s)? This action cannot be undone.`;
                    confirmBulkAction.className = 'btn btn-danger';
                    confirmBulkAction.innerHTML = '<i class="fas fa-trash me-1"></i>Delete';
                }

                // Handle confirmation
                confirmBulkAction.onclick = function () {
                    form.submit();
                };

                bulkActionModal.show();
            });
        });

        // Get the task table body
        const taskTableBody = document.querySelector('.task-table tbody');

        if (taskTableBody) {
            // Initialize Sortable
            const sortable = new Sortable(taskTableBody, {
                animation: 150,
                handle: '.drag-handle',
                ghostClass: 'sortable-ghost',
                chosenClass: 'sortable-chosen',
                dragClass: 'sortable-drag',
                onEnd: function (evt) {
                    const taskId = evt.item.dataset.taskId;
                    const newPosition = evt.newIndex;
                    const oldPosition = evt.oldIndex;

                    if (newPosition !== oldPosition) {
                        // Show notification
                        showNotification(`Task moved from position ${oldPosition + 1} to ${newPosition + 1}`);

                        // Update the task priority based on position
                        updateTaskPriority(taskId, getNewPriority(newPosition));
                    }
                }
            });
        }

        // Function to update a task's priority
        function updateTaskPriority(taskId, priority) {
            fetch(`tasks.php?action=update_priority&id=${taskId}&priority=${priority}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification(data.message);
                    } else {
                        showNotification(data.message, 'danger');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('An error occurred while updating the task priority', 'danger');
                });
        }

        // Determine the new priority based on position
        function getNewPriority(position) {
            const totalTasks = document.querySelectorAll('.task-table tbody tr').length;

            // Upper third is high priority
            if (position < totalTasks / 3) {
                return 'high';
            }
            // Middle third is medium priority
            else if (position < (totalTasks * 2) / 3) {
                return 'medium';
            }
            // Lower third is low priority
            else {
                return 'low';
            }
        }

        // Function to show notification
        function showNotification(message, type = 'success') {
            const notificationContainer = document.createElement('div');
            notificationContainer.className = `alert alert-${type} alert-dismissible fade show position-fixed bottom-0 end-0 m-3`;
            notificationContainer.setAttribute('role', 'alert');
            notificationContainer.style.zIndex = '9999';
            notificationContainer.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;

            document.body.appendChild(notificationContainer);

            // Auto-remove after 3 seconds
            setTimeout(() => {
                notificationContainer.classList.remove('show');
                setTimeout(() => {
                    notificationContainer.remove();
                }, 150);
            }, 3000);
        }
    });
</script>

<?php
// Removed footer include to prevent duplication
?>