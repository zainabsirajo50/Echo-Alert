<?php
session_start();
require "path.php";
require "app/database/connection.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response_text = isset($_POST['response_text']) ? trim($_POST['response_text']) : '';
    $reportid = isset($_POST['reportid']) ? intval($_POST['reportid']) : 0;
    $userid = $_SESSION['user_id'];

    if (!empty($response_text) && $reportid > 0) {
        $stmt = $conn->prepare("INSERT INTO responses (reportid, userid, response_text) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $reportid, $userid, $response_text);
        if ($stmt->execute()) {
            $_SESSION['message'] = "Response posted successfully!";
            $_SESSION['type'] = "success-message";
        } else {
            $_SESSION['message'] = "Failed to post the response.";
            $_SESSION['type'] = "error-message";
        }
        $stmt->close();
    } else {
        $_SESSION['message'] = "Invalid response or report ID.";
        $_SESSION['type'] = "error-message";
    }

    header("Location: view-reports.php?reportid=" . $reportid);
    exit();
}
?>