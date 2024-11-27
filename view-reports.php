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

    <header>
        <div class="header-container">
            <div class="header-buttons">
            <!-- Dynamically set the link based on user type -->
            <button  onclick="window.location.href='<?php echo $user_type === 'govt_worker' ? BASE_URL . '/govt-homepage.php' : BASE_URL . '/user-homepage.php'; ?>'">
                Home
            </button>
            </div>
            <div class="header-buttons">
                <button onclick="window.location.href='<?php echo BASE_URL; ?>/pageview/events/index.php'">View
                    Events</button>
                  
            </div>
    
              <!-- Profile Dropdown -->
              <div class="profile-dropdown">
                <button class="profile-button">
                <div>
                 Hi, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!
                </div>
                </button>
                <div class="dropdown-menu">
                    <a href="<?php echo BASE_URL; ?>/view_profile.php">View Profile</a>
                    <a href="<?php echo BASE_URL; ?>/settings.php">Settings</a>
                    <a href="<?php echo BASE_URL; ?>/logout.php">Logout</a>
                </div>
            </div>
        </div>
        </div>
    </header>

    <div class="response-card">
        <div class="report-info">
            <h1>Report Details</h1>
            <p><strong>Issue Type:</strong> <?php echo htmlspecialchars($report['issue_type']); ?></p>
            <p><strong>Location:</strong> <?php echo htmlspecialchars($report['location']); ?></p>
            <p><strong>Date Reported:</strong> <?php echo htmlspecialchars($report['date_reported']); ?></p>
            <p><strong>Upvotes:</strong> <?php echo htmlspecialchars($report['upvote_count']); ?></p>
        </div>
        <div class="response-section">
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
                <form action="post-response.php" method="POST">
                    <textarea name="response_text" rows="5" placeholder="Write your response here..." required></textarea>
                    <input type="hidden" name="reportid" value="<?php echo $reportid; ?>">
                    <button class="submit-button" type="submit">Submit Response</button>
                </form>
        </div>
        <?php else: ?>
            <p>You must be logged in to post a response.</p>
        <?php endif; ?>
        <br>
        <a href="<?php echo BASE_URL; ?>/govt-homepage.php" style="text-decoration: none; font-size: 16px; color: #348AA7;">&larr; Back to Homepage</a>
    </div>
</body>
</html>