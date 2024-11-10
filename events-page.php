<?php
// events_page.php
session_start();
require 'connection.php'; // Ensure this path is correct

// Check if the connection is established
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Insert test events into the database if no events exist
$query_check_events = "SELECT COUNT(*) AS count FROM events";
$result_check = $conn->query($query_check_events);
$row = $result_check->fetch_assoc();

if ($row['count'] == 0) {
    // Insert test data if the events table is empty
    $insert_query = "
    INSERT INTO events (event_name, event_date, event_location, event_description) 
    VALUES
    ('Community Cleanup Day', '2024-11-15', 'Central Park, Downtown', 'Join us for a community cleanup event at Central Park. Volunteers will help clean the park and surrounding areas to keep our city beautiful and sustainable.'),
    ('Environmental Awareness Workshop', '2024-11-20', 'City Hall, Conference Room 3', 'Attend this free workshop on environmental awareness, where we’ll cover topics such as recycling, water conservation, and reducing carbon footprints.'),
    ('Beach Clean-Up Drive', '2024-12-05', 'Sunny Beach, West Coast', 'Help us keep our beaches clean! Volunteers will gather to pick up trash, sort recyclable materials, and spread awareness about ocean pollution.'),
    ('Tree Planting Campaign', '2024-12-10', 'Oakwood Park', 'Be a part of our tree planting campaign to increase green spaces and help combat climate change. No prior experience necessary—just bring a willingness to help!'),
    ('Sustainability Fair', '2024-12-15', 'Community Center, Northside', 'Come visit the Sustainability Fair to learn more about eco-friendly products and sustainable practices. Local vendors and community organizations will be on hand to showcase their green initiatives.'),
    ('Recycling Drive', '2024-12-22', 'Greenfield Plaza', 'We’re collecting recyclable materials! Bring your glass, plastic, and paper to Greenfield Plaza for recycling. Let’s reduce waste together!'),
    ('Wildlife Conservation Awareness', '2025-01-05', 'Riverstone Park', 'Join us for a wildlife conservation seminar, where experts will discuss how we can protect local wildlife and preserve biodiversity in our area.'),
    ('Eco-Friendly Lifestyle Expo', '2025-01-12', 'Expo Center, Downtown', 'A full day event showcasing products and services that promote sustainable living. Come explore green alternatives for everyday items, from clothing to home appliances.');
    ";

    // Execute the insert query
    if ($conn->query($insert_query) === TRUE) {
        echo "Events inserted successfully.";
    } else {
        echo "Error inserting events: " . $conn->error;
    }
}

// Fetch events from the database
$query = "SELECT * FROM events";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    // Fetch events data
    $events = [];
    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }
} else {
    $events = [];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- Header Section with Buttons and Search Bar -->
<header>
    <div class="header-container">
        <div class="header-buttons">
            <button onclick="window.location.href='reports.php'">Create Report</button>
            <button onclick="window.location.href='user-homepage.php'">Home</button>
        </div>

        <div class="header-search">
            <form method="GET" action="search_results.php">
                <input type="text" name="search_query" placeholder="Search reports or events..." required>
                <button type="submit">Search</button>
            </form>
        </div>
    </div>
</header>

<!-- Events Section -->
<div class="events-container">
    <h2>Upcoming Events</h2>

    <?php if (!empty($events)): ?>
        <div class="events-cards">
            <?php foreach ($events as $event): ?>
                <div class="event-card">
                    <h3 class="event-name"><?php echo htmlspecialchars($event['event_name']); ?></h3>
                    <p class="event-date"><?php echo date('F j, Y', strtotime($event['event_date'])); ?></p>
                    <p class="event-location"><?php echo htmlspecialchars($event['event_location']); ?></p>
                    <p class="event-description"><?php echo htmlspecialchars($event['event_description']); ?></p>
                    <!-- Example button for further interaction -->
                    <button>RSVP</button>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No upcoming events available.</p>
    <?php endif; ?>
</div>

</body>
</html>