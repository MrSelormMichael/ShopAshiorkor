<?php
// Include the database configuration file
include 'config.php';

// Fetch products from the database
$query = "SELECT * FROM products ORDER BY created_at DESC";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $products = array();
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
    // Return products as JSON
    header('Content-Type: application/json');
    echo json_encode($products);
} else {
    // No products found
    echo json_encode(array());
}

// Close the database connection
$conn->close();
?>