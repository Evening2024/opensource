<?php
// Include the database connection file
require 'db_connection.php';

// Initialize variables for form data and error messages
$park_name = $location = $description = "";
$error_message = "";
$edit_park_id = null;

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle adding or editing park details
    if (isset($_POST["save_park"])) {
        // Retrieve form data
        $park_name = trim($_POST["park_name"]);
        $location = trim($_POST["location"]);
        $description = trim($_POST["description"]);

        // Validate inputs
        if (empty($park_name) || empty($location) || empty($description)) {
            $error_message = "All fields are required. Please fill in all the details.";
        } else {
            // Check if this is an update or a new addition
            if (isset($_POST["park_id"])) {
                // Update the existing park details in the database
                $park_id = $_POST["park_id"];
                $update_query = "UPDATE national_parks SET park_name = ?, location = ?, description = ? WHERE park_id = ?";
                $stmt = $conn->prepare($update_query);
                $stmt->bind_param("sssi", $park_name, $location, $description, $park_id);
                $stmt->execute();
                $stmt->close();
            } else {
                // Add new park details to the database
                $insert_query = "INSERT INTO national_parks (park_name, location, description) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($insert_query);
                $stmt->bind_param("sss", $park_name, $location, $description);
                $stmt->execute();
                $stmt->close();
            }

            // Redirect to refresh page data
            header("Location: " . $_SERVER["REQUEST_URI"]);
            exit;
        }
    } elseif (isset($_POST["delete_park"])) {
        // Handle deleting park details
        $park_id = $_POST["park_id"];
        
        // Delete the park details from the database
        $delete_query = "DELETE FROM national_parks WHERE park_id = ?";
        $stmt = $conn->prepare($delete_query);
        $stmt->bind_param("i", $park_id);
        $stmt->execute();
        $stmt->close();
    }
}

// Retrieve existing park details from the database
$park_details_data = [];
$query = "SELECT * FROM national_parks";
$result = $conn->query($query);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $park_details_data[] = $row;
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
    <title>National Parks Management</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="bootstrap/dist/css/bootstrap.min.css">
    <!-- Custom CSS -->
    <style>
        /* Customize page appearance */
        body {
            background-color: #f3f7fa;
        }

        form {
            padding: 20px;
            border-radius: 5px;
            background-color: #ffffff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .btn-custom {
            background-color: #007bff;
            color: white;
        }

        .table th, .table td {
            vertical-align: middle;
            text-align: center; /* Center align table cell content */
        }

        .table th {
            background-color: #007bff;
            color: black;
        }

        .table-responsive {
            overflow-x: auto; /* Allow horizontal scrolling for large tables */
        }
    </style>
</head>

<body>
    <!-- Include header.php file for the header -->
    <?php include 'header.php'; ?>

    <div class="container">
        <h2 class="mt-3">National Parks Management</h2>

        <!-- Button to add park -->
        <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#parkModal">Add Park</button>

        <!-- Park details form modal -->
        <div class="modal fade" id="parkModal" tabindex="-1" role="dialog" aria-labelledby="parkModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="parkModalLabel">Add/Edit Park Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" id="parkForm">
                            <div class="form-group">
                                <label for="park_name">Park Name:</label>
                                <input type="text" class="form-control" id="park_name" name="park_name" value="<?php echo htmlspecialchars($park_name); ?>">
                            </div>
                            <div class="form-group">
                                <label for="location">Location:</label>
                                <input type="text" class="form-control" id="location" name="location" value="<?php echo htmlspecialchars($location); ?>">
                            </div>
                            <div class="form-group">
                                <label for="description">Description:</label>
                                <textarea class="form-control" id="description" name="description" rows="3" style="text-align: justify;"><?php echo htmlspecialchars($description); ?></textarea>
                            </div>

                            <!-- If editing, include a hidden input for the park_id -->
                            <?php if ($edit_park_id): ?>
                                <input type="hidden" name="park_id" value="<?php echo $edit_park_id; ?>">
                            <?php endif; ?>
                            
                            <button type="submit" class="btn btn-primary" name="save_park">Save Park Details</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Display existing park details in a well-decorated table -->
       <!-- Display existing park details in a well-decorated table -->
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Park Name</th>
                <th>Location</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($park_details_data as $index => $park_details): ?>
                <tr>
                    <td><?php echo $index + 1; ?></td> <!-- Display serial number -->
                    <td><?php echo htmlspecialchars($park_details["park_name"]); ?></td>
                    <td><?php echo htmlspecialchars($park_details["location"]); ?></td>
                    <td><?php echo htmlspecialchars($park_details["description"]); ?></td>
                    <td>
                        <button class="btn btn-secondary btn-sm" onclick="editPark('<?php echo $park_details["park_id"]; ?>', '<?php echo addslashes($park_details["park_name"]); ?>', '<?php echo addslashes($park_details["location"]); ?>', '<?php echo addslashes($park_details["description"]); ?>')">Edit</button>
                        <button class="btn btn-danger btn-sm" onclick="deletePark('<?php echo $park_details["park_id"]; ?>')">Delete</button>
                        <!-- Rate button linking to addrates.php with park name as a query parameter -->
                        <a href="addRates.php?park_name=<?php echo urlencode($park_details["park_name"]); ?>" class="btn btn-warning btn-sm">Rate</a>
                        <!-- View Rates button linking to viewRates.php with park name as a query parameter -->
                        <a href="viewRates.php?park_name=<?php echo urlencode($park_details["park_name"]); ?>" class="btn btn-info btn-sm">View Rates</a>
                        <!-- Images button linking to parkimages.com with park ID and name -->
                        <a href="parkimages.php?park_id=<?php echo $park_details["park_id"]; ?>&park_name=<?php echo urlencode($park_details["park_name"]); ?>" class="btn btn-success btn-sm">Images</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>


    <!-- Custom JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function editPark(parkId, parkName, location, description) {
            // Set form values to the existing park details
            document.getElementById("park_name").value = parkName;
            document.getElementById("location").value = location;
            document.getElementById("description").value = description;

            // Add hidden input for park_id
            var parkIdInput = document.createElement("input");
            parkIdInput.type = "hidden";
            parkIdInput.name = "park_id";
            parkIdInput.value = parkId;

            // Append hidden input to the form
            var form = document.querySelector("#parkForm");
            form.appendChild(parkIdInput);
            
            // Show the modal for adding/editing park details
            $('#parkModal').modal('show');
        }

        function deletePark(parkId) {
            if (confirm('Are you sure you want to delete this park?')) {
                // Create a form and submit a POST request to delete the park
                var form = document.createElement('form');
                form.method = 'POST';
                form.action = '';

                // Add hidden input for park_id
                var parkIdInput = document.createElement('input');
                parkIdInput.type = 'hidden';
                parkIdInput.name = 'park_id';
                parkIdInput.value = parkId;
                
                // Add hidden input for delete_park
                var deleteInput = document.createElement('input');
                deleteInput.type = 'hidden';
                deleteInput.name = 'delete_park';
                deleteInput.value = '1';
                
                // Append inputs to the form
                form.appendChild(parkIdInput);
                form.appendChild(deleteInput);
                
                // Append the form to the body and submit it
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</body>

</html>
