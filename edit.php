<?php
// views/tasks/edit.php
// Edit task form

$pageTitle = 'Edit Task';
// Removed header include to prevent duplication
?>

<style>
    /* Task Edit specific styles */
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
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    
    .task-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 0.5rem 2rem rgba(33, 40, 50, 0.25);
    }
    
    .card-header {
        background: linear-gradient(45deg, #4e73df, #224abe);
        color: white;
        padding: 1rem 1.5rem;
        font-weight: 600;
        border: none;
    }
    
    .card-body {
        padding: 1.8rem;
    }
    
    .form-label {
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #555;
    }
    
    [data-bs-theme="dark"] .form-label {
        color: #ccd;
    }
    
    .input-group-text {
        background-color: #f8f9fc;
        border-right: none;
    }
    
    [data-bs-theme="dark"] .input-group-text {
        background-color: #333;
        color: #ccd;
    }
    
    .form-control, .form-select {
        padding: 0.7rem 1rem;
        border-radius: 6px;
        transition: all 0.2s ease;
    }
    
    .input-group .form-control {
        border-left: none;
    }
    
    .input-group .form-control:focus + .input-group-text {
        border-color: #bac8f3;
    }
    
    .btn-primary, .btn-secondary, .btn-danger {
        padding: 0.6rem 1.25rem;
        font-weight: 600;
        border-radius: 6px;
        transition: all 0.2s ease;
    }
    
    .btn-primary:hover, .btn-secondary:hover, .btn-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
    
    .btn-primary {
        background: linear-gradient(45deg, #4e73df, #224abe);
        border: none;
    }
    
    .btn-primary:hover {
        background: linear-gradient(45deg, #4668c5, #1d3d9e);
    }
    
    .btn-danger {
        background: linear-gradient(45deg, #e74a3b, #be392d);
        border: none;
    }
    
    .btn-danger:hover {
        background: linear-gradient(45deg, #d44639, #ad352a);
    }
    
    .task-history {
        background-color: rgba(78, 115, 223, 0.05);
        padding: 1.5rem;
        border-radius: 8px;
    }
    
    [data-bs-theme="dark"] .task-history {
        background-color: rgba(78, 115, 223, 0.1);
    }
    
    .history-item {
        display: flex;
        align-items: center;
        margin-bottom: 0.5rem;
    }
    
    .history-icon {
        width: 2rem;
        height: 2rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
    }
    
    .history-icon-created {
        background-color: rgba(28, 200, 138, 0.1);
        color: #1cc88a;
    }
    
    .history-icon-updated {
        background-color: rgba(78, 115, 223, 0.1);
        color: #4e73df;
    }
    
    .status-select {
        position: relative;
    }
    
    .status-pending, .status-completed {
        display: flex;
        align-items: center;
    }
    
    .status-pending::before, .status-completed::before {
        content: '';
        display: inline-block;
        width: 0.75rem;
        height: 0.75rem;
        border-radius: 50%;
        margin-right: 0.5rem;
    }
    
    .status-pending::before {
        background-color: #f6c23e;
    }
    
    .status-completed::before {
        background-color: #1cc88a;
    }
    
    /* Priority styling */
    .priority-low, .priority-medium, .priority-high {
        display: flex;
        align-items: center;
    }
    
    .priority-low::before, .priority-medium::before, .priority-high::before {
        content: '';
        display: inline-block;
        width: 0.75rem;
        height: 0.75rem;
        border-radius: 50%;
        margin-right: 0.5rem;
    }
    
    .priority-high::before {
        background-color: #e74a3b;
    }
    
    .priority-medium::before {
        background-color: #f6c23e;
    }
    
    .priority-low::before {
        background-color: #1cc88a;
    }
</style>

<!-- Page Header -->
<div class="page-header d-flex justify-content-between align-items-center">
    <h1 class="h3 mb-0 text-gray-800">
        <i class="fas fa-edit me-2"></i> Edit Task
    </h1>
    <a href="tasks.php" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i> Back to Tasks
    </a>
</div>

<div class="card task-card">
    <div class="card-header">
        <h6 class="m-0 font-weight-bold"><i class="fas fa-clipboard-list me-2"></i>Task Details</h6>
    </div>
    <div class="card-body">
        <form action="tasks.php?action=update&id=<?php echo $task['id']; ?>" method="post" id="editTaskForm">
            <div class="row">
                <div class="col-md-8">
                    <div class="mb-4">
                        <label for="title" class="form-label">Task Title <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-heading"></i></span>
                            <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($task['title']); ?>" required>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="description" class="form-label">Description</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-align-left"></i></span>
                            <textarea class="form-control" id="description" name="description" rows="5"><?php echo htmlspecialchars($task['description']); ?></textarea>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="mb-4">
                        <label for="due_date" class="form-label">Due Date</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                            <input type="text" class="form-control date-picker" id="due_date" name="due_date" 
                                   value="<?php echo $task['due_date'] ? date('Y-m-d H:i', strtotime($task['due_date'])) : ''; ?>">
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="priority" class="form-label">Priority</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-flag"></i></span>
                            <select class="form-select" id="priority" name="priority">
                                <option value="low" class="priority-low" <?php echo $task['priority'] === 'low' ? 'selected' : ''; ?>>Low</option>
                                <option value="medium" class="priority-medium" <?php echo $task['priority'] === 'medium' ? 'selected' : ''; ?>>Medium</option>
                                <option value="high" class="priority-high" <?php echo $task['priority'] === 'high' ? 'selected' : ''; ?>>High</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="status" class="form-label">Status</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-tasks"></i></span>
                            <select class="form-select" id="status" name="status">
                                <option value="pending" class="status-pending" <?php echo $task['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                <option value="completed" class="status-completed" <?php echo $task['status'] === 'completed' ? 'selected' : ''; ?>>Completed</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="category_id" class="form-label">Category</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-tag"></i></span>
                            <select class="form-select" id="category_id" name="category_id">
                                <option value="0">None</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?php echo $category['id']; ?>" 
                                            <?php echo $task['category_id'] == $category['id'] ? 'selected' : ''; ?>
                                            style="background-color: <?php echo $category['color']; ?>; color: white;">
                                        <?php echo $category['name']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                <a href="tasks.php?action=view&id=<?php echo $task['id']; ?>" class="btn btn-secondary me-md-2">
                    <i class="fas fa-times me-1"></i> Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Update Task
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card task-card">
    <div class="card-header">
        <h6 class="m-0 font-weight-bold"><i class="fas fa-history me-2"></i>Task History</h6>
    </div>
    <div class="card-body">
        <div class="task-history">
            <div class="history-item">
                <div class="history-icon history-icon-created">
                    <i class="fas fa-plus"></i>
                </div>
                <div>
                    <strong>Created</strong> on <?php echo date('F j, Y, g:i a', strtotime($task['created_at'])); ?>
                </div>
            </div>
            
            <?php if ($task['updated_at'] !== $task['created_at']): ?>
                <div class="history-item">
                    <div class="history-icon history-icon-updated">
                        <i class="fas fa-edit"></i>
                    </div>
                    <div>
                        <strong>Last updated</strong> on <?php echo date('F j, Y, g:i a', strtotime($task['updated_at'])); ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="row mt-4">
            <div class="col-md-6">
                <a href="tasks.php?action=view&id=<?php echo $task['id']; ?>" class="btn btn-primary w-100">
                    <i class="fas fa-eye me-1"></i> View Task
                </a>
            </div>
            <div class="col-md-6">
                <a href="#" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#deleteTaskModal">
                    <i class="fas fa-trash me-1"></i> Delete Task
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Delete Task Confirmation Modal -->
<div class="modal fade" id="deleteTaskModal" tabindex="-1" aria-labelledby="deleteTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteTaskModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i>Confirm Delete
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this task? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <a href="tasks.php?action=delete&id=<?php echo $task['id']; ?>" class="btn btn-danger">
                    <i class="fas fa-trash me-1"></i> Delete
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    // Form validation
    document.getElementById('editTaskForm').addEventListener('submit', function(event) {
        const title = document.getElementById('title').value.trim();
        
        if (title === '') {
            event.preventDefault();
            alert('Task title is required.');
            document.getElementById('title').focus();
        }
    });
    
    // Update task status when a user selects the "Completed" status
    document.getElementById('status').addEventListener('change', function() {
        if (this.value === 'completed') {
            // Visual feedback that the task is being marked as complete
            document.getElementById('status').closest('.input-group').classList.add('border-success');
            
            // Optional: change other UI elements to reflect completed state
        } else {
            document.getElementById('status').closest('.input-group').classList.remove('border-success');
        }
    });
</script>

<?php 
// Removed footer include to prevent duplication
?>