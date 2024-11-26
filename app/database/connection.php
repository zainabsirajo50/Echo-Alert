<?php
include ROOT_PATH . "/config.php";
// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
} else {
    echo "Successfully connected to database.<br>";
}

$result = $conn->query("SHOW COLUMNS FROM users LIKE 'user_type'");
if ($result->num_rows == 0) {
    // Add column if it doesn't exist
    $conn->query("ALTER TABLE users ADD COLUMN user_type VARCHAR(100) NOT NULL");
}

// Create the users table if it doesn't exist
$sql_create_users = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL

);";

$sql_create_reports = "CREATE TABLE IF NOT EXISTS reports (
    reportid INT AUTO_INCREMENT PRIMARY KEY,
    userid INT NOT NULL,
    issue_type VARCHAR(255) NOT NULL,
    location VARCHAR(255) NOT NULL,
    date_reported DATETIME DEFAULT CURRENT_TIMESTAMP,
    upvote_count INT DEFAULT 0,
    FOREIGN KEY (userid) REFERENCES users(id) ON DELETE CASCADE
);";

$sql_create_events = "CREATE TABLE IF NOT EXISTS events (
    event_id INT AUTO_INCREMENT PRIMARY KEY,
    event_name VARCHAR(255) NOT NULL,
    event_date DATE NOT NULL,
    event_location VARCHAR(255) NOT NULL,
    event_description TEXT
);";

// Execute the query to create the table
if ($conn->query($sql_create_users) === TRUE && $conn->query($sql_create_reports) === TRUE && $conn->query($sql_create_events) === TRUE) {
    echo "Tables created successfully.<br>";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}



// Fetch users from the database
$sql_fetch_users = "SELECT DISTINCT id, name FROM users";
$result_users = $conn->query($sql_fetch_users);

