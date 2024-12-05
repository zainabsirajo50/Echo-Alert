<?php
session_start();
require "path.php";
require "app/database/connection.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reportid = isset($_POST['reportid']) ? intval($_POST['reportid']) : 0;
    $status = isset($_POST['status']) ? $_POST['status'] : 'Pending';

    if ($reportid > 0 && in_array($status, ['Pending', 'In Progress', 'Resolved'])) {
        $stmt = $conn->prepare("UPDATE reports SET status = ? WHERE reportid = ?");
        $stmt->bind_param("si", $status, $reportid);
        if ($stmt->execute()) {
            $_SESSION['message'] = "Status updated successfully!";
            $_SESSION['type'] = "success";
        } else {
            $_SESSION['message'] = "Failed to update status.";
            $_SESSION['type'] = "error";
        }
        $stmt->close();
    } else {
        $_SESSION['message'] = "Invalid report ID or status.";
        $_SESSION['type'] = "error";
    }

    header("Location: view-reports.php?reportid=" . $reportid);
    exit();
}
?>