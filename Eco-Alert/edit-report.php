<?php
    include "path.php";
    require ROOT_PATH . "/app/database/connection.php";
    include(ROOT_PATH . "/app/controllers/reports.php");

    if (isset($_GET['reportid'])) {
        $reportId = intval($_GET['reportid']); // Sanitize input
    
        // Fetch report details
        $stmt = $conn->prepare("SELECT * FROM reports WHERE reportid = ?");
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
    <link rel="stylesheet" href="../../src/css/LoginForm.css">
</head>

<body>

    <!-- Header Section with Buttons and Search Bar -->
    <header>
        <div class="header-container">
            <div class="header-buttons">
                <button onclick="window.location.href='<?php echo $user_type === 'govt_worker' ? BASE_URL . '/govt-homepage.php' : BASE_URL . '/user-homepage.php'; ?>'">
                    Home
                </button>
                    <?php if ($user_type !== 'govt_worker'): ?>
                        <button onclick="window.location.href='<?php echo BASE_URL; ?>/pageview/reports/index.php'">Create
                        Report</button>

                    <?php endif; ?>
                    <button onclick="window.location.href='<?php echo BASE_URL; ?>/pageview/events/index.php'">View
                        Events</button>
                </div>

                <div class="header-search">
                    <form method="GET" action="search_results.php">
                        <input type="text" name="search_query" placeholder="Search reports or events..." required>
                        <button type="submit">Search</button>
                    </form>
                </div>

      
            <!-- Profile Dropdown -->
            <div class="profile-dropdown">
                <button class="profile-button">
                <div>
                 Hi, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!
                </div>
                </button>
                <div class="dropdown-menu">
                    <a href="<?php echo BASE_URL; ?>/view-profile.php">View Profile</a>
                    <a href="<?php echo BASE_URL; ?>/settings.php">Settings</a>
                    <a href="<?php echo BASE_URL; ?>/logout.php">Logout</a>
                </div>
            </div>
        </div>
    </header>

    <!-- Report Edit Form -->
    <div class="report-form">
    <h2>Edit Report</h2>
    <form method="POST" action="submit-report.php">

        <!-- Hidden input for report ID -->
        <input type="hidden" name="reportid" value="<?php echo $report['reportid']; ?>">

        <div class="form-group">
            <label for="issue_type">Issue Type:</label>
            <select id="issue_type" name="issue_type" required>
                <?php
                // Loops through each issue type from the database
                while ($issue_type = $issue_types_result->fetch_assoc()) {
                    // Check if the current issue type matches the report's issue_type_id
                    $selected = ($report['issue_type_id'] == $issue_type['id']) ? 'selected' : '';
                    echo "<option value=\"{$issue_type['id']}\" $selected>{$issue_type['issue_name']}</option>";
                }
                ?>
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
