<?php
// views/calendar/month.php
// Monthly calendar view

$pageTitle = 'Calendar - Month View';
// Removed header include to prevent duplication

// Get month information
$currentDate = new DateTime($currentDate);
$currentMonth = $currentDate->format('n');
$currentYear = $currentDate->format('Y');

// Get first day of the month
$firstDayOfMonth = new DateTime("$currentYear-$currentMonth-01");
$lastDayOfMonth = new DateTime($firstDayOfMonth->format('Y-m-t'));

// Get starting day of the week (0 = Sunday, 6 = Saturday)
$startingDayOfWeek = (int) $firstDayOfMonth->format('w');

// Get number of days in the month
$daysInMonth = (int) $lastDayOfMonth->format('d');

// Calculate previous and next month dates
$prevMonth = clone $firstDayOfMonth;
$prevMonth->modify('-1 month');

$nextMonth = clone $firstDayOfMonth;
$nextMonth->modify('+1 month');

// Group tasks by date
$tasksByDate = [];
foreach ($tasks as $task) {
    $taskDate = date('Y-m-d', strtotime($task['due_date']));
    if (!isset($tasksByDate[$taskDate])) {
        $tasksByDate[$taskDate] = [];
    }
    $tasksByDate[$taskDate][] = $task;
}
?>

