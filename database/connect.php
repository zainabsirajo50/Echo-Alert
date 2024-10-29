<?php

$host = "localhost";
$user = "root";
$pass = "IluqBMGFtc9!";
$db = "EcoAlert";

$conn = new MySQLi($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Database connection error: " . $conn->connect_error);
} else {
    echo "Database connected successfully.<br>";
}