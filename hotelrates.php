<?php
// Include the database connection file
require 'db_connection.php';

// Retrieve all available rooms and their details including associated hotel name
$query = "SELECT r.*, a.property_name 
          FROM rooms r 
          INNER JOIN accommodations a ON r.property_id = a.property_id 
          ORDER BY r.room_id";
$result = $conn->query($query);

// Initialize room data array
$rooms_data = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $rooms_data[] = $row;
    }
}

// Close the database connection
$conn->close();
?>
<?php
// Include the database connection file
require 'db_connection.php';

// Retrieve the property_id from the URL parameter
if (isset($_GET['hotel_id'])) {
    $property_id = $_GET['hotel_id'];
} else {
    // Handle case where property_id is not provided
    echo "Error: Property ID not specified.";
    exit;
}

// Retrieve all available rooms and their details for a specific hotel
$query = "SELECT r.*, a.property_name 
          FROM rooms r 
          INNER JOIN accommodations a ON r.property_id = a.property_id 
          WHERE r.property_id = $property_id
          ORDER BY r.room_id";
$result = $conn->query($query);

// Initialize room data array
$rooms_data = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $rooms_data[] = $row;
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Room Rates</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>

    <!-- Include header.php file for the header -->
    <?php include 'header.php'; ?>

    <div class="container">
        <!-- Button to navigate back to accommodations.php -->
        <a href="accomodations.php" class="btn btn-primary mt-3">Back to Accommodations</a>

        <?php if (!empty($rooms_data)): ?>
            <?php $hotel_name = htmlspecialchars($rooms_data[0]["property_name"]); ?>
            <h2 class="mt-3"><?php echo $hotel_name; ?> Room Rates</h2>
            <div class="mt-4">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Room Type</th>
                            <th>Capacity</th>
                            <th>Menu Type</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($rooms_data as $room): ?>
    <tr>
        <td><?php echo htmlspecialchars($room["room_type"]); ?></td>
        <td><?php echo $room["capacity"]; ?></td>
        <td><?php echo htmlspecialchars($room["menu_type"]); ?></td>
        <td>
            <!-- Add Rates Button -->
            <a href="add_hotelrates.php?property_id=<?php echo $room['property_id']; ?>&room_id=<?php echo $room['room_id']; ?>&hotel_id=<?php echo $property_id; ?>" class="btn btn-primary">Add Rates</a>

            <!-- View Rates Button -->
            <a href="view_hotelrates.php?property_id=<?php echo $room['property_id']; ?>&room_id=<?php echo $room['room_id']; ?>&hotel_id=<?php echo $property_id; ?>" class="btn btn-secondary">View Rates</a>
        </td>
    </tr>
<?php endforeach; ?>

                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-info mt-3">No rooms available at the moment.</div>
        <?php endif; ?>
    </div>

    <!-- Include Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
