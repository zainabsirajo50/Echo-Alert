<?php
include("../../path.php");
require(ROOT_PATH . "/app/controllers/reports.php");


$user_type = isset($_SESSION['user_type']) ? $_SESSION['user_type'] : 'community_member'; // Default to 'community_member' if not set
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Report</title>
    <link rel="stylesheet" href="../../src/css/LoginForm.css">
</head>

<body>

    <!-- Header Section with Buttons and Search Bar -->
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
        xhr.open('GET', '../../fetch-notifications.php', true);
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
        xhr.open('POST', '../../mark_as_read.php', true); // Send POST request to mark_as_read.php
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

    <!-- Report Submission Form -->
    <div class="report-form">
        <h2>Submit a Report</h2>
        <form method="POST" action="index.php">

            <?php include(ROOT_PATH . "/app/messages/errors.php"); ?>

            <div class="form-group">
                <label>Issue Type:</label>
                <select name="issue_type_id" required>
                    <option value="">Select an issue type</option>
                    <?php foreach ($issue_types as $type): ?>

                        <option value="<?php echo $type['id']; ?>"><?php echo $type['issue_name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Location:</label>
                <input type="text" name="location" value="<?php echo $location; ?>">
            </div>
            <div>
                <button type="submit" class="submit-button">Submit Report</button>
            </div>
        </form>
    </div>


</body>

</html>