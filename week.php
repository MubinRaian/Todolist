<?php
// views/calendar/week.php
// Weekly calendar view

$pageTitle = 'Calendar - Week View';
// Removed header include to prevent duplication

// Get week information
$currentDate = new DateTime($currentDate);
$currentDay = $currentDate->format('d');
$currentMonth = $currentDate->format('n');
$currentYear = $currentDate->format('Y');

// Adjust to get Monday of the week
$dayOfWeek = $currentDate->format('N'); // 1 (Monday) to 7 (Sunday)
$mondayOffset = $dayOfWeek - 1;
$monday = clone $currentDate;
$monday->modify('-' . $mondayOffset . ' days');

// Calculate dates for the week
$weekDays = [];
for ($i = 0; $i < 7; $i++) {
    $day = clone $monday;
    $day->modify('+' . $i . ' days');
    $weekDays[] = $day;
}

// Calculate previous and next week dates
$prevWeek = clone $monday;
$prevWeek->modify('-7 days');

$nextWeek = clone $monday;
$nextWeek->modify('+7 days');

// Group tasks by date and hour
$tasksByDay = [];
foreach ($tasks as $task) {
    $taskDate = date('Y-m-d', strtotime($task['due_date']));
    $taskHour = date('H', strtotime($task['due_date']));

    if (!isset($tasksByDay[$taskDate])) {
        $tasksByDay[$taskDate] = [];
    }

    if (!isset($tasksByDay[$taskDate][$taskHour])) {
        $tasksByDay[$taskDate][$taskHour] = [];
    }

    $tasksByDay[$taskDate][$taskHour][] = $task;
}
?>

