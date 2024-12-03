<?php
// report_submission.php

include "path.php";
require "app/controllers/reports.php"; // Ensure this path is correct

$user_type = isset($_SESSION['user_type']) ? $_SESSION['user_type'] : 'community_member'; // Default to 'community_member' if not set
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Report</title>
    <link rel="stylesheet" href="src/css/LoginForm.css">


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>

<body>

    <!-- Header Section with Buttons and Search Bar -->
    <header>

        <div class="header-container">
            <div class="header-buttons">
                <!-- Dynamically set the link based on user type -->
                <button
                    onclick="window.location.href='<?php echo $user_type === 'govt_worker' ? BASE_URL . '/govt-homepage.php' : BASE_URL . '/user-homepage.php'; ?>'">
                    Home
                </button>
            </div>
            <div class="header-buttons">
                <button onclick="window.location.href='<?php echo BASE_URL; ?>/pageview/events/index.php'">View
                    Events</button>

            </div>
        <!-- Notification Bell Button -->
        <div class="notification-container">
        <button class="notification-button" onclick="toggleDropdown()">
            🔔
        </button>
        
        <!-- Notifications Dropdown -->
        <div id="notifications-dropdown" class="notifications-dropdown">
        <button class="clear-notifications-button" onclick="clearAllNotifications()">Clear All Notifications</button>
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

        function clearAllNotifications() {
            if (!confirm("Are you sure you want to clear all notifications?")) {
                return; // Exit if the user cancels
            }

            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'clear-all-notifications.php', true); // Create a new PHP endpoint
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    // Clear the notifications list in the UI
                    document.getElementById('notifications-list').innerHTML = "<li>No notifications found.</li>";

                    // Optionally, remove the new-notification animation from the bell
                    const bellButton = document.querySelector('.notification-button');
                    bellButton.classList.remove('new-notification');
                }
            };
            xhr.send(); // No additional data needed for clearing all notifications
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
        </div>
    </header>
<br>
<br>
<br>
<br>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <ul class="navbar-list">
            <li><a href="govt-homepage.php">All</a></li>
            <li><a href="govt-homepage.php?filter=recent">Recents</a></li>
            <li><a href="govt-homepage.php?filter=most_votes">Most Votes</a></li>
        </ul>
        <div class="navbar-search">
            <form method="GET" action="govt-homepage.php">
                <input type="text" name="search_query" placeholder="Search by location and issue..."
                    value="<?php echo isset($_GET['search_query']) ? htmlspecialchars($_GET['search_query']) : ''; ?>">
                <button type="submit">Search</button>
            </form>
        </div>
    </nav>

    <!-- Report Submission Form -->

    <div class="report-form">
        <h2>Reports Near Me</h2>
        <section class="reports-section">
            <ul>
                <?php if (!empty($reports)): ?>
                    <?php foreach ($reports as $report): ?>
                        <!-- Wrap the entire report card inside an <a> tag -->
                        <a href="<?php echo BASE_URL; ?>/view-reports.php?reportid=<?php echo $report['reportid']; ?>"
                            class="report-link">
                            <li class="report-item">
                                <h3><?php echo htmlspecialchars($report['issue_name']); ?> Issue</h3>
                                <p><strong>Location:</strong> <?php echo htmlspecialchars($report['location']); ?></p>
                                <p><strong>Date:</strong> <?php echo date('F j, Y', strtotime($report['date_reported'])); ?></p>
                                <p><strong>Upvotes:</strong> <?php echo $report['upvote_count']; ?></p>

                                <!-- Display the status with corresponding color -->
                                <p><strong>Status:</strong>
                                    <?php
                                    $status = htmlspecialchars($report['status']);
                                    // Style based on the status value
                                    $status_class = '';
                                    switch ($status) {
                                        case 'Pending':
                                            $status_class = 'status-pending';
                                            break;
                                        case 'In Progress':
                                            $status_class = 'status-in-progress';
                                            break;
                                        case 'Resolved':
                                            $status_class = 'status-resolved';
                                            break;
                                        default:
                                            $status_class = 'status-default';
                                    }
                                    ?>
                                    <span class="<?php echo $status_class; ?>"><?php echo $status; ?></span>
                                </p>


                            </li>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No reports found.</p>
                <?php endif; ?>
            </ul>
        </section>
    </div>

</body>

</html>