/**
 * assets/js/script.js
 * Custom JavaScript for Task Manager application
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize Bootstrap tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Initialize Bootstrap popovers
    const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    popoverTriggerList.map(function(popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });
    
    // Auto-close alerts after 5 seconds
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
    
    // Toggle task status checkboxes
    const taskCheckboxes = document.querySelectorAll('.task-checkbox');
    if (taskCheckboxes.length > 0) {
        taskCheckboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                toggleTaskStatus(this.dataset.id);
            });
        });
    }
    
    // Select all tasks checkbox
    const selectAllCheckbox = document.getElementById('selectAll');
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.task-select');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    }
    
    // Task deletion confirmation
    const deleteButtons = document.querySelectorAll('.delete-task');
    if (deleteButtons.length > 0) {
        deleteButtons.forEach(function(button) {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                confirmDeleteTask(this.dataset.id);
            });
        });
    }
    
    // Category deletion confirmation
    const deleteCategoryButtons = document.querySelectorAll('.delete-category');
    if (deleteCategoryButtons.length > 0) {
        deleteCategoryButtons.forEach(function(button) {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                confirmDeleteCategory(this.dataset.id);
            });
        });
    }
    
    // Bulk actions
    const bulkActionButtons = document.querySelectorAll('.bulk-action');
    if (bulkActionButtons.length > 0) {
        bulkActionButtons.forEach(function(button) {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                handleBulkAction(this.dataset.action);
            });
        });
    }
    
    // Password visibility toggle
    const togglePasswordButtons = document.querySelectorAll('.toggle-password');
    if (togglePasswordButtons.length > 0) {
        togglePasswordButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                togglePasswordVisibility(this);
            });
        });
    }
    
    // Form validations
    validateForms();
    
    // Initialize date pickers
    initializeDatePickers();
});

/**
 * Toggle task status via AJAX
 */
function toggleTaskStatus(taskId) {
    // Show loading spinner
    showSpinner();
    
    // Send AJAX request
    fetch('tasks.php?action=toggle&id=' + taskId, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        }
    })
    .then(response => {
        if (response.ok) {
            // Refresh the page or update UI
            window.location.reload();
        } else {
            throw new Error('Failed to toggle task status');
        }
    })
    .catch(error => {
        // Hide spinner
        hideSpinner();
        
        // Show error
        showToast('Error', error.message, 'danger');
    });
}

/**
 * Confirm task deletion
 */
function confirmDeleteTask(taskId) {
    // Get modal element
    const modal = document.getElementById('deleteTaskModal') || createDeleteTaskModal();
    const bootstrapModal = new bootstrap.Modal(modal);
    
    // Set confirm button action
    const confirmButton = document.getElementById('confirmDelete');
    confirmButton.href = 'tasks.php?action=delete&id=' + taskId;
    
    // Show modal
    bootstrapModal.show();
}

/**
 * Create delete task modal if not exists
 */
function createDeleteTaskModal() {
    const modal = document.createElement('div');
    modal.className = 'modal fade';
    modal.id = 'deleteTaskModal';
    modal.tabIndex = '-1';
    modal.setAttribute('aria-labelledby', 'deleteTaskModalLabel');
    modal.setAttribute('aria-hidden', 'true');
    
    modal.innerHTML = `
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteTaskModalLabel">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this task? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <a href="#" class="btn btn-danger" id="confirmDelete">Delete</a>
                </div>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    return modal;
}

/**
 * Confirm category deletion
 */
function confirmDeleteCategory(categoryId) {
    // Get modal element
    const modal = document.getElementById('deleteCategoryModal') || createDeleteCategoryModal();
    const bootstrapModal = new bootstrap.Modal(modal);
    
    // Set confirm button action
    const confirmButton = document.getElementById('confirmDeleteCategory');
    confirmButton.href = 'categories.php?action=delete&id=' + categoryId;
    
    // Show modal
    bootstrapModal.show();
}

/**
 * Create delete category modal if not exists
 */
function createDeleteCategoryModal() {
    const modal = document.createElement('div');
    modal.className = 'modal fade';
    modal.id = 'deleteCategoryModal';
    modal.tabIndex = '-1';
    modal.setAttribute('aria-labelledby', 'deleteCategoryModalLabel');
    modal.setAttribute('aria-hidden', 'true');
    
    modal.innerHTML = `
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteCategoryModalLabel">Confirm Delete Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this category? Tasks assigned to this category will no longer be categorized.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <a href="#" class="btn btn-danger" id="confirmDeleteCategory">Delete</a>
                </div>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    return modal;
}

