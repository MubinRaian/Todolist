</div>
<!-- End Main Content Container -->

<!-- Footer -->
<footer class="bg-light py-4 mt-auto border-top">
    <div class="container">
        <div class="row g-4 align-items-center">
            <div class="col-md-4">
                <div class="d-flex align-items-center mb-2">
                    <i class="fas fa-check-circle text-primary me-2 fa-lg"></i>
                    <h5 class="mb-0 fw-bold">Task Manager</h5>
                </div>
                <p class="mb-0 text-muted small">Stay organized, boost productivity, and never miss a deadline with our
                    professional task management solution.</p>
            </div>
            <div class="col-md-4">
                <h6 class="text-uppercase fw-bold mb-3">Quick Links</h6>
                <ul class="list-unstyled mb-0">
                    <li class="mb-2"><a href="index.php" class="text-decoration-none text-secondary"><i
                                class="fas fa-chevron-right me-1 small"></i> Dashboard</a></li>
                    <li class="mb-2"><a href="tasks.php" class="text-decoration-none text-secondary"><i
                                class="fas fa-chevron-right me-1 small"></i> Tasks</a></li>
                    <li class="mb-2"><a href="calendar.php" class="text-decoration-none text-secondary"><i
                                class="fas fa-chevron-right me-1 small"></i> Calendar</a></li>
                    <li><a href="profile.php" class="text-decoration-none text-secondary"><i
                                class="fas fa-chevron-right me-1 small"></i> Profile</a></li>
                </ul>
            </div>
            <div class="col-md-4">
                <h6 class="text-uppercase fw-bold mb-3">Legal</h6>
                <ul class="list-unstyled mb-0">
                    <li class="mb-2"><a href="#" class="text-decoration-none text-secondary" data-bs-toggle="modal"
                            data-bs-target="#privacyModal"><i class="fas fa-shield-alt me-1"></i> Privacy Policy</a>
                    </li>
                    <li class="mb-2"><a href="#" class="text-decoration-none text-secondary" data-bs-toggle="modal"
                            data-bs-target="#termsModal"><i class="fas fa-gavel me-1"></i> Terms of Service</a></li>
                    <li><a href="#" class="text-decoration-none text-secondary"><i class="fas fa-envelope me-1"></i>
                            Contact Us</a></li>
                </ul>
            </div>
        </div>
        <hr class="my-4">
        <div class="row">
            <div class="col-md-6">
                <p class="mb-0 small text-muted">&copy; <?php echo date('Y'); ?> Task Manager. All rights reserved.</p>
            </div>
            <div class="col-md-6 text-md-end">
                <div class="social-icons">
                    <a href="#" class="text-decoration-none me-3 text-secondary"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="text-decoration-none me-3 text-secondary"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-decoration-none me-3 text-secondary"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#" class="text-decoration-none text-secondary"><i class="fab fa-github"></i></a>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- Bootstrap JS with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- jQuery (required for some plugins) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Flatpickr (Date Picker) -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<!-- Custom JavaScript -->
<script src="assets/js/script.js"></script>

<!-- Initialize date pickers -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Initialize flatpickr on all date input fields
        flatpickr(".date-picker", {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            time_24hr: true
        });

        // Initialize date range pickers
        flatpickr(".date-range-from", {
            enableTime: false,
            dateFormat: "Y-m-d",
            onChange: function (selectedDates, dateStr, instance) {
                // Update the minimum date of the 'to' date picker
                const toDatePicker = document.querySelector('.date-range-to')._flatpickr;
                if (toDatePicker) {
                    toDatePicker.set('minDate', dateStr);
                }
            }
        });

        flatpickr(".date-range-to", {
            enableTime: false,
            dateFormat: "Y-m-d"
        });

        // Initialize tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Initialize popovers
        const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
        popoverTriggerList.map(function (popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl);
        });
    });
</script>

<?php if (isset($extraScripts))
    echo $extraScripts; ?>
</body>

</html>