<?php
// Include the database connection file
require 'db_connection.php';

// Check if rate_id, property_id, room_id, and hotel_id are provided in the URL
if (isset($_GET['rate_id']) && isset($_GET['property_id']) && isset($_GET['room_id']) && isset($_GET['hotel_id'])) {
    // Retrieve parameters from the URL
    $rate_id = intval($_GET['rate_id']);
    $property_id = intval($_GET['property_id']);
    $room_id = intval($_GET['room_id']);
    $hotel_id = intval($_GET['hotel_id']);

    // Prepare DELETE query to remove the rate based on rate_id
    $delete_query = "DELETE FROM rates WHERE rate_id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $rate_id);

    // Execute the DELETE query
    $stmt->execute();

    // Check if the rate was successfully deleted
    if ($stmt->affected_rows > 0) {
        // Redirect to view_hotelrates.php with property_id, room_id, and hotel_id parameters
        header("Location: view_hotelrates.php?property_id={$property_id}&room_id={$room_id}&hotel_id={$hotel_id}");
        exit; // Ensure script stops here to perform the redirect
    } else {
        echo '<div class="alert alert-danger mt-3">Failed to delete rate.</div>';
    }

    // Close prepared statement
    $stmt->close();
} else {
    echo '<div class="alert alert-danger mt-3">Missing parameters for rate deletion.</div>';
}

// Close the database connection
$conn->close();
?>
