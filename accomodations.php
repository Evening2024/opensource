<?php
// Include the database connection file
require 'db_connection.php';

// Initialize variables for form data and error messages
$property_name = $property_chain = $location = $description = $email = "";
$error_message = "";
$edit_property_id = null;
$accommodation_data = []; // Initialize accommodation data as an empty array

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle adding or editing accommodation details
    if (isset($_POST["save_accommodation"])) {
        // Retrieve form data including the email field
        $property_name = trim($_POST["property_name"]);
        $property_chain = trim($_POST["property_chain"]);
        $location = trim($_POST["location"]);
        $description = trim($_POST["description"]);
        $email = trim($_POST["email"]);

        // Validate inputs
        if (empty($property_name) || empty($location) || empty($description) || empty($email)) {
            $error_message = "All fields are required. Please fill in all the details.";
        } else {
            // Check if this is an update or a new addition
            if (isset($_POST["property_id"])) {
                // Update the existing accommodation details in the database
                $property_id = $_POST["property_id"];
                $update_query = "UPDATE accommodations SET property_name = ?, property_chain = ?, location = ?, description = ?, email = ? WHERE property_id = ?";
                $stmt = $conn->prepare($update_query);
                $stmt->bind_param("sssssi", $property_name, $property_chain, $location, $description, $email, $property_id);
                $stmt->execute();
                $stmt->close();
            } else {
                // Add new accommodation details to the database
                $insert_query = "INSERT INTO accommodations (property_name, property_chain, location, description, email) VALUES (?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($insert_query);
                $stmt->bind_param("sssss", $property_name, $property_chain, $location, $description, $email);
                $stmt->execute();
                $stmt->close();
            }

            // Redirect to refresh page data
            header("Location: " . $_SERVER["REQUEST_URI"]);
            exit;
        }
    } elseif (isset($_POST["delete_accommodation"])) {
        // Handle deleting accommodation details
        $property_id = $_POST["property_id"];

        // Delete the accommodation details from the database
        $delete_query = "DELETE FROM accommodations WHERE property_id = ?";
        $stmt = $conn->prepare($delete_query);
        $stmt->bind_param("i", $property_id);
        $stmt->execute();
        $stmt->close();
    }
}

// Retrieve existing accommodation details from the database
$query = "SELECT * FROM accommodations";
$result = $conn->query($query);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $accommodation_data[] = $row;
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
    <title>Accommodations Management</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
            min-width: 120px; /* Set minimum width for table cells */
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
        <h2 class="mt-3">Accommodations Management</h2>

        <!-- Button to add accommodation -->
        <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#accommodationModal">Add Accommodation</button>

        <!-- Accommodation details form modal -->
        <div class="modal fade" id="accommodationModal" tabindex="-1" role="dialog" aria-labelledby="accommodationModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="accommodationModalLabel">Add/Edit Accommodation Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" id="accommodationForm">
                            <div class="form-group">
                                <label for="property_name">Property Name:</label>
                                <input type="text" class="form-control" id="property_name" name="property_name" value="<?php echo htmlspecialchars($property_name); ?>">
                            </div>
                            <div class="form-group">
                                <label for="property_chain">Property Chain:</label>
                                <input type="text" class="form-control" id="property_chain" name="property_chain" value="<?php echo htmlspecialchars($property_chain); ?>">
                            </div>
                            <div class="form-group">
                                <label for="location">Location:</label>
                                <input type="text" class="form-control" id="location" name="location" value="<?php echo htmlspecialchars($location); ?>">
                            </div>
                            <div class="form-group">
                                <label for="description">Description:</label>
                                <textarea class="form-control" id="description" name="description" rows="3"><?php echo htmlspecialchars($description); ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="email">Email:</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
                            </div>

                            <!-- If editing, include a hidden input for the property_id -->
                            <?php if ($edit_property_id): ?>
                                <input type="hidden" name="property_id" value="<?php echo $edit_property_id; ?>">
                            <?php endif; ?>
                            
                            <button type="submit" class="btn btn-primary" name="save_accommodation">Save Accommodation Details</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Display existing accommodation details in a well-decorated table -->
        <!-- Display existing accommodation details in a well-decorated table -->
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Property Name</th>
                <th>Property Chain</th>
                <th>Location</th>
                <th>Description</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($accommodation_data as $index => $accommodation): ?>
                <tr>
                    <td><?php echo $index + 1; ?></td> <!-- Display serial number -->
                    <td><?php echo htmlspecialchars($accommodation["property_name"]); ?></td>
                    <td><?php echo htmlspecialchars($accommodation["property_chain"]); ?></td>
                    <td><?php echo htmlspecialchars($accommodation["location"]); ?></td>
                    <td><?php echo htmlspecialchars($accommodation["description"]); ?></td>
                    <td><?php echo htmlspecialchars($accommodation["email"]); ?></td>
                    <td>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-secondary btn-sm"
                                onclick="editAccommodation('<?php echo $accommodation["property_id"]; ?>', '<?php echo addslashes($accommodation["property_name"]); ?>', '<?php echo addslashes($accommodation["property_chain"]); ?>', '<?php echo addslashes($accommodation["location"]); ?>', '<?php echo addslashes($accommodation["description"]); ?>', '<?php echo addslashes($accommodation["email"]); ?>')">Edit</button>
                            <button type="button" class="btn btn-danger btn-sm"
                                onclick="deleteAccommodation('<?php echo $accommodation["property_id"]; ?>')">Delete</button>
                            <!-- Button dropdown for additional actions -->
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    More Actions
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="rooms.php?hotel_id=<?php echo $accommodation["property_id"]; ?>">Room Details</a>
                                    <a class="dropdown-item" href="hotelrates.php?hotel_id=<?php echo $accommodation["property_id"]; ?>">Rates</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="hotelimages.php?hotel_id=<?php echo $accommodation["property_id"]; ?>">Images</a>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>


    </div>

    <!-- Custom JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function editAccommodation(propertyId, propertyName, propertyChain, location, description, email) {
            // Set form values to the existing accommodation details
            document.getElementById("property_name").value = propertyName;
            document.getElementById("property_chain").value = propertyChain;
            document.getElementById("location").value = location;
            document.getElementById("description").value = description;
            document.getElementById("email").value = email; // Set email field value

            // Add hidden input for property_id
            var propertyIdInput = document.createElement("input");
            propertyIdInput.type = "hidden";
            propertyIdInput.name = "property_id";
            propertyIdInput.value = propertyId;

            // Append hidden input to the form
            var form = document.querySelector("#accommodationForm");
            form.appendChild(propertyIdInput);

            // Show the modal for adding/editing accommodation details
            $('#accommodationModal').modal('show');
        }

        function deleteAccommodation(propertyId) {
            if (confirm('Are you sure you want to delete this accommodation?')) {
                // Create a form and submit a POST request to delete the accommodation
                var form = document.createElement('form');
                form.method = 'POST';
                form.action = '';

                // Add hidden input for property_id
                var propertyIdInput = document.createElement('input');
                propertyIdInput.type = 'hidden';
                propertyIdInput.name = 'property_id';
                propertyIdInput.value = propertyId;

                // Add hidden input for delete_accommodation
                var deleteInput = document.createElement('input');
                deleteInput.type = 'hidden';
                deleteInput.name = 'delete_accommodation';
                deleteInput.value = '1';

                // Append inputs to the form
                form.appendChild(propertyIdInput);
                form.appendChild(deleteInput);

                // Append the form to the body and submit it
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</body>

</html>
