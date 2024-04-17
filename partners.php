<?php
// Include the database connection file
require 'db_connection.php';

// Initialize variables for form data and error messages
$partner_name = $partner_logo = "";
$error_message = "";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["delete_partner"])) {
        // Delete the partner from the database
        $partner_id = $_POST["partner_id"];
        
        // Retrieve the logo file path for the partner
        $query = "SELECT partner_logo FROM partners WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $partner_id);
        $stmt->execute();
        $stmt->bind_result($partner_logo);
        $stmt->fetch();
        $stmt->close();
        
        // Delete the logo file from the server
        if (!empty($partner_logo) && file_exists($partner_logo)) {
            unlink($partner_logo);
        }
        
        // Delete the partner from the database
        $delete_query = "DELETE FROM partners WHERE id = ?";
        $stmt = $conn->prepare($delete_query);
        $stmt->bind_param("i", $partner_id);
        $stmt->execute();
        $stmt->close();
    } elseif (!empty($_POST["partner_name"]) && isset($_FILES["partner_logo"]) && $_FILES["partner_logo"]["error"] == UPLOAD_ERR_OK) {
        // Retrieve form data
        $partner_name = $_POST["partner_name"];
        
        // Handle file upload for partner logo
        $allowed_types = ["image/png", "image/jpeg"];
        $file_info = finfo_open(FILEINFO_MIME_TYPE);
        $file_type = finfo_file($file_info, $_FILES["partner_logo"]["tmp_name"]);
        finfo_close($file_info);
        
        if (in_array($file_type, $allowed_types) && $_FILES["partner_logo"]["size"] <= 10 * 1024 * 1024) {
            // Save the file in the logos folder
            $logo_filename = "logos/" . basename($_FILES["partner_logo"]["name"]);
            move_uploaded_file($_FILES["partner_logo"]["tmp_name"], $logo_filename);
            $partner_logo = $logo_filename;

            // Insert new data into the database
            $insert_query = "INSERT INTO partners (partner_name, partner_logo) VALUES (?, ?)";
            $stmt = $conn->prepare($insert_query);
            $stmt->bind_param("ss", $partner_name, $partner_logo);
            $stmt->execute();
        } else {
            $error_message = "Invalid file type or file size exceeds the limit.";
        }
    } else {
        $error_message = "Please fill in all fields and upload a valid logo file.";
    }
}

// Retrieve existing data from the database
$partners_data = [];
$query = "SELECT * FROM partners";
$result = $conn->query($query);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $partners_data[] = $row;
    }
}

// Close the database connection
$conn->close();
?>
<!-- HTML Section -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Partners Page</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="bootstrap/dist/css/bootstrap.min.css">
    <!-- Custom CSS -->
    <style>
        /* Add your custom CSS for the form here */
        /* Customize form appearance */
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

        .logo-preview {
            width: 100px;
            height: 100px;
            object-fit: cover;
            margin-bottom: 10px;
        }

        /* Grid layout for existing partners */
        .partner-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }

        /* Partner info styling */
        .partner-info {
            text-align: center;
        }

        /* Delete button styling */
        .delete-btn {
            margin-top: 10px;
            color: red;
        }
    </style>
</head>

<body>
    <!-- Include header.php file for the header -->
    <?php include 'header.php'; ?>

    <!-- Partners form and display section -->
    <div class="container">
        <h2>Partners</h2>
        <!-- Display error messages if any -->
        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>
        <!-- Form to add a new partner -->
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="partner_name">Partner Name:</label>
                <input type="text" class="form-control" id="partner_name" name="partner_name" required>
            </div>
            <div class="form-group">
                <label for="partner_logo">Partner Logo:</label>
                <input type="file" class="form-control" id="partner_logo" name="partner_logo" accept=".png, .jpeg" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Partner</button>
        </form>

        <!-- Display existing partners in a grid of 4 columns -->
        <h3>Existing Partners</h3>
        <div class="partner-grid">
            <?php foreach ($partners_data as $partner): ?>
                <div class="partner-info">
                    <img src="<?php echo htmlspecialchars($partner["partner_logo"]); ?>" alt="Partner Logo" class="logo-preview">
                    <p><?php echo htmlspecialchars($partner["partner_name"]); ?></p>
                    <!-- Delete button form -->
                    <form method="POST">
                        <input type="hidden" name="partner_id" value="<?php echo htmlspecialchars($partner["id"]); ?>">
                        <button type="submit" name="delete_partner" class="btn btn-danger delete-btn">Delete</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>

</html>
