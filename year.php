<?php
// views/calendar/year.php
// Yearly calendar view

$pageTitle = 'Calendar - Year View';
// Removed header include to prevent duplication

// Get year information
$currentDate = new DateTime($currentDate);
$currentYear = $currentDate->format('Y');

// Calculate previous and next year dates
$prevYear = clone $currentDate;
$prevYear->modify('-1 year');

$nextYear = clone $currentDate;
$nextYear->modify('+1 year');

// Group tasks by month and date
$tasksByDate = [];
foreach ($tasks as $task) {
    $taskDate = date('Y-m-d', strtotime($task['due_date']));
    if (!isset($tasksByDate[$taskDate])) {
        $tasksByDate[$taskDate] = [];
    }
    $tasksByDate[$taskDate][] = $task;
}

// Count tasks per month
$taskCountByMonth = [];
for ($month = 1; $month <= 12; $month++) {
    $taskCountByMonth[$month] = 0;
}

foreach ($tasksByDate as $date => $dateTasks) {
    $month = (int) date('n', strtotime($date));
    $taskCountByMonth[$month] += count($dateTasks);
}

// Month names
$monthNames = [
    1 => 'January',
    2 => 'February',
    3 => 'March',
    4 => 'April',
    5 => 'May',
    6 => 'June',
    7 => 'July',
    8 => 'August',
    9 => 'September',
    10 => 'October',
    11 => 'November',
    12 => 'December'
];
?>

<div class="row mb-4">
    <div class="col">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-calendar-alt"></i> Calendar
        </h1>
    </div>
    <div class="col-auto">
        <a href="tasks.php?action=create" class="btn btn-primary">
            <i class="fas fa-plus"></i> New Task
        </a>
    </div>
</div>

