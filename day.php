<?php
// views/calendar/day.php
// Daily calendar view

$pageTitle = 'Calendar - Day View';
// Removed header include to prevent duplication

// Get day information
$currentDate = new DateTime($currentDate);
$currentDay = $currentDate->format('d');
$currentMonth = $currentDate->format('n');
$currentYear = $currentDate->format('Y');

// Calculate previous and next day dates
$prevDay = clone $currentDate;
$prevDay->modify('-1 day');

$nextDay = clone $currentDate;
$nextDay->modify('+1 day');

// Group tasks by hour
$tasksByHour = [];
foreach ($tasks as $task) {
    $taskHour = date('H', strtotime($task['due_date']));
    if (!isset($tasksByHour[$taskHour])) {
        $tasksByHour[$taskHour] = [];
    }
    $tasksByHour[$taskHour][] = $task;
}
?>

<div class="row mb-4">
    <div class="col">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-calendar-day"></i> Calendar
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
            <a href="calendar.php?view=day&date=<?php echo $prevDay->format('Y-m-d'); ?>"
                class="btn btn-outline-primary">
                <i class="fas fa-chevron-left"></i> Previous
            </a>
            <a href="calendar.php?view=day&date=<?php echo date('Y-m-d'); ?>" class="btn btn-outline-primary">
                Today
            </a>
            <a href="calendar.php?view=day&date=<?php echo $nextDay->format('Y-m-d'); ?>"
                class="btn btn-outline-primary">
                Next <i class="fas fa-chevron-right"></i>
            </a>
        </div>

        <h4 class="mb-0 font-weight-bold text-primary">
            <?php echo $currentDate->format('l, F j, Y'); ?>
        </h4>

        <div class="btn-group">
            <a href="calendar.php?view=day&date=<?php echo $currentDate->format('Y-m-d'); ?>"
                class="btn btn-outline-primary active">
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
                class="btn btn-outline-primary">
                Year
            </a>
        </div>
    </div>
    <div class="card-body">
        <!-- Day Calendar -->
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th width="100">Time</th>
                        <th>Tasks</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Display hourly slots for the day
                    for ($hour = 0; $hour < 24; $hour++) {
                        $formattedHour = sprintf('%02d:00', $hour);
                        $timeStr = date('g:i A', strtotime($formattedHour));

                        // Highlight current hour
                        $isCurrentHour = (date('Y-m-d') == $currentDate->format('Y-m-d') && (int) date('H') == $hour);
                        $rowClass = $isCurrentHour ? 'table-primary' : '';

                        echo '<tr class="' . $rowClass . '">';
                        echo '<td class="text-center">' . $timeStr . '</td>';
                        echo '<td>';

                        // Display tasks for this hour
                        if (isset($tasksByHour[$hour]) && !empty($tasksByHour[$hour])) {
                            echo '<div class="day-tasks">';
                            foreach ($tasksByHour[$hour] as $task) {
                                $statusClass = $task['status'] === 'completed' ? 'text-decoration-line-through' : '';
                                $priorityClass = getPriorityClass($task['priority']);

                                echo '<div class="day-task mb-2 p-2 border-start border-3 border-' . $priorityClass . ' bg-' . $priorityClass . '-subtle">';
                                echo '<div class="d-flex justify-content-between align-items-center">';
                                echo '<div class="' . $statusClass . '">';
                                echo '<a href="tasks.php?action=view&id=' . $task['id'] . '" class="fw-bold">' . $task['title'] . '</a>';

                                if (!empty($task['description'])) {
                                    echo '<p class="small mb-0">' . mb_substr($task['description'], 0, 100) . (mb_strlen($task['description']) > 100 ? '...' : '') . '</p>';
                                }

                                echo '<small class="text-muted">' . date('g:i A', strtotime($task['due_date'])) . '</small>';

                                if (!empty($task['category_name'])) {
                                    echo '<span class="badge ms-2" style="background-color: ' . $task['category_color'] . '">' . $task['category_name'] . '</span>';
                                }

                                echo '</div>';
                                echo '<div>';
                                echo '<span class="badge bg-' . $priorityClass . '">' . ucfirst($task['priority']) . '</span>';
                                echo '<div class="btn-group btn-group-sm ms-2">';
                                echo '<a href="tasks.php?action=edit&id=' . $task['id'] . '" class="btn btn-sm btn-primary" title="Edit"><i class="fas fa-edit"></i></a>';
                                echo '<a href="tasks.php?action=toggle&id=' . $task['id'] . '" class="btn btn-sm btn-' . ($task['status'] === 'completed' ? 'warning' : 'success') . '" title="' . ($task['status'] === 'completed' ? 'Mark as Pending' : 'Mark as Completed') . '">';
                                echo '<i class="fas ' . ($task['status'] === 'completed' ? 'fa-undo' : 'fa-check') . '"></i></a>';
                                echo '</div>';
                                echo '</div>';
                                echo '</div>';
                                echo '</div>';
                            }
                            echo '</div>';
                        } else {
                            // Add a "New Task" button for empty slots
                            echo '<a href="tasks.php?action=create&due_date=' . $currentDate->format('Y-m-d') . ' ' . sprintf('%02d:00', $hour) . '" class="btn btn-sm btn-outline-primary">';
                            echo '<i class="fas fa-plus"></i> Add Task</a>';
                        }

                        echo '</td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .day-tasks {
        max-height: 300px;
        overflow-y: auto;
    }

    .day-task {
        border-radius: 4px;
    }
</style>

<?php
// Removed footer include to prevent duplication
?>