<?php
// Include the database configuration file
include 'config.php';

// Fetch events from the database
$query = "SELECT * FROM events ORDER BY event_date DESC";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $events = array();
    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }
    // Return events as JSON
    header('Content-Type: application/json');
    echo json_encode($events);
} else {
    // No events found
    echo json_encode(array());
}

// Close the database connection
$conn->close();
?>