<?php
// signup.php
session_start();

require 'connection.php'; // Ensure this path is correct

// Check if the connection is established
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form data
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    // Basic validation
    if ($password === $confirmPassword) {
        // Hash the password for security
        

        // Prepare and bind
        $insert_user_query = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($insert_user_query);

        if ($stmt) {
            $stmt->bind_param("sss", $name, $email, $password);

            // Execute the statement
            if ($stmt->execute()) {
                // Add JavaScript code to show popup after successful signup
                echo '<script>alert("User registered successfully!");</script>';
                header("Location: /~zsirajo1/src/LoginForm.php");
                exit(); // Ensure no further code is executed after redirect
            } else {
                echo "Error: " . $stmt->error; // Use stmt->error for error message
            }

            // Close the statement
            $stmt->close();
        } else {
            echo "Error preparing statement: " . $conn->error; // Handle statement preparation errors
        }
    } else {
        echo "<script>alert('Passwords do not match');</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="LoginForm.css">
    <title>Sign Up</title>
</head>
<body>
    <div class="signup-form">
        <h2>Sign Up</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label>Name:</label>
                <input type="text" name="name" required />
            </div>
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" required />
            </div>
            <div class="form-group">
                <label>Password:</label>
                <input type="password" name="password" required />
            </div>
            <div class="form-group">
                <label>Confirm Password:</label>
                <input type="password" name="confirm_password" required />
            </div>
            <button type="submit" class="submit-button">Sign Up</button>
        </form>
        <p class="signup-link">Already have an account? <a href="/~zsirajo1/src/LoginForm.php">Sign In</a></p>
    </div>
</body>
</html>