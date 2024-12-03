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