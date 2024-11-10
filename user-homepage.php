<?php
// report_submission.php
session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

include "path.php";
require "app/database/connection.php"; // Ensure this path is correct

// Check if the connection is established
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form data
    $userid = $_SESSION['userid']; // Assuming user is logged in and userid is stored in session
    $issueType = $_POST['issue_type'] ?? '';
    $location = $_POST['location'] ?? '';


    // Insert the report into the database
    $insert_report_query = "INSERT INTO reports (userid, issue_type, location) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($insert_report_query);

    if ($stmt) {
        $stmt->bind_param("iss", $userid, $issueType, $location);

        // Execute the statement
        if ($stmt->execute()) {
            echo '<script>alert("Report submitted successfully!");</script>';
            // Redirect or display a success message
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
}

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
                <button onclick="window.location.href='<?php echo BASE_URL; ?>/user-homepage.php'">Home</button>
                <button onclick="window.location.href='<?php echo BASE_URL; ?>/pageview/reports/index.php'">Create
                    Report</button>
                <button onclick="window.location.href='<?php echo BASE_URL; ?>/pageview/events/index.php'">View
                    Events</button>
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
        <h2>Reports Near Me</h2>

    </div>

</body>

</html>