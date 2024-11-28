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

// Ensure the 'user_type' column exists in the users table
$result = $conn->query("SHOW COLUMNS FROM users LIKE 'user_type'");
if ($result->num_rows == 0) {
    $conn->query("ALTER TABLE users ADD COLUMN user_type VARCHAR(100) NOT NULL");
}

// Create the 'users' table if it does not exist
$sql_create_users = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);";

// Create the 'reports' table if it does not exist
$sql_create_reports = "CREATE TABLE IF NOT EXISTS reports (
    reportid INT AUTO_INCREMENT PRIMARY KEY,
    userid INT NOT NULL,
    issue_type_id INT NOT NULL,
    location VARCHAR(255) NOT NULL,
    date_reported DATETIME DEFAULT CURRENT_TIMESTAMP,
    upvote_count INT DEFAULT 0,
    FOREIGN KEY (userid) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (issue_type_id) REFERENCES issue_types(id) ON DELETE RESTRICT
);";

// Create the 'events' table if it does not exist
$sql_create_events = "CREATE TABLE IF NOT EXISTS events (
    event_id INT AUTO_INCREMENT PRIMARY KEY,
    event_name VARCHAR(255) NOT NULL,
    event_date DATE NOT NULL,
    event_location VARCHAR(255) NOT NULL,
    event_description TEXT
);";

// Create the 'issue_types' table if it does not exist
$sql_create_issue_types = "CREATE TABLE IF NOT EXISTS issue_types (
    id INT AUTO_INCREMENT PRIMARY KEY,
    issue_name VARCHAR(255) NOT NULL UNIQUE
);";

// Execute all table creation queries
if (
    $conn->query($sql_create_users) === TRUE &&
    $conn->query($sql_create_reports) === TRUE &&
    $conn->query($sql_create_events) === TRUE &&
    $conn->query($sql_create_issue_types) === TRUE
) {
    echo "Tables created successfully.<br>";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}

// Insert default issue types if they don't already exist
$default_issue_types = [
    'Pollution',
    'Illegal Dumping',
    'Wildlife Concern',
    'Deforestation',
    'Other'
];

foreach ($default_issue_types as $type) {
    $stmt = $conn->prepare("INSERT IGNORE INTO issue_types (issue_name) VALUES (?)");
    $stmt->bind_param("s", $type);
    if ($stmt->execute()) {
        echo "Default issue type '$type' added successfully.<br>";
    } else {
        echo "Error adding issue type '$type': " . $conn->error . "<br>";
    }
    $stmt->close();
}

// Alter 'reports' table to use 'issue_type_id' if it still has the 'issue_type' column
$result = $conn->query("SHOW COLUMNS FROM reports LIKE 'issue_type'");
if ($result->num_rows > 0) {
    $sql_alter_reports = "
        ALTER TABLE reports 
        DROP COLUMN issue_type, 
        ADD COLUMN issue_type_id INT NOT NULL, 
        ADD CONSTRAINT fk_issue_type FOREIGN KEY (issue_type_id) REFERENCES issue_types(id) ON DELETE RESTRICT;
    ";
    if ($conn->query($sql_alter_reports) === TRUE) {
        echo "'reports' table updated to use 'issue_type_id'.<br>";
    } else {
        echo "Error updating 'reports' table: " . $conn->error . "<br>";
    }
}

// Fetch users from the database
$sql_fetch_users = "SELECT DISTINCT id, name FROM users";
$result_users = $conn->query($sql_fetch_users);
