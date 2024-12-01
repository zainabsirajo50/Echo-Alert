<?php
session_start();
require ROOT_PATH . "/app/database/connection.php"; // Ensure this path is correct

// Check if the connection is established
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Insert test events into the database if no events exist
function insertTestEvents($conn)
{
    $query_check_events = "SELECT COUNT(*) AS count FROM events";
    $result_check = $conn->query($query_check_events);
    $row = $result_check->fetch_assoc();

    if ($row['count'] == 0) {
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
        $conn->query($insert_query);
    }
}
insertTestEvents($conn);

// Function to fetch all events
function getAllEvents($conn)
{
    $query = "SELECT * FROM events ORDER BY event_date ASC";
    $result = $conn->query($query);
    $events = [];
    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }
    return $events;
}

// Function to fetch recent events
function getRecentEvents($conn)
{
    $today = date('Y-m-d');
    $query = "SELECT * FROM events WHERE event_date >= ? ORDER BY event_date ASC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $today);
    $stmt->execute();
    $result = $stmt->get_result();
    $events = [];
    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }
    $stmt->close();
    return $events;
}

// Function to search events by location and name
function searchEvents($conn, $searchQuery)
{
    $query = "SELECT * FROM events WHERE event_name LIKE ? OR event_location LIKE ? ORDER BY event_date ASC";
    $searchTerm = "%" . $searchQuery . "%";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
    $events = [];
    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }
    $stmt->close();
    return $events;
}

// Determine which data to fetch based on user action
$searchQuery = isset($_GET['search_query']) ? $_GET['search_query'] : null;
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';

if ($searchQuery) {
    $events = searchEvents($conn, $searchQuery);
} elseif ($filter === 'recent') {
    $events = getRecentEvents($conn);
} else {
    $events = getAllEvents($conn);
}
