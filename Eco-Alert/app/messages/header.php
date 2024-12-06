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
    </header>