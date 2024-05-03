<?php
// Include the database connection file
require 'db_connection.php';

// Initialize variables
$error = '';
$hotel_id = isset($_GET['hotel_id']) ? intval($_GET['hotel_id']) : null;
$hotel_name = '';

// Retrieve hotel name based on hotel_id
if ($hotel_id) {
    $query_hotel = "SELECT property_name FROM accommodations WHERE property_id = ?";
    $stmt_hotel = $conn->prepare($query_hotel);
    $stmt_hotel->bind_param("i", $hotel_id);
    $stmt_hotel->execute();
    $stmt_hotel->store_result();

    // Bind the result
    $stmt_hotel->bind_result($hotel_name);

    // Fetch hotel name
    if ($stmt_hotel->fetch()) {
        $stmt_hotel->close();
    } else {
        $stmt_hotel->close();
        $error = "Hotel not found.";
    }
} else {
    $error = "Invalid hotel ID.";
}

// Handle file upload
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["upload"])) {
    if (isset($_FILES["image"])) {
        $file = $_FILES["image"];

        // Validate uploaded file
        $file_name = $file["name"];
        $file_tmp = $file["tmp_name"];
        $file_size = $file["size"];
        $file_type = $file["type"];
        $allowed_extensions = ["jpeg", "jpg", "png"];
        $max_file_size = 8 * 1024 * 1024; // 8 MB

        $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        // Check if file extension is allowed
        if (!in_array($file_extension, $allowed_extensions)) {
            $error = "Only JPEG, JPG, and PNG files are allowed.";
        } elseif ($file_size > $max_file_size) {
            $error = "File size should not exceed 8 MB.";
        } else {
            // Check number of existing images for the hotel
            $query_count_images = "SELECT COUNT(*) FROM hotel_images WHERE hotel_id = ?";
            $stmt_count_images = $conn->prepare($query_count_images);
            $stmt_count_images->bind_param("i", $hotel_id);
            $stmt_count_images->execute();
            $stmt_count_images->bind_result($image_count);
            $stmt_count_images->fetch();
            $stmt_count_images->close();

            if ($image_count >= 3) {
                $error = "You can only upload up to three images for this hotel.";
            } else {
                // Move uploaded file to directory and insert into database
                $upload_dir = "hoteluploads/";
                $file_new_name = uniqid() . "." . $file_extension;
                $destination = $upload_dir . $file_new_name;

                if (move_uploaded_file($file_tmp, $destination)) {
                    // Insert image details into database
                    $insert_query = "INSERT INTO hotel_images (hotel_id, image_path) VALUES (?, ?)";
                    $stmt_insert = $conn->prepare($insert_query);
                    $stmt_insert->bind_param("is", $hotel_id, $destination);
                    $stmt_insert->execute();
                    $stmt_insert->close();
                } else {
                    $error = "Failed to upload file.";
                }
            }
        }
    }
}

// Handle image deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete"])) {
    $image_id = isset($_POST["image_id"]) ? intval($_POST["image_id"]) : null;

    if ($image_id) {
        // Delete image from database
        $delete_query = "DELETE FROM hotel_images WHERE image_id = ?";
        $stmt_delete = $conn->prepare($delete_query);
        $stmt_delete->bind_param("i", $image_id);
        $stmt_delete->execute();
        $stmt_delete->close();

        // Redirect back to the same page after deletion
        header("Location: hotelimages.php?hotel_id=$hotel_id");
        exit;
    }
}

// Retrieve uploaded images for the hotel
$images = [];
$query_images = "SELECT * FROM hotel_images WHERE hotel_id = ?";
$stmt_images = $conn->prepare($query_images);
$stmt_images->bind_param("i", $hotel_id);
$stmt_images->execute();
$result_images = $stmt_images->get_result();

while ($row = $result_images->fetch_assoc()) {
    $images[] = $row;
}

$stmt_images->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Images</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .container {
            max-width: 800px;
            margin-top: 50px;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
        }

        .error {
            color: red;
            margin-bottom: 20px;
        }

        .image-container {
            margin-top: 20px;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .image-card {
            width: calc(33.33% - 10px); /* Three images per row with margin */
            margin-bottom: 20px;
        }

        .image-card img {
            width: 100%;
            height: auto;
            border-radius: 5px;
        }
    </style>
</head>

<body>

 <!-- Include header.php file for the header -->
 <?php include 'header.php'; ?>

    <div class="container">
    <a href="accomodations.php" class="btn btn-primary"> Back to Accommodations</a>
        <h2>Images for <?php echo htmlspecialchars($hotel_name); ?></h2>
        <?php if (!empty($error)) : ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>


        <!-- Display Uploaded Images -->
        <div class="image-container">
            <?php foreach ($images as $image) : ?>
                <div class="card image-card">
                    <img src="<?php echo $image['image_path']; ?>" class="card-img-top" alt="Uploaded Image">
                    <div class="card-body">
                        <form method="POST">
                            <input type="hidden" name="image_id" value="<?php echo $image['image_id']; ?>">
                            <button type="submit" name="delete" class="btn btn-danger">Delete</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    
        <!-- Upload Form -->
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <input type="file" name="image" class="form-control-file">
            </div>
            <button type="submit" name="upload" class="btn btn-primary">Upload Image</button>
        </form>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
