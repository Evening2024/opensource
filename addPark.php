<?php
// Include the database connection file
require 'db_connection.php';

// Initialize variables for form data and error messages
$park_name = $location = $owned_by = $description = "";
$error_message = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $park_name = trim($_POST["park_name"]);
    $location = trim($_POST["location"]);
    $owned_by = trim($_POST["owned_by"]);
    $description = trim($_POST["description"]);

    // Check for empty inputs
    if (empty($park_name) || empty($location) || empty($owned_by)) {
        $error_message = "Please fill in all required fields.";
    } else {
        // Insert the data into the database
        $insert_query = "INSERT INTO national_parks (park_name, location, owned_by, description) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("ssss", $park_name, $location, $owned_by, $description);
        $stmt->execute();
        $stmt->close();

        // Redirect to parks.php page after successful insertion
        header("Location: parks.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add National Park</title>
    <!-- Include Bootstrap for form styling -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Form styling */
        form {
            padding: 20px;
            border-radius: 5px;
            background-color: #f7f7f7;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        label {
            font-weight: bold;
        }

        .form-control {
            margin-bottom: 10px;
        }

       

        /* Additional styling for the form */
        .form-group button {
            background-color: #4CAF50;
            color: white;
            border-radius: 5px;
            border: none;
        }
    </style>
</head>

<body>
    <!-- Include header.php file for the header -->
    <?php include 'header.php'; ?>
    <!-- Form container -->
    <div class="container">
        <h2>Register National Park</h2>

        <!-- Form for registering national park details -->
        <form method="POST">
            <div class="form-group">
                <label for="park_name">National Park Name:</label>
                <input type="text" class="form-control" id="park_name" name="park_name" required>
            </div>
            <div class="form-group">
                <label for="location">Location:</label>
                <input type="text" class="form-control" id="location" name="location" required>
            </div>
            <div class="form-group">
                <label for="owned_by">Owned By:</label>
                <input type="text" class="form-control" id="owned_by" name="owned_by" required>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea class="form-control" id="description" name="description" rows="4"></textarea>
            </div>

            <!-- Submit button -->
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Add Park</button>
            </div>
        </form>
    </div>

    <!-- Include JavaScript files -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
