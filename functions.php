<?php
// includes/functions.php
// Helper functions for the application

// Clean input data to prevent XSS attacks
function cleanInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Redirect to another page
function redirect($location) {
    header("Location: $location");
    exit;
}

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Check if user is admin
function isAdmin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

// Flash messages
function setFlashMessage($type, $message) {
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message
    ];
}

function getFlashMessage() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

// Display flash message
function displayFlashMessage() {
    $flash = getFlashMessage();
    if ($flash) {
        $type = $flash['type'];
        $message = $flash['message'];
        echo "<div class='alert alert-$type alert-dismissible fade show' role='alert'>
                $message
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
              </div>";
    }
}

// Format date for display
function formatDate($date, $format = 'M d, Y') {
    return date($format, strtotime($date));
}

// Get time ago string
function timeAgo($datetime) {
    $time = strtotime($datetime);
    $now = time();
    $diff = $now - $time;
    
    if ($diff < 60) {
        return 'just now';
    } elseif ($diff < 3600) {
        $minutes = floor($diff / 60);
        return $minutes . ' minute' . ($minutes > 1 ? 's' : '') . ' ago';
    } elseif ($diff < 86400) {
        $hours = floor($diff / 3600);
        return $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ago';
    } elseif ($diff < 604800) {
        $days = floor($diff / 86400);
        return $days . ' day' . ($days > 1 ? 's' : '') . ' ago';
    } elseif ($diff < 2592000) {
        $weeks = floor($diff / 604800);
        return $weeks . ' week' . ($weeks > 1 ? 's' : '') . ' ago';
    } elseif ($diff < 31536000) {
        $months = floor($diff / 2592000);
        return $months . ' month' . ($months > 1 ? 's' : '') . ' ago';
    } else {
        $years = floor($diff / 31536000);
        return $years . ' year' . ($years > 1 ? 's' : '') . ' ago';
    }
}

// Get task priority class for color styling
function getPriorityClass($priority) {
    switch ($priority) {
        case 'high':
            return 'danger';
        case 'medium':
            return 'warning';
        case 'low':
            return 'success';
        default:
            return 'secondary';
    }
}

// Calculate task completion percentage
function calculateCompletionPercentage($userId) {
    $totalTasks = fetchOne("SELECT COUNT(*) as total FROM tasks WHERE user_id = ?", [$userId]);
    $completedTasks = fetchOne("SELECT COUNT(*) as completed FROM tasks WHERE user_id = ? AND status = 'completed'", [$userId]);
    
    if ($totalTasks['total'] == 0) {
        return 0;
    }
    
    return round(($completedTasks['completed'] / $totalTasks['total']) * 100);
}

// Generate pagination links
function generatePagination($currentPage, $totalPages, $urlPattern) {
    $html = '<nav aria-label="Page navigation"><ul class="pagination justify-content-center">';
    
    // Previous button
    if ($currentPage > 1) {
        $html .= '<li class="page-item"><a class="page-link" href="' . sprintf($urlPattern, $currentPage - 1) . '">Previous</a></li>';
    } else {
        $html .= '<li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>';
    }
    
    // Page numbers
    $start = max(1, $currentPage - 2);
    $end = min($totalPages, $currentPage + 2);
    
    if ($start > 1) {
        $html .= '<li class="page-item"><a class="page-link" href="' . sprintf($urlPattern, 1) . '">1</a></li>';
        if ($start > 2) {
            $html .= '<li class="page-item disabled"><a class="page-link" href="#">...</a></li>';
        }
    }
    
    for ($i = $start; $i <= $end; $i++) {
        if ($i == $currentPage) {
            $html .= '<li class="page-item active"><a class="page-link" href="#">' . $i . '</a></li>';
        } else {
            $html .= '<li class="page-item"><a class="page-link" href="' . sprintf($urlPattern, $i) . '">' . $i . '</a></li>';
        }
    }
    
    if ($end < $totalPages) {
        if ($end < $totalPages - 1) {
            $html .= '<li class="page-item disabled"><a class="page-link" href="#">...</a></li>';
        }
        $html .= '<li class="page-item"><a class="page-link" href="' . sprintf($urlPattern, $totalPages) . '">' . $totalPages . '</a></li>';
    }
    
    // Next button
    if ($currentPage < $totalPages) {
        $html .= '<li class="page-item"><a class="page-link" href="' . sprintf($urlPattern, $currentPage + 1) . '">Next</a></li>';
    } else {
        $html .= '<li class="page-item disabled"><a class="page-link" href="#">Next</a></li>';
    }
    
    $html .= '</ul></nav>';
    
    return $html;
}

// Get user categories
function getUserCategories($userId) {
    return fetchAll("SELECT * FROM categories WHERE user_id = ? ORDER BY name ASC", [$userId]);
}
?>