<?php
// Include database connection
include_once('db_connection.php');

// Initialize variables for park_id and park_name
$park_id = $park_name = '';
$error_message = '';

// Check if park_id and park_name are provided via URL
if (isset($_GET['park_id'], $_GET['park_name'])) {
    $park_id = $_GET['park_id'];
    $park_name = urldecode($_GET['park_name']);

    // Retrieve uploaded images for the park
    $query = "SELECT * FROM park_images WHERE park_id = ? AND park_name = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('is', $park_id, $park_name);
    $stmt->execute();
    $result = $stmt->get_result();

    // Determine if upload form should be displayed based on image count
    $image_count = $result->num_rows;
    $can_upload = ($image_count < 3);
}

// Process image deletion
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];

    // Delete image from database
    $delete_query = "DELETE FROM park_images WHERE image_id = ?";
    $delete_stmt = $conn->prepare($delete_query);
    $delete_stmt->bind_param('i', $delete_id);
    $delete_stmt->execute();

    // Redirect back to parkimages.php
    header("Location: parkimages.php?park_id=$park_id&park_name=" . urlencode($park_name));
    exit;
}

// Process image upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['image']) && $can_upload) {
    $upload_dir = 'parkimages/';
    $upload_file = $upload_dir . basename($_FILES['image']['name']);
    $imageFileType = strtolower(pathinfo($upload_file, PATHINFO_EXTENSION));

    // Check file size
    if ($_FILES['image']['size'] > 7000000) {
        $error_message = 'Sorry, your file is too large. Maximum file size allowed is 7MB.';
    } elseif (!in_array($imageFileType, ['jpg', 'jpeg', 'png'])) {
        $error_message = 'Sorry, only JPG, JPEG, and PNG files are allowed.';
    } else {
        if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_file)) {
            // File uploaded successfully, insert into database
            $image_name = basename($_FILES['image']['name']);

            $insert_query = "INSERT INTO park_images (park_id, park_name, image_name) VALUES (?, ?, ?)";
            $insert_stmt = $conn->prepare($insert_query);
            $insert_stmt->bind_param('iss', $park_id, $park_name, $image_name);
            $insert_stmt->execute();

            // Redirect back to parkimages.php
            header("Location: parkimages.php?park_id=$park_id&park_name=" . urlencode($park_name));
            exit;
        } else {
            $error_message = 'Failed to upload file.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Park Images - <?php echo htmlspecialchars($park_name); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            padding: 20px;
            margin: 0;
            padding: 0;
        }

        h2 {
            color: #007bff;
            margin-bottom: 20px;
            text-align: center;
        }

        .image-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        .image-item {
            margin: 10px;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            width: calc(33.33% - 20px); /* Three items per row */
            max-width: 300px;
        }

        .image-item img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
        }

        .btn {
            margin-top: 5px;
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 5px 10px;
            border-radius: 3px;
            cursor: pointer;
            text-decoration: none;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .upload-form {
            text-align: center;
            margin-top: 20px;
        }

        .upload-form input[type="file"] {
            margin-bottom: 10px;
        }

        .error-message {
            color: red;
            font-weight: bold;
            text-align: center;
            margin-top: 10px;
        }

        .upload-info {
            text-align: center;
            margin-bottom: 20px;
        }

          /* Style for the back button */
        .back-button {
            background-color: #007bff;
            color: white;
            text-decoration: none;
            padding: 5px 10px;
            border-radius: 3px;
            cursor: pointer;
            margin-left: auto; /* Push the button to the right */
            margin-left:20%;
        }

        .back-button:hover {
            background-color: #0056b3;
        }
    </style>

    <script>
        // Automatically hide error message after 3 seconds
        setTimeout(function () {
            var errorDiv = document.getElementById('errorDiv');
            if (errorDiv) {
                errorDiv.style.display = 'none';
            }
        }, 3000);
    </script>
</head>

<body>
    <?php include_once('header.php'); ?></br>

    <a href="parks.php" class="btn btn-secondary back-button">Back to Parks</a>

    <h2>Images for <strong><?php echo htmlspecialchars($park_name); ?></strong></h2>

    <div class="upload-info">
        <p><strong>Supported image types:</strong> JPG, JPEG, PNG</p>
        <p><strong>Maximum size per image:</strong> 7MB</p>
    </div>

    <div class="image-container">
        <?php while ($row = $result->fetch_assoc()) : ?>
            <div class="image-item">
                <img src="parkimages/<?php echo htmlspecialchars($row['image_name']); ?>" alt="Park Image">
                <form method="post">
                    <input type="hidden" name="delete_id" value="<?php echo htmlspecialchars($row['image_id']); ?>">
                    <button type="submit" class="btn">Delete</button>
                </form>
            </div>
        <?php endwhile; ?>
    </div>

    <?php if ($can_upload) : ?>
        <div class="upload-form">
            <form method="post" enctype="multipart/form-data">
                <input type="file" name="image" accept="image/jpeg, image/jpg, image/png"><br>
                <button type="submit" class="btn">Upload Image</button>
            </form>
        </div>
    <?php else : ?>
        <div class="error-message">Cannot upload anymore. Park already has 3 images.</div>
    <?php endif; ?>

    <?php if (!empty($error_message)) : ?>
        <div id="errorDiv" class="error-message">
            <?php echo $error_message; ?>
        </div>
    <?php endif; ?>

</body>

</html>

<?php
// Close statement and database connection
$stmt->close();
$conn->close();
?>
