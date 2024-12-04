<?php
include "path.php";
require ROOT_PATH . "/app/database/connection.php";

// Checks if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $reportid = $_POST['reportid'] ?? null;
    $location = $_POST['location'] ?? null;
    $issue_type = $_POST['issue_type'] ?? null;

    // Validate required fields
    if ($reportid && $location && $issue_type) {
        // Prepares the update query
        $stmt = $conn->prepare("UPDATE reports SET location = ?, issue_type_id = ? WHERE reportid = ?");
        $stmt->bind_param("sii", $location, $issue_type, $reportid);

        // Execute the query
        if ($stmt->execute()) {
            // Redirect to the view page
            header("Location: view-profile.php?success=1");
            exit;
        } else {
            echo "Error updating report: " . $conn->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "All fields are required!";
    }
} else {
    echo "Invalid request.";
}
?>
