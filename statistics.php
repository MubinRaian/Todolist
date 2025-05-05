<?php
// views/user/statistics.php
// User task statistics view

$pageTitle = 'Task Statistics';
// Removed header include to prevent duplication
?>

<div class="row mb-4">
    <div class="col">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-chart-bar"></i> Task Statistics
        </h1>
    </div>
    <div class="col-auto">
        <a href="profile.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Profile
        </a>
    </div>
</div>

<!-- Task Overview Card -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Task Overview</h6>
    </div>
    <div class="card-body">
        <!-- Task Counts -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Tasks</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $taskStats['total']; ?>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-tasks fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Completed</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <?php echo $taskStats['completed']; ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pending</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $taskStats['pending']; ?>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clock fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-danger shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Overdue</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $taskStats['overdue']; ?>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-exclamation-circle fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Completion Progress -->
        <div class="row mb-4">
            <div class="col-lg-6">
                <h4 class="small font-weight-bold">Task Completion Rate <span
                        class="float-end"><?php echo $taskStats['completion_rate']; ?>%</span></h4>
                <div class="progress mb-4">
                    <?php
                    $progressClass = 'bg-danger';
                    if ($taskStats['completion_rate'] >= 70) {
                        $progressClass = 'bg-success';
                    } else if ($taskStats['completion_rate'] >= 40) {
                        $progressClass = 'bg-warning';
                    }
                    ?>
                    <div class="progress-bar <?php echo $progressClass; ?>" role="progressbar"
                        style="width: <?php echo $taskStats['completion_rate']; ?>%"
                        aria-valuenow="<?php echo $taskStats['completion_rate']; ?>" aria-valuemin="0"
                        aria-valuemax="100"></div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6">
                                <canvas id="completionChart" width="200" height="200"></canvas>
                            </div>
                            <div class="col-6 d-flex flex-column justify-content-center">
                                <div class="mb-2">
                                    <i class="fas fa-circle text-success me-1"></i> Completed:
                                    <?php echo $taskStats['completed']; ?>
                                </div>
                                <div>
                                    <i class="fas fa-circle text-warning me-1"></i> Pending:
                                    <?php echo $taskStats['pending']; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Task Priority and Categories -->
<div class="row">
    <!-- Tasks by Priority -->
    <div class="col-lg-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Tasks by Priority</h6>
            </div>
            <div class="card-body">
                <div class="chart-container" style="position: relative; height:300px;">
                    <canvas id="priorityChart"></canvas>
                </div>

                <div class="mt-4">
                    <div class="row text-center">
                        <div class="col-4">
                            <span class="badge bg-danger d-block">High</span>
                            <div class="h4 mt-2"><?php echo $taskStats['priority']['high']; ?></div>
                            <div class="small text-muted">
                                <?php
                                $highPercentage = $taskStats['total'] > 0 ? round(($taskStats['priority']['high'] / $taskStats['total']) * 100) : 0;
                                echo $highPercentage . '%';
                                ?>
                            </div>
                        </div>
                        <div class="col-4">
                            <span class="badge bg-warning d-block">Medium</span>
                            <div class="h4 mt-2"><?php echo $taskStats['priority']['medium']; ?></div>
                            <div class="small text-muted">
                                <?php
                                $mediumPercentage = $taskStats['total'] > 0 ? round(($taskStats['priority']['medium'] / $taskStats['total']) * 100) : 0;
                                echo $mediumPercentage . '%';
                                ?>
                            </div>
                        </div>
                        <div class="col-4">
                            <span class="badge bg-success d-block">Low</span>
                            <div class="h4 mt-2"><?php echo $taskStats['priority']['low']; ?></div>
                            <div class="small text-muted">
                                <?php
                                $lowPercentage = $taskStats['total'] > 0 ? round(($taskStats['priority']['low'] / $taskStats['total']) * 100) : 0;
                                echo $lowPercentage . '%';
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tasks by Category -->
    <div class="col-lg-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Tasks by Category</h6>
            </div>
            <div class="card-body">
                <?php if (empty($taskStats['categories'])): ?>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> No categories found. <a href="tasks.php?action=create">Create a
                            task with a category</a> to see statistics.
                    </div>
                <?php else: ?>
                    <div class="chart-container" style="position: relative; height:300px;">
                        <canvas id="categoryChart"></canvas>
                    </div>

                    <div class="mt-4">
                        <ul class="list-group">
                            <?php foreach ($taskStats['categories'] as $category): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <?php echo $category['name'] ?: 'Uncategorized'; ?>
                                    <span class="badge bg-primary rounded-pill"><?php echo $category['count']; ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Extra JavaScript for Charts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Task completion chart
    const completionCtx = document.getElementById('completionChart').getContext('2d');
    const completionChart = new Chart(completionCtx, {
        type: 'doughnut',
        data: {
            labels: ['Completed', 'Pending'],
            datasets: [{
                data: [
                    <?php echo $taskStats['completed']; ?>,
                    <?php echo $taskStats['pending']; ?>
                ],
                backgroundColor: [
                    '#1cc88a',  // Success green
                    '#f6c23e'   // Warning yellow
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

    // Task priority chart
    const priorityCtx = document.getElementById('priorityChart').getContext('2d');
    const priorityChart = new Chart(priorityCtx, {
        type: 'bar',
        data: {
            labels: ['High', 'Medium', 'Low'],
            datasets: [{
                label: 'Tasks by Priority',
                data: [
                    <?php echo $taskStats['priority']['high']; ?>,
                    <?php echo $taskStats['priority']['medium']; ?>,
                    <?php echo $taskStats['priority']['low']; ?>
                ],
                backgroundColor: [
                    '#e74a3b',  // Danger red (high)
                    '#f6c23e',  // Warning yellow (medium)
                    '#1cc88a'   // Success green (low)
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });

    // Category chart (only if categories exist)
    <?php if (!empty($taskStats['categories'])): ?>
        const categoryCtx = document.getElementById('categoryChart').getContext('2d');
        const categoryChart = new Chart(categoryCtx, {
            type: 'pie',
            data: {
                labels: [
                    <?php
                    foreach ($taskStats['categories'] as $category) {
                        echo "'" . ($category['name'] ?: 'Uncategorized') . "', ";
                    }
                    ?>
                ],
                datasets: [{
                    data: [
                        <?php
                        foreach ($taskStats['categories'] as $category) {
                            echo $category['count'] . ", ";
                        }
                        ?>
                    ],
                    backgroundColor: [
                        '#4e73df',  // Primary blue
                        '#1cc88a',  // Success green
                        '#36b9cc',  // Info cyan
                        '#f6c23e',  // Warning yellow
                        '#e74a3b',  // Danger red
                        '#5a5c69',  // Secondary grey
                        '#858796',  // Light grey
                        '#f8f9fc',  // Light blue
                        '#d1d3e2',  // Light cyan
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        display: false
                    }
                }
            }
        });
    <?php endif; ?>
</script>

<?php
// Removed footer include to prevent duplication
?>