/**
 * Handle bulk actions
 */
function handleBulkAction(action) {
    // Check if any tasks are selected
    const selectedTasks = document.querySelectorAll('.task-select:checked');
    if (selectedTasks.length === 0) {
        showToast('Warning', 'Please select at least one task', 'warning');
        return;
    }
    
    // Get form and update action
    const form = document.getElementById('bulkActionsForm');
    
    switch (action) {
        case 'complete':
            form.action = 'tasks.php?action=bulk_complete';
            confirmBulkAction(`Are you sure you want to mark ${selectedTasks.length} tasks as completed?`, form);
            break;
        
        case 'pending':
            form.action = 'tasks.php?action=bulk_pending';
            confirmBulkAction(`Are you sure you want to mark ${selectedTasks.length} tasks as pending?`, form);
            break;
        
        case 'delete':
            form.action = 'tasks.php?action=bulk_delete';
            confirmBulkAction(`Are you sure you want to delete ${selectedTasks.length} tasks? This action cannot be undone.`, form);
            break;
    }
}

/**
 * Confirm bulk action
 */
function confirmBulkAction(message, form) {
    // Get or create modal
    const modal = document.getElementById('bulkActionModal') || createBulkActionModal();
    const bootstrapModal = new bootstrap.Modal(modal);
    
    // Update modal body
    document.getElementById('bulkActionModalBody').textContent = message;
    
    // Set confirm button action
    document.getElementById('confirmBulkAction').onclick = function() {
        form.submit();
    };
    
    // Show modal
    bootstrapModal.show();
}

/**
 * Create bulk action modal if not exists
 */
