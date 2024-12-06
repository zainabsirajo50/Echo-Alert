<?php
include 'path.php';
require 'app/controllers/reports.php'; // Ensure this file exists and includes the right logic

// Query the database for all notifications (not user-specific)
$query = "SELECT * FROM notifications ORDER BY created_at DESC";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Add the 'unread' or 'read' class based on the 'is_read' field in the database
        $notificationClass = ($row['is_read'] == 0) ? 'unread' : 'read';
        
        // Display the notification with the correct class and a data attribute for the notification ID
        echo "<li id='notification_{$row['id']}' class='notification-item $notificationClass' data-id='{$row['id']}' onclick='markAsRead({$row['id']})'>";
        echo htmlspecialchars($row['message']);
        echo "</li>";
    }
} else {
    echo "<li>No notifications found.</li>";
}
?>