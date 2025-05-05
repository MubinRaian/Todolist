<?php
// views/tasks/view.php
// Task detail view

$pageTitle = 'View Task';
// Removed header include to prevent duplication
?>

<style>
    /* Task View specific styles */
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
        padding: 1.5rem;
    }

    .task-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .task-actions .btn {
        transition: all 0.2s ease;
    }

    .task-actions .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .btn-done {
        background: linear-gradient(45deg, #1cc88a, #169a6f);
        border: none;
        padding: 0.8rem 1.5rem;
        color: white;
        font-weight: 600;
        border-radius: 6px;
    }

    .btn-done:hover {
        background: linear-gradient(45deg, #169a6f, #0f8a5f);
    }

    .btn-pending {
        background: linear-gradient(45deg, #f6c23e, #d9a520);
        border: none;
        padding: 0.8rem 1.5rem;
        color: white;
        font-weight: 600;
        border-radius: 6px;
    }

    .btn-pending:hover {
        background: linear-gradient(45deg, #d9a520, #c18e18);
    }

    .task-info-section {
        margin-bottom: 1.5rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }

    [data-bs-theme="dark"] .task-info-section {
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }

    .task-info-section:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }

    .task-info-label {
        font-weight: 700;
        color: #555;
        margin-bottom: 0.5rem;
    }

    [data-bs-theme="dark"] .task-info-label {
        color: #ccd;
    }

    .task-description {
        background-color: rgba(78, 115, 223, 0.05);
        padding: 1.25rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
    }

    [data-bs-theme="dark"] .task-description {
        background-color: rgba(78, 115, 223, 0.08);
    }

    .status-indicator {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        transition: all 0.3s ease;
    }

    .status-completed {
        background: linear-gradient(45deg, rgba(28, 200, 138, 0.2), rgba(28, 200, 138, 0.1));
        border: 3px solid rgba(28, 200, 138, 0.6);
    }

    .status-pending {
        background: linear-gradient(45deg, rgba(246, 194, 62, 0.2), rgba(246, 194, 62, 0.1));
        border: 3px solid rgba(246, 194, 62, 0.6);
    }

    .status-icon {
        font-size: 3.5rem;
    }

    .status-text {
        text-align: center;
        font-weight: 700;
        font-size: 1.25rem;
        margin-bottom: 1.5rem;
    }

    .comment-form {
        background-color: rgba(78, 115, 223, 0.03);
        padding: 1.25rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
    }

    [data-bs-theme="dark"] .comment-form {
        background-color: rgba(78, 115, 223, 0.08);
    }

    .quick-action-btn {
        margin-bottom: 1rem;
        padding: 0.8rem;
        width: 100%;
        font-weight: 600;
        border-radius: 6px;
        transition: all 0.2s ease;
    }

    .quick-action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .info-icon {
        width: 1.5rem;
        text-align: center;
        margin-right: 0.5rem;
    }

    .task-meta {
        display: flex;
        align-items: center;
        margin-bottom: 0.5rem;
    }

    .badge {
        font-weight: 600;
        padding: 0.5em 0.8em;
        border-radius: 6px;
    }
</style>

<!-- Page Header -->
<div class="page-header d-flex justify-content-between align-items-center">
    <h1 class="h3 mb-0 text-gray-800">
        <i class="fas fa-clipboard-list me-2"></i> Task Details
    </h1>
    <a href="tasks.php" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i> Back to Tasks
    </a>
</div>

<div class="row">
    <!-- Task Details -->
    <div class="col-lg-8">
        <div class="card task-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold">
                    <?php echo htmlspecialchars($task['title']); ?>
                    <?php if ($task['status'] === 'completed'): ?>
                        <span class="badge bg-success ms-2"><i class="fas fa-check me-1"></i>Completed</span>
                    <?php else: ?>
                        <span class="badge bg-warning ms-2"><i class="fas fa-hourglass me-1"></i>Pending</span>
                    <?php endif; ?>
                </h6>
                <div class="task-actions">
                    <a href="tasks.php?action=edit&id=<?php echo $task['id']; ?>" class="btn btn-sm btn-primary">
                        <i class="fas fa-edit me-1"></i> Edit
                    </a>
                    <a href="#" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteTaskModal">
                        <i class="fas fa-trash me-1"></i> Delete
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- Quick Actions -->
                <div class="task-actions mb-4">
                    <?php if ($task['status'] === 'pending'): ?>
                        <a href="tasks.php?action=toggle&id=<?php echo $task['id']; ?>" class="btn btn-done">
                            <i class="fas fa-check-circle me-2"></i> Mark as Completed
                        </a>
                    <?php else: ?>
                        <a href="tasks.php?action=toggle&id=<?php echo $task['id']; ?>" class="btn btn-pending">
                            <i class="fas fa-undo me-2"></i> Mark as Pending
                        </a>
                    <?php endif; ?>

                    <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#shareTaskModal">
                        <i class="fas fa-share-alt me-1"></i> Share
                    </button>

                    <a href="tasks.php?action=undo&id=<?php echo $task['id']; ?>" class="btn btn-secondary">
                        <i class="fas fa-undo me-1"></i> Undo Last Action
                    </a>
                </div>

                <?php if (!empty($task['description'])): ?>
                    <div class="task-description">
                        <h6 class="task-info-label"><i class="fas fa-align-left me-2"></i>Description</h6>
                        <p class="mb-0"><?php echo nl2br(htmlspecialchars($task['description'])); ?></p>
                    </div>
                <?php endif; ?>

                <div class="task-info-section">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="task-info-label"><i class="fas fa-calendar-alt me-2"></i>Due Date</h6>
                            <?php if (!empty($task['due_date'])): ?>
                                <div class="task-meta">
                                    <i class="fas fa-calendar info-icon text-primary"></i>
                                    <?php echo date('F j, Y', strtotime($task['due_date'])); ?>
                                </div>
                                <div class="task-meta">
                                    <i class="fas fa-clock info-icon text-primary"></i>
                                    <?php echo date('g:i A', strtotime($task['due_date'])); ?>
                                </div>
                            <?php else: ?>
                                <p class="text-muted"><i class="fas fa-minus me-1"></i> No due date set</p>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-6">
                            <h6 class="task-info-label"><i class="fas fa-flag me-2"></i>Priority</h6>
                            <?php
                            $priorityClass = getPriorityClass($task['priority']);
                            $priorityText = ucfirst($task['priority']);
                            $priorityIcon = '';

                            if ($task['priority'] === 'high') {
                                $priorityIcon = '<i class="fas fa-arrow-up me-1"></i>';
                            } else if ($task['priority'] === 'medium') {
                                $priorityIcon = '<i class="fas fa-equals me-1"></i>';
                            } else {
                                $priorityIcon = '<i class="fas fa-arrow-down me-1"></i>';
                            }
                            ?>
                            <span class="badge bg-<?php echo $priorityClass; ?>">
                                <?php echo $priorityIcon . $priorityText; ?>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="task-info-section">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="task-info-label"><i class="fas fa-tag me-2"></i>Category</h6>
                            <?php if (!empty($task['category_name'])): ?>
                                <span class="badge" style="background-color: <?php echo $task['category_color']; ?>">
                                    <i class="fas fa-tag me-1"></i><?php echo $task['category_name']; ?>
                                </span>
                            <?php else: ?>
                                <p class="text-muted"><i class="fas fa-minus me-1"></i> No category</p>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-6">
                            <h6 class="task-info-label"><i class="fas fa-calendar-plus me-2"></i>Created</h6>
                            <div class="task-meta">
                                <i class="fas fa-calendar-plus info-icon text-info"></i>
                                <?php echo date('F j, Y', strtotime($task['created_at'])); ?>
                            </div>
                            <div class="task-meta">
                                <i class="fas fa-clock info-icon text-info"></i>
                                <?php echo date('g:i A', strtotime($task['created_at'])); ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Task Timeline -->
                <div class="task-info-section">
                    <h6 class="task-info-label"><i class="fas fa-history me-2"></i>Task History</h6>
                    <div class="timeline">
                        <div class="timeline-item">
                            <i class="fas fa-plus-circle text-success me-2"></i>
                            Created on <?php echo date('F j, Y', strtotime($task['created_at'])); ?> at
                            <?php echo date('g:i A', strtotime($task['created_at'])); ?>
                        </div>
                        <?php if ($task['updated_at'] && $task['updated_at'] !== $task['created_at']): ?>
                            <div class="timeline-item mt-2">
                                <i class="fas fa-edit text-primary me-2"></i>
                                Last updated on <?php echo date('F j, Y', strtotime($task['updated_at'])); ?> at
                                <?php echo date('g:i A', strtotime($task['updated_at'])); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if ($task['status'] === 'pending' && !empty($task['due_date'])): ?>
                    <?php
                    $dueDate = new DateTime($task['due_date']);
                    $now = new DateTime();
                    $interval = $now->diff($dueDate);
                    $isPast = $dueDate < $now;
                    ?>

                    <div class="alert <?php echo $isPast ? 'alert-danger' : 'alert-info'; ?> d-flex align-items-center">
                        <?php if ($isPast): ?>
                            <i class="fas fa-exclamation-triangle fs-4 me-3"></i>
                            <div>
                                This task is <strong>overdue</strong> by
                                <?php
                                if ($interval->days > 0) {
                                    echo $interval->days . ' day' . ($interval->days > 1 ? 's' : '');
                                } else {
                                    echo $interval->h . ' hour' . ($interval->h > 1 ? 's' : '');
                                }
                                ?>
                            </div>
                        <?php else: ?>
                            <i class="fas fa-info-circle fs-4 me-3"></i>
                            <div>
                                This task is due in
                                <?php
                                if ($interval->days > 0) {
                                    echo $interval->days . ' day' . ($interval->days > 1 ? 's' : '');
                                } else {
                                    echo $interval->h . ' hour' . ($interval->h > 1 ? 's' : '');
                                }
                                ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Comments Section -->
        <div class="card task-card">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold"><i class="fas fa-comments me-2"></i>Comments</h6>
            </div>
            <div class="card-body">
                <!-- Comment Form -->
                <form action="tasks.php?action=add_comment&id=<?php echo $task['id']; ?>" method="post"
                    class="comment-form mb-4">
                    <div class="mb-3">
                        <textarea class="form-control" name="comment" rows="3"
                            placeholder="Add a comment..."></textarea>
                    </div>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-comment me-1"></i> Add Comment
                        </button>
                    </div>
                </form>

                <!-- Comments List -->
                <div class="comments-list">
                    <?php
                    // Get comments
                    $comments = Task::getTaskComments($task['id']);

                    if (empty($comments)) {
                        echo '<div class="alert alert-info d-flex align-items-center">
                            <i class="fas fa-info-circle fs-4 me-3"></i>
                            <div>No comments yet. Be the first to add a comment!</div>
                        </div>';
                    } else {
                        foreach ($comments as $comment) {
                            echo '<div class="card mb-3">
                                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fas fa-user-circle me-2"></i> 
                                        <strong>' . htmlspecialchars($comment['username']) . '</strong>
                                    </div>
                                    <small class="text-muted">' . timeAgo($comment['created_at']) . '</small>
                                </div>
                                <div class="card-body">
                                    ' . nl2br(htmlspecialchars($comment['comment'])) . '
                                </div>
                            </div>';
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Task Status Card -->
        <div class="card task-card">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold"><i class="fas fa-chart-pie me-2"></i>Task Status</h6>
            </div>
            <div class="card-body text-center">
                <?php if ($task['status'] === 'completed'): ?>
                    <div class="status-indicator status-completed">
                        <i class="fas fa-check-circle status-icon text-success"></i>
                    </div>
                    <div class="status-text text-success">Completed</div>
                    <a href="tasks.php?action=toggle&id=<?php echo $task['id']; ?>"
                        class="btn btn-pending quick-action-btn">
                        <i class="fas fa-undo me-2"></i> Mark as Pending
                    </a>
                <?php else: ?>
                    <div class="status-indicator status-pending">
                        <i class="fas fa-hourglass-half status-icon text-warning"></i>
                    </div>
                    <div class="status-text text-warning">Pending</div>
                    <a href="tasks.php?action=toggle&id=<?php echo $task['id']; ?>" class="btn btn-done quick-action-btn">
                        <i class="fas fa-check-circle me-2"></i> Mark as Done
                    </a>
                <?php endif; ?>

                <div class="d-grid gap-2 mt-3">
                    <a href="tasks.php?action=edit&id=<?php echo $task['id']; ?>"
                        class="btn btn-primary quick-action-btn">
                        <i class="fas fa-edit me-2"></i> Edit Task
                    </a>
                    <a href="#" class="btn btn-danger quick-action-btn" data-bs-toggle="modal"
                        data-bs-target="#deleteTaskModal">
                        <i class="fas fa-trash me-2"></i> Delete Task
                    </a>
                </div>
            </div>
        </div>

        <!-- Related Tasks -->
        <div class="card task-card">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold"><i class="fas fa-link me-2"></i>Related Tasks</h6>
            </div>
            <div class="card-body">
                <?php
                // This would normally fetch related tasks from the database
                // For now, we'll just display a placeholder
                ?>
                <div class="alert alert-info d-flex align-items-center">
                    <i class="fas fa-info-circle fs-4 me-3"></i>
                    <div>No related tasks found.</div>
                </div>

                <div class="d-grid gap-2 mt-3">
                    <a href="tasks.php?action=create" class="btn btn-success">
                        <i class="fas fa-plus me-1"></i> Create New Task
                    </a>
                </div>
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
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
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

<!-- Share Task Modal -->
<div class="modal fade" id="shareTaskModal" tabindex="-1" aria-labelledby="shareTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="shareTaskModalLabel">
                    <i class="fas fa-share-alt me-2"></i>Share Task
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label for="shareEmail" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="shareEmail" placeholder="Enter email address">
                    </div>
                    <div class="mb-3">
                        <label for="shareMessage" class="form-label">Message (Optional)</label>
                        <textarea class="form-control" id="shareMessage" rows="3"
                            placeholder="Add a personal message..."></textarea>
                    </div>
                </form>

                <div class="d-flex align-items-center mt-3 pt-3 border-top">
                    <div class="me-3">
                        <strong>Or share via:</strong>
                    </div>
                    <div class="social-share-buttons">
                        <button class="btn btn-sm btn-primary me-1"><i class="fab fa-facebook-f"></i></button>
                        <button class="btn btn-sm btn-info me-1"><i class="fab fa-twitter"></i></button>
                        <button class="btn btn-sm btn-success me-1"><i class="fab fa-whatsapp"></i></button>
                        <button class="btn btn-sm btn-secondary"><i class="fas fa-envelope"></i></button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-info">
                    <i class="fas fa-paper-plane me-1"></i> Share
                </button>
            </div>
        </div>
    </div>
</div>

<?php
// Removed footer include to prevent duplication
?>