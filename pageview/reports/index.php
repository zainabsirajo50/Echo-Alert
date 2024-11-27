<?php
include("../../path.php");
include(ROOT_PATH . "/app/controllers/reports.php");

$user_type = isset($_SESSION['user_type']) ? $_SESSION['user_type'] : 'community_member'; // Default to 'community_member' if not set
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Report</title>
    <link rel="stylesheet" href="../../src/css/LoginForm.css"> 
</head>

<body>

     <!-- Header Section with Buttons and Search Bar -->
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

    <!-- Report Submission Form -->
    <div class="report-form">
        <h2>Submit a Report</h2>
        <form method="POST" action="index.php">

            <?php include(ROOT_PATH . "/app/messages/errors.php"); ?>

            <div class="form-group">
                <label>Issue Type:</label>
                <input type="text" name="issue_type" value="<?php echo $issue_type; ?>">
            </div>
            <div class="form-group">
                <label>Location:</label>
                <input type="text" name="location" value="<?php echo $location; ?>">
            </div>
            <div>
                <button type="submit" class="submit-button">Submit Report</button>
            </div>
        </form>
    </div>


</body>

</html>