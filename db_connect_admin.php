<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "monitoring_system_admin";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Connection is open for use in other scripts
?>
