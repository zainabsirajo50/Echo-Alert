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
$stmt = $conn->prepare("SELECT r.response_text, DATE(r.response_date) AS response_date, u.name 
                        FROM responses r 
                        JOIN users u ON r.userid = u.id 
                        WHERE r.reportid = ? ORDER BY r.response_date DESC");
$stmt->bind_param("i", $reportid);
$stmt->execute();
$responses = $stmt->get_result();
$stmt->close();
?>

<?php
// Assuming user type is stored in the session as 'user_type'
$user_type = isset($_SESSION['user_type']) ? $_SESSION['user_type'] : 'community_member'; // Default to 'community_member' if not set
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="src/css/response-page.css"> <!-- Link to the updated CSS -->
    <link rel="stylesheet" href="src/css/LoginForm.css">
    <title>View Report</title>
</head>

<body>

    <!-- Header Section with Buttons and Profile Dropdown -->
    <?php include(ROOT_PATH . "/app/messages/header.php"); ?>

    <div class="response-card">
        <div class="report-info">
            <h1>Report Details</h1>
            <p><strong>Issue Type:</strong> <?php echo htmlspecialchars($report['issue_type']); ?></p>
            <p><strong>Location:</strong> <?php echo htmlspecialchars($report['location']); ?></p>
            <p><strong>Date Reported:</strong>
                <em>(<?php echo date('M j, Y', strtotime($response['response_date'])); ?>)</em>
            </p>
            <p><strong>Upvotes:</strong> <?php echo htmlspecialchars($report['upvote_count']); ?></p>
            <p><strong>Status:</strong>
                <?php
                $status = htmlspecialchars($report['status']);
                // Style based on the status value
                $status_class = '';
                switch ($status) {
                    case 'Pending':
                        $status_class = 'status-pending';
                        break;
                    case 'In Progress':
                        $status_class = 'status-in-progress';
                        break;
                    case 'Resolved':
                        $status_class = 'status-resolved';
                        break;
                    default:
                        $status_class = 'status-default';
                }
                ?>

                <span class="<?php echo $status_class; ?>"><?php echo $status; ?></span>

                <?php if ($user_type === 'govt_worker'): ?>
                <form action="update-report-status.php" method="POST">
                    <div class="select-status-container">
                        <select name="status" id="status" class="select-status" required>
                            <option value="Pending" <?php echo $report['status'] === 'Pending' ? 'selected' : ''; ?>>Pending
                            </option>
                            <option value="In Progress" <?php echo $report['status'] === 'In Progress' ? 'selected' : ''; ?>>
                                In Progress</option>
                            <option value="Resolved" <?php echo $report['status'] === 'Resolved' ? 'selected' : ''; ?>>
                                Resolved</option>
                        </select>
                    </div>
                    <input type="hidden" name="reportid" value="<?php echo $reportid; ?>">
                    <button type="submit" class="submit-button">Update Status</button>
                </form>
            <?php endif; ?>
            </p>
        </div>


        <div class="response-section">
            <h2>Responses</h2>
            <?php if ($responses->num_rows > 0): ?>
                <ul>
                    <?php while ($response = $responses->fetch_assoc()): ?>
                        <li>
                            <strong><?php echo htmlspecialchars($response['name']); ?>:</strong>
                            <?php echo htmlspecialchars($response['response_text']); ?>
                            <em>(<?php echo date('M j, Y', strtotime($response['response_date'])); ?>)</em></p>
                            <?php echo htmlspecialchars($response['status']); ?>
                        </li>
                    <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <p>No responses yet. Be the first to respond!</p>
            <?php endif; ?>

            <h2>Post a Response</h2>
            <?php if (isset($_SESSION['user_id'])): ?>
                <form action="post-response.php" method="POST">
                    <textarea name="response_text" rows="5" placeholder="Write your response here..."
                        required></textarea><br>

                    <input type="hidden" name="reportid" value="<?php echo $reportid; ?>">
                    <button class="submit-button" type="submit">Submit Response</button>
                </form>
            <?php else: ?>
                <p>You must be logged in to post a response.</p>
            <?php endif; ?>
        </div>
        <br>
        <a href="<?php echo BASE_URL; ?>/govt-homepage.php"
            style="text-decoration: none; font-size: 16px; color: #348AA7;">&larr; Back to Homepage</a>
    </div>
</body>

</html>