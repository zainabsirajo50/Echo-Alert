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
    <link rel="stylesheet" href="src/css/style.css">

</head>

<body>

    <!-- Header Section with Buttons and Profile Dropdown -->
    <?php include(ROOT_PATH . "/app/messages/header.php"); ?>

    <!-- Display Total Reports -->
    <div class="report-summary">
        <h1>Total Reports: <?php echo $total_reports; ?></h1>
    </div>

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

    <!-- Display Total Reports -->
    <div class="report-summary">
        <h2>Total Reports: <?php echo $total_reports; ?></h2>
    </div>

    <!-- Report Section -->
    <div class="events-container">
        <h2>Reports</h2>

        <!-- Display Reports -->
        <section class="events-cards">

            <?php if (!empty($reports)): ?>
                <?php foreach ($reports as $report): ?>
                    <div class="event-card">
                        <h3 class="event-name"><?php echo htmlspecialchars($report['issue_name']); ?> Issue</h3>
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
                            <button type="submit" class="upvote-button">⬆ Upvote</button>
                        </form>

                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No reports found.</p>
            <?php endif; ?>

        </section>
    </div>

</body>

</html>