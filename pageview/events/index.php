<?php
include "../../path.php";
include ROOT_PATH . "/app/controllers/events.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events</title>
    <link rel="stylesheet" href="../../src/css/style.css">
</head>

<body>

    <!-- Header Section with Buttons and Search Bar -->
    <header>
        <div class="header-container">
            <div class="header-buttons">
                <button onclick="window.location.href='<?php echo BASE_URL; ?>/user-homepage.php'">Home</button>
                <button onclick="window.location.href='<?php echo BASE_URL; ?>/pageview/reports/index.php'">Create
                    Report</button>
                <button onclick="window.location.href='<?php echo BASE_URL; ?>/pageview/events/index.php'">View
                    Events</button>
            </div>

            <div class="header-search">
                <form method="GET" action="search_results.php">
                    <input type="text" name="search_query" placeholder="Search reports or events..." required>
                    <button type="submit">Search</button>
                </form>
            </div>

            <!-- Profile Dropdown -->
            <div class="profile-dropdown">
                <button class="profile-button">
                <?php echo $_SESSION['user_name']; ?>
                </button>
                <div class="dropdown-menu">
                    <a href="<?php echo BASE_URL; ?>/view_profile.php">View Profile</a>
                    <a href="<?php echo BASE_URL; ?>/settings.php">Settings</a>
                    <a href="<?php echo BASE_URL; ?>/logout.php">Logout</a>
                </div>
            </div>
        </div>
    </header>

    <!-- Events Section -->
    <div class="events-container">
        <h2>Upcoming Events</h2>

        <?php if (!empty($events)): ?>
            <div class="events-cards">
                <?php foreach ($events as $event): ?>
                    <div class="event-card">
                        <h3 class="event-name"><?php echo htmlspecialchars($event['event_name']); ?></h3>
                        <p class="event-date"><?php echo date('F j, Y', strtotime($event['event_date'])); ?></p>
                        <p class="event-location"><?php echo htmlspecialchars($event['event_location']); ?></p>
                        <p class="event-description"><?php echo htmlspecialchars($event['event_description']); ?></p>
                        <!-- Example button for further interaction -->
                        <button>RSVP</button>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No upcoming events available.</p>
        <?php endif; ?>
    </div>

</body>

</html>