<div class="row mb-4">
    <div class="col">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-calendar"></i> Calendar
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
            <a href="calendar.php?view=month&date=<?php echo $prevMonth->format('Y-m-d'); ?>"
                class="btn btn-outline-primary">
                <i class="fas fa-chevron-left"></i> Previous
            </a>
            <a href="calendar.php?view=month&date=<?php echo date('Y-m-d'); ?>" class="btn btn-outline-primary">
                Today
            </a>
            <a href="calendar.php?view=month&date=<?php echo $nextMonth->format('Y-m-d'); ?>"
                class="btn btn-outline-primary">
                Next <i class="fas fa-chevron-right"></i>
            </a>
        </div>

        <h4 class="mb-0 font-weight-bold text-primary">
            <?php echo $currentDate->format('F Y'); ?>
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
                class="btn btn-outline-primary active">
                Month
            </a>
            <a href="calendar.php?view=year&date=<?php echo $currentDate->format('Y-m-d'); ?>"
                class="btn btn-outline-primary">
                Year
            </a>
        </div>
    </div>
    <div class="card-body">
        <!-- Month Calendar -->
        <div class="table-responsive">
            <table class="table table-bordered calendar-table">
                <thead>
                    <tr>
                        <th>Sunday</th>
                        <th>Monday</th>
                        <th>Tuesday</th>
                        <th>Wednesday</th>
                        <th>Thursday</th>
                        <th>Friday</th>
                        <th>Saturday</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Initialize counters
                    $dayCount = 1;
                    $calendarDay = 1;

                    // Determine the number of rows needed (maximum 6 weeks)
                    $totalDays = $startingDayOfWeek + $daysInMonth;
                    $totalRows = ceil($totalDays / 7);

                    // Build calendar rows
                    for ($row = 0; $row < $totalRows; $row++) {
                        echo '<tr style="height: 120px;">';

                        // Build calendar columns (days)
                        for ($col = 0; $col < 7; $col++) {
                            // Add "today" class for current day
                            $isToday = ($currentYear == date('Y') && $currentMonth == date('n') && $calendarDay == date('j'));
                            $todayClass = $isToday ? 'bg-light' : '';

                            // Calculate the actual date for this cell
                            $cellDate = null;
                            $inMonth = false;

                            // Handle cells before first day of month
                            if ($row == 0 && $col < $startingDayOfWeek) {
                                // Show days from previous month
                                $prevMonthLastDay = (int) $prevMonth->format('t');
                                $prevMonthDay = $prevMonthLastDay - ($startingDayOfWeek - $col - 1);

                                $cellDate = $prevMonth->format('Y-m') . '-' . sprintf('%02d', $prevMonthDay);

                                echo '<td class="text-muted">';
                                echo '<div class="calendar-day text-muted">' . $prevMonthDay . '</div>';
                            }
                            // Handle cells after last day of month
                            else if ($calendarDay > $daysInMonth) {
                                // Show days from next month
                                $nextMonthDay = $dayCount - $daysInMonth;

                                $cellDate = $nextMonth->format('Y-m') . '-' . sprintf('%02d', $nextMonthDay);

                                echo '<td class="text-muted">';
                                echo '<div class="calendar-day text-muted">' . $nextMonthDay . '</div>';

                                $dayCount++;
                            }
                            // Handle cells within current month
                            else {
                                $cellDate = $currentYear . '-' . sprintf('%02d', $currentMonth) . '-' . sprintf('%02d', $calendarDay);
                                $inMonth = true;

                                echo '<td class="' . $todayClass . '">';
                                echo '<div class="calendar-day">' . $calendarDay . '</div>';

                                $calendarDay++;
                                $dayCount++;
                            }

                            // Display tasks for this date
                            if (isset($tasksByDate[$cellDate])) {
                                echo '<div class="calendar-tasks">';

                                // Limit to a reasonable number of tasks to show
                                $maxTasksToShow = 3;
                                $tasksForDate = $tasksByDate[$cellDate];
                                $tasksToShow = array_slice($tasksForDate, 0, $maxTasksToShow);
                                $remainingTasks = count($tasksForDate) - $maxTasksToShow;

                                foreach ($tasksToShow as $task) {
                                    $statusClass = $task['status'] === 'completed' ? 'text-decoration-line-through' : '';
                                    $priorityClass = getPriorityClass($task['priority']);

                                    echo '<div class="calendar-task bg-' . $priorityClass . '-subtle border-start border-3 border-' . $priorityClass . ' ps-2 mb-1">';
                                    echo '<a href="tasks.php?action=view&id=' . $task['id'] . '" class="' . $statusClass . '">';
                                    echo $task['title'];
                                    echo '</a>';
                                    echo '</div>';
                                }

                                // Show indicator for additional tasks
                                if ($remainingTasks > 0) {
                                    echo '<div class="calendar-task-more text-center">';
                                    echo '<a href="tasks.php?date_from=' . $cellDate . '&date_to=' . $cellDate . '" class="badge bg-primary">';
                                    echo '+' . $remainingTasks . ' more';
                                    echo '</a>';
                                    echo '</div>';
                                }

                                echo '</div>';
                            }

                            // Add day link for adding tasks
                            if ($inMonth) {
                                echo '<div class="calendar-add-task">';
                                echo '<a href="tasks.php?action=create&due_date=' . $cellDate . ' 12:00:00" class="btn btn-sm btn-outline-primary">';
                                echo '<i class="fas fa-plus"></i>';
                                echo '</a>';
                                echo '</div>';
                            }

                            echo '</td>';
                        }

                        echo '</tr>';

                        // Break if we've displayed all days
                        if ($calendarDay > $daysInMonth && $row >= 3) {
                            break;
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Task Legend -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Task Priority Legend</h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <div class="d-flex align-items-center mb-2">
                    <div class="bg-danger-subtle border-start border-3 border-danger ps-2 py-2 me-2"
                        style="width: 100px;">
                        &nbsp;
                    </div>
                    <span>High Priority</span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="d-flex align-items-center mb-2">
                    <div class="bg-warning-subtle border-start border-3 border-warning ps-2 py-2 me-2"
                        style="width: 100px;">
                        &nbsp;
                    </div>
                    <span>Medium Priority</span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="d-flex align-items-center mb-2">
                    <div class="bg-success-subtle border-start border-3 border-success ps-2 py-2 me-2"
                        style="width: 100px;">
                        &nbsp;
                    </div>
                    <span>Low Priority</span>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .calendar-table td {
        width: 14.28%;
        height: 120px;
        vertical-align: top;
        padding: 5px;
        position: relative;
    }

    .calendar-day {
        font-weight: bold;
        margin-bottom: 8px;
    }

    .calendar-tasks {
        max-height: 75px;
        overflow-y: auto;
    }

    .calendar-task {
        font-size: 0.8rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        padding: 2px 4px;
        background-color: #f8f9fa;
        margin-bottom: 2px;
        border-radius: 3px;
    }

    .calendar-task a {
        text-decoration: none;
        color: #333;
    }

    .calendar-task-more {
        margin-top: 2px;
    }

    .calendar-add-task {
        position: absolute;
        bottom: 5px;
        right: 5px;
    }
</style>

<?php
// Removed footer include to prevent duplication
?>