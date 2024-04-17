<?php
// PHP Section

// Include the database connection file
require 'db_connection.php';

// Initialize variables for form data and error messages
$company_name = $pobox = $email = $phone = $website = "";
$logo = "";
$error_message = "";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate form data
    if (empty($_POST["company_name"]) || empty($_POST["pobox"]) ||
        empty($_POST["email"]) || empty($_POST["phone"]) ||
        empty($_POST["website"])) {
        $error_message = "All fields must be filled out.";
    } else {
        // Retrieve form data
        $company_name = $_POST["company_name"];
        $pobox = $_POST["pobox"];
        $email = $_POST["email"];
        $phone = $_POST["phone"];
        $website = $_POST["website"];
        
        // Handle file upload for logo
        if (isset($_FILES["logo"]) && $_FILES["logo"]["error"] == UPLOAD_ERR_OK) {
            $allowed_types = ["image/png", "image/jpeg", "application/pdf"];
            $file_info = finfo_open(FILEINFO_MIME_TYPE);
            $file_type = finfo_file($file_info, $_FILES["logo"]["tmp_name"]);
            finfo_close($file_info);
            
            if (in_array($file_type, $allowed_types) && $_FILES["logo"]["size"] <= 10 * 1024 * 1024) {
                // Save the file (you can customize the destination path)
                $logo_filename = "logos/" . basename($_FILES["logo"]["name"]);
                move_uploaded_file($_FILES["logo"]["tmp_name"], $logo_filename);
                $logo = $logo_filename;
            } else {
                $error_message = "Invalid file type or file size exceeds the limit.";
            }
        }

        // If no errors, insert or update the data in the database
        if (empty($error_message)) {
            // Check if data already exists in the database
            $query = "SELECT COUNT(*) FROM company_details";
            $result = $conn->query($query);
            
            if ($result->fetch_assoc()["COUNT(*)"] > 0) {
                // Data already exists, update the existing record
                $update_query = "UPDATE company_details SET
                    company_name = ?, pobox = ?, email = ?, phone = ?, website = ?, logo = ?
                    WHERE id = 1";
                $stmt = $conn->prepare($update_query);
                $stmt->bind_param("ssssss", $company_name, $pobox, $email, $phone, $website, $logo);
                $stmt->execute();
            } else {
                // Insert new data into the database
                $insert_query = "INSERT INTO company_details (company_name, pobox, email, phone, website, logo)
                    VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($insert_query);
                $stmt->bind_param("ssssss", $company_name, $pobox, $email, $phone, $website, $logo);
                $stmt->execute();
            }
        }
    }
}

// Retrieve existing data from the database
$query = "SELECT * FROM company_details WHERE id = 1";
$result = $conn->query($query);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $company_name = $row["company_name"];
    $pobox = $row["pobox"];
    $email = $row["email"];
    $phone = $row["phone"];
    $website = $row["website"];
    $logo = $row["logo"];
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
    <title>Company Details Form</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="bootstrap/dist/css/bootstrap.min.css">
    <!-- Custom CSS -->
    <style>
        /* Add your custom CSS for the form here */
        /* Example: Customize form appearance */
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
    </style>
</head>

<body>
    <!-- Include header.php file for the header -->
    <?php include 'header.php'; ?>

    <!-- Company details form -->
    <div class="container">
        <h2>Company Details</h2>
        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="company_name">Company Name:</label>
                <input type="text" class="form-control" id="company_name" name="company_name" value="<?php echo htmlspecialchars($company_name); ?>" required>
            </div>
            <div class="form-group">
                <label for="pobox">P.O. Box:</label>
                <input type="text" class="form-control" id="pobox" name="pobox" value="<?php echo htmlspecialchars($pobox); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone:</label>
                <input type="text" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($phone); ?>" required>
            </div>
            <div class="form-group">
                <label for="website">Website:</label>
                <input type="text" class="form-control" id="website" name="website" value="<?php echo htmlspecialchars($website); ?>" required>
            </div>
            <div class="form-group">
                <label for="logo">Logo:</label>
                <?php if (!empty($logo)): ?>
                    <img src="<?php echo htmlspecialchars($logo); ?>" alt="Company Logo" class="logo-preview">
                <?php endif; ?>
                <input type="file" class="form-control" id="logo" name="logo" accept=".png, .jpeg, .pdf">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>


</body>

</html>
