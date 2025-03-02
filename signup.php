<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $contact = $_POST['contact'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    try {
        echo "Debug: Username = $username, Email = $email, Contact = $contact <br>";

        $stmt = $pdo->prepare("INSERT INTO users (username, email, contact, password_hash) VALUES (?, ?, ?, ?)");
        $stmt->execute([$username, $email, $contact, $password]);

        echo "✅ Registration successful! <a href='index.html'>Login here</a>";
    } catch (PDOException $e) {
        echo "❌ Error: " . $e->getMessage();  
    }
}
?>
