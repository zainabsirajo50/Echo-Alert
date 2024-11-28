<?php
// report_submission.php
include "path.php";
require "app/controllers/reports.php"; // Ensure this path is correct
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
                    <button onclick="window.location.href='<?php echo $user_type === 'govt_worker' ? BASE_URL . '/govt-homepage.php' : BASE_URL . '/user-homepage.php'; ?>'">
                        Home
                    </button>
                        <?php if ($user_type !== 'govt_worker'): ?>
                            <button onclick="window.location.href='<?php echo BASE_URL; ?>/pageview/reports/index.php'">Create
                            Report</button>

                        <?php endif; ?>
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

    <!-- Report Section -->
    <div class="report-form">
        <h2>Reports Near Me</h2>

        <!-- Display Reports -->
        <section class="reports-section">
            <ul>
                <?php if (!empty($reports)): ?>
                    <?php foreach ($reports as $report): ?>
                        <li class="report-item">
                            <h3><?php echo htmlspecialchars($report['issue_type']); ?></h3>
                            <p><strong>Location:</strong> <?php echo htmlspecialchars($report['location']); ?></p>
                            <p><strong>Date:</strong> <?php echo date('F j, Y', strtotime($report['date_reported'])); ?></p>
                            <p><strong>Upvotes:</strong> <?php echo $report['upvote_count']; ?></p>

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