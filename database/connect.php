<?php
<?php

$host = "localhost";
$user = "root";
$pass = "IluqBMGFtc9!";
$db = "EcoAlertDB";

$conn = new MySQLi($host, $user, $pass, $db);

//connect to mysql
if ($conn->connect_error){
    die("Database connection error" . $conn->connect_error);
}

// Read mysql file
$sql = file_get_contents('init_db.sql');

// Execute mysql script
if ($conn->multi_query($sql)) {
    echo "Database and tables created successfully!";
} else {
    echo "Error creating database: " . $conn->connect_error;
}

$conn->close();

?>