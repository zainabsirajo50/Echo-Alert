<?php
include 'app/controllers/reports.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Report</title>
    <link rel="stylesheet" href="../../src/css/LoginForm.css"> <!-- Link to your custom CSS -->
</head>
<body>

<!-- Header Section with Buttons and Search Bar -->
<header>
    <div class="header-container">
        <div class="header-buttons">
            <button onclick="window.location.href='/Eco-Alert/user-homepage.php'">Home</button>
            <button onclick="window.location.href='../events/index.php'">View Events</button>
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
    <h2>Submit a Report</h2>
    <form method="POST" action="">
        <div class="form-group">
            <label>Issue Type:</label>
            <input type="text" name="issue_type" required />
        </div>
        <div class="form-group">
            <label>Location:</label>
            <input type="text" name="location" required />
        </div>
        <button type="submit" class="submit-button">Submit Report</button>
    </form>
</div>

</body>
</html>