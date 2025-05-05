<?php
// controllers/UserController.php
// Controller for user profile operations

require_once 'models/User.php';
require_once 'models/Task.php';

class UserController
{
    // Display user profile
    public function profile()
    {
        // Check if user is logged in
        requireLogin();

        $userId = getCurrentUserId();

        // Get user data
        $user = User::getUserById($userId);

        // Get task statistics
        $taskStats = Task::getTaskStatistics($userId);

        // Include header, view, and footer
        include 'views/layouts/header.php';
        include 'views/user/profile.php';
        include 'views/layouts/footer.php';
    }

    // Process profile update
    public function updateProfile()
    {
        // Check if user is logged in
        requireLogin();

        $userId = getCurrentUserId();

        // Validate form data
        $username = cleanInput($_POST['username']);
        $email = cleanInput($_POST['email']);

        if (empty($username) || empty($email)) {
            setFlashMessage('danger', 'Username and email are required');
            redirect('profile.php');
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            setFlashMessage('danger', 'Invalid email format');
            redirect('profile.php');
            return;
        }

        // Update profile
        $result = User::updateProfile($userId, [
            'username' => $username,
            'email' => $email
        ]);

        if ($result['success']) {
            // Update session data
            $_SESSION['username'] = $username;
            $_SESSION['email'] = $email;

            setFlashMessage('success', 'Profile updated successfully');
        } else {
            setFlashMessage('danger', $result['message']);
        }

        redirect('profile.php');
    }

    // Process password change
    public function changePassword()
    {
        // Check if user is logged in
        requireLogin();

        $userId = getCurrentUserId();

        // Validate form data
        $currentPassword = $_POST['current_password'];
        $newPassword = $_POST['new_password'];
        $confirmPassword = $_POST['confirm_password'];

        // Validation checks
        if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
            setFlashMessage('danger', 'All password fields are required');
            redirect('profile.php');
            return;
        }

        if (strlen($newPassword) < 6) {
            setFlashMessage('danger', 'New password must be at least 6 characters long');
            redirect('profile.php');
            return;
        }

        if ($newPassword !== $confirmPassword) {
            setFlashMessage('danger', 'New passwords do not match');
            redirect('profile.php');
            return;
        }

        // Change password
        $result = User::changePassword($userId, $currentPassword, $newPassword);

        if ($result['success']) {
            setFlashMessage('success', 'Password changed successfully');
        } else {
            setFlashMessage('danger', $result['message']);
        }

        redirect('profile.php');
    }

    // Process profile image upload
    public function uploadProfileImage()
    {
        // Check if user is logged in
        requireLogin();

        $userId = getCurrentUserId();

        // Check if file was uploaded
        if (!isset($_FILES['profile_image']) || $_FILES['profile_image']['error'] === UPLOAD_ERR_NO_FILE) {
            setFlashMessage('danger', 'No file was uploaded');
            redirect('profile.php');
            return;
        }

        // Validate file
        $file = $_FILES['profile_image'];
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $maxFileSize = 2 * 1024 * 1024; // 2MB

        if (!in_array($file['type'], $allowedTypes)) {
            setFlashMessage('danger', 'Invalid file type. Only JPG, PNG, and GIF are allowed');
            redirect('profile.php');
            return;
        }

        if ($file['size'] > $maxFileSize) {
            setFlashMessage('danger', 'File size too large. Maximum size is 2MB');
            redirect('profile.php');
            return;
        }

        // Upload profile image
        $result = User::uploadProfileImage($userId, $file);

        if ($result['success']) {
            setFlashMessage('success', 'Profile image updated successfully');
        } else {
            setFlashMessage('danger', $result['message']);
        }

        redirect('profile.php');
    }

    // Toggle user theme preference
    public function toggleTheme()
    {
        // Check if user is logged in
        requireLogin();

        $userId = getCurrentUserId();

        // Get current theme
        $currentTheme = getUserTheme();

        // Toggle theme
        $newTheme = $currentTheme === 'light' ? 'dark' : 'light';

        // Update user theme
        setUserTheme($newTheme);

        // Redirect back to referring page
        $referer = $_SERVER['HTTP_REFERER'] ?? 'index.php';
        redirect($referer);
    }

    // Display user statistics
    public function statistics()
    {
        // Check if user is logged in
        requireLogin();

        $userId = getCurrentUserId();

        // Get task statistics
        $taskStats = Task::getTaskStatistics($userId);

        // Include header, view, and footer
        include 'views/layouts/header.php';
        include 'views/user/statistics.php';
        include 'views/layouts/footer.php';
    }
}