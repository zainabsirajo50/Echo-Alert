<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
echo '<br>';

session_start();
require ROOT_PATH . "/app/database/connection.php";

// Initialize an empty array to store any error messages
$errors = [];
$issue_type = '';
$location = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!isset($_SESSION['user_id'])) {
        die("You must be logged in to submit a report.");
    }

    $issue_type = trim($_POST['issue_type']);
    $location = trim($_POST['location']);
    $userid = $_SESSION['user_id'];

    if (empty($issue_type)) {
        $errors[] = "Issue type is required.";
    }

    if (empty($location)) {
        $errors[] = "Location is required.";
    }

    if (count($errors) === 0) {
        $stmt = $conn->prepare("INSERT INTO reports (userid, issue_type, location) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $userid, $issue_type, $location);

        if ($stmt->execute()) {
            // Success message
            $_SESSION['message'] = 'Report submitted successfully!';
            $_SESSION['type'] = 'success-message';
            header("Location: " . BASE_URL . "/pageview/reports/index.php");
            exit();
        } else {
            $_SESSION['message'] = 'Failed to create question';
            $_SESSION['type'] = 'error-message';
        }

        $stmt->close();
    }
}

// Fetch all reports from the database
function selectALLReports($conn)
{
    $stmt = $conn->prepare("SELECT * FROM reports");
    $stmt->execute();
    $result = $stmt->get_result();

    $reports = [];
    while ($row = $result->fetch_assoc()) {
        $reports[] = $row;
    }

    $stmt->close();

    return $reports;
}

// Call the function to fetch reports
$reports = selectALLReports($conn);
