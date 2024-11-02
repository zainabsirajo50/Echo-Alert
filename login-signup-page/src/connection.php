<?php

$servername = "localhost";
$username = "zsirajo1";
$password = "zsirajo1";
$database = "zsirajo1";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Database connection error: " . $conn->connect_error);
} else {
    echo "Database connected successfully.<br>";
}

// Get server info using mysqli
echo $conn->server_info . "\n"; // This is the correct way to get the server info

$conn->close();
?>