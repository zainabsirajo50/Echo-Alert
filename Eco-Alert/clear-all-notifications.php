<?php
session_start();
require 'path.php'; // Adjust the path to your setup
require 'app/database/connection.php'; // Ensure the database connection is included

// Delete all notifications
$query = "DELETE FROM notifications";
if ($conn->query($query) === TRUE) {
    echo "All notifications deleted.";
} else {
    echo "Error deleting notifications: " . $conn->error;
}
?>