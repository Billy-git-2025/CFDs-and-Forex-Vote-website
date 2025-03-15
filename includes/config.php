<?php
// Database configuration
define('DB_PATH', __DIR__ . '/../database/blog.db');
define('UPLOADS_DIR', __DIR__ . '/../images/uploads');
define('UPLOADS_URL', '/images/uploads');

// Initialize database if it doesn't exist
function init_database() {
    if (!file_exists(DB_PATH)) {
        $db = new SQLite3(DB_PATH);
        $sql = file_get_contents(__DIR__ . '/../database/schema.sql');
        $db->exec($sql);
        $db->close();
        
        // Create upload directory if it doesn't exist
        if (!file_exists(UPLOADS_DIR)) {
            mkdir(UPLOADS_DIR, 0755, true);
        }
        
        return true;
    }
    return false;
}

// Connect to the database
function db_connect() {
    return new SQLite3(DB_PATH);
}

// Format a date for display
function format_date($date) {
    return date('F j, Y', strtotime($date));
}

// Create URL-friendly slug from a string
function create_slug($string) {
    $slug = strtolower(trim($string));
    $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
    $slug = preg_replace('/-+/', '-', $slug);
    return trim($slug, '-');
}

// Escape HTML for safe output
function h($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
} 