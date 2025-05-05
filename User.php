<?php
// models/User.php
// User model for authentication and profile management

class User
{
    // Register a new user
    public static function register($username, $email, $password)
    {
        // Check if username already exists
        $existingUser = fetchOne("SELECT id FROM users WHERE username = ?", [$username]);
        if ($existingUser) {
            return ['success' => false, 'message' => 'Username already exists'];
        }

        // Check if email already exists
        $existingEmail = fetchOne("SELECT id FROM users WHERE email = ?", [$email]);
        if ($existingEmail) {
            return ['success' => false, 'message' => 'Email already exists'];
        }

        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert new user
        $userId = insert('users', [
            'username' => $username,
            'email' => $email,
            'password' => $hashedPassword,
            'theme_preference' => 'light'
        ]);

        if ($userId) {
            // Create default categories for the user
            $defaultCategories = [
                ['name' => 'Work', 'color' => '#4e73df', 'user_id' => $userId],
                ['name' => 'Personal', 'color' => '#1cc88a', 'user_id' => $userId],
                ['name' => 'Urgent', 'color' => '#e74a3b', 'user_id' => $userId]
            ];

            foreach ($defaultCategories as $category) {
                insert('categories', $category);
            }

            return ['success' => true, 'message' => 'Registration successful', 'user_id' => $userId];
        } else {
            return ['success' => false, 'message' => 'Registration failed'];
        }
    }

    // Login user
    public static function login($email, $password)
    {
        // Get user by email
        $user = fetchOne("SELECT * FROM users WHERE email = ?", [$email]);

        if (!$user) {
            return ['success' => false, 'message' => 'Invalid email or password'];
        }

        // Verify password
        if (password_verify($password, $user['password'])) {
            return ['success' => true, 'message' => 'Login successful', 'user' => $user];
        } else {
            return ['success' => false, 'message' => 'Invalid email or password'];
        }
    }

    // Get user by ID
    public static function getUserById($userId)
    {
        return fetchOne("SELECT id, username, email, profile_image, theme_preference, created_at FROM users WHERE id = ?", [$userId]);
    }

    // Update user profile
    public static function updateProfile($userId, $data)
    {
        // Check if email already exists for another user
        if (isset($data['email'])) {
            $existingEmail = fetchOne("SELECT id FROM users WHERE email = ? AND id != ?", [$data['email'], $userId]);
            if ($existingEmail) {
                return ['success' => false, 'message' => 'Email already exists'];
            }
        }

        // Check if username already exists for another user
        if (isset($data['username'])) {
            $existingUser = fetchOne("SELECT id FROM users WHERE username = ? AND id != ?", [$data['username'], $userId]);
            if ($existingUser) {
                return ['success' => false, 'message' => 'Username already exists'];
            }
        }

        // Update user profile
        update('users', $data, 'id = ?', [$userId]);

        return ['success' => true, 'message' => 'Profile updated successfully'];
    }

    // Change password
    public static function changePassword($userId, $currentPassword, $newPassword)
    {
        // Get user
        $user = fetchOne("SELECT password FROM users WHERE id = ?", [$userId]);

        // Verify current password
        if (!password_verify($currentPassword, $user['password'])) {
            return ['success' => false, 'message' => 'Current password is incorrect'];
        }

        // Hash new password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Update password
        update('users', ['password' => $hashedPassword], 'id = ?', [$userId]);

        return ['success' => true, 'message' => 'Password changed successfully'];
    }

    // Upload profile image
    public static function uploadProfileImage($userId, $file)
    {
        // Check if file is valid
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'message' => 'File upload failed'];
        }

        // Check file type
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($file['type'], $allowedTypes)) {
            return ['success' => false, 'message' => 'Invalid file type. Only JPG, PNG, and GIF are allowed.'];
        }

        // Check file size (max 2MB)
        if ($file['size'] > 2 * 1024 * 1024) {
            return ['success' => false, 'message' => 'File size too large. Maximum size is 2MB.'];
        }

        // Create uploads directory if it doesn't exist
        $uploadDir = 'uploads/profile_images/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Generate unique filename
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid('profile_') . '.' . $extension;
        $filepath = $uploadDir . $filename;

        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            // Update user profile with new image path
            update('users', ['profile_image' => $filepath], 'id = ?', [$userId]);

            return ['success' => true, 'message' => 'Profile image updated successfully', 'filepath' => $filepath];
        } else {
            return ['success' => false, 'message' => 'Failed to save uploaded file'];
        }
    }

    // Get user task counts for dashboard
    public static function getUserTaskCounts($userId)
    {
        $counts = [];

        // Total tasks
        $sql = "SELECT COUNT(*) as count FROM tasks WHERE user_id = ?";
        $result = fetchOne($sql, [$userId]);
        $counts['total'] = $result['count'];

        // Pending tasks
        $sql = "SELECT COUNT(*) as count FROM tasks WHERE user_id = ? AND status = 'pending'";
        $result = fetchOne($sql, [$userId]);
        $counts['pending'] = $result['count'];

        // Completed tasks
        $sql = "SELECT COUNT(*) as count FROM tasks WHERE user_id = ? AND status = 'completed'";
        $result = fetchOne($sql, [$userId]);
        $counts['completed'] = $result['count'];

        // Tasks due today
        $today = date('Y-m-d');
        $sql = "SELECT COUNT(*) as count FROM tasks WHERE user_id = ? AND DATE(due_date) = ?";
        $result = fetchOne($sql, [$userId, $today]);
        $counts['today'] = $result['count'];

        // Overdue tasks
        $sql = "SELECT COUNT(*) as count FROM tasks WHERE user_id = ? AND DATE(due_date) < ? AND status = 'pending'";
        $result = fetchOne($sql, [$userId, $today]);
        $counts['overdue'] = $result['count'];

        return $counts;
    }
}
?>