<?php
session_start();
header('Content-Type: application/json');
include 'db_connect.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(array('status' => 'error', 'message' => 'User not logged in'));
    exit();
}

$user_id = $_SESSION['user_id'];

// Prepare SQL statement
if ($stmt = $conn->prepare("SELECT red_duration, orange_duration, combined_duration, date_time, status FROM data WHERE user_id = ? ORDER BY id DESC LIMIT 1")) {
    
    // Bind parameters
    $stmt->bind_param("i", $user_id);

    // Execute the query
    $stmt->execute();
    $result = $stmt->get_result();

    // Initialize response
    $response = array(
        'status' => 'error',
        'data' => array(
            'red_duration' => '00:00:00',
            'orange_duration' => '00:00:00',
            'date_time' => '',
            'combined_duration' => '00:00:00',
            'status' => 'default_status'
        )
    );

    // Check if any rows were returned
    if ($result->num_rows > 0) {
        $response['status'] = 'success';
        $response['data'] = $result->fetch_assoc();
    }

    // Close the statement
    $stmt->close();
} else {
    // Handle SQL statement preparation error
    echo json_encode(array('status' => 'error', 'message' => 'Prepare failed: ' . $conn->error));
}

// Close the database connection
$conn->close();

// Output response as JSON
echo json_encode($response);
?>
