<?php
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "todo_db"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM tasks WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['user_id']); 
$stmt->execute();
$result = $stmt->get_result();
$tasks = $result->fetch_all(MYSQLI_ASSOC);


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $due_date = $_POST['due_date'];
    $priority = $_POST['priority'];
    $category = $_POST['category'];
    $tags = $_POST['tags'];
    
    $insert_sql = "INSERT INTO tasks (user_id, title, description, due_date, priority, category, tags) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $insert_stmt = $conn->prepare($insert_sql);
    $insert_stmt->bind_param("issssss", $_SESSION['user_id'], $title, $description, $due_date, $priority, $category, $tags);
    $insert_stmt->execute();
    
    header("Location: dashboard.php"); 
    exit;
}


if (isset($_GET['complete'])) {
    $task_id = $_GET['complete'];
    $update_sql = "UPDATE tasks SET status = 'Complete' WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("i", $task_id);
    $update_stmt->execute();
    
    header("Location: dashboard.php"); 
    exit;
}


if (isset($_GET['delete'])) {
    $task_id = $_GET['delete'];
    $delete_sql = "DELETE FROM tasks WHERE id = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bind_param("i", $task_id);
    $delete_stmt->execute();
    
    header("Location: dashboard.php"); 
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body>
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
    <div class="task-container">
        <h2>My Tasks</h2>
        <button onclick="toggleDarkMode()">🌙 Dark Mode</button>
    
        <form id="task-form" method="POST">
            <input type="text" id="title" name="title" placeholder="Task Title" required>
            <textarea id="description" name="description" placeholder="Description"></textarea>
            <input type="date" id="due_date" name="due_date">
            <select id="priority" name="priority">
                <option value="Low">Low</option>
                <option value="Medium">Medium</option>
                <option value="High">High</option>
            </select>
            <input type="text" id="category" name="category" placeholder="Category">
            <input type="text" id="tags" name="tags" placeholder="Tags (comma separated)">
            <button type="submit">Add Task</button>
        </form>
    
        <input type="text" id="search" placeholder="Search tasks..." onkeyup="searchTasks()">
    
        <div id="task-list">
            <?php foreach ($tasks as $task): ?>
                <div class="task">
                    <h3><?php echo htmlspecialchars($task['title']); ?></h3>
                    <p><?php echo htmlspecialchars($task['description']); ?></p>
                    <p>Due: <?php echo $task['due_date']; ?> | Priority: <?php echo $task['priority']; ?></p>
                    <button onclick="window.location.href='dashboard.php?complete=<?php echo $task['id']; ?>'">✅ Complete</button>
                    <button onclick="window.location.href='dashboard.php?delete=<?php echo $task['id']; ?>'">❌ Delete</button>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <p><a href="logout.php">Logout</a></p>
</body>
</html>
s
