<?php
// report_submission.php
session_start();
include "path.php";
require "app/database/connection.php"; // Ensure this path is correct

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
                <button onclick="window.location.href='<?php echo BASE_URL; ?>/pageview/events/index.php'">View
                    Events</button>
                    <button onclick="window.location.href='<?php echo BASE_URL; ?>/settings.php'">Settings
                    </button> 
            </div>

            <div class="header-search">
                <form method="GET" action="search_results.php">
                    <input type="text" name="search_query" placeholder="Search reports or events..." required>
                    <button type="submit">Search</button>
                </form>
            </div>

        </div>
    </header>

    <!-- Report Submission Form -->

    <div class="report-form">
        <h2>Govt Reports Near Me</h2>

    </div>

</body>

</html>