<?php
    session_start();
    include "path.php";
    require ROOT_PATH . "/app/database/connection.php";

if (isset($_POST['reportId'])) {
    $reportId = intval($_POST['reportId']); // Sanitize the input

    // Prepare the SQL DELETE query
    $stmt = $conn->prepare("DELETE FROM reports WHERE reportid = ?");
    $stmt->bind_param("i", $reportId);

    if ($stmt->execute()) {
        echo "Report deleted successfully.";
    } else {
        echo "Error: Failed to delete the report.";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request.";
}
?>