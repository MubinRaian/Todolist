<?php
// config/database.php
// Database connection configuration

// Define database credentials
define('DB_HOST', 'localhost');
define('DB_USER', 'root');     // Change to your database username
define('DB_PASS', '');         // Change to your database password
define('DB_NAME', 'todolist');

// Create database connection
function dbConnect()
{
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}

// Get database connection
function getDbConnection()
{
    static $conn = null;

    if ($conn === null) {
        $conn = dbConnect();
    }

    return $conn;
}

// Execute query and return result
function executeQuery($sql, $params = [])
{
    $conn = getDbConnection();
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    if (!empty($params)) {
        $types = '';
        $bindParams = [];

        // Create types string and bind parameters array
        foreach ($params as $param) {
            if (is_int($param)) {
                $types .= 'i';
            } elseif (is_float($param)) {
                $types .= 'd';
            } elseif (is_string($param)) {
                $types .= 's';
            } else {
                $types .= 'b';
            }
            $bindParams[] = $param;
        }

        // Add types to beginning of bind_params array
        array_unshift($bindParams, $types);

        // Bind parameters dynamically
        if (!empty($bindParams)) {
            $stmt->bind_param(...$bindParams);
        }
    }

    $stmt->execute();
    $result = $stmt->get_result();

    return $result;
}

// Fetch all rows from a query result
function fetchAll($sql, $params = [])
{
    $result = executeQuery($sql, $params);
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Fetch single row from a query result
function fetchOne($sql, $params = [])
{
    $result = executeQuery($sql, $params);
    return $result->fetch_assoc();
}

// Insert data into a table and return the last inserted ID
function insert($table, $data)
{
    $conn = getDbConnection();

    $columns = implode(', ', array_keys($data));
    $placeholders = implode(', ', array_fill(0, count($data), '?'));

    $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
    $params = array_values($data);

    executeQuery($sql, $params);

    return $conn->insert_id;
}

// Update data in a table
function update($table, $data, $where, $whereParams = [])
{
    $setStatements = [];
    $params = [];

    foreach ($data as $column => $value) {
        $setStatements[] = "$column = ?";
        $params[] = $value;
    }

    $setClause = implode(', ', $setStatements);
    $sql = "UPDATE $table SET $setClause WHERE $where";

    $params = array_merge($params, $whereParams);

    executeQuery($sql, $params);
}

// Delete data from a table
function delete($table, $where, $params = [])
{
    $sql = "DELETE FROM $table WHERE $where";
    executeQuery($sql, $params);
}
?>