<?php
session_start();

include "path.php";
require ROOT_PATH . "/app/database/connection.php";

// Fetch user details from the database
$user_id = $_SESSION['user_id'];
$user_type = isset($_SESSION['user_type']) ? $_SESSION['user_type'] : 'community_member'; // Default to 'community_member' if not set
$query = "SELECT name, email, user_type FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo "User not found.";
    exit();
}

// Handle form submission for updating user details
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $pasword = $_POST('password');
    $update_query = "UPDATE users SET name = ?, email = ?, password = ?, WHERE id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ssi", $name, $email, $password, $user_id);

    if ($stmt->execute()) {
        echo "<script>alert('Settings updated successfully!');</script>";
        $_SESSION['user_name'] = $name; // Update session data if name changes
    } else {
        echo "<script>alert('Failed to update settings.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="src/css/LoginForm.css">
    <title>User Settings</title>
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
    <h1>User Settings</h1>
    <form method="POST" action="">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
        <br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
        <br>
        <button type="submit" class="submit-button">Update</button>
    </form>
    <br>
    <a class="signup-link" href="<?php echo BASE_URL; ?>/change_password.php">Change Password</a>
    <br>
    <a class="signup-link" href="<?php echo BASE_URL; ?>/logout.php">Logout</a>
</div>
</body>
</html>