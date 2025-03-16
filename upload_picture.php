<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["profile_picture"])) {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
    move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file);
    
    $query = "UPDATE users SET profile_picture = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $target_file, $_SESSION['user_id']);
    $stmt->execute();
    echo "Profile picture updated successfully!";
}
?>