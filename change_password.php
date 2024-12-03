<?php
session_start();

include "path.php";
require ROOT_PATH . "/app/database/connection.php";

$user_type = isset($_SESSION['user_type']) ? $_SESSION['user_type'] : 'community_member'; // Default to 'community_member' if not set

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "You must be logged in to change your password.";
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Fetch the current password from the database
    $query = "SELECT password FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user) {
        echo "User not found.";
        exit();
    }

    // Validate current password
    if ($user['password'] !== $current_password) {
        echo "<script>alert('Current password is incorrect.');</script>";
    } elseif ($new_password !== $confirm_password) {
        echo "<script>alert('New password and confirm password do not match.');</script>";
    } else {
        // Update the password in the database
        $update_query = "UPDATE users SET password = ? WHERE id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("si", $new_password, $user_id);

        if ($stmt->execute()) {
            echo "<script>alert('Password changed successfully!');</script>";
            header("Location: " . BASE_URL . "/settings.php"); // Redirect to settings page
            exit();
        } else {
            echo "<script>alert('Failed to update password.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="src/css/LoginForm.css">
    <title>Change Password</title>
</head>
<body>

<header>
    <div class="header-container">
    <div class="header-buttons">
                <button onclick="window.location.href='<?php echo $user_type === 'govt_worker' ? BASE_URL . '/govt-homepage.php' : BASE_URL . '/user-homepage.php'; ?>'">
                    Home
                </button>
                    <?php if ($user_type !== 'govt_worker'): ?>
                        <button onclick="window.location.href='<?php echo BASE_URL; ?>/pageview/reports/index.php'">Create
                        Report</button>

                    <?php endif; ?>
                    <button onclick="window.location.href='<?php echo BASE_URL; ?>/pageview/events/index.php'">View
                        Events</button>
                </div>

                <div class="header-search">
                    <form method="GET" action="search_results.php">
                        <input type="text" name="search_query" placeholder="Search reports or events..." required>
                        <button type="submit">Search</button>
                    </form>
                </div>

                        <!-- Notification Bell Button -->
    <div class="notification-container">
        <button class="notification-button" onclick="toggleDropdown()">
            🔔
        </button>
        
        <!-- Notifications Dropdown -->
        <div id="notifications-dropdown" class="notifications-dropdown">
            <ul id="notifications-list">
                <!-- Notifications will be populated here dynamically -->
                <li>No notifications found.</li>
            </ul>
        </div>
    </div>

    <script>
    // Function to fetch notifications from the server
    function fetchNotifications() {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'fetch-notifications.php', true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                // Update the notification dropdown with the new data
                document.getElementById('notifications-list').innerHTML = xhr.responseText;

                // Check for unread notifications
                const notifications = document.querySelectorAll('.notification-item.unread');
                const bellButton = document.querySelector('.notification-button');
                console.log(bellButton);  // Log the bell button to verify if it's selected correctly

                // Log the count of unread notifications
                console.log('Unread notifications count:', notifications.length);

                // Add 'new-notification' class if there are unread notifications
                if (notifications.length > 0) {
                    console.log('Adding "new-notification" class to bell button');
                    bellButton.classList.add('new-notification');
                } else {
                    console.log('Removing "new-notification" class from bell button');
                    bellButton.classList.remove('new-notification');
                }
            }
        };
        xhr.send();
    }

    // Fetch notifications every 5 seconds (5000 milliseconds)
    setInterval(fetchNotifications, 1000);

    // Toggle dropdown visibility
    function toggleDropdown() {
        const dropdown = document.getElementById("notifications-dropdown");
        dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
    }

    // Mark notification as read when clicked
    function markAsRead(notificationId) {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'mark_as_read.php', true); // Send POST request to mark_as_read.php
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                // Optionally update the notification to show it is read
                document.getElementById('notification_' + notificationId).classList.add('read');
            }
        };
        xhr.send('notification_id=' + notificationId);
    }
    </script>
        <!-- Profile Dropdown -->
        <div class="profile-dropdown">
            <button class="profile-button">
                <div>
                 Hi, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!
                </div>
            </button>
            <div class="dropdown-menu">
                <a href="<?php echo BASE_URL; ?>/view_profile.php">View Profile</a>
                <a href="<?php echo BASE_URL; ?>/settings.php">Settings</a>
                <a href="<?php echo BASE_URL; ?>/logout.php">Logout</a>
            </div>
        </div>
    </div>
</header>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>


<div class="report-form">
    <h1>Change Password</h1>
    <form method="POST" action="">
        <label for="current_password">Current Password:</label>
        <input type="password" id="current_password" name="current_password" required>
        <br>

        <label for="new_password">New Password:</label>
        <input type="password" id="new_password" name="new_password" required>
        <br>

        <label for="confirm_password">Confirm New Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" required>
        <br>

        <button type="submit" class="submit-button">Update Password</button>
    </form>
    <br>
    <a class="signup-link" href="<?php echo BASE_URL; ?>/settings.php">Back to Settings</a>
</div>

</body>
</html>