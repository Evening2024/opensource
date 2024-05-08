<?php
// Include database connection
include 'db_connection.php';

// Check if policy_id is provided in the URL
if (isset($_GET['policy_id'])) {
    $policy_id = $_GET['policy_id'];

    // Fetch the specific policy from the database
    $sql = "SELECT * FROM policies WHERE id = $policy_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $type = $row['policy_type'];
        $description = $row['description'];
    } else {    
        echo "Policy not found";
        exit; // Exit if policy is not found
    }
} else {
    echo "Policy ID not provided";
    exit; // Exit if policy_id is not provided
}

// Handle form submission for updating policy
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_type = $_POST['type'];
    $new_description = $_POST['description'];

    // Update policy in the database
    $update_sql = "UPDATE policies SET policy_type='$new_type', description='$new_description' WHERE id=$policy_id";

    if ($conn->query($update_sql) === TRUE) {
        // Redirect back to policies.php after successful update
        header("Location: policies.php");
        exit;
    } else {
        echo "Error updating policy: " . $conn->error;
    }
}

// Close database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Policy</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Edit Policy</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?policy_id=' . $policy_id; ?>" method="post">
            <div class="form-group">
                <label for="type">Policy Type:</label>
                <input type="text" class="form-control" id="type" name="type" value="<?php echo $type; ?>" required>
            </div>
            <div class="form-group">
                <label for="description">Policy Description:</label>
                <textarea class="form-control" id="description" name="description" required><?php echo $description; ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Update Policy</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
