<?php
// views/layouts/header.php
// Header layout for all pages

// Get current theme
$theme = isLoggedIn() ? getUserTheme() : 'light';
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="<?php echo $theme; ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Manager - <?php echo $pageTitle ?? 'Home'; ?></title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <!-- Flatpickr (Date Picker) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">

    <style>
        :root {
            --primary-color: #4e73df;
            --primary-dark: #224abe;
            --secondary-color: #6c757d;
            --success-color: #1cc88a;
            --info-color: #36b9cc;
            --warning-color: #f6c23e;
            --danger-color: #e74a3b;
            --light-gray: #f8f9fc;
            --dark-gray: #5a5c69;
        }

        body {
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Modern navbar styles */
        .navbar {
            padding: 0.7rem 1rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-weight: 700;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1.3rem;
        }

        .navbar-brand i {
            font-size: 1.4rem;
        }

        .bg-gradient-primary-to-secondary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        }

        .form-control:focus,
        .input-group-text:focus,
        .form-check-input:focus {
            box-shadow: 0 0 0 0.25rem rgba(78, 115, 223, 0.25);
            border-color: #bac8f3;
        }

        /* Enhanced navigation links */
        .nav-link {
            position: relative;
            font-weight: 500;
            padding: 0.8rem 1.2rem;
            transition: all 0.3s ease;
            border-radius: 5px;
            margin: 0 0.15rem;
        }

        .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .nav-link.active {
            background-color: rgba(255, 255, 255, 0.2);
        }

        .nav-link i {
            margin-right: 6px;
            position: relative;
            top: 1px;
        }

        /* Improved dropdown menus */
        .dropdown-menu {
            padding: 0.5rem 0;
            border: none;
            border-radius: 8px;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .dropdown-item {
            padding: 0.7rem 1.5rem;
            font-weight: 500;
            transition: all 0.2s ease;
            position: relative;
        }

        .dropdown-item:hover {
            background-color: rgba(78, 115, 223, 0.1);
            padding-left: 1.8rem;
        }

        .dropdown-item i {
            width: 1.25rem;
            text-align: center;
            margin-right: 0.5rem;
            transition: all 0.2s ease;
        }

        .dropdown-item:hover i {
            color: var(--primary-color);
        }

        .dropdown-divider {
            margin: 0.3rem 0;
        }

        /* Create Task button */
        .btn-create-task {
            background: var(--success-color);
            border-color: var(--success-color);
            font-weight: 600;
            padding: 0.5rem 1rem;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: all 0.2s ease;
            border-radius: 6px;
        }

        .btn-create-task:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
            background-color: #19b378;
            border-color: #19b378;
        }

        /* User profile dropdown */
        .user-dropdown-toggle {
            display: flex;
            align-items: center;
            font-weight: 600;
            padding: 0.5rem 1rem;
            transition: all 0.2s ease;
            border-radius: 6px;
        }

        .user-dropdown-toggle:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .user-dropdown-toggle i {
            font-size: 1.2rem;
            margin-right: 8px;
        }

        .card {
            border-radius: 0.5rem;
            overflow: hidden;
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100">
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-gradient-primary-to-secondary sticky-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-check-circle"></i> Task Manager
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <?php if (isLoggedIn()): ?>
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link <?php echo $pageTitle === 'Dashboard' ? 'active' : ''; ?>" href="index.php">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $pageTitle === 'My Tasks' ? 'active' : ''; ?>" href="tasks.php">
                                <i class="fas fa-tasks"></i> My Tasks
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo strpos($pageTitle, 'Calendar') !== false ? 'active' : ''; ?>"
                                href="calendar.php">
                                <i class="fas fa-calendar-alt"></i> Calendar
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="calendarDropdown" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-calendar-day"></i> Calendar Views
                            </a>
                            <ul class="dropdown-menu shadow-sm" aria-labelledby="calendarDropdown">
                                <li><a class="dropdown-item" href="calendar.php?view=day"><i
                                            class="fas fa-calendar-day"></i> Day View</a></li>
                                <li><a class="dropdown-item" href="calendar.php?view=week"><i
                                            class="fas fa-calendar-week"></i> Week View</a></li>
                                <li><a class="dropdown-item" href="calendar.php?view=month"><i
                                            class="fas fa-calendar-alt"></i> Month View</a></li>
                                <li><a class="dropdown-item" href="calendar.php?view=year"><i class="fas fa-calendar"></i>
                                        Year View</a></li>
                            </ul>
                        </li>
                    </ul>

                    <ul class="navbar-nav">
                        <li class="nav-item me-2">
                            <a class="btn btn-create-task text-white" href="tasks.php?action=create">
                                <i class="fas fa-plus me-1"></i> New Task
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle user-dropdown-toggle" href="#" id="userDropdown"
                                role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user-circle"></i> <?php echo getCurrentUsername(); ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="profile.php"><i class="fas fa-user"></i> Profile</a></li>
                                <li><a class="dropdown-item" href="profile.php?action=statistics"><i
                                            class="fas fa-chart-bar"></i> Statistics</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item" href="auth.php?action=toggle_theme">
                                        <?php if ($theme === 'light'): ?>
                                            <i class="fas fa-moon"></i> Dark Mode
                                        <?php else: ?>
                                            <i class="fas fa-sun"></i> Light Mode
                                        <?php endif; ?>
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item text-danger" href="auth.php?action=logout"><i
                                            class="fas fa-sign-out-alt"></i> Logout</a></li>
                            </ul>
                        </li>
                    </ul>
                <?php else: ?>
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link <?php echo $pageTitle === 'Login' ? 'active' : ''; ?>" href="login.php">
                                <i class="fas fa-sign-in-alt me-1"></i> Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $pageTitle === 'Register' ? 'active' : ''; ?>"
                                href="register.php">
                                <i class="fas fa-user-plus me-1"></i> Register
                            </a>
                        </li>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Main Content Container -->
    <div class="container my-4 flex-grow-1">
        <?php displayFlashMessage(); ?>