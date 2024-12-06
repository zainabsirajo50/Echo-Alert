<?php

include 'path.php';
require 'app/controllers/reports.php'; // Ensure this file exists and includes the right logic

// Check if notification_id is provided via POST
if (isset($_POST['notification_id'])) {
    // Sanitize and validate the notification ID
    $notification_id = intval($_POST['notification_id']); // Ensure the value is an integer

    // Check if notification_id is valid (greater than 0)
    if ($notification_id > 0) {
        // Update the notification status to 'read'
        $stmt = $conn->prepare("UPDATE notifications SET is_read = 1 WHERE id = ?");
        $stmt->bind_param("i", $notification_id);

        if ($stmt->execute()) {
            echo "Notification marked as read.";
        } else {
            echo "Error updating notification.";
        }

        $stmt->close();
    } else {
        echo "Invalid notification ID.";
    }
} else {
    echo "No notification ID provided.";
}
?>