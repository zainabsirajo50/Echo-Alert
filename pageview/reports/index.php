<?php
include("../../path.php");
require(ROOT_PATH . "/app/controllers/reports.php");


$user_type = isset($_SESSION['user_type']) ? $_SESSION['user_type'] : 'community_member'; // Default to 'community_member' if not set
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Report</title>
    <link rel="stylesheet" href="../../src/css/LoginForm.css">
</head>

<body>

    <!-- Header Section with Buttons and Profile Dropdown -->
    <?php include(ROOT_PATH . "/app/messages/header.php"); ?>

    <!-- Report Submission Form -->
    <div class="report-form">
        <h2>Submit a Report</h2>
        <form method="POST" action="index.php">

            <?php include(ROOT_PATH . "/app/messages/errors.php"); ?>

            <div class="form-group">
                <label>Issue Type:</label>
                <select name="issue_type_id" required>
                    <option value="">Select an issue type</option>
                    <?php foreach ($issue_types as $type): ?>

                        <option value="<?php echo $type['id']; ?>"><?php echo $type['issue_name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Location:</label>
                <input type="text" name="location" value="<?php echo $location; ?>">
            </div>
            <div>
                <button type="submit" class="submit-button">Submit Report</button>
            </div>
        </form>
    </div>


</body>

</html>