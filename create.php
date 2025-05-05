<?php
// views/tasks/create.php
// Create task form

$pageTitle = 'Create New Task';
// Removed header include to prevent duplication
?>

<style>
    /* Task creation page specific styles */
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
        border-top-left-radius: 10px !important;
        border-top-right-radius: 10px !important;
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

    .form-control,
    .form-select {
        padding: 0.7rem 1rem;
        border-radius: 6px;
        transition: all 0.2s ease;
    }

    .input-group .form-control {
        border-left: none;
    }

    .input-group .form-control:focus+.input-group-text {
        border-color: #bac8f3;
    }

    .btn-primary,
    .btn-secondary {
        padding: 0.6rem 1.25rem;
        font-weight: 600;
        border-radius: 6px;
        transition: all 0.2s ease;
    }

    .btn-primary:hover,
    .btn-secondary:hover {
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

    .category-section {
        background: rgba(78, 115, 223, 0.05);
        border-radius: 10px;
        padding: 1.5rem;
        margin-top: 1.5rem;
    }

    [data-bs-theme="dark"] .category-section {
        background: rgba(78, 115, 223, 0.1);
    }
</style>

<!-- Page Header -->
<div class="page-header d-flex justify-content-between align-items-center">
    <h1 class="h3 mb-0 text-gray-800">
        <i class="fas fa-plus-circle"></i> Create New Task
    </h1>
    <a href="tasks.php" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to Tasks
    </a>
</div>

<!-- Task Creation Card -->
<div class="card task-card mb-4">
    <div class="card-header">
        <h6 class="m-0 font-weight-bold"><i class="fas fa-clipboard-list me-2"></i>Task Details</h6>
    </div>
    <div class="card-body">
        <form action="tasks.php?action=store" method="post" id="createTaskForm">
            <div class="row">
                <div class="col-md-8">
                    <div class="mb-4">
                        <label for="title" class="form-label">Task Title <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-heading"></i></span>
                            <input type="text" class="form-control" id="title" name="title"
                                placeholder="Enter task title" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="description" class="form-label">Description</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-align-left"></i></span>
                            <textarea class="form-control" id="description" name="description" rows="5"
                                placeholder="Enter task description"></textarea>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-4">
                        <label for="due_date" class="form-label">Due Date</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                            <input type="text" class="form-control date-picker" id="due_date" name="due_date"
                                placeholder="Select due date and time">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="priority" class="form-label">Priority</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-flag"></i></span>
                            <select class="form-select" id="priority" name="priority">
                                <option value="low">Low</option>
                                <option value="medium" selected>Medium</option>
                                <option value="high">High</option>
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
                <button type="reset" class="btn btn-secondary me-md-2">
                    <i class="fas fa-undo me-1"></i> Reset
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Create Task
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Category Management Card -->
<div class="category-section">
    <div class="card task-card mb-0">
        <div class="card-header">
            <h6 class="m-0 font-weight-bold"><i class="fas fa-tags me-2"></i>Category Management</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Category Form -->
                <div class="col-md-6">
                    <form action="categories.php?action=store" method="post" id="createCategoryForm">
                        <div class="mb-3">
                            <label for="category_name" class="form-label">New Category</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-tag"></i></span>
                                <input type="text" class="form-control" id="category_name" name="name"
                                    placeholder="Enter category name" required>
                                <input type="color" class="form-control form-control-color" id="category_color"
                                    name="color" value="#3498db" title="Choose your color">
                                <button class="btn btn-primary" type="submit"><i class="fas fa-plus me-1"></i>
                                    Add</button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Category List -->
                <div class="col-md-6">
                    <label class="form-label">Your Categories</label>
                    <div class="list-group shadow-sm">
                        <?php foreach ($categories as $category): ?>
                            <div
                                class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="badge me-2"
                                        style="background-color: <?php echo $category['color']; ?>">&nbsp;&nbsp;&nbsp;</span>
                                    <?php echo $category['name']; ?>
                                </div>
                                <div class="btn-group btn-group-sm">
                                    <a href="categories.php?action=edit&id=<?php echo $category['id']; ?>"
                                        class="btn btn-sm btn-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="categories.php?action=delete&id=<?php echo $category['id']; ?>"
                                        class="btn btn-sm btn-danger delete-category"
                                        data-id="<?php echo $category['id']; ?>" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Category Confirmation Modal -->
<div class="modal fade" id="deleteCategoryModal" tabindex="-1" aria-labelledby="deleteCategoryModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteCategoryModalLabel"><i class="fas fa-exclamation-triangle me-2"></i>
                    Confirm Delete Category</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this category? Tasks assigned to this category will no longer be
                    categorized.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <a href="#" class="btn btn-danger" id="confirmDeleteCategory"><i class="fas fa-trash me-1"></i>
                    Delete</a>
            </div>
        </div>
    </div>
</div>

<script>
    // Category deletion confirmation
    document.querySelectorAll('.delete-category').forEach(function (button) {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            const categoryId = this.dataset.id;
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteCategoryModal'));
            const confirmDelete = document.getElementById('confirmDeleteCategory');

            confirmDelete.href = `categories.php?action=delete&id=${categoryId}`;
            deleteModal.show();
        });
    });

    // Form validation
    document.getElementById('createTaskForm').addEventListener('submit', function (event) {
        const title = document.getElementById('title').value.trim();

        if (title === '') {
            event.preventDefault();
            alert('Task title is required.');
            document.getElementById('title').focus();
        }
    });
</script>

<?php
// Removed footer include to prevent duplication
?>