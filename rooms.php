<?php
// Include the database connection file
require 'db_connection.php';

// Initialize variables
$property_id = intval($_GET['hotel_id']);
$room_type = $capacity = $menu_type = '';
$error_message = '';

// Retrieve the hotel name for the page title
$hotel_name = '';
$hotel_query = "SELECT property_name FROM accommodations WHERE property_id = ?";
$stmt_hotel = $conn->prepare($hotel_query);
$stmt_hotel->bind_param("i", $property_id);
$stmt_hotel->execute();
$result_hotel = $stmt_hotel->get_result();

if ($result_hotel->num_rows > 0) {
    $hotel_data = $result_hotel->fetch_assoc();
    $hotel_name = $hotel_data['property_name'];
}
$stmt_hotel->close();

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
            // Insert new room details
            $insert_query = "INSERT INTO rooms (property_id, room_type, capacity, menu_type) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($insert_query);
            $stmt->bind_param("isss", $property_id, $room_type, $capacity, $menu_type);
            $stmt->execute();
            $stmt->close();
        }
    } elseif (isset($_POST["save_edited_room"])) {
        // Update room details
        $room_id = intval($_POST["room_id"]);
        $room_type = mysqli_real_escape_string($conn, $_POST["room_type"]);
        $capacity = intval($_POST["capacity"]);
        $menu_type = mysqli_real_escape_string($conn, $_POST["menu_type"]);

        // Validate inputs
        if (empty($room_type) || empty($menu_type) || $capacity <= 0) {
            $error_message = "Please fill in all fields with valid values.";
        } else {
            // Update room details
            $update_query = "UPDATE rooms SET room_type = ?, capacity = ?, menu_type = ? WHERE room_id = ?";
            $stmt = $conn->prepare($update_query);
            $stmt->bind_param("sisi", $room_type, $capacity, $menu_type, $room_id);
            $stmt->execute();
            $stmt->close();
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
    <title><?php echo $hotel_name; ?> Rooms</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>

    <!-- Header -->
    <?php include 'header.php'; ?>

    <div class="container mt-5">
        <h2><?php echo $hotel_name; ?> Rooms</h2>

        <!-- Back to Accommodations Button -->
        <a href="accomodations.php" class="btn btn-primary mb-3">Back to Accommodations</a>

        <!-- Add Room Form -->
        <form method="POST" action="rooms.php?hotel_id=<?php echo $property_id; ?>">
            <input type="hidden" name="property_id" value="<?php echo $property_id; ?>">
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="room_type">Room Type:</label>
                    <input type="text" class="form-control" id="room_type" name="room_type" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="capacity">Capacity:</label>
                    <input type="number" class="form-control" id="capacity" name="capacity" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="menu_type">Menu Type:</label>
                    <input type="text" class="form-control" id="menu_type" name="menu_type" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary" name="save_room">Add Room</button>
            <p class="text-danger mt-2"><?php echo $error_message; ?></p>
        </form>

        <!-- Existing Rooms Table -->
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
                                <button class="btn btn-info btn-sm" onclick="editRoom('<?php echo $room["room_id"]; ?>', '<?php echo addslashes($room["room_type"]); ?>', '<?php echo $room["capacity"]; ?>', '<?php echo addslashes($room["menu_type"]); ?>')">Edit</button>
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

    <!-- Include Bootstrap JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- JavaScript Function for Edit Popup -->
    <script>
        function editRoom(roomId, roomType, capacity, menuType) {
            // Set values in the edit modal form
            document.getElementById('editRoomId').value = roomId;
            document.getElementById('editRoomType').value = roomType;
            document.getElementById('editCapacity').value = capacity;
            document.getElementById('editMenuType').value = menuType;

            // Show the edit modal
            $('#editRoomModal').modal('show');
        }
    </script>

    <!-- Modal for Editing Room -->
    <div class="modal fade" id="editRoomModal" tabindex="-1" role="dialog" aria-labelledby="editRoomModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editRoomModalLabel">Edit Room Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Form for editing room details -->
                    <form method="POST" action="rooms.php?hotel_id=<?php echo $property_id; ?>">
                        <input type="hidden" name="room_id" id="editRoomId">
                        <div class="form-group">
                            <label for="editRoomType">Room Type:</label>
                            <input type="text" class="form-control" id="editRoomType" name="room_type" required>
                        </div>
                        <div class="form-group">
                            <label for="editCapacity">Capacity:</label>
                            <input type="number" class="form-control" id="editCapacity" name="capacity" required>
                        </div>
                        <div class="form-group">
                            <label for="editMenuType">Menu Type:</label>
                            <input type="text" class="form-control" id="editMenuType" name="menu_type" required>
                        </div>
                        <button type="submit" class="btn btn-primary" name="save_edited_room">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
