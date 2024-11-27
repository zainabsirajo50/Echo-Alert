<?php
session_start();
include "path.php";
require ROOT_PATH . "/app/database/connection.php";

$reportid = isset($_GET['reportid']) ? intval($_GET['reportid']) : 0;

// Fetch the report
$stmt = $conn->prepare("SELECT * FROM reports WHERE reportid = ?");
$stmt->bind_param("i", $reportid);
$stmt->execute();
$report = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Fetch all responses for this report
$stmt = $conn->prepare("SELECT r.response_text, r.response_date, u.name 
                        FROM responses r 
                        JOIN users u ON r.userid = u.id 
                        WHERE r.reportid = ? ORDER BY r.response_date DESC");
$stmt->bind_param("i", $reportid);
$stmt->execute();
$responses = $stmt->get_result();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Report</title>
</head>
<body>
    <h1>Report Details</h1>
    <p><strong>Issue Type:</strong> <?php echo htmlspecialchars($report['issue_type']); ?></p>
    <p><strong>Location:</strong> <?php echo htmlspecialchars($report['location']); ?></p>
    <p><strong>Date Reported:</strong> <?php echo htmlspecialchars($report['date_reported']); ?></p>
    <p><strong>Upvotes:</strong> <?php echo htmlspecialchars($report['upvote_count']); ?></p>

    <h2>Responses</h2>
    <?php if ($responses->num_rows > 0): ?>
        <ul>
            <?php while ($response = $responses->fetch_assoc()): ?>
                <li>
                    <strong><?php echo htmlspecialchars($response['name']); ?>:</strong>
                    <?php echo htmlspecialchars($response['response_text']); ?>
                    <em>(<?php echo htmlspecialchars($response['response_date']); ?>)</em>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p>No responses yet. Be the first to respond!</p>
    <?php endif; ?>

    <h2>Post a Response</h2>
    <?php if (isset($_SESSION['user_id'])): ?>
        <form action="post_response.php" method="POST">
            <textarea name="response_text" rows="5" placeholder="Write your response here..." required></textarea>
            <input type="hidden" name="reportid" value="<?php echo $reportid; ?>">
            <button type="submit">Submit Response</button>
        </form>
    <?php else: ?>
        <p>You must be logged in to post a response.</p>
    <?php endif; ?>
</body>
</html>