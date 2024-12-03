<?php


session_start();
require ROOT_PATH . "/app/database/connection.php";

// Initialize an empty array to store any error messages
$errors = [];
$location = '';
$user_type = isset($_SESSION['user_type']) ? $_SESSION['user_type'] : 'community_member'; // Default to 'community_member' if not set
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
// Check if the form was submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!isset($_SESSION['user_id'])) {
        die("You must be logged in to submit a report.");
    }

    // Sanitize and receive input
    $issue_type_id = intval($_POST['issue_type_id']);
    $location = trim($_POST['location']);
    $userid = $_SESSION['user_id'];

    // Validate the inputs
    if (empty($issue_type_id) || !is_numeric($issue_type_id)) {
        $errors[] = "Please select a valid issue type.";
    } else {
        // Ensure the issue_type_id exists in the database
        $stmt = $conn->prepare("SELECT id, issue_name FROM issue_types WHERE id = ?");
        $stmt->bind_param("i", $issue_type_id);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 0) {
            $errors[] = "Selected issue type does not exist.";
        } else {
            // Fetch the issue name from the database
            $stmt->bind_result($id, $issue_name);
            $stmt->fetch();
        }
        $stmt->close();
    }

    if (empty($location)) {
        $errors[] = "Location is required.";
    }

    // If no errors, proceed to insert the data
    if (count($errors) === 0) {
        $sql = "INSERT INTO reports (userid, issue_type_id, location) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iis", $userid, $issue_type_id, $location);

        if ($stmt->execute()) {
             // Create a notification for the user
        $message = "A report about '$issue_name' has been submitted. in '$location'";
        createNotification($userid, $message, $conn);

       
            $_SESSION['message'] = "Report submitted successfully!";
            $_SESSION['type'] = "success-message";
            header("Location: " . BASE_URL . "/pageview/reports/index.php");
            exit();
        } else {
            $_SESSION['message'] = 'Failed to submit the report.';
            $_SESSION['type'] = 'error-message';
        }

        $stmt->close();
    }
}

// Function to create a new notification
function createNotification($userid, $message, $conn) {
    $stmt = $conn->prepare("INSERT INTO notifications (user_id, message) VALUES (?, ?)");
    $stmt->bind_param("is", $userid, $message);
    if ($stmt->execute()) {
        echo "Notification created successfully.<br>";
    } else {
        echo "Error creating notification: " . $stmt->error . "<br>";
    }
    $stmt->close();
}

// Function to fetch notifications
function getNotifications($user_id, $conn) {
    $query = "SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id); // Ensure user_id is properly passed
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC); // Fetch notifications as associative array
    } else {
        return []; // Return an empty array if no notifications are found
    }
}

// functions.php (or wherever your function is)
function getAllNotifications($conn) {
    // Create the query to fetch all notifications
    $query = "SELECT * FROM notifications";
    
    // Execute the query
    $result = $conn->query($query);
    
    // Check if the query was successful
    if ($result) {
        // Fetch all notifications as an associative array
        return $result->fetch_all(MYSQLI_ASSOC);
    } else {
        // Handle any errors that may occur
        return [];
    }
}

// Fetch the notifications for the logged-in user
$notifications = getAllNotifications($conn);



// Fetch all reports from the database
function selectALLReports($conn)
{
    $stmt = $conn->prepare("
        SELECT 
            r.reportid,
            it.issue_name,
            r.location,
            r.date_reported,
            r.status,
            r.upvote_count
        FROM 
            reports r
        JOIN 
            issue_types it ON r.issue_type_id = it.id
        ORDER BY 
            r.date_reported ASC
    ");
    $stmt->execute();
    $result = $stmt->get_result();

    $reports = [];
    while ($row = $result->fetch_assoc()) {
        $reports[] = $row;
    }

    $stmt->close();
    return $reports;
}
// Call the function to fetch reports
$reports = selectALLReports($conn);

// Fetch issue types from the database
function selectALLTypes($conn)
{
    $stmt = $conn->prepare("SELECT id, issue_name FROM issue_types ORDER BY id ASC");
    $stmt->execute();
    $result = $stmt->get_result();

    $issue_types = [];
    while ($row = $result->fetch_assoc()) {
        $issue_types[] = $row;
    }

    $stmt->close();

    return $issue_types;
}

$issue_types = selectALLTypes($conn);

// Fetch reports based on search query for issue name or location
function searchReportsByQuery($conn, $search_query)
{
    // Base SQL query
    $sql = "
        SELECT 
            r.reportid,
            it.issue_name,
            r.location,
            r.date_reported,
            r.status,
            r.upvote_count
        FROM 
            reports r
        JOIN 
            issue_types it ON r.issue_type_id = it.id
        WHERE 
            it.issue_name LIKE ? OR r.location LIKE ?
        ORDER BY 
            r.date_reported DESC
    ";

    // Prepare the SQL statement
    $stmt = $conn->prepare($sql);

    // Bind the search query as a LIKE parameter
    $like_query = "%" . $search_query . "%";
    $stmt->bind_param("ss", $like_query, $like_query);

    // Execute the statement
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    // Fetch all matching reports
    $reports = [];
    while ($row = $result->fetch_assoc()) {
        $reports[] = $row;
    }

    // Close the statement and return the reports
    $stmt->close();
    return $reports;
}

// Fetch most recent reports
function selectRecentReports($conn)
{
    $sql = "
        SELECT 
            r.reportid,
            it.issue_name,
            r.location,
            r.date_reported,
            r.status,
            r.upvote_count
        FROM 
            reports r
        JOIN 
            issue_types it ON r.issue_type_id = it.id
        ORDER BY 
            r.date_reported DESC
    ";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();

    $reports = [];
    while ($row = $result->fetch_assoc()) {
        $reports[] = $row;
    }

    $stmt->close();
    return $reports;
}

// Fetch most voted reports
function selectMostVotedReports($conn)
{
    $sql = "
        SELECT 
            r.reportid,
            it.issue_name,
            r.location,
            r.date_reported,
            r.status,
            r.upvote_count
        FROM 
            reports r
        JOIN 
            issue_types it ON r.issue_type_id = it.id
        ORDER BY 
            r.upvote_count DESC
    ";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();

    $reports = [];
    while ($row = $result->fetch_assoc()) {
        $reports[] = $row;
    }

    $stmt->close();
    return $reports;
}

// Check for search query or filters
$search_query = isset($_GET['search_query']) ? trim($_GET['search_query']) : null;
$filter = isset($_GET['filter']) ? trim($_GET['filter']) : 'all';

// Apply filters or search
if ($search_query) {
    $reports = searchReportsByQuery($conn, $search_query);
} else {
    switch ($filter) {
        case 'recent':
            $reports = selectRecentReports($conn); // Fetch recent reports
            break;
        case 'most_votes':
            $reports = selectMostVotedReports($conn); // Fetch most voted reports
            break;
        default:
            $reports = selectALLReports($conn); // Fetch all reports
            break;
    }
}
