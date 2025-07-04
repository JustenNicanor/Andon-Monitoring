<?php
header('Content-Type: application/json');

$servername = "localhost"; // Database server address
$username = "root";        // Database username
$password = "";            // Database password
$dbname = "monitoring_system"; // Database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Modify the SQL query to filter notifications where status is "Stop Line"
$sql = "SELECT user_id, date_time, status FROM data_tech WHERE status = 'Stop Line' ORDER BY date_time DESC";
$result = $conn->query($sql);

$notifications = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $notifications[] = [
            'id' => $row['user_id'],
            'time' => $row['date_time'],
            'status' => $row['status'] // Include the 'status' in the notification array
        ];
    }
}

echo json_encode($notifications);

$conn->close();
?>
