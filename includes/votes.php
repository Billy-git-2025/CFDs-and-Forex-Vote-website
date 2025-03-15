<?php
require_once 'config.php';

/**
 * Initialize the votes table in the database
 */
function init_votes_table() {
    global $db;
    
    if (!$db) {
        init_database();
        global $db;
    }
    
    // Create votes table if it doesn't exist
    $query = "CREATE TABLE IF NOT EXISTS votes (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        vote_type TEXT NOT NULL,
        voted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        user_ip TEXT
    )";
    
    $db->exec($query);
}

/**
 * Get the current vote counts
 * 
 * @return array Array with long and short vote counts
 */
function get_vote_counts() {
    global $db;
    
    if (!$db) {
        init_database();
        global $db;
    }
    
    // Initialize default counts
    $counts = [
        'long' => 0,
        'short' => 0
    ];
    
    try {
        // Count long votes
        $stmt = $db->prepare("SELECT COUNT(*) FROM votes WHERE vote_type = 'long'");
        $stmt->execute();
        $counts['long'] = (int)$stmt->fetchColumn();
        
        // Count short votes
        $stmt = $db->prepare("SELECT COUNT(*) FROM votes WHERE vote_type = 'short'");
        $stmt->execute();
        $counts['short'] = (int)$stmt->fetchColumn();
        
    } catch (PDOException $e) {
        // Log error and return default counts
        error_log("Error getting vote counts: " . $e->getMessage());
    }
    
    return $counts;
}

/**
 * Add a new vote to the database
 * 
 * @param string $vote_type Either 'long' or 'short'
 * @return bool True if vote was added successfully
 */
function add_vote($vote_type) {
    global $db;
    
    if (!$db) {
        init_database();
        global $db;
    }
    
    // Validate vote type
    if ($vote_type !== 'long' && $vote_type !== 'short') {
        return false;
    }
    
    try {
        // Get the user's IP address (for basic rate limiting)
        $user_ip = $_SERVER['REMOTE_ADDR'];
        
        // Insert the vote
        $stmt = $db->prepare("INSERT INTO votes (vote_type, user_ip) VALUES (?, ?)");
        $stmt->execute([$vote_type, $user_ip]);
        
        return true;
    } catch (PDOException $e) {
        // Log error
        error_log("Error adding vote: " . $e->getMessage());
        return false;
    }
}

// Initialize votes table when this file is included
init_votes_table();
?> 