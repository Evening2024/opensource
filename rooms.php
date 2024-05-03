<?php
// Include the database connection file
require 'db_connection.php';

// Initialize variables
$property_id = intval($_GET['hotel_id']);
$room_type = $capacity = $menu_type = '';
$error_message = '';

// Handle form submission for adding/editing rooms
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["save_room"])) {
        // Sanitize form inputs
        $room_type = mysqli_real_escape_string($conn, $_POST["room_type"]);
        $capacity = intval($_POST["capacity"]);
        $menu_type = mysqli_real_escape_string($conn, $_POST["menu_type"]);

        // Validate inputs
        if (empty($room_type) || empty($menu_type) || $capacity <= 0) {
            $error_message = "Please fill in all fields with valid values.";
        } else {
            // Insert or update room details
            if (isset($_POST["room_id"])) {
                // Update room details
                $room_id = intval($_POST["room_id"]);
                $update_query = "UPDATE rooms SET room_type = ?, capacity = ?, menu_type = ? WHERE room_id = ?";
                $stmt = $conn->prepare($update_query);
                $stmt->bind_param("sisi", $room_type, $capacity, $menu_type, $room_id);
                $stmt->execute();
                $stmt->close();
            } else {
                // Insert new room details
                $insert_query = "INSERT INTO rooms (property_id, room_type, capacity, menu_type) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($insert_query);
                $stmt->bind_param("isss", $property_id, $room_type, $capacity, $menu_type);
                $stmt->execute();
                $stmt->close();
            }
        }
    } elseif (isset($_POST["delete_room"])) {
        // Delete room
        $room_id = intval($_POST["room_id"]);
        $delete_query = "DELETE FROM rooms WHERE room_id = ?";
        $stmt = $conn->prepare($delete_query);
        $stmt->bind_param("i", $room_id);
        $stmt->execute();
        $stmt->close();
    }
}

// Retrieve existing room details for the specified accommodation
$rooms_data = [];
$query = "SELECT * FROM rooms WHERE property_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $property_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $rooms_data[] = $row;
    }
}
$stmt->close();

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Details</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="bootstrap/dist/css/bootstrap.min.css">
</head>

<body>

  <!-- Include header.php file for the header -->
  <?php include 'header.php'; ?>

</br></br>
 <!-- Back button to return to accommodations page -->
 <a href="accomodations.php" class="btn btn-primary mb-3">Back To Accomodations</a>
    <div class="container">
        <h2 class="mt-3">Room Details</h2>

        <!-- Form for adding/editing rooms -->
        <form method="POST" action="rooms.php?hotel_id=<?php echo $property_id; ?>">
            <input type="hidden" name="property_id" value="<?php echo $property_id; ?>">
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="room_type">Room Type:</label>
                    <input type="text" class="form-control" id="room_type" name="room_type" value="<?php echo htmlspecialchars($room_type); ?>" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="capacity">Capacity:</label>
                    <input type="number" class="form-control" id="capacity" name="capacity" value="<?php echo htmlspecialchars($capacity); ?>" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="menu_type">Menu Type:</label>
                    <input type="text" class="form-control" id="menu_type" name="menu_type" value="<?php echo htmlspecialchars($menu_type); ?>" required>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary" name="save_room">Save Room Details</button>
            <p class="text-danger"><?php echo $error_message; ?></p>
        </form>

        <!-- Display existing room details -->
        <div class="mt-4">
            <h4>Existing Room Details</h4>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Room Type</th>
                        <th>Capacity</th>
                        <th>Menu Type</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rooms_data as $index => $room): ?>
                        <tr>
                            <td><?php echo $index + 1; ?></td>
                            <td><?php echo htmlspecialchars($room["room_type"]); ?></td>
                            <td><?php echo htmlspecialchars($room["capacity"]); ?></td>
                            <td><?php echo htmlspecialchars($room["menu_type"]); ?></td>
                            <td>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="room_id" value="<?php echo $room['room_id']; ?>">
                                    <button type="submit" class="btn btn-info btn-sm" name="edit_room">Edit</button>
                                </form>
                                <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this room?');">
                                    <input type="hidden" name="room_id" value="<?php echo $room['room_id']; ?>">
                                    <button type="submit" class="btn btn-danger btn-sm" name="delete_room">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Include Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