<div class="row mb-4">
    <div class="col">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-calendar-week"></i> Calendar
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
            <a href="calendar.php?view=week&date=<?php echo $prevWeek->format('Y-m-d'); ?>"
                class="btn btn-outline-primary">
                <i class="fas fa-chevron-left"></i> Previous
            </a>
            <a href="calendar.php?view=week&date=<?php echo date('Y-m-d'); ?>" class="btn btn-outline-primary">
                This Week
            </a>
            <a href="calendar.php?view=week&date=<?php echo $nextWeek->format('Y-m-d'); ?>"
                class="btn btn-outline-primary">
                Next <i class="fas fa-chevron-right"></i>
            </a>
        </div>

        <h4 class="mb-0 font-weight-bold text-primary">
            <?php
            echo $monday->format('M d') . ' - ' . end($weekDays)->format('M d, Y');
            ?>
        </h4>

        <div class="btn-group">
            <a href="calendar.php?view=day&date=<?php echo $currentDate->format('Y-m-d'); ?>"
                class="btn btn-outline-primary">
                Day
            </a>
            <a href="calendar.php?view=week&date=<?php echo $currentDate->format('Y-m-d'); ?>"
                class="btn btn-outline-primary active">
                Week
            </a>
            <a href="calendar.php?view=month&date=<?php echo $currentDate->format('Y-m-d'); ?>"
                class="btn btn-outline-primary">
                Month
            </a>
            <a href="calendar.php?view=year&date=<?php echo $currentDate->format('Y-m-d'); ?>"
                class="btn btn-outline-primary">
                Year
            </a>
        </div>
    </div>
    <div class="card-body">
        <!-- Week Calendar -->
        <div class="table-responsive">
            <table class="table table-bordered week-calendar">
                <thead>
                    <tr>
                        <th width="60">Time</th>
                        <?php
                        foreach ($weekDays as $day) {
                            $isToday = ($day->format('Y-m-d') === date('Y-m-d'));
                            $dayClass = $isToday ? 'bg-light' : '';
                            echo '<th class="text-center ' . $dayClass . '">';
                            echo $day->format('D') . '<br>';
                            echo '<span class="badge ' . ($isToday ? 'bg-primary' : 'bg-secondary') . ' rounded-pill">';
                            echo $day->format('d');
                            echo '</span>';
                            echo '</th>';
                        }
                        ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Display working hours (8 AM to 8 PM)
                    for ($hour = 8; $hour < 21; $hour++) {
                        $formattedHour = sprintf('%02d:00', $hour);
                        $timeStr = date('g:i A', strtotime($formattedHour));

                        echo '<tr>';
                        echo '<td class="text-center">' . $timeStr . '</td>';

                        // Display cells for each day
                        foreach ($weekDays as $day) {
                            $dayStr = $day->format('Y-m-d');
                            $isToday = ($dayStr === date('Y-m-d'));
                            $cellClass = $isToday ? 'bg-light' : '';

                            echo '<td class="' . $cellClass . '">';

                            // Display tasks for this day and hour
                            if (isset($tasksByDay[$dayStr][$hour]) && !empty($tasksByDay[$dayStr][$hour])) {
                                foreach ($tasksByDay[$dayStr][$hour] as $task) {
                                    $statusClass = $task['status'] === 'completed' ? 'text-decoration-line-through' : '';
                                    $priorityClass = getPriorityClass($task['priority']);

                                    echo '<div class="week-task p-1 mb-1 border-start border-3 border-' . $priorityClass . ' bg-' . $priorityClass . '-subtle">';
                                    echo '<a href="tasks.php?action=view&id=' . $task['id'] . '" class="' . $statusClass . ' small">';
                                    echo $task['title'];
                                    echo '</a>';

                                    if (!empty($task['category_name'])) {
                                        echo '<span class="badge ms-1" style="background-color: ' . $task['category_color'] . '; font-size: 0.65rem;">';
                                        echo $task['category_name'];
                                        echo '</span>';
                                    }

                                    echo '</div>';
                                }
                            } else {
                                // Add a small plus icon for adding tasks
                                echo '<a href="tasks.php?action=create&due_date=' . $dayStr . ' ' . sprintf('%02d:00', $hour) . '" class="btn btn-sm btn-link text-muted p-0">';
                                echo '<i class="fas fa-plus-circle"></i>';
                                echo '</a>';
                            }

                            echo '</td>';
                        }

                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Tasks Outside Working Hours -->
        <div class="card mt-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Tasks Outside Working Hours</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <?php
                                foreach ($weekDays as $day) {
                                    $isToday = ($day->format('Y-m-d') === date('Y-m-d'));
                                    $dayClass = $isToday ? 'bg-light' : '';
                                    echo '<th class="text-center ' . $dayClass . '">';
                                    echo $day->format('D, M d');
                                    echo '</th>';
                                }
                                ?>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <?php
                                foreach ($weekDays as $day) {
                                    $dayStr = $day->format('Y-m-d');
                                    $isToday = ($dayStr === date('Y-m-d'));
                                    $cellClass = $isToday ? 'bg-light' : '';

                                    echo '<td class="' . $cellClass . '">';

                                    // Check if there are tasks outside the working hours
                                    $hasTasks = false;
                                    if (isset($tasksByDay[$dayStr])) {
                                        foreach ($tasksByDay[$dayStr] as $hourKey => $hourTasks) {
                                            if ((int) $hourKey < 8 || (int) $hourKey >= 21) {
                                                $hasTasks = true;
                                                break;
                                            }
                                        }
                                    }

                                    if ($hasTasks) {
                                        echo '<ul class="list-group list-group-flush">';

                                        // Morning hours (before 8 AM)
                                        for ($hour = 0; $hour < 8; $hour++) {
                                            if (isset($tasksByDay[$dayStr][$hour]) && !empty($tasksByDay[$dayStr][$hour])) {
                                                foreach ($tasksByDay[$dayStr][$hour] as $task) {
                                                    outputOutsideHoursTask($task);
                                                }
                                            }
                                        }

                                        // Evening hours (after 8 PM)
                                        for ($hour = 21; $hour < 24; $hour++) {
                                            if (isset($tasksByDay[$dayStr][$hour]) && !empty($tasksByDay[$dayStr][$hour])) {
                                                foreach ($tasksByDay[$dayStr][$hour] as $task) {
                                                    outputOutsideHoursTask($task);
                                                }
                                            }
                                        }

                                        echo '</ul>';
                                    } else {
                                        echo '<div class="text-center text-muted small">No tasks</div>';
                                    }

                                    echo '</td>';
                                }

                                // Helper function to output tasks
                                function outputOutsideHoursTask($task)
                                {
                                    $statusClass = $task['status'] === 'completed' ? 'text-decoration-line-through' : '';
                                    $priorityClass = getPriorityClass($task['priority']);

                                    echo '<li class="list-group-item border-0 p-1">';
                                    echo '<div class="d-flex justify-content-between align-items-center">';
                                    echo '<div class="' . $statusClass . ' small">';
                                    echo '<a href="tasks.php?action=view&id=' . $task['id'] . '">' . $task['title'] . '</a>';
                                    echo '<span class="ms-2 badge bg-' . $priorityClass . '">' . ucfirst($task['priority']) . '</span>';
                                    echo '</div>';
                                    echo '<small class="text-muted">' . date('g:i A', strtotime($task['due_date'])) . '</small>';
                                    echo '</div>';
                                    echo '</li>';
                                }
                                ?>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .week-calendar th,
    .week-calendar td {
        width: 14%;
        height: 80px;
        vertical-align: top;
    }

    .week-task {
        border-radius: 4px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>

<?php
// Removed footer include to prevent duplication
?>