<?php
// Include the database connection file
require 'db_connection.php';

// Initialize variables
$room_id = isset($_GET['room_id']) ? intval($_GET['room_id']) : null;
$property_id = isset($_GET['property_id']) ? intval($_GET['property_id']) : null;
$hotel_id = isset($_GET['hotel_id']) ? intval($_GET['hotel_id']) : null; // Added hotel_id retrieval

// Retrieve rates for the specified room
$rates_data = [];
$room_type = '';

if ($room_id) {
    // Query to fetch room type based on room_id
    $query_room = "SELECT room_type FROM rooms WHERE room_id = ?";
    $stmt_room = $conn->prepare($query_room);
    $stmt_room->bind_param("i", $room_id);
    $stmt_room->execute();
    $stmt_room->store_result();

    // Bind the result
    $stmt_room->bind_result($room_type);

    // Fetch room type
    if ($stmt_room->fetch()) {
        $stmt_room->close();

        // Query to fetch rates based on room_id
        $query_rates = "SELECT * FROM rates WHERE room_id = ?";
        $stmt_rates = $conn->prepare($query_rates);
        $stmt_rates->bind_param("i", $room_id);
        $stmt_rates->execute();
        $result = $stmt_rates->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $rates_data[] = $row;
            }
        }

        $stmt_rates->close();
    } else {
        $stmt_room->close();
    }
}


// Handle form submission for updating rates
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_rates"])) {
    $rate_id = intval($_POST["rate_id"]);
    $season_name = mysqli_real_escape_string($conn, $_POST["season_name"]);
    $season_start = $_POST["season_start"];
    $season_end = $_POST["season_end"];
    $price = floatval($_POST["price"]);

    // Validate inputs
    if (empty($season_name) || empty($season_start) || empty($season_end) || $price <= 0) {
        echo '<div class="alert alert-danger mt-3">Please fill in all fields with valid values.</div>';
    } else {
        // Update rates in the database
        $update_query = "UPDATE rates SET season_name = ?, season_start = ?, season_end = ?, price_usd = ? WHERE rate_id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("sssdi", $season_name, $season_start, $season_end, $price, $rate_id);

        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            // Redirect to view_hotelrates.php with property_id and room_id parameters
            header("Location: view_hotelrates.php?property_id={$property_id}&room_id={$room_id}&hotel_id={$hotel_id}");
            exit; // Ensure script stops here to perform the redirect
        }  else {
            // Display alert message using JavaScript
            echo '<script>alert("Nothing was updated."); window.location.href = "view_hotelrates.php?property_id=' . $property_id . '&room_id=' . $room_id . '&hotel_id=' . $hotel_id . '";</script>';
        }
        

        $stmt->close();
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
    <title>View Room Rates</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>

    <!-- Include header.php file for the header -->
    <?php require 'header.php'; ?>

      <!-- Button to navigate to hotelrates.php with hotel_id -->
      <div class="container mt-3">
        <a href="http://localhost/safari/hotelrates.php?hotel_id=<?php echo $hotel_id; ?>" class="btn btn-primary">
            Back to Room types
        </a>
    </div>

    <div class="container">
        <h2 class="mt-3">View Room Rates for <?php echo htmlspecialchars($room_type); ?></h2>

        <!-- Display Room Rates with Edit Form -->
        <div class="mt-4">
            <?php if (!empty($rates_data)): ?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Season Name</th>
                            <th>Season Start</th>
                            <th>Season End</th>
                            <th>Price (USD)</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rates_data as $rate): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($rate["season_name"]); ?></td>
                                <td><?php echo $rate["season_start"]; ?></td>
                                <td><?php echo $rate["season_end"]; ?></td>
                                <td><?php echo $rate["price_usd"]; ?></td>
                                <td>
                                    <!-- Edit Rates Form - Button to Trigger Edit -->
                                    <button type="button" class="btn btn-info btn-sm" 
                                            onclick="openEditForm(<?php echo $rate['rate_id']; ?>, '<?php echo htmlspecialchars($rate['season_name']); ?>', '<?php echo $rate['season_start']; ?>', '<?php echo $rate['season_end']; ?>', '<?php echo $rate['price_usd']; ?>', <?php echo $property_id; ?>, <?php echo $room_id; ?>, <?php echo $hotel_id; ?>)">
                                        Edit
                                    </button>
                                    <!-- Delete Rates Button - Pass room_id, property_id, and hotel_id to confirmDelete() -->
                                    <button type="button" class="btn btn-danger btn-sm" 
                                            onclick="confirmDelete(<?php echo $rate['rate_id']; ?>, <?php echo $hotel_id; ?>, <?php echo $property_id; ?>, <?php echo $room_id; ?>)">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <!-- No rates found message -->
                <p>No rates found for this room.</p>
            <?php endif; ?>
        </div>
    </div>

        <!-- Edit Rates Modal -->
        <div class="modal fade" id="editRateModal" tabindex="-1" role="dialog" aria-labelledby="editRateModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="editRateForm" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editRateModalLabel">Edit Room Rate</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="editRateId" name="rate_id">
                        <div class="form-group">
                            <label for="editSeasonName">Season Name:</label>
                            <input type="text" class="form-control" id="editSeasonName" name="season_name" required>
                        </div>
                        <div class="form-group">
                            <label for="editSeasonStart">Season Start:</label>
                            <input type="date" class="form-control" id="editSeasonStart" name="season_start" required>
                        </div>
                        <div class="form-group">
                            <label for="editSeasonEnd">Season End:</label>
                            <input type="date" class="form-control" id="editSeasonEnd" name="season_end" required>
                        </div>
                        <div class="form-group">
                            <label for="editPrice">Price (USD):</label>
                            <input type="number" step="0.01" class="form-control" id="editPrice" name="price" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" name="update_rates">Update Rate</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
            </div>

     <!-- Include Bootstrap JS and jQuery -->
     <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- JavaScript for Edit and Delete Functions -->
    <script>
        // Function to open the edit form with rate details
        function openEditForm(rateId, seasonName, seasonStart, seasonEnd, price, propertyId, roomId, hotelId) {
            // Set the action URL for the edit form
            $('#editRateForm').attr('action', 'view_hotelrates.php?property_id=' + propertyId + '&room_id=' + roomId + '&hotel_id=' + hotelId);
            
            // Populate the edit form with rate details
            $('#editRateId').val(rateId);
            $('#editSeasonName').val(seasonName);
            $('#editSeasonStart').val(seasonStart);
            $('#editSeasonEnd').val(seasonEnd);
            $('#editPrice').val(price);
            
            // Show the edit modal
            $('#editRateModal').modal('show');
        }
// Function to confirm delete action
function confirmDelete(rateId, hotelId, propertyId, roomId) {
    if (confirm('Are you sure you want to delete this rate?')) {
        // Send AJAX request to delete_rate.php with rate_id, hotel_id, property_id, and room_id
        $.ajax({
            type: 'GET',
            url: 'delete_rate.php',
            data: {
                rate_id: rateId,
                hotel_id: hotelId,
                property_id: propertyId,
                room_id: roomId
            },
            success: function(response) {
                // Redirect to view_hotelrates.php after successful deletion
                window.location.href = 'view_hotelrates.php?property_id=' + propertyId + '&room_id=' + roomId + '&hotel_id=' + hotelId;
            },
            error: function(xhr, status, error) {
                console.error(error);
                alert('Failed to delete rate.');
            }
        });
    }
}

    </script>


</body>

</html>
