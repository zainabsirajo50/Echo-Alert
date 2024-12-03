<?php
// report_submission.php
include "path.php";
require "app/controllers/reports.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Reports</title>
    <link rel="stylesheet" href="src/css/LoginForm.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    

</head>

<body>

    <!-- Header Section with Buttons, Search Bar, and Profile Dropdown -->
    <header>
        <div class="header-container">
            <div class="header-buttons">
                <button
                    onclick="window.location.href='<?php echo $user_type === 'govt_worker' ? BASE_URL . '/govt-homepage.php' : BASE_URL . '/user-homepage.php'; ?>'">
                    Home
                </button>
                <?php if ($user_type !== 'govt_worker'): ?>
                    <button onclick="window.location.href='<?php echo BASE_URL; ?>/pageview/reports/index.php'">Create
                        Report</button>

                <?php endif; ?>
                <button onclick="window.location.href='<?php echo BASE_URL; ?>/pageview/events/index.php'">View
                    Events</button>
            </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            

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
    <!-- Navigation Bar -->
    <nav class="navbar">
        <ul class="navbar-list">
            <li><a href="user-homepage.php">All</a></li>
            <li><a href="user-homepage.php?filter=recent">Recents</a></li>
            <li><a href="user-homepage.php?filter=most_votes">Most Votes</a></li>
        </ul>
        <div class="navbar-search">
            <form method="GET" action="user-homepage.php">
                <input type="text" name="search_query" placeholder="Search by location and issue..."
                    value="<?php echo isset($_GET['search_query']) ? htmlspecialchars($_GET['search_query']) : ''; ?>">
                <button type="submit">Search</button>
            </form>
        </div>
    </nav>

    <!-- Report Section -->
    <div class="report-form">
        <h2>Reports</h2>

        <!-- Display Reports -->
        <section class="reports-section">
            <ul>
                <?php if (!empty($reports)): ?>
                    <?php foreach ($reports as $report): ?>
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
                            <!-- Upvote Form -->
                            <form method="POST" action="upvote.php">
                                <input type="hidden" name="report_id" value="<?php echo $report['reportid']; ?>">
                                <button type="submit" class="upvote-button">
                                    ⬆
                                </button>
                            </form>
                            <!-- Downvote Form -->
                            <form method="POST" action="downvote.php">
                                <input type="hidden" name="report_id" value="<?php echo $report['reportid']; ?>">
                                <button type="submit" class="upvote-button">
                                    ⬇
                                </button>
                            </form>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No reports found.</p>
                <?php endif; ?>
            </ul>
        </section>
    </div>

</body>

</html>