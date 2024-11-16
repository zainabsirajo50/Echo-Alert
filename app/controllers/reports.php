<?php
// report_submission.php
session_start();
include "../../path.php";
require ROOT_PATH . "/app/database/connection.php";// Ensure this path is correct
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
    var_dump($_POST);

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


