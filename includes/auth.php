<?php
require_once 'config.php';

// Start a session if not already started
function start_session() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

// Check if a user is logged in
function is_logged_in() {
    start_session();
    return isset($_SESSION['user_id']);
}

// Get the current logged-in user's information
function get_current_user() {
    if (!is_logged_in()) {
        return null;
    }
    
    $db = db_connect();
    $query = "SELECT id, username, email, role FROM users WHERE id = :id";
    
    $stmt = $db->prepare($query);
    $stmt->bindValue(':id', $_SESSION['user_id'], SQLITE3_INTEGER);
    
    $result = $stmt->execute();
    $user = $result->fetchArray(SQLITE3_ASSOC);
    
    $db->close();
    return $user;
}

// Authenticate a user with username and password
function login($username, $password) {
    $db = db_connect();
    $query = "SELECT id, username, password, email, role FROM users WHERE username = :username";
    
    $stmt = $db->prepare($query);
    $stmt->bindValue(':username', $username, SQLITE3_TEXT);
    
    $result = $stmt->execute();
    $user = $result->fetchArray(SQLITE3_ASSOC);
    
    $db->close();
    
    if ($user && password_verify($password, $user['password'])) {
        start_session();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        return true;
    }
    
    return false;
}

// Log out the current user
function logout() {
    start_session();
    session_unset();
    session_destroy();
}

// Check if the current user has a specific role
function user_has_role($role) {
    if (!is_logged_in()) {
        return false;
    }
    
    return $_SESSION['role'] === $role;
}

// Check if the current user is an admin
function is_admin() {
    return user_has_role('admin');
}

// Register a new user
function register_user($username, $password, $email, $role = 'author') {
    $db = db_connect();
    
    // Check if username or email already exists
    $query = "SELECT COUNT(*) as count FROM users WHERE username = :username OR email = :email";
    $stmt = $db->prepare($query);
    $stmt->bindValue(':username', $username, SQLITE3_TEXT);
    $stmt->bindValue(':email', $email, SQLITE3_TEXT);
    $result = $stmt->execute();
    $row = $result->fetchArray(SQLITE3_ASSOC);
    
    if ($row['count'] > 0) {
        $db->close();
        return false; // User already exists
    }
    
    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Insert the new user
    $query = "INSERT INTO users (username, password, email, role) VALUES (:username, :password, :email, :role)";
    $stmt = $db->prepare($query);
    $stmt->bindValue(':username', $username, SQLITE3_TEXT);
    $stmt->bindValue(':password', $hashed_password, SQLITE3_TEXT);
    $stmt->bindValue(':email', $email, SQLITE3_TEXT);
    $stmt->bindValue(':role', $role, SQLITE3_TEXT);
    
    $result = $stmt->execute();
    $user_id = $db->lastInsertRowID();
    
    $db->close();
    return $user_id;
}

// Update a user's information
function update_user($id, $data) {
    $db = db_connect();
    
    $fields = [];
    $values = [];
    
    // Only update fields that are provided
    if (isset($data['username'])) {
        $fields[] = "username = :username";
        $values[':username'] = $data['username'];
    }
    
    if (isset($data['email'])) {
        $fields[] = "email = :email";
        $values[':email'] = $data['email'];
    }
    
    if (isset($data['role'])) {
        $fields[] = "role = :role";
        $values[':role'] = $data['role'];
    }
    
    if (isset($data['password']) && !empty($data['password'])) {
        $fields[] = "password = :password";
        $values[':password'] = password_hash($data['password'], PASSWORD_DEFAULT);
    }
    
    if (empty($fields)) {
        $db->close();
        return false; // Nothing to update
    }
    
    $query = "UPDATE users SET " . implode(", ", $fields) . " WHERE id = :id";
    $stmt = $db->prepare($query);
    
    foreach ($values as $key => $value) {
        $stmt->bindValue($key, $value, SQLITE3_TEXT);
    }
    
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
    $result = $stmt->execute();
    
    $db->close();
    return $result ? true : false;
} 