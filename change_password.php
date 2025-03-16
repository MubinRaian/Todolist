<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $old_password = $_POST['old_password'];
    $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
    
    $query = "UPDATE users SET password = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $new_password, $user_id);
    if ($stmt->execute()) {
        echo "Password changed successfully!";
    } else {
        echo "Error updating password.";
    }
}
?>