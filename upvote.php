<?php
require 'path.php';
require 'app/controllers/reports.php';

if (isset($_POST['report_id']) && is_numeric($_POST['report_id']) && isset($_SESSION['user_id'])) {
    $reportid = $_POST['report_id'];
    $userid = $_SESSION['user_id']; // Assume user ID is stored in the session

    // Sanitize input
    $reportid = mysqli_real_escape_string($conn, $reportid);
    $userid = mysqli_real_escape_string($conn, $userid);

    // Check if the user has already voted on this report
    $query_check = "SELECT * FROM votes WHERE user_id = $userid AND report_id = $reportid";
    $result = mysqli_query($conn, $query_check);

    if (mysqli_num_rows($result) > 0) {
        // User has voted, fetch the existing vote type
        $vote = mysqli_fetch_assoc($result);
        $current_vote = $vote['vote_type'];

        if ($current_vote == 'upvote') {
            // If already upvoted, allow user to remove the upvote (no need to update the count)
            $query_remove_vote = "DELETE FROM votes WHERE user_id = $userid AND report_id = $reportid";
            $query_upvote = "UPDATE reports SET upvote_count = upvote_count - 1 WHERE reportid = $reportid";

            // Execute removal of the upvote and decrement count
            if (mysqli_query($conn, $query_remove_vote) && mysqli_query($conn, $query_upvote)) {
                header('Location: ' . BASE_URL . '/user-homepage.php');
                exit();
            } else {
                echo "Error: " . mysqli_error($conn);
            }
        }
    } else {
        // If no vote exists yet, allow the user to upvote
        $query_log_vote = "INSERT INTO votes (user_id, report_id, vote_type) VALUES ($userid, $reportid, 'upvote')";
        $query_upvote = "UPDATE reports SET upvote_count = upvote_count + 1 WHERE reportid = $reportid";

        if (mysqli_query($conn, $query_upvote) && mysqli_query($conn, $query_log_vote)) {
            header('Location: ' . BASE_URL . '/user-homepage.php');
            exit();
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
} else {
    echo "Invalid request.";
}