function createBulkActionModal() {
    const modal = document.createElement('div');
    modal.className = 'modal fade';
    modal.id = 'bulkActionModal';
    modal.tabIndex = '-1';
    modal.setAttribute('aria-labelledby', 'bulkActionModalLabel');
    modal.setAttribute('aria-hidden', 'true');
    
    modal.innerHTML = `
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bulkActionModalLabel">Confirm Action</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="bulkActionModalBody">
                    Are you sure you want to perform this action on the selected tasks?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmBulkAction">Confirm</button>
                </div>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    return modal;
}

/**
 * Toggle password visibility
 */
function togglePasswordVisibility(button) {
    const passwordInput = document.getElementById(button.dataset.target);
    const icon = button.querySelector('i');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

/**
 * Initialize date pickers
 */
function initializeDatePickers() {
    // Task due date picker
    const dueDatePickers = document.querySelectorAll('.date-picker');
    if (dueDatePickers.length > 0) {
        dueDatePickers.forEach(function(input) {
            flatpickr(input, {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                time_24hr: true
            });
        });
    }
    
    // Date range pickers
    const dateRangeFrom = document.querySelectorAll('.date-range-from');
    if (dateRangeFrom.length > 0) {
        dateRangeFrom.forEach(function(input) {
            flatpickr(input, {
                enableTime: false,
                dateFormat: "Y-m-d",
                onChange: function(selectedDates, dateStr, instance) {
                    // Update the minimum date of the 'to' date picker
                    const toInput = input.closest('form').querySelector('.date-range-to');
                    if (toInput && toInput._flatpickr) {
                        toInput._flatpickr.set('minDate', dateStr);
                    }
                }
            });
        });
    }
    
    const dateRangeTo = document.querySelectorAll('.date-range-to');
    if (dateRangeTo.length > 0) {
        dateRangeTo.forEach(function(input) {
            flatpickr(input, {
                enableTime: false,
                dateFormat: "Y-m-d"
            });
        });
    }
}

/**
 * Form validations
 */
function validateForms() {
    // Task form validation
    const taskForm = document.getElementById('createTaskForm') || document.getElementById('editTaskForm');
    if (taskForm) {
        taskForm.addEventListener('submit', function(event) {
            const title = document.getElementById('title').value.trim();
            
            if (title === '') {
                event.preventDefault();
                showToast('Error', 'Task title is required', 'danger');
                document.getElementById('title').focus();
            }
        });
    }
    
    // Registration form validation
    const registerForm = document.getElementById('registerForm');
    if (registerForm) {
        registerForm.addEventListener('submit', function(event) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            
            if (password !== confirmPassword) {
                event.preventDefault();
                showToast('Error', 'Passwords do not match', 'danger');
                document.getElementById('confirm_password').focus();
            }
        });
    }
    
    // Change password form validation
    const changePasswordForm = document.getElementById('changePasswordForm');
    if (changePasswordForm) {
        changePasswordForm.addEventListener('submit', function(event) {
            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            
            if (newPassword !== confirmPassword) {
                event.preventDefault();
                showToast('Error', 'New passwords do not match', 'danger');
                document.getElementById('confirm_password').focus();
            }
        });
    }
}

/**
 * Show loading spinner
 */
function showSpinner() {
    // Check if spinner container exists
    let spinnerContainer = document.getElementById('spinnerContainer');
    
    if (!spinnerContainer) {
        // Create spinner container
        spinnerContainer = document.createElement('div');
        spinnerContainer.id = 'spinnerContainer';
        spinnerContainer.className = 'position-fixed top-0 start-0 w-100 h-100 d-flex justify-content-center align-items-center bg-dark bg-opacity-50';
        spinnerContainer.style.zIndex = '9999';
        
        // Create spinner
        const spinner = document.createElement('div');
        spinner.className = 'spinner-border text-light';
        spinner.role = 'status';
        
        // Create spinner text
        const spinnerText = document.createElement('span');
        spinnerText.className = 'visually-hidden';
        spinnerText.textContent = 'Loading...';
        
        // Append spinner to container
        spinner.appendChild(spinnerText);
        spinnerContainer.appendChild(spinner);
        
        // Append container to body
        document.body.appendChild(spinnerContainer);
    } else {
        // Show existing spinner
        spinnerContainer.style.display = 'flex';
    }
}

/**
 * Hide loading spinner
 */
function hideSpinner() {
    const spinnerContainer = document.getElementById('spinnerContainer');
    if (spinnerContainer) {
        spinnerContainer.style.display = 'none';
    }
}

/**
 * Show toast notification
 */
function showToast(title, message, type = 'info') {
    // Check if toast container exists
    let toastContainer = document.querySelector('.toast-container');
    
    if (!toastContainer) {
        // Create toast container
        toastContainer = document.createElement('div');
        toastContainer.className = 'toast-container position-fixed bottom-0 end-0 p-3';
        document.body.appendChild(toastContainer);
    }
    
    // Create toast
    const toastId = 'toast-' + Date.now();
    const toast = document.createElement('div');
    toast.className = `toast bg-${type} text-white`;
    toast.id = toastId;
    toast.setAttribute('role', 'alert');
    toast.setAttribute('aria-live', 'assertive');
    toast.setAttribute('aria-atomic', 'true');
    
    // Create toast header
    const toastHeader = document.createElement('div');
    toastHeader.className = 'toast-header';
    
    const titleElement = document.createElement('strong');
    titleElement.className = 'me-auto';
    titleElement.textContent = title;
    
    const closeButton = document.createElement('button');
    closeButton.type = 'button';
    closeButton.className = 'btn-close';
    closeButton.setAttribute('data-bs-dismiss', 'toast');
    closeButton.setAttribute('aria-label', 'Close');
    
    toastHeader.appendChild(titleElement);
    toastHeader.appendChild(closeButton);
    
    // Create toast body
    const toastBody = document.createElement('div');
    toastBody.className = 'toast-body';
    toastBody.textContent = message;
    
    // Append elements to toast
    toast.appendChild(toastHeader);
    toast.appendChild(toastBody);
    
    // Append toast to container
    toastContainer.appendChild(toast);
    
    // Show toast
    const bsToast = new bootstrap.Toast(toast, {
        autohide: true,
        delay: 5000
    });
    bsToast.show();
    
    // Remove toast after hiding
    toast.addEventListener('hidden.bs.toast', function() {
        toast.remove();
        
        // Remove container if empty
        if (toastContainer.children.length === 0) {
            toastContainer.remove();
        }
    });
}