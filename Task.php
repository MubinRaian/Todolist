<?php
// models/Task.php
// Task model for CRUD operations

class Task
{
    // Get all tasks for a user with optional filtering
    public static function getAllTasks($userId, $filters = [])
    {
        $sql = "SELECT t.*, c.name as category_name, c.color as category_color 
                FROM tasks t 
                LEFT JOIN categories c ON t.category_id = c.id 
                WHERE t.user_id = ?";
        
        $params = [$userId];
        
        // Apply filters
        if (!empty($filters)) {
            // Filter by status
            if (isset($filters['status']) && $filters['status'] !== 'all') {
                $sql .= " AND t.status = ?";
                $params[] = $filters['status'];
            }
            
            // Filter by priority
            if (isset($filters['priority']) && $filters['priority'] !== 'all') {
                $sql .= " AND t.priority = ?";
                $params[] = $filters['priority'];
            }
            
            // Filter by category
            if (isset($filters['category_id']) && $filters['category_id'] != 0) {
                $sql .= " AND t.category_id = ?";
                $params[] = $filters['category_id'];
            }
            
            // Filter by search term
            if (isset($filters['search']) && !empty($filters['search'])) {
                $sql .= " AND (t.title LIKE ? OR t.description LIKE ?)";
                $searchTerm = "%{$filters['search']}%";
                $params[] = $searchTerm;
                $params[] = $searchTerm;
            }
            
            // Filter by due date range
            if (isset($filters['date_from']) && !empty($filters['date_from'])) {
                $sql .= " AND t.due_date >= ?";
                $params[] = $filters['date_from'] . ' 00:00:00';
            }
            
            if (isset($filters['date_to']) && !empty($filters['date_to'])) {
                $sql .= " AND t.due_date <= ?";
                $params[] = $filters['date_to'] . ' 23:59:59';
            }
        }
        
        // Apply sorting
        $orderBy = 'due_date ASC'; // Default order
        
        if (isset($filters['sort_by'])) {
            switch ($filters['sort_by']) {
                case 'title':
                    $orderBy = 'title ASC';
                    break;
                case 'priority':
                    $orderBy = 'FIELD(priority, "high", "medium", "low")';
                    break;
                case 'due_date_asc':
                    $orderBy = 'due_date ASC';
                    break;
                case 'due_date_desc':
                    $orderBy = 'due_date DESC';
                    break;
                case 'created_asc':
                    $orderBy = 'created_at ASC';
                    break;
                case 'created_desc':
                    $orderBy = 'created_at DESC';
                    break;
            }
        }
        
        $sql .= " ORDER BY $orderBy";
        
        // Apply pagination
        if (isset($filters['page']) && isset($filters['limit'])) {
            $page = (int) $filters['page'];
            $limit = (int) $filters['limit'];
            $offset = ($page - 1) * $limit;
            
            $sql .= " LIMIT $offset, $limit";
        }
        
        return fetchAll($sql, $params);
    }
    
    // Count total tasks for pagination
    public static function countTasks($userId, $filters = [])
    {
        $sql = "SELECT COUNT(*) as total FROM tasks WHERE user_id = ?";
        $params = [$userId];
        
        // Apply filters (same as in getAllTasks)
        if (!empty($filters)) {
            // Filter by status
            if (isset($filters['status']) && $filters['status'] !== 'all') {
                $sql .= " AND status = ?";
                $params[] = $filters['status'];
            }
            
            // Filter by priority
            if (isset($filters['priority']) && $filters['priority'] !== 'all') {
                $sql .= " AND priority = ?";
                $params[] = $filters['priority'];
            }
            
            // Filter by category
            if (isset($filters['category_id']) && $filters['category_id'] != 0) {
                $sql .= " AND category_id = ?";
                $params[] = $filters['category_id'];
            }
            
            // Filter by search term
            if (isset($filters['search']) && !empty($filters['search'])) {
                $sql .= " AND (title LIKE ? OR description LIKE ?)";
                $searchTerm = "%{$filters['search']}%";
                $params[] = $searchTerm;
                $params[] = $searchTerm;
            }
            
            // Filter by due date range
            if (isset($filters['date_from']) && !empty($filters['date_from'])) {
                $sql .= " AND due_date >= ?";
                $params[] = $filters['date_from'] . ' 00:00:00';
            }
            
            if (isset($filters['date_to']) && !empty($filters['date_to'])) {
                $sql .= " AND due_date <= ?";
                $params[] = $filters['date_to'] . ' 23:59:59';
            }
        }
        
        $result = fetchOne($sql, $params);
        return $result['total'];
    }
    
    // Get a single task by ID
    public static function getTaskById($taskId, $userId)
    {
        $sql = "SELECT t.*, c.name as category_name, c.color as category_color 
                FROM tasks t 
                LEFT JOIN categories c ON t.category_id = c.id 
                WHERE t.id = ? AND t.user_id = ?";
        
        return fetchOne($sql, [$taskId, $userId]);
    }
    
    // Create a new task
    public static function createTask($data)
    {
        return insert('tasks', $data);
    }
    
    // Update an existing task
    public static function updateTask($taskId, $userId, $data)
    {
        update('tasks', $data, 'id = ? AND user_id = ?', [$taskId, $userId]);
        return true;
    }
    
    // Delete a task
    public static function deleteTask($taskId, $userId)
    {
        delete('tasks', 'id = ? AND user_id = ?', [$taskId, $userId]);
        return true;
    }
    
