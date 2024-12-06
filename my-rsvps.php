<?php
session_start();
require "path.php";
include ROOT_PATH . "/app/controllers/events.php";

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My RSVPs</title>
    <link rel="stylesheet" href="src/css/style.css">
    <link rel="stylesheet" href="src/css/LoginForm.css">
    <link rel="stylesheet" href="src/css/view_profile.css">

</head>

<body>

    <!-- Header Section with Buttons and Profile Dropdown -->
    <?php include(ROOT_PATH . "/app/messages/header.php"); ?>

    <div>

<div style='margin-left: 145px;' class="events-container">
    <h2 style='margin-top: 100px;'>Upcoming Events</h2>
 
    <?php if (!empty($rsvps)): ?>
        <div id="my-report-group" class="events-cards">
        <?php include(ROOT_PATH . "/app/messages/errors.php"); ?>
            <?php foreach ($rsvps as $rsvp): ?>
                <div class="event-card" id="my-report">
                    <h3 class="event-name"><?php echo htmlspecialchars($rsvp['event_name']); ?></h3>
                    <p class="event-date"><?php echo date('F j, Y', strtotime($rsvp['event_date'])); ?></p>
                    <p class="event-location"><?php echo htmlspecialchars($rsvp['event_location']); ?></p>
                    <p class="event-description"><?php echo htmlspecialchars($rsvp['rsvp_code']); ?></p>

                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No RSVPs found. Start RSVPing to events!</p>
    <?php endif; ?>
</div>
</div>
</body>

</html>