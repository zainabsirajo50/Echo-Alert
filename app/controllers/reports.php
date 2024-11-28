<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
echo '<br>';

session_start();
require ROOT_PATH . "/app/database/connection.php";

// Initialize an empty array to store any error messages
$errors = [];
$location = '';

// Fetch issue types from the database
function selectALLTypes($conn)
{
    $stmt = $conn->prepare("SELECT id, issue_name FROM issue_types ORDER BY id ASC");
    $stmt->execute();
    $result = $stmt->get_result();

    $issue_types = [];
    while ($row = $result->fetch_assoc()) {
        $issue_types[] = $row;
    }

    $stmt->close();

    return $issue_types;
}

$issue_types = selectALLTypes($conn);



// Check if the form was submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!isset($_SESSION['user_id'])) {
        die("You must be logged in to submit a report.");
    }

    // Sanitize and receive input
    $issue_type_id = intval($_POST['issue_type_id']);
    $location = trim($_POST['location']);
    $userid = $_SESSION['user_id'];

    // Validate the inputs
    if (empty($issue_type_id) || !is_numeric($issue_type_id)) {
        $errors[] = "Please select a valid issue type.";
    } else {
        // Ensure the issue_type_id exists in the database
        $stmt = $conn->prepare("SELECT id FROM issue_types WHERE id = ?");
        $stmt->bind_param("i", $issue_type_id);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 0) {
            $errors[] = "Selected issue type does not exist.";
        }
        $stmt->close();
    }

    if (empty($location)) {
        $errors[] = "Location is required.";
    }

    // If no errors, proceed to insert the data
    if (count($errors) === 0) {
        $stmt = $conn->prepare("INSERT INTO reports (userid, issue_type_id, location) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $userid, $issue_type_id, $location);

        if ($stmt->execute()) {
            $_SESSION['message'] = "Report submitted successfully!";
            $_SESSION['type'] = "success-message";
            header("Location: " . BASE_URL . "/pageview/reports/index.php");
            exit();
        } else {
            $_SESSION['message'] = 'Failed to submit the report.';
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