<!-- Calendar Navigation and View Selector -->
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <div class="btn-group">
            <a href="calendar.php?view=year&date=<?php echo $prevYear->format('Y-m-d'); ?>"
                class="btn btn-outline-primary">
                <i class="fas fa-chevron-left"></i> Previous
            </a>
            <a href="calendar.php?view=year&date=<?php echo date('Y-m-d'); ?>" class="btn btn-outline-primary">
                This Year
            </a>
            <a href="calendar.php?view=year&date=<?php echo $nextYear->format('Y-m-d'); ?>"
                class="btn btn-outline-primary">
                Next <i class="fas fa-chevron-right"></i>
            </a>
        </div>

        <h4 class="mb-0 font-weight-bold text-primary">
            <?php echo $currentYear; ?>
        </h4>

        <div class="btn-group">
            <a href="calendar.php?view=day&date=<?php echo $currentDate->format('Y-m-d'); ?>"
                class="btn btn-outline-primary">
                Day
            </a>
            <a href="calendar.php?view=week&date=<?php echo $currentDate->format('Y-m-d'); ?>"
                class="btn btn-outline-primary">
                Week
            </a>
            <a href="calendar.php?view=month&date=<?php echo $currentDate->format('Y-m-d'); ?>"
                class="btn btn-outline-primary">
                Month
            </a>
            <a href="calendar.php?view=year&date=<?php echo $currentDate->format('Y-m-d'); ?>"
                class="btn btn-outline-primary active">
                Year
            </a>
        </div>
    </div>
    <div class="card-body">
        <!-- Year Calendar -->
        <div class="row">
            <?php
            // Display months in a 3x4 grid (3 months per row)
            for ($month = 1; $month <= 12; $month++) {
                // Create date for first day of month
                $firstDayOfMonth = new DateTime("$currentYear-$month-01");

                // Get number of days in the month
                $daysInMonth = (int) $firstDayOfMonth->format('t');

                // Get starting day of the week (0 = Sunday, 6 = Saturday)
                $startingDayOfWeek = (int) $firstDayOfMonth->format('w');

                // Check if this is the current month
                $isCurrentMonth = ($currentYear == date('Y') && $month == date('n'));

                echo '<div class="col-md-4 mb-4">';
                echo '<div class="card ' . ($isCurrentMonth ? 'border-primary' : '') . '">';
                echo '<div class="card-header py-2 ' . ($isCurrentMonth ? 'bg-primary text-white' : '') . '">';

                // Month header with task count
                echo '<div class="d-flex justify-content-between align-items-center">';
                echo '<h6 class="m-0 font-weight-bold">';
                echo '<a href="calendar.php?view=month&date=' . $firstDayOfMonth->format('Y-m-d') . '" class="' . ($isCurrentMonth ? 'text-white' : 'text-primary') . '">';
                echo $monthNames[$month];
                echo '</a>';
                echo '</h6>';

                if ($taskCountByMonth[$month] > 0) {
                    echo '<span class="badge ' . ($isCurrentMonth ? 'bg-white text-primary' : 'bg-primary text-white') . ' rounded-pill">';
                    echo $taskCountByMonth[$month];
                    echo '</span>';
                }

                echo '</div>';
                echo '</div>';

                echo '<div class="card-body p-0">';
                echo '<table class="table table-bordered table-sm mini-calendar m-0">';

                // Day headers (S M T W T F S)
                echo '<thead>';
                echo '<tr>';
                echo '<th>S</th><th>M</th><th>T</th><th>W</th><th>T</th><th>F</th><th>S</th>';
                echo '</tr>';
                echo '</thead>';

                echo '<tbody>';

                // Initialize counters
                $dayCount = 1;

                // Determine the number of rows needed
                $totalDays = $startingDayOfWeek + $daysInMonth;
                $totalRows = ceil($totalDays / 7);

                // Build calendar rows
                for ($row = 0; $row < $totalRows; $row++) {
                    echo '<tr>';

                    // Build calendar columns (days)
                    for ($col = 0; $col < 7; $col++) {
                        // Skip days before the start of the month
                        if ($row == 0 && $col < $startingDayOfWeek) {
                            echo '<td></td>';
                            continue;
                        }

                        // Skip days after the end of the month
                        if ($dayCount > $daysInMonth) {
                            echo '<td></td>';
                            continue;
                        }

                        // Get current date for this cell
                        $cellDate = sprintf('%s-%02d-%02d', $currentYear, $month, $dayCount);

                        // Check if this is today
                        $isToday = ($cellDate === date('Y-m-d'));
                        $todayClass = $isToday ? 'bg-light font-weight-bold' : '';

                        // Check if there are tasks for this date
                        $hasTask = isset($tasksByDate[$cellDate]) && !empty($tasksByDate[$cellDate]);
                        $taskClass = $hasTask ? 'has-task' : '';

                        echo '<td class="text-center ' . $todayClass . ' ' . $taskClass . '" style="height:30px;">';

                        // Day number with link to day view
                        echo '<a href="calendar.php?view=day&date=' . $cellDate . '" ' .
                            'class="' . ($hasTask ? 'text-primary' : '') . '" ' .
                            'data-bs-toggle="tooltip" title="' . count($tasksByDate[$cellDate] ?? []) . ' tasks">';
                        echo $dayCount;
                        echo '</a>';

                        echo '</td>';

                        $dayCount++;
                    }

                    echo '</tr>';

                    // Break if we've displayed all days
                    if ($dayCount > $daysInMonth) {
                        break;
                    }
                }

                echo '</tbody>';
                echo '</table>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
            ?>
        </div>
    </div>
</div>

<!-- Task Summary for the Year -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Task Summary for <?php echo $currentYear; ?></h6>
    </div>
    <div class="card-body">
        <div class="row">
            <!-- Task Count by Month Chart -->
            <div class="col-lg-8 mb-4">
                <div class="chart-container" style="position: relative; height:300px;">
                    <canvas id="tasksByMonthChart"></canvas>
                </div>
            </div>

            <!-- Task Stats -->
            <div class="col-lg-4 mb-4">
                <?php
                // Count total tasks for the year
                $totalTasks = array_sum($taskCountByMonth);

                // Count completed tasks
                $completedTasks = 0;
                foreach ($tasks as $task) {
                    if ($task['status'] === 'completed') {
                        $completedTasks++;
                    }
                }

                // Calculate completion rate
                $completionRate = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;

                // Count tasks by priority
                $tasksByPriority = [
                    'high' => 0,
                    'medium' => 0,
                    'low' => 0
                ];

                foreach ($tasks as $task) {
                    $tasksByPriority[$task['priority']]++;
                }
                ?>

                <h4 class="small font-weight-bold">Yearly Completion Rate <span
                        class="float-end"><?php echo $completionRate; ?>%</span></h4>
                <div class="progress mb-4">
                    <?php
                    $progressClass = 'bg-danger';
                    if ($completionRate >= 70) {
                        $progressClass = 'bg-success';
                    } else if ($completionRate >= 40) {
                        $progressClass = 'bg-warning';
                    }
                    ?>
                    <div class="progress-bar <?php echo $progressClass; ?>" role="progressbar"
                        style="width: <?php echo $completionRate; ?>%" aria-valuenow="<?php echo $completionRate; ?>"
                        aria-valuemin="0" aria-valuemax="100"></div>
                </div>

                <div class="mb-4">
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6 border-end">
                                <div class="h4 mb-0"><?php echo $totalTasks; ?></div>
                                <div class="small text-muted">Total Tasks</div>
                            </div>
                            <div class="col-6">
                                <div class="h4 mb-0"><?php echo $completedTasks; ?></div>
                                <div class="small text-muted">Completed</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <h6 class="font-weight-bold mb-2">Tasks by Priority</h6>
                    <div class="row text-center">
                        <div class="col-4">
                            <span class="badge bg-danger d-block mb-1">High</span>
                            <div class="h5 mb-0"><?php echo $tasksByPriority['high']; ?></div>
                        </div>
                        <div class="col-4">
                            <span class="badge bg-warning d-block mb-1">Medium</span>
                            <div class="h5 mb-0"><?php echo $tasksByPriority['medium']; ?></div>
                        </div>
                        <div class="col-4">
                            <span class="badge bg-success d-block mb-1">Low</span>
                            <div class="h5 mb-0"><?php echo $tasksByPriority['low']; ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .mini-calendar th,
    .mini-calendar td {
        width: 14.28%;
        text-align: center;
        padding: 3px !important;
    }

    .has-task {
        position: relative;
    }

    .has-task:after {
        content: '';
        position: absolute;
        bottom: 3px;
        left: 50%;
        transform: translateX(-50%);
        width: 4px;
        height: 4px;
        border-radius: 50%;
        background-color: #4e73df;
    }
</style>

<!-- Chart.js for the bar chart -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Task count by month chart
        const ctx = document.getElementById('tasksByMonthChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [
                    <?php
                    foreach ($monthNames as $name) {
                        echo "'$name', ";
                    }
                    ?>
                ],
                datasets: [{
                    label: 'Tasks per Month',
                    data: [
                        <?php
                        for ($i = 1; $i <= 12; $i++) {
                            echo $taskCountByMonth[$i] . ', ';
                        }
                        ?>
                    ],
                    backgroundColor: [
                        'rgba(78, 115, 223, 0.2)',
                        'rgba(28, 200, 138, 0.2)',
                        'rgba(54, 185, 204, 0.2)',
                        'rgba(246, 194, 62, 0.2)',
                        'rgba(231, 74, 59, 0.2)',
                        'rgba(90, 92, 105, 0.2)',
                        'rgba(133, 135, 150, 0.2)',
                        'rgba(248, 249, 252, 0.2)',
                        'rgba(209, 211, 226, 0.2)',
                        'rgba(78, 115, 223, 0.2)',
                        'rgba(28, 200, 138, 0.2)',
                        'rgba(54, 185, 204, 0.2)'
                    ],
                    borderColor: [
                        'rgba(78, 115, 223, 1)',
                        'rgba(28, 200, 138, 1)',
                        'rgba(54, 185, 204, 1)',
                        'rgba(246, 194, 62, 1)',
                        'rgba(231, 74, 59, 1)',
                        'rgba(90, 92, 105, 1)',
                        'rgba(133, 135, 150, 1)',
                        'rgba(248, 249, 252, 1)',
                        'rgba(209, 211, 226, 1)',
                        'rgba(78, 115, 223, 1)',
                        'rgba(28, 200, 138, 1)',
                        'rgba(54, 185, 204, 1)'
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
    });
</script>

<?php
// Removed footer include to prevent duplication
?>