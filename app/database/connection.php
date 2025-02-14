<?php
include "config.php";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Ensure the 'user_type' column exists in the users table
$result = $conn->query("SHOW COLUMNS FROM users LIKE 'user_type'");
if ($result->num_rows == 0) {
    $conn->query("ALTER TABLE users ADD COLUMN user_type VARCHAR(100) NOT NULL");
}

// Ensure the 'status' column exists in the reports table
$result = $conn->query("SHOW COLUMNS FROM reports LIKE 'status'");
if ($result->num_rows == 0) {
    $conn->query("ALTER TABLE reports ADD COLUMN status ENUM('Pending', 'In Progress', 'Resolved') NOT NULL DEFAULT 'Pending'");
}

// Create the 'users' table if it does not exist
$sql_create_users = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    user_type VARCHAR(100) NOT NULL
);";

// Create the 'reports' table if it does not exist
$sql_create_reports = "CREATE TABLE IF NOT EXISTS reports (
    reportid INT AUTO_INCREMENT PRIMARY KEY,
    userid INT NOT NULL,
    issue_type_id INT NOT NULL,
    location VARCHAR(255) NOT NULL,
    date_reported DATETIME DEFAULT CURRENT_TIMESTAMP,
    upvote_count INT DEFAULT 0,
    status ENUM('Pending', 'In Progress', 'Resolved') NOT NULL DEFAULT 'Pending',
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

$sql_create_responses = "CREATE TABLE IF NOT EXISTS responses (
    responseid INT AUTO_INCREMENT PRIMARY KEY,
    reportid INT NOT NULL,
    userid INT NOT NULL,
    response_text TEXT NOT NULL,
    response_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (reportid) REFERENCES reports(reportid) ON DELETE CASCADE,
    FOREIGN KEY (userid) REFERENCES users(id) ON DELETE CASCADE
);";

$sql_create_votes = "CREATE TABLE IF NOT EXISTS votes (
    vote_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    report_id INT NOT NULL,
    vote_type ENUM('upvote') NOT NULL,
    vote_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (report_id) REFERENCES reports(reportid) ON DELETE CASCADE,
    UNIQUE (user_id, report_id)
);";

$sql_create_notifications = "CREATE TABLE IF NOT EXISTS notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    message TEXT NOT NULL,
    type ENUM('info', 'alert', 'success', 'error') DEFAULT 'info',
    is_read BOOLEAN DEFAULT FALSE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);";

// Create the 'issue_types' table if it does not exist
$sql_create_issue_types = "CREATE TABLE IF NOT EXISTS issue_types (
    id INT AUTO_INCREMENT PRIMARY KEY,
    issue_name VARCHAR(255) NOT NULL UNIQUE
);";

$sql_create_rsvps = "CREATE TABLE IF NOT EXISTS rsvps (
    rsvp_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    event_id INT NOT NULL,
    rsvp_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    rsvp_code VARCHAR(10) NOT NULL UNIQUE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (event_id) REFERENCES events(event_id) ON DELETE CASCADE
);";

// Execute all table creation queries
$conn->query($sql_create_users);
$conn->query($sql_create_reports);
$conn->query($sql_create_events);
$conn->query($sql_create_issue_types);
$conn->query($sql_create_responses);
$conn->query($sql_create_rsvps);
$conn->query($sql_create_notifications);
$conn->query($sql_create_votes);

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
    $stmt->execute();
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
    $conn->query($sql_alter_reports);
}

// Reset upvote_count in the reports table
$sql_reset_upvote_count = "
    UPDATE reports r
    LEFT JOIN (
        SELECT report_id, COUNT(*) AS vote_count
        FROM votes
        GROUP BY report_id
    ) v ON r.reportid = v.report_id
    SET r.upvote_count = IFNULL(v.vote_count, 0);
";
$conn->query($sql_reset_upvote_count);

// Fetch users from the database
$sql_fetch_users = "SELECT DISTINCT id, name FROM users";
$result_users = $conn->query($sql_fetch_users);
?>
