<?php
// includes/session.php
// Session management functions

// Start session
session_start();

// Set session timeout to 2 hours (7200 seconds)
$session_timeout = 7200;

// Check if session has expired
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $session_timeout)) {
    // Session has expired, destroy it
    session_unset();
    session_destroy();
    
    // Redirect to login page
    header("Location: login.php?expired=1");
    exit;
}

// Update last activity time
$_SESSION['last_activity'] = time();

// Regenerate session ID periodically (every 30 minutes) to prevent session fixation
if (!isset($_SESSION['created'])) {
    $_SESSION['created'] = time();
} else if (time() - $_SESSION['created'] > 1800) {
    // Session is older than 30 minutes, regenerate ID
    session_regenerate_id(true);
    $_SESSION['created'] = time();
}

// Set secure session cookie parameters (if HTTPS is available)
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
    $cookieParams = session_get_cookie_params();
    session_set_cookie_params(
        $cookieParams["lifetime"],
        $cookieParams["path"],
        $cookieParams["domain"],
        true,  // Secure
        true   // HttpOnly
    );
}

// Function to get current user ID
function getCurrentUserId() {
    return $_SESSION['user_id'] ?? null;
}

// Function to get current username
function getCurrentUsername() {
    return $_SESSION['username'] ?? null;
}

// Function to get current user's theme preference
function getUserTheme() {
    return $_SESSION['theme_preference'] ?? 'light';
}

// Function to set user theme preference
function setUserTheme($theme) {
    $_SESSION['theme_preference'] = $theme;
    
    // Also update in database if user is logged in
    if (isLoggedIn()) {
        update('users', ['theme_preference' => $theme], 'id = ?', [getCurrentUserId()]);
    }
}

// Function to require login for protected pages
function requireLogin() {
    if (!isLoggedIn()) {
        setFlashMessage('warning', 'You must be logged in to access that page.');
        redirect('login.php');
    }
}

// Function to store user data in session after login
function setUserSession($user) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['theme_preference'] = $user['theme_preference'];
    $_SESSION['created'] = time();
    $_SESSION['last_activity'] = time();
    
    // Regenerate session ID for security
    session_regenerate_id(true);
}

// Function to clear user session on logout
function clearUserSession() {
    session_unset();
    session_destroy();
    
    // Start a new session for flash messages
    session_start();
}
?>