    // Mark a task as completed or pending
    public static function toggleTaskStatus($taskId, $userId)
    {
        $task = self::getTaskById($taskId, $userId);
        
        if (!$task) {
            return false;
        }
        
        $newStatus = $task['status'] === 'completed' ? 'pending' : 'completed';
        
        update('tasks', ['status' => $newStatus], 'id = ? AND user_id = ?', [$taskId, $userId]);
        return true;
    }
    
    // Get tasks for calendar view
    public static function getTasksForCalendar($userId, $startDate, $endDate)
    {
        $sql = "SELECT t.*, c.name as category_name, c.color as category_color 
                FROM tasks t 
                LEFT JOIN categories c ON t.category_id = c.id 
                WHERE t.user_id = ? AND t.due_date BETWEEN ? AND ?
                ORDER BY t.due_date ASC";
        
        return fetchAll($sql, [$userId, $startDate, $endDate]);
    }
    
    // Get tasks due today
    public static function getTasksDueToday($userId)
    {
        $today = date('Y-m-d');
        
        $sql = "SELECT t.*, c.name as category_name, c.color as category_color 
                FROM tasks t 
                LEFT JOIN categories c ON t.category_id = c.id 
                WHERE t.user_id = ? AND DATE(t.due_date) = ?
                ORDER BY t.priority = 'high' DESC, t.due_date ASC";
        
        return fetchAll($sql, [$userId, $today]);
    }
    
    // Get overdue tasks
    public static function getOverdueTasks($userId)
    {
        $today = date('Y-m-d');
        
        $sql = "SELECT t.*, c.name as category_name, c.color as category_color 
                FROM tasks t 
                LEFT JOIN categories c ON t.category_id = c.id 
                WHERE t.user_id = ? AND DATE(t.due_date) < ? AND t.status = 'pending'
                ORDER BY t.due_date ASC";
        
        return fetchAll($sql, [$userId, $today]);
    }
    
    // Get upcoming tasks (within next 7 days)
    public static function getUpcomingTasks($userId)
    {
        $today = date('Y-m-d');
        $nextWeek = date('Y-m-d', strtotime('+7 days'));
        
        $sql = "SELECT t.*, c.name as category_name, c.color as category_color 
                FROM tasks t 
                LEFT JOIN categories c ON t.category_id = c.id 
                WHERE t.user_id = ? AND DATE(t.due_date) BETWEEN ? AND ? AND t.status = 'pending'
                ORDER BY t.due_date ASC";
        
        return fetchAll($sql, [$userId, $today, $nextWeek]);
    }
    
    // Get task statistics
    public static function getTaskStatistics($userId)
    {
        $totalTasks = self::countTasks($userId);
        $completedTasks = self::countTasks($userId, ['status' => 'completed']);
        $pendingTasks = self::countTasks($userId, ['status' => 'pending']);
        $highPriorityTasks = self::countTasks($userId, ['priority' => 'high']);

        $stats = [
            'total' => $totalTasks,
            'completed' => $completedTasks,
            'pending' => $pendingTasks,
            'high_priority' => $highPriorityTasks,
            'completion_percentage' => $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0
        ];

        return $stats;
    }

    // Add a comment to a task
    public static function addComment($taskId, $userId, $comment)
    {
        $data = [
            'task_id' => $taskId,
            'user_id' => $userId,
            'comment' => $comment
        ];

        return insert('comments', $data);
    }

    // Get comments for a task
    public static function getTaskComments($taskId)
    {
        $sql = "SELECT c.*, u.username 
                FROM comments c 
                JOIN users u ON c.user_id = u.id 
                WHERE c.task_id = ? 
                ORDER BY c.created_at DESC";

        return fetchAll($sql, [$taskId]);
    }

    // Add an entry to task history for undo/redo
    public static function addTaskHistory($taskId, $userId, $actionType, $previousData = null, $newData = null)
    {
        $data = [
            'task_id' => $taskId,
            'user_id' => $userId,
            'action_type' => $actionType,
            'previous_data' => $previousData ? json_encode($previousData) : null,
            'new_data' => $newData ? json_encode($newData) : null
        ];

        return insert('task_history', $data);
    }

    // Get task history
    public static function getTaskHistory($taskId, $limit = 10)
    {
        $sql = "SELECT h.*, u.username 
                FROM task_history h 
                JOIN users u ON h.user_id = u.id 
                WHERE h.task_id = ? 
                ORDER BY h.created_at DESC 
                LIMIT ?";

        return fetchAll($sql, [$taskId, $limit]);
    }

    // Undo last task action
    public static function undoLastAction($taskId, $userId)
    {
        // Get the most recent history entry
        $sql = "SELECT * FROM task_history 
                WHERE task_id = ? AND user_id = ? 
                ORDER BY created_at DESC 
                LIMIT 1";

        $lastAction = fetchOne($sql, [$taskId, $userId]);

        if (!$lastAction) {
            return false;
        }

        // Restore previous data based on action type
        switch ($lastAction['action_type']) {
            case 'update':
                $previousData = json_decode($lastAction['previous_data'], true);
                if ($previousData) {
                    update('tasks', $previousData, 'id = ? AND user_id = ?', [$taskId, $userId]);
                }
                break;

            case 'status_change':
                $previousData = json_decode($lastAction['previous_data'], true);
                if (isset($previousData['status'])) {
                    update('tasks', ['status' => $previousData['status']], 'id = ? AND user_id = ?', [$taskId, $userId]);
                }
                break;

            // Note: Can't undo a delete or redo a create without more complex logic
        }

        // Delete this history entry
        delete('task_history', 'id = ?', [$lastAction['id']]);

        return true;
    }
}