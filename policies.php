<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'db_connection.php'; // Include your database connection file

// Initialize variables
$description = '';
$type = '';
$error_message = '';

// Process form data when submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action']) && $_POST['action'] == 'add_policy') {
        // Retrieve and sanitize form data
        $type = htmlspecialchars(trim($_POST['type']));
        $description = htmlspecialchars(trim($_POST['description']));

        // Insert into database
        $sql = "INSERT INTO policies (policy_type, description) VALUES ('$type', '$description')";

        if ($conn->query($sql) === TRUE) {
            $success_message = "Policy added successfully";
        } else {
            $error_message = "Error adding policy: " . $conn->error;
        }
    } elseif (isset($_POST['action']) && $_POST['action'] == 'update_policy') {
        // Update policy
        $policy_id = $_POST['policy_id'];
        $type = htmlspecialchars(trim($_POST['type']));
        $description = htmlspecialchars(trim($_POST['description']));

        // Update policy in the database
        $update_sql = "UPDATE policies SET policy_type='$type', description='$description' WHERE id=$policy_id";

        if ($conn->query($update_sql) === TRUE) {
            $success_message = "Policy updated successfully";
        } else {
            $error_message = "Error updating policy: " . $conn->error;
        }
    } elseif (isset($_POST['action']) && $_POST['action'] == 'delete_policy') {
        // Delete policy
        $policy_id = $_POST['policy_id'];

        // Delete policy from the database
        $delete_sql = "DELETE FROM policies WHERE id = $policy_id";

        if ($conn->query($delete_sql) === TRUE) {
            $success_message = "Policy deleted successfully";
        } else {
            $error_message = "Error deleting policy: " . $conn->error;
        }
    }
}

// Retrieve policies from database
$sql = "SELECT * FROM policies";
$result = $conn->query($sql);

// Close database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Policies</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .policy-container {
            margin-top: 20px;
        }
        .policy-container textarea {
            width: 100%;
            height: 100px;
        }
        .policy-container .btn {
            margin-top: 10px;
        }
        .policy-list {
            margin-top: 30px;
        }
        .policy-list th, .policy-list td {
            text-align: center;
            vertical-align: middle;
        }
        .policy-list td.description {
            text-align: left;
            white-space: pre-line; /* Preserve line breaks and wrap text */
        }
    </style>
</head>
<body>
    <?php include('header.php'); ?>

    <div class="container policy-container">
        <h2>Add New Policy</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="hidden" name="action" value="add_policy">
            <div class="form-group">
                <label for="type">Policy Type:</label>
                <input type="text" class="form-control" id="type" name="type" required>
            </div>
            <div class="form-group">
                <label for="description">Policy Description:</label>
                <textarea class="form-control" id="description" name="description" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Add Policy</button>
        </form>

        <?php if (!empty($error_message)) : ?>
            <div id="errorMessage" class="alert alert-danger mt-3"><?php echo $error_message; ?></div>
            <script>
                setTimeout(function() {
                    document.getElementById('errorMessage').style.display = 'none';
                    location.reload();
                }, 3000);
            </script>
        <?php endif; ?>

        <?php if (isset($success_message)) : ?>
            <div id="successMessage" class="alert alert-success mt-3"><?php echo $success_message; ?></div>
            <script>
                setTimeout(function() {
                window.location.href = "policies.php";
            }, 3000); // 3000 milliseconds = 3 seconds
            </script>
        <?php endif; ?>

        <hr>

        <h2>Existing Policies</h2>
        <table class="table policy-list">
            <thead>
                <tr>
                    <th>Policy Type</th>
                    <th>Policy Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        // Format description with line breaks after full stops
                        $formatted_description = nl2br(preg_replace('/\. /', ".<br>", $row['description']));

                        echo "<tr>
                                <td>{$row['policy_type']}</td>
                                <td class='description'>{$formatted_description}</td>
                                <td>
                                <a href='edit_policy.php?policy_id={$row['id']}' class='btn btn-sm btn-primary'>Edit</a>
                                    <form action='' method='post' style='display: inline;'>
                                        <input type='hidden' name='action' value='delete_policy'>
                                        <input type='hidden' name='policy_id' value='{$row['id']}'>
                                        <button type='submit' class='btn btn-sm btn-danger' onclick='return confirm(\"Are you sure you want to delete this policy?\")'>Delete</button>
                                    </form>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>No policies found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Edit Policy Modal -->
    <div class="modal fade" id="editPolicyModal" tabindex="-1" role="dialog" aria-labelledby="editPolicyModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPolicyModalLabel">Edit Policy</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editPolicyForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="update_policy">
                        <input type="hidden" id="edit_policy_id" name="policy_id" value="">
                        <div class="form-group">
                            <label for="edit_type">Policy Type:</label>
                            <input type="text" class="form-control" id="edit_type" name="policy_type" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_description">Policy Description:</label>
                            <textarea class="form-control" id="edit_description" name="description" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

    <script>
        function editPolicy(policyId, type, description) {
            // Set values in the edit policy form
            document.getElementById('edit_policy_id').value = policyId;
            document.getElementById('edit_type').value = type;
            document.getElementById('edit_description').value = description;

            // Show the edit policy modal
            $('#editPolicyModal').modal('show');
        }
    </script>
</body>
</html>
