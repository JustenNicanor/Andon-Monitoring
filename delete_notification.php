<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "monitoring_system";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Connection failed: ' . $conn->connect_error]);
    exit();
}

// Ensure the request method is POST and 'id' is provided
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);

    // Prepare and execute delete statement
    if ($stmt = $conn->prepare('DELETE FROM data_tech WHERE user_id = ?')) {
        $stmt->bind_param('i', $id);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Notification not found']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Deletion failed']);
        }

        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Prepare statement failed']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}

$conn->close();
?>
