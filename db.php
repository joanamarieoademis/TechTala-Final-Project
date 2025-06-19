<?php
require('db.connect.php');


// Functions that can be used to all files

function executeQuery($sql, $data){
    global $conn;
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        die("Prepare failed: " . $conn->error . " | SQL: " . $sql);
    }
    
    if (count($data) > 0) {
        $values = array_values($data);
        $types = str_repeat('s', count($values));
        $stmt->bind_param($types, ...$values);
    }
    
    $stmt->execute();
    return $stmt;
}

function selectAll($table, $conditions = []){
    global $conn;
    
    if (!$conn) {
        die("Database connection failed");
    }
    
    $sql = "SELECT * FROM $table";
    
    if (empty($conditions)){
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }
        $stmt->execute();
        $records = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        return $records;
    } else {
        $i = 0;
        foreach ($conditions as $key => $value){
            if ($i === 0){
                $sql = $sql . " WHERE $key = ?";
            } else {
                $sql = $sql . " AND $key = ?";
            }
            $i++;
        }
        
        $stmt = executeQuery($sql, $conditions);
        $records = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        return $records;
    }
}

function selectOne($table, $conditions = []){
    global $conn;
    
    $sql = "SELECT * FROM $table";

    $i = 0;
    foreach ($conditions as $key => $value){
        if ($i === 0){
            $sql = $sql . " WHERE $key = ?";
        } else {
            $sql = $sql . " AND $key = ?";
        }
        $i++;
    }
    
    $sql = $sql . " LIMIT 1";
    $stmt = executeQuery($sql, $conditions);
    $records = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    
    return !empty($records) ? $records[0] : [];
}

function create($table, $data){
    global $conn;
    
    unset($data['publish']); 
    
    $sql = "INSERT INTO $table SET ";
    $i = 0;
    foreach ($data as $key => $value){
        if ($i === 0){
            $sql = $sql . " $key = ?";
        } else {
            $sql = $sql . ", $key = ?";
        }
        $i++;
    }
    
    $stmt = executeQuery($sql, $data);
    
    $id = $conn->insert_id;
    return $id;
}

function update($table, $id, $data) {
    global $conn;

    $sql = "UPDATE `$table` SET ";
    $columns = array_keys($data);
    $placeholders = array_map(fn($col) => "`$col` = ?", $columns);
    $sql .= implode(', ', $placeholders);
    $sql .= " WHERE `id` = ?";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("SQL ERROR: " . $conn->error . "<br>Query: $sql");
    }

    $values = array_values($data);
    $values[] = $id;

    $types = '';
    foreach ($values as $val) {
        $types .= is_int($val) ? 'i' : (is_float($val) ? 'd' : 's');
    }

    $stmt->bind_param($types, ...$values);
    $stmt->execute();

    return $stmt->affected_rows;
}

function delete($table, $id){
    global $conn;
    
    $sql = "DELETE FROM $table WHERE id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    
    return $stmt->affected_rows;
}

// Get posts -- admin 
function posts() {
    global $conn;
    $sql = "SELECT p.id AS post_id, p.title, p.created_at, u.username
            FROM post p
            LEFT JOIN users u ON p.users_id = u.id
            ORDER BY p.created_at DESC";

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}


// Get users -- admin
function users() {
    global $conn;
    $sql = "SELECT 
                u.id AS user_id, u.username, u.email, u.role, 
                u.gender, u.bio, u.profile_picture, u.created_at,
                COALESCE(p.post_count, 0) AS post_count,
                COALESCE(c.comment_count, 0) AS comment_count
            FROM users u
            LEFT JOIN (SELECT users_id, COUNT(*) AS post_count FROM post GROUP BY users_id) p ON u.id = p.users_id
            LEFT JOIN (SELECT users_id, COUNT(*) AS comment_count FROM comments GROUP BY users_id) c ON u.id = c.users_id
            ORDER BY u.created_at DESC";
    return $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
}

// Get user role -- admin
function role($role) {
    $role = strtolower($role);
    return in_array($role, ['admin', 'author']) ? $role : 'reader';
}


// Date formatting -- admin and reader
function assignDate($date) {
    return date('M j, Y', strtotime($date));
}

// Make the text short -- admin and reader
function shortText($text, $length = 150) {
    $text = trim(strip_tags(htmlspecialchars_decode($text))); // Decode entities, remove tags, trim
    return strlen($text) > $length ? substr($text, 0, $length) . '...' : $text;
}

// Inserting information -- reader
function insert($table, $data) {
    global $conn;
    $keys = implode(',', array_keys($data));
    $values = implode("','", array_map('addslashes', array_values($data)));
    $sql = "INSERT INTO $table ($keys) VALUES ('$values')";
    mysqli_query($conn, $sql);
    return mysqli_insert_id($conn);
}

// Removing html tags -- creations author
function remove($html) {
    $html = str_replace(['</p><p>', '<br>', '<br/>', '<br />'], ["\n\n", "\n", "\n", "\n"], $html);
    return strip_tags($html);
}
?>