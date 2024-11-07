<?php

$host = "eco-alert.c1gg4gm00tau.us-east-2.rds.amazonaws.com";
$user = "hafsa";
$pass = "ecoAlert";
$db = "EcoAlert";

$conn = new MySQLi($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Database connection error: " . $conn->connect_error);
} else {
    echo "Database connected successfully.<br>";
}