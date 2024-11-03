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
}

// Create the users table if it doesn't exist
$sql_create_users = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);";

// Execute the query to create the table
if ($conn->query($sql_create_users) === TRUE) {
    echo "Table 'users' created successfully.<br>";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}

// Fetch users from the database
$sql_fetch_users = "SELECT DISTINCT id, name FROM users";
$result_users = $conn->query($sql_fetch_users);

// Check if the query was successful
if ($result_users) {
    // Check if there are any rows returned
    if ($result_users->num_rows > 0) {
        // Fetch user data and store it in the $users array
        $users = []; // Initialize the array before using it
        while ($row = $result_users->fetch_assoc()) {
            $users[] = $row;
        }
        print_r($users); // Optional: Print fetched users for debugging
    } else {
        // No users found in the database
        echo "No users found.";
    }
} else {
    // Error executing the query
    echo "Error fetching users: " . $conn->error;
}

// Get server info using mysqli
echo "Server info: " . $conn->server_info . "<br>"; // Display server info


?>