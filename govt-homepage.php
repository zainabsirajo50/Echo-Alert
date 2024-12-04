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

    <!-- Header Section with Buttons and Profile Dropdown -->
    <?php include(ROOT_PATH . "/app/messages/header.php"); ?>

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