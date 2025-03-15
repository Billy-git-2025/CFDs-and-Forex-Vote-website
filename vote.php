<?php
require_once 'includes/config.php';
require_once 'includes/votes.php';

// Set header to return JSON
header('Content-Type: application/json');

// Initialize response
$response = [
    'success' => false,
    'message' => '',
    'counts' => [
        'long' => 0,
        'short' => 0
    ]
];

// Check if it's a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if vote type is provided
    if (isset($_POST['vote_type'])) {
        $vote_type = $_POST['vote_type'];
        
        // Add the vote
        if (add_vote($vote_type)) {
            $response['success'] = true;
            $response['message'] = 'Vote recorded successfully';
        } else {
            $response['message'] = 'Failed to record vote';
        }
    } else {
        $response['message'] = 'Vote type is required';
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Just return current vote counts for GET requests
    $response['success'] = true;
    $response['message'] = 'Vote counts retrieved';
} else {
    $response['message'] = 'Invalid request method';
}

// Get the latest vote counts
$response['counts'] = get_vote_counts();

// Return JSON response
echo json_encode($response);
?> 