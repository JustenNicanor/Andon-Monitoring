<?php
session_start();
header('Content-Type: application/json');
include 'db_connect.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(array('status' => 'error', 'message' => 'User not logged in'));
    exit();
}

// Retrieve form data
$user_id = $_SESSION['user_id'];
$red_duration = $_POST['red_duration'] ?? '';
$orange_duration = $_POST['orange_duration'] ?? '';
$date_time = $_POST['date_time'] ?? '';
$combined_duration = $_POST['combined_duration'] ?? '';
$status = $_POST['status'] ?? 'default_status'; // Default value if not provided

// Validate and sanitize input
$red_duration = filter_var($red_duration, FILTER_SANITIZE_STRING);
$orange_duration = filter_var($orange_duration, FILTER_SANITIZE_STRING);
$date_time = filter_var($date_time, FILTER_SANITIZE_STRING);
$combined_duration = filter_var($combined_duration, FILTER_SANITIZE_STRING);
$status = filter_var($status, FILTER_SANITIZE_STRING);

try {
    // Start transaction
    $conn->begin_transaction();

    // Prepare and execute SQL query for the 'data' table
    $stmt = $conn->prepare("INSERT INTO data (user_id, red_duration, orange_duration, date_time, combined_duration, status) 
                             VALUES (?, ?, ?, ?, ?, ?)");

    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param('ssssss', $user_id, $red_duration, $orange_duration, $date_time, $combined_duration, $status);

    if (!$stmt->execute()) {
        throw new Exception("Execute failed: " . $stmt->error);
    }

    // Prepare and execute SQL query for the 'data_tech' table
    $stmt_backup = $conn->prepare("INSERT INTO data_tech (user_id, red_duration, orange_duration, date_time, combined_duration, status) 
                                   VALUES (?, ?, ?, ?, ?, ?)");

    if (!$stmt_backup) {
        throw new Exception("Prepare failed for backup: " . $conn->error);
    }

    $stmt_backup->bind_param('ssssss', $user_id, $red_duration, $orange_duration, $date_time, $combined_duration, $status);

    if (!$stmt_backup->execute()) {
        throw new Exception("Execute failed for backup: " . $stmt_backup->error);
    }


    // Prepare and execute SQL query for the 'data_tech' table
    $stmt_backup = $conn->prepare("INSERT INTO data_admin (user_id, red_duration, orange_duration, date_time, combined_duration, status) 
    VALUES (?, ?, ?, ?, ?, ?)");

    if (!$stmt_backup) {
    throw new Exception("Prepare failed for backup: " . $conn->error);
    }

    $stmt_backup->bind_param('ssssss', $user_id, $red_duration, $orange_duration, $date_time, $combined_duration, $status);

    if (!$stmt_backup->execute()) {
    throw new Exception("Execute failed for backup: " . $stmt_backup->error);
    }

    // Commit transaction
    $conn->commit();

    // Close statements
    $stmt->close();
    $stmt_backup->close();

    echo json_encode(array('status' => 'success'));
} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();
    echo json_encode(array('status' => 'error', 'message' => $e->getMessage()));
}

// Close the database connection
$conn->close();
?>
