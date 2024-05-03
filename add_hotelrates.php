<?php
// Include the database connection file
require 'db_connection.php';

// Initialize variables
$room_id = intval($_GET['room_id']);
$property_id = intval($_GET['property_id']);
$hotel_id = intval($_GET['hotel_id']); // Obtain hotel_id from URL

$season_name = $season_start = $season_end = $price = '';
$error_message = '';

// Handle form submission for adding rates
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["save_rates"])) {
    // Sanitize form inputs
    $season_name = mysqli_real_escape_string($conn, $_POST["season_name"]);
    $season_start = $_POST["season_start"];
    $season_end = $_POST["season_end"];
    $price = floatval($_POST["price"]);

    // Validate inputs
    if (empty($season_name) || empty($season_start) || empty($season_end) || $price <= 0) {
        $error_message = "Please fill in all fields with valid values.";
    } else {
        // Insert rates into the database
        $insert_query = "INSERT INTO rates (room_id, season_name, season_start, season_end, price_usd) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("isssd", $room_id, $season_name, $season_start, $season_end, $price);
        $stmt->execute();
        $stmt->close();

        // Redirect to rates view page with property_id, room_id, and hotel_id
        header("Location: view_hotelrates.php?property_id=$property_id&room_id=$room_id&hotel_id=$hotel_id");
        exit;
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
    <title>Add Room Rates</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>

    <!-- Include header.php file for the header -->
    <?php include 'header.php'; ?>

    <div class="container">
        <h2 class="mt-3">Add Room Rates</h2>

        <!-- Form for adding rates -->
        <form method="POST" action="">
            <div class="form-group">
                <label for="season_name">Season Name:</label>
                <input type="text" class="form-control" id="season_name" name="season_name" value="<?php echo htmlspecialchars($season_name); ?>" required>
            </div>
            <div class="form-group">
                <label for="season_start">Season Start:</label>
                <input type="date" class="form-control" id="season_start" name="season_start" value="<?php echo $season_start; ?>" required>
            </div>
            <div class="form-group">
                <label for="season_end">Season End:</label>
                <input type="date" class="form-control" id="season_end" name="season_end" value="<?php echo $season_end; ?>" required>
            </div>
            <div class="form-group">
                <label for="price">Price (USD):</label>
                <input type="number" step="0.01" class="form-control" id="price" name="price" value="<?php echo $price; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary" name="save_rates">Save Rates</button>
            <p class="text-danger"><?php echo $error_message; ?></p>
        </form>

    </div>

    <!-- Include Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
