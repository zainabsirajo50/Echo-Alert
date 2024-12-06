<?php
require 'path.php';  // Ensure correct path to connection file
require 'app/controllers/reports.php';

if (isset($_POST['report_id']) && is_numeric($_POST['report_id'])) {
    $reportid = $_POST['report_id'];

    // Sanitize the report ID (though it's numeric, always good practice)
    $reportid = mysqli_real_escape_string($conn, $reportid);

    // Increment the upvote count
    $query = "UPDATE reports SET upvote_count = upvote_count + 1 WHERE reportid = $reportid"; 

    if (mysqli_query($conn, $query)) {
        // Success, redirect back
        header('Location: ' . BASE_URL . '/user-homepage.php');
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    echo "Invalid report ID.";
}
?>