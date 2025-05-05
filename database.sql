-- database.sql
-- Database schema for ToDoList application

-- Create database (if not exists)
CREATE DATABASE IF NOT EXISTS todolist CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Use the database
USE todolist;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    profile_image VARCHAR(255) DEFAULT NULL,
    theme_preference ENUM('light', 'dark') DEFAULT 'light',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Categories table
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    name VARCHAR(50) NOT NULL,
    color VARCHAR(7) DEFAULT '#3498db',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Tasks table
CREATE TABLE IF NOT EXISTS tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(100) NOT NULL,
    description TEXT,
    due_date DATETIME,
    priority ENUM('low', 'medium', 'high') DEFAULT 'medium',
    status ENUM('pending', 'completed') DEFAULT 'pending',
    category_id INT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Comments table
CREATE TABLE IF NOT EXISTS comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    task_id INT NOT NULL,
    user_id INT NOT NULL,
    comment TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (task_id) REFERENCES tasks(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Task sharing table
CREATE TABLE IF NOT EXISTS task_sharing (
    id INT AUTO_INCREMENT PRIMARY KEY,
    task_id INT NOT NULL,
    user_id INT NOT NULL,
    permission ENUM('view', 'edit') DEFAULT 'view',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (task_id) REFERENCES tasks(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Task history table for undo/redo functionality
CREATE TABLE IF NOT EXISTS task_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    task_id INT NOT NULL,
    user_id INT NOT NULL,
    action_type ENUM('create', 'update', 'delete', 'status_change') NOT NULL,
    previous_data JSON,
    new_data JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (task_id) REFERENCES tasks(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Create indexes for better performance
CREATE INDEX idx_tasks_user_id ON tasks(user_id);
CREATE INDEX idx_tasks_status ON tasks(status);
CREATE INDEX idx_tasks_priority ON tasks(priority);
CREATE INDEX idx_tasks_due_date ON tasks(due_date);
CREATE INDEX idx_tasks_category_id ON tasks(category_id);
CREATE INDEX idx_categories_user_id ON categories(user_id);
CREATE INDEX idx_comments_task_id ON comments(task_id);
CREATE INDEX idx_task_sharing_task_id ON task_sharing(task_id);
CREATE INDEX idx_task_sharing_user_id ON task_sharing(user_id);
CREATE INDEX idx_task_history_task_id ON task_history(task_id);
CREATE INDEX idx_task_history_user_id ON task_history(user_id);

-- Insert a default admin user (password: admin1234)
INSERT INTO users (id, username, email, password, theme_preference)
VALUES (1, 'admin', 'admin@example.com', '$2y$10$JR9.sKVczK9n8cP9/j9j2uGM.LCBkJjd8PiYvkwjgD4oFYKzn9STu', 'light');

-- Then insert default categories
INSERT INTO categories (user_id, name, color) VALUES 
(1, 'Work', '#4e73df'),
(1, 'Personal', '#1cc88a'),
(1, 'Urgent', '#e74a3b');

-- Insert sample tasks for demo
INSERT INTO tasks (user_id, title, description, due_date, priority, status, category_id) VALUES
(1, 'Complete project proposal', 'Draft the project proposal for client review', DATE_ADD(NOW(), INTERVAL 2 DAY), 'high', 'pending', 1),
(1, 'Buy groceries', 'Milk, eggs, bread, fruits', DATE_ADD(NOW(), INTERVAL 1 DAY), 'medium', 'pending', 2),
(1, 'Schedule doctor appointment', 'Annual check-up', DATE_ADD(NOW(), INTERVAL 5 DAY), 'low', 'pending', 2),
(1, 'Pay utility bills', 'Electricity, water, internet', DATE_ADD(NOW(), INTERVAL -1 DAY), 'high', 'completed', 3),
(1, 'Team meeting', 'Weekly progress meeting with team', DATE_ADD(NOW(), INTERVAL 3 DAY), 'medium', 'pending', 1);