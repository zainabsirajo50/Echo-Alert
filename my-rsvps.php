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
    <link rel="stylesheet" href="src/css/LoginForm.css">
</head>

<body>

    <!-- Header Section with Buttons and Profile Dropdown -->
    <?php include(ROOT_PATH . "/app/messages/header.php"); ?>

    <div>

        <?php if (!empty($rsvps)): ?>
            <table>
                <?php include(ROOT_PATH . "/app/messages/errors.php"); ?>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Event Name</th>
                        <th>Event Date</th>
                        <th>Event Location</th>
                        <th>RSVP Code</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rsvps as $key => $rsvp): ?>
                        <tr>
                            <td><?php echo $key + 1; ?></td>
                            <td><?php echo htmlspecialchars($rsvp['event_name']); ?></td>
                            <td><?php echo date('F j, Y', strtotime($rsvp['event_date'])); ?></td>
                            <td><?php echo htmlspecialchars($rsvp['event_location']); ?></td>
                            <td><?php echo htmlspecialchars($rsvp['rsvp_code']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No RSVPs found. Start RSVPing to events!</p>
        <?php endif; ?>
    </div>
</body>

</html>