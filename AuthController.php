<?php
// controllers/AuthController.php
// Controller for authentication operations

require_once 'models/User.php';

class AuthController
{
    // Display login form
    public function showLogin()
    {
        // Check if user is already logged in
        if (isLoggedIn()) {
            redirect('index.php');
        }

        // Display login form
        include 'views/auth/login.php';
    }

    // Process login form
    public function login()
    {
        // Check if user is already logged in
        if (isLoggedIn()) {
            redirect('index.php');
        }

        // Validate form data
        $email = cleanInput($_POST['email']);
        $password = $_POST['password'];

        if (empty($email) || empty($password)) {
            setFlashMessage('danger', 'Email and password are required');
            redirect('login.php');
            return;
        }

        // Attempt login
        $result = User::login($email, $password);

        if ($result['success']) {
            // Set user session
            setUserSession($result['user']);

            setFlashMessage('success', 'Login successful');
            redirect('index.php');
        } else {
            setFlashMessage('danger', $result['message']);
            redirect('login.php');
        }
    }

    // Display registration form
    public function showRegister()
    {
        // Check if user is already logged in
        if (isLoggedIn()) {
            redirect('index.php');
        }

        // Display registration form
        include 'views/auth/register.php';
    }

    // Process registration form
    public function register()
    {
        // Check if user is already logged in
        if (isLoggedIn()) {
            redirect('index.php');
        }

        // Validate form data
        $username = cleanInput($_POST['username']);
        $email = cleanInput($_POST['email']);
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirm_password'];

        // Validation checks
        $errors = [];

        if (empty($username)) {
            $errors[] = 'Username is required';
        }

        if (empty($email)) {
            $errors[] = 'Email is required';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email format';
        }

        if (empty($password)) {
            $errors[] = 'Password is required';
        } elseif (strlen($password) < 6) {
            $errors[] = 'Password must be at least 6 characters long';
        }

        if ($password !== $confirmPassword) {
            $errors[] = 'Passwords do not match';
        }

        if (!empty($errors)) {
            // Set error messages
            setFlashMessage('danger', implode('<br>', $errors));
            redirect('register.php');
            return;
        }

        // Attempt registration
        $result = User::register($username, $email, $password);

        if ($result['success']) {
            // Get user data for session
            $user = User::getUserById($result['user_id']);

            // Set user session
            setUserSession($user);

            setFlashMessage('success', 'Registration successful');
            redirect('index.php');
        } else {
            setFlashMessage('danger', $result['message']);
            redirect('register.php');
        }
    }

    // Process logout
    public function logout()
    {
        // Clear user session
        clearUserSession();

        setFlashMessage('success', 'You have been logged out');
        redirect('login.php');
    }

    // Display profile page
    public function profile()
    {
        // Check if user is logged in
        requireLogin();

        $userId = getCurrentUserId();

        // Get user data
        $user = User::getUserById($userId);

        // Include view
        include 'views/user/profile.php';
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

        // Upload profile image
        $result = User::uploadProfileImage($userId, $_FILES['profile_image']);

        if ($result['success']) {
            setFlashMessage('success', 'Profile image updated successfully');
        } else {
            setFlashMessage('danger', $result['message']);
        }

        redirect('profile.php');
    }

    // Toggle theme preference
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
}