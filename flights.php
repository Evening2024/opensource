<?php
// Include database connection
include 'db_connection.php';

// Process form data when submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action']) && $_POST['action'] == 'register') {
        // Plane registration form submitted
        $company_name = $_POST['company_name'];
        $email = $_POST['email'];
        $registration_number = $_POST['registration_number'];

        // Insert into database
        $sql = "INSERT INTO planes (company_name, email, registration_number) 
                VALUES ('$company_name', '$email', '$registration_number')";

        if ($conn->query($sql) === TRUE) {
            $success_message = "Plane registered successfully";
        } else {
            $error_message = "Error: " . $sql . "<br>" . $conn->error;
        }
    } elseif (isset($_POST['action']) && $_POST['action'] == 'delete') {
        // Plane deletion form submitted
        $plane_id = $_POST['plane_id'];

        // Delete from database
        $delete_sql = "DELETE FROM planes WHERE id = $plane_id";

        if ($conn->query($delete_sql) === TRUE) {
            $success_message = "Plane deleted successfully";
        } else {
            $error_message = "Error deleting plane: " . $conn->error;
        }
    } elseif (isset($_POST['action']) && $_POST['action'] == 'update') {
        // Plane update form submitted
        $plane_id = $_POST['plane_id'];
        $company_name = $_POST['company_name'];
        $email = $_POST['email'];
        $registration_number = $_POST['registration_number'];

        // Update plane details in the database
        $update_sql = "UPDATE planes SET company_name='$company_name', email='$email', registration_number='$registration_number' WHERE id=$plane_id";

        if ($conn->query($update_sql) === TRUE) {
            $success_message = "Plane details updated successfully";
        } else {
            $error_message = "Error updating plane details: " . $conn->error;
        }
    }
}
?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Plane Registration</title>
        <!-- Bootstrap CSS -->
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
        <?php include('header.php')?>
        <div class="container mt-5">
            <h2>Register a Plane</h2>
            <!-- Plane Registration Form -->
            <form id="registerForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <input type="hidden" name="action" value="register">
                <div class="form-group">
                    <label for="company_name">Company Name:</label>
                    <input type="text" class="form-control" id="company_name" name="company_name" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="registration_number">Registration Number:</label>
                    <input type="text" class="form-control" id="registration_number" name="registration_number" required>
                </div>
                <button type="submit" class="btn btn-primary">Register</button>
            </form>

            <hr>

            <h2>Registered Planes</h2>
            <!-- Display Registered Planes in a Table -->
            <table class="table">
                <thead>
                    <tr>
                        <th>Company Name</th>
                        <th>Email</th>
                        <th>Registration Number</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    // Retrieve planes data from database
                    $sql = "SELECT * FROM planes";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>{$row['company_name']}</td>
                                    <td>{$row['email']}</td>
                                    <td>{$row['registration_number']}</td>
                                    <td>
                                        <button class='btn btn-sm btn-primary' onclick='editPlane({$row['id']}, \"{$row['company_name']}\", \"{$row['email']}\", \"{$row['registration_number']}\")'>Edit</button>
                                        <a href='add_rates.php?id={$row['id']}&name={$row['company_name']}' class='btn btn-sm btn-info'> Rates</a>
                                       
                                        <form action='' method='post' style='display: inline;'>
                                            <input type='hidden' name='action' value='delete'>
                                            <input type='hidden' name='plane_id' value='{$row['id']}'>
                                            <button type='submit' class='btn btn-sm btn-danger' onclick='return confirm(\"Are you sure you want to delete this plane?\")'>Delete</button>
                                        </form>
                                    </td>
                                </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>No planes registered</td></tr>";
                    }
                ?>
                </tbody>
            </table>

            <!-- Edit Plane Modal -->
            <div id="editPlaneModal" class="modal" style="display: none;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Plane Details</h5>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <!-- Modal Body -->
                        <div class="modal-body">
                            <form id="editPlaneForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                                <input type="hidden" name="action" value="update">
                                <input type="hidden" id="edit_plane_id" name="plane_id">
                                <div class="form-group">
                                    <label for="edit_company_name">Company Name:</label>
                                    <input type="text" class="form-control" id="edit_company_name" name="company_name" required>
                                </div>
                                <div class="form-group">
                                    <label for="edit_email">Email:</label>
                                    <input type="email" class="form-control" id="edit_email" name="email" required>
                                </div>
                                <div class="form-group">
                                    <label for="edit_registration_number">Registration Number:</label>
                                    <input type="text" class="form-control" id="edit_registration_number" name="registration_number" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <?php
            // Display success or error messages with auto-refresh after 3 seconds
            function displayMessage($message, $type = 'success') {
                echo "<div id='message' class='alert alert-$type'>$message</div>";
                echo '<script type="text/javascript">
                    setTimeout(function() {
                        window.location.href = "flights.php";
                    }, 3000); // 3000 milliseconds = 3 seconds
                </script>';
            }

            if (isset($success_message)) {
                displayMessage($success_message, 'success');
            }
            if (isset($error_message)) {
                displayMessage($error_message, 'danger');
            }
            ?>  
        </div>

        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

        <!-- JavaScript Function to Populate Edit Modal -->
        <script>
            function editPlane(planeId, companyName, email, registrationNumber) {
                document.getElementById('edit_plane_id').value = planeId;
                document.getElementById('edit_company_name').value = companyName;
                document.getElementById('edit_email').value = email;
                document.getElementById('edit_registration_number').value = registrationNumber;
                $('#editPlaneModal').modal('show');
            }
        </script>
    </body>
    </html>

<?php
// Close database connection
$conn->close();
?>
