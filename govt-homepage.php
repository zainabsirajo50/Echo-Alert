<?php
// report_submission.php

include "path.php";
require "app/controllers/reports.php"; // Ensure this path is correct

$user_type = isset($_SESSION['user_type']) ? $_SESSION['user_type'] : 'community_member'; // Default to 'community_member' if not set

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Report</title>
    <link rel="stylesheet" href="src/css/LoginForm.css">
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