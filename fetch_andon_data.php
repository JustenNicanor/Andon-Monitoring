<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login_admin.html");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "monitoring_system";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the user ID from the request
$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 1;

// Fetch the most recent row for the given user ID
$sql = "SELECT status, red_duration, orange_duration, combined_duration, date_time FROM data_admin WHERE user_id = ? ORDER BY date_time DESC LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Fetch associative array
$data = $result->fetch_assoc();

// Debugging: Print fetched data
error_log(print_r($data, true));

$stmt->close();
$conn->close();

// Check if data is not empty
if ($data) {
    // Format the response
    $response = [
        'status' => $data['status'] ?? 'Unknown',
        'red_duration' => $data['red_duration'] ?? '00:00:00',
        'orange_duration' => $data['orange_duration'] ?? '00:00:00',
        'combined_duration' => $data['combined_duration'] ?? '00:00:00',
        'date_time' => $data['date_time'] ?? 'Not Available',
    ];
} else {
    // If no data found, set default values
    $response = [
        'status' => 'Unknown',
        'red_duration' => '00:00:00',
        'orange_duration' => '00:00:00',
        'combined_duration' => '00:00:00',
        'date_time' => 'Not Available', // Default value if no data
    ];
}

// Return the data as JSON
header('Content-Type: application/json');
echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>
