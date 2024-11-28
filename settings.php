<?php
session_start();

include "path.php";
require ROOT_PATH . "/app/database/connection.php";

// Fetch user details from the database
$user_id = $_SESSION['user_id'];
$user_type = isset($_SESSION['user_type']) ? $_SESSION['user_type'] : 'community_member'; // Default to 'community_member' if not set
$query = "SELECT name, email, user_type FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo "User not found.";
    exit();
}

// Handle form submission for updating user details
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $pasword = $_POST('password');
    $update_query = "UPDATE users SET name = ?, email = ?, password = ?, WHERE id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ssi", $name, $email, $password, $user_id);

    if ($stmt->execute()) {
        echo "<script>alert('Settings updated successfully!');</script>";
        $_SESSION['user_name'] = $name; // Update session data if name changes
    } else {
        echo "<script>alert('Failed to update settings.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="src/css/LoginForm.css">
    <title>User Settings</title>
</head>
<body>

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
                    <a href="<?php echo BASE_URL; ?>/view_profile.php">View Profile</a>
                    <a href="<?php echo BASE_URL; ?>/settings.php">Settings</a>
                    <a href="<?php echo BASE_URL; ?>/logout.php">Logout</a>
                </div>
            </div>
        </div>
    </header>
    <div class="report-form">
    <h1>User Settings</h1>
    <form method="POST" action="">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
        <br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
        <br>
        <button type="submit" class="submit-button">Update</button>
    </form>
    <br>
    <a class="signup-link" href="<?php echo BASE_URL; ?>/change_password.php">Change Password</a>
    <br>
    <a class="signup-link" href="<?php echo BASE_URL; ?>/logout.php">Logout</a>
</div>
</body>
</html>