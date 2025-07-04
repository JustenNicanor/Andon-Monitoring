<?php
session_start();
include 'db_connect_admin.php';

// Retrieve form data
$user = $_POST['username'];
$pass = md5($_POST['password']); // For simplicity, using md5; use bcrypt or Argon2 for production.

// Prepare and execute SQL query
$sql = "SELECT id FROM users WHERE username='$user' AND password='$pass'";
$result = $conn->query($sql);

if ($result === false) {
    // Handle query error
    echo json_encode(['success' => false, 'message' => 'Error: ' . $conn->error]);
    $conn->close();
    exit();
}

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $_SESSION['user_id'] = $row['id'];
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid username or password']);
}

// Close connection
$conn->close();
?>
