<?php
session_start();

// Redirect to login if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: /login.php");
    exit();
}

require 'connection.php'; // Database connection

// Fetch user details from the database
$user_id = $_SESSION['user_id'];
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
    $update_query = "UPDATE users SET name = ?, email = ? WHERE id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ssi", $name, $email, $user_id);

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
    <title>User Settings</title>
</head>
<body>
    <h1>User Settings</h1>
    <form method="POST" action="">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
        <br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
        <br>
        <button type="submit">Update</button>
    </form>
    <br>
    <a href="/change_password.php">Change Password</a>
    <br>
    <a href="/logout.php">Logout</a>
</body>
</html>