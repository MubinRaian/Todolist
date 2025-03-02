<?php
// db.php - Database Connection
$host = 'localhost';
$dbname = 'todo_db';
$username = 'root'; // Change this if needed
$password = ''; // Change this if needed

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>