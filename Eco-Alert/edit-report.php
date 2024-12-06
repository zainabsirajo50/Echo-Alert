<?php
    include "path.php";
    require ROOT_PATH . "/app/database/connection.php";
    include(ROOT_PATH . "/app/controllers/reports.php");

    if (isset($_GET['reportid'])) {
        $reportId = intval($_GET['reportid']); // Sanitize input
    
        // Fetch report details
        // $stmt = $conn->prepare("SELECT * FROM reports WHERE reportid = ?");

        $stmt = $conn->prepare("SELECT * 
        FROM reports r
        JOIN issue_types it ON r.issue_type_id = it.id 
        WHERE reportid = ?");

        $stmt->bind_param("i", $reportId);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows > 0) {
            $report = $result->fetch_assoc();
        } else {
            echo "Report not found.";
        }

        $issue_types_result = $conn->query("SELECT * FROM issue_types");
    
        $stmt->close();
    } else {
        echo "No report ID specified.";
    }

    
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Report</title>
    <link rel="stylesheet" href="src/css/LoginForm.css">
</head>

<body>
    <?php include(ROOT_PATH . "/app/messages/header.php"); ?>

    <!-- Report Edit Form -->
    <div class="report-form">
    <h2>Edit Report</h2>
    <form method="POST" action="submit-report.php">
        <?php include(ROOT_PATH . "/app/messages/errors.php"); ?>

        <!-- Hidden input for report ID -->
        <input type="hidden" name="reportid" value="<?php echo $report['reportid']; ?>">

        <div class="form-group">
            <label for="issue_type">Issue Type:</label>
            <select id="issue_type" name="issue_type" required>
            <?php
            foreach ($issue_types as $issue_type):
                $selected = ($report['issue_type_id'] == $issue_type['id']) ? 'selected' : '';
            ?>
                <option value="<?php echo $issue_type['id']; ?>" <?php echo $selected; ?>>
                    <?php echo htmlspecialchars($issue_type['issue_name']); ?>
                </option>
            <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="location">Location:</label>
            <input type="text" id="location" name="location" value="<?php echo htmlspecialchars($report['location']); ?>" required>
        </div>

        <div>
            <button type="submit" class="submit-button">Edit Report</button>
        </div>
    </form>
</div>



</body>

</html>