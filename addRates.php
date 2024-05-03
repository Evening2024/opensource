<?php
// Include database connection file (update with your actual connection parameters)
include 'db_connection.php';

// Initialize variables
$error_message = '';
$success_message = '';

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the park_name from URL parameters
    $park_name = isset($_GET['park_name']) ? filter_var($_GET['park_name'], FILTER_SANITIZE_STRING) : '';

    // Initialize form data variables
    $seasonname = isset($_POST['seasonname']) ? filter_var($_POST['seasonname'], FILTER_SANITIZE_STRING) : '';
    $seasonduration = isset($_POST['seasonduration']) ? filter_var($_POST['seasonduration'], FILTER_SANITIZE_STRING) : '';
    $ea_citizen_adult = isset($_POST['ea_citizen_adult']) ? filter_var($_POST['ea_citizen_adult'], FILTER_SANITIZE_STRING) : '';
    $ea_citizen_children = isset($_POST['ea_citizen_children']) ? filter_var($_POST['ea_citizen_children'], FILTER_SANITIZE_STRING) : '';
    $ea_citizen_below_5 = isset($_POST['ea_citizen_below_5']) ? filter_var($_POST['ea_citizen_below_5'], FILTER_SANITIZE_STRING) : '';
    $non_ea_citizen_adult = isset($_POST['non_ea_citizen_adult']) ? filter_var($_POST['non_ea_citizen_adult'], FILTER_SANITIZE_STRING) : '';
    $non_ea_citizen_children = isset($_POST['non_ea_citizen_children']) ? filter_var($_POST['non_ea_citizen_children'], FILTER_SANITIZE_STRING) : '';
    $non_ea_citizen_below_5 = isset($_POST['non_ea_citizen_below_5']) ? filter_var($_POST['non_ea_citizen_below_5'], FILTER_SANITIZE_STRING) : '';
    $tz_resident_above_16 = isset($_POST['tz_resident_above_16']) ? filter_var($_POST['tz_resident_above_16'], FILTER_SANITIZE_STRING) : '';
    $tz_resident_children = isset($_POST['tz_resident_children']) ? filter_var($_POST['tz_resident_children'], FILTER_SANITIZE_STRING) : '';
    $tz_resident_below_5 = isset($_POST['tz_resident_below_5']) ? filter_var($_POST['tz_resident_below_5'], FILTER_SANITIZE_STRING) : '';
    $guide_entry_fee = isset($_POST['guide_entry_fee']) ? filter_var($_POST['guide_entry_fee'], FILTER_SANITIZE_STRING) : '';
    $vehicle_fee = isset($_POST['vehicle_fee']) ? filter_var($_POST['vehicle_fee'], FILTER_SANITIZE_STRING) : '';

    // Validate inputs to ensure required fields are not empty
    if (empty($park_name) || empty($seasonname) || empty($seasonduration) || empty($ea_citizen_adult) ||
        empty($non_ea_citizen_adult) || empty($tz_resident_above_16)) {
        $error_message = 'Please fill in all required fields.';
    } else {
        // Use a prepared statement to insert data into the database
        $stmt = $conn->prepare("INSERT INTO national_park_details (park_name, seasonname, seasonduration, ea_citizen_adult, ea_citizen_children, ea_citizen_below_5, non_ea_citizen_adult, non_ea_citizen_children, non_ea_citizen_below_5, tz_resident_above_16, tz_resident_children, tz_resident_below_5, guide_entry_fee, vehicle_fee) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        // Bind parameters to the prepared statement
        $stmt->bind_param("ssssssssssssss",
            $park_name,
            $seasonname,
            $seasonduration,
            $ea_citizen_adult,
            $ea_citizen_children,
            $ea_citizen_below_5,
            $non_ea_citizen_adult,
            $non_ea_citizen_children,
            $non_ea_citizen_below_5,
            $tz_resident_above_16,
            $tz_resident_children,
            $tz_resident_below_5,
            $guide_entry_fee,
            $vehicle_fee
        );

        // Execute the prepared statement
        if ($stmt->execute()) {
            // Successful insertion
            $success_message = 'Data successfully inserted into the database.';
            // Display the success message for 3 seconds and then redirect to parks.php
            echo '<div class="alert alert-success">' . $success_message . '</div>';
            echo '<script type="text/javascript">
                setTimeout(function() {
                    window.location.href = "parks.php";
                }, 3000); // 3000 milliseconds = 3 seconds
            </script>';
        }
         else {
            // Error during insertion
            $error_message = 'Failed to insert data into the database: ' . $stmt->error;
        }

        // Close the prepared statement
        $stmt->close();
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
    <title>National Park Registration</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="bootstrap/dist/css/bootstrap.min.css">
    <!-- Custom CSS -->
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

        /* Section styling */
        .section-header {
            font-weight: bold;
            margin-top: 20px;
        }

        /* Custom button styling */
        .btn-primary {
            margin-bottom: 10px;
        }
        .section-header {
    font-size: 24px; /* Set the desired font size */
}

    </style>
</head>

<body>
    <!-- Include header.php file for the header -->
    <?php include 'header.php'; ?>

    
    <!-- Retrieve park name from URL parameter -->
    <?php
    $park_name = isset($_GET['park_name']) ? filter_var($_GET['park_name'], FILTER_SANITIZE_STRING) : '';
    ?>

    <!-- Form for registering national park details -->
    <div class="container">
        <h2>Register National Park Details</h2>

        <!-- Display error messages if any -->
        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <!-- Form to register national park details -->
        <form method="POST">

              <!-- Park Name -->
              <div class="form-group">
                <label for="park_name">Park Name:</label>
                <input type="text" class="form-control" id="park_name" name="park_name" value="<?php echo htmlspecialchars($park_name); ?>" readonly>
            </div>
            <!-- National Park Information -->
            
            <div class="form-group">
                <label for="region">Season name  </label>
                <input type="text" class="form-control" id="seasonname" name="seasonname">
            </div>

            <div class="form-group">
                <label for="region">Season duration  </label>
                <input type="text" class="form-control" id="seasonduration" name="seasonduration">
            </div>

            <!-- Park Fees Section -->
            <div class="section-header" style="font-size: 24px;">Park  Fees</div>

            <div class="form-group">
                <label for="ea_citizen_adult">EA Citizen Adult (16 yrs and above):</label>
                <input type="number" class="form-control" id="ea_citizen_adult" name="ea_citizen_adult">
            </div>
            <div class="form-group">
                <label for="ea_citizen_children">EA Citizen Children (5 yrs to 15 yrs):</label>
                <input type="number" class="form-control" id="ea_citizen_children" name="ea_citizen_children">
            </div>
            <div class="form-group">
                <label for="ea_citizen_below_5">EA Citizen Below 5:</label>
                <input type="number" class="form-control" id="ea_citizen_below_5" name="ea_citizen_below_5">
            </div>
            <div class="form-group">
                <label for="non_ea_citizen_adult">Non-EA Citizen Adult (16 yrs and above):</label>
                <input type="number" class="form-control" id="non_ea_citizen_adult" name="non_ea_citizen_adult">
            </div>
            <div class="form-group">
                <label for="non_ea_citizen_children">Non-EA Citizen Children (5 yrs to 15 yrs):</label>
                <input type="number" class="form-control" id="non_ea_citizen_children" name="non_ea_citizen_children">
            </div>
            <div class="form-group">
                <label for="non_ea_citizen_below_5">Non-EA Citizen Below 5:</label>
                <input type="number" class="form-control" id="non_ea_citizen_below_5" name="non_ea_citizen_below_5">
            </div>
            <div class="form-group">
                <label for="tz_resident_above_16">TZ Resident Above 16 Years:</label>
                <input type="number" class="form-control" id="tz_resident_above_16" name="tz_resident_above_16">
            </div>
            <div class="form-group">
                <label for="tz_resident_children">TZ Resident Children (5 yrs to 15 yrs):</label>
                <input type="number" class="form-control" id="tz_resident_children" name="tz_resident_children">
            </div>
            <div class="form-group">
                <label for="tz_resident_below_5">TZ Resident Below 5:</label>
                <input type="number" class="form-control" id="tz_resident_below_5" name="tz_resident_below_5">
            </div>

            <!-- Hotel Concession Fees Section -->
            <div class="section-header" style="font-size: 24px;">Hotel Concession Fees</div>

            <div class="form-group">
                <label for="ea_citizen_adult">EA Citizen Adult (16 yrs and above):</label>
                <input type="number" class="form-control" id="ea_citizen_adult" name="ea_citizen_adult">
            </div>
            <div class="form-group">
                <label for="ea_citizen_children">EA Citizen Children (5 yrs to 15 yrs):</label>
                <input type="number" class="form-control" id="ea_citizen_children" name="ea_citizen_children">
            </div>
            <div class="form-group">
                <label for="ea_citizen_below_5">EA Citizen Below 5:</label>
                <input type="number" class="form-control" id="ea_citizen_below_5" name="ea_citizen_below_5">
            </div>
            <div class="form-group">
                <label for="non_ea_citizen_adult">Non-EA Citizen Adult (16 yrs and above):</label>
                <input type="number" class="form-control" id="non_ea_citizen_adult" name="non_ea_citizen_adult">
            </div>
            <div class="form-group">
                <label for="non_ea_citizen_children">Non-EA Citizen Children (5 yrs to 15 yrs):</label>
                <input type="number" class="form-control" id="non_ea_citizen_children" name="non_ea_citizen_children">
            </div>
            <div class="form-group">
                <label for="non_ea_citizen_below_5">Non-EA Citizen Below 5:</label>
                <input type="number" class="form-control" id="non_ea_citizen_below_5" name="non_ea_citizen_below_5">
            </div>
            <div class="form-group">
                <label for="tz_resident_above_16">TZ Resident Above 16 Years:</label>
                <input type="number" class="form-control" id="tz_resident_above_16" name="tz_resident_above_16">
            </div>
            <div class="form-group">
                <label for="tz_resident_children">TZ Resident Children (5 yrs to 15 yrs):</label>
                <input type="number" class="form-control" id="tz_resident_children" name="tz_resident_children">
            </div>
            <div class="form-group">
                <label for="tz_resident_below_5">TZ Resident Below 5:</label>
                <input type="number" class="form-control" id="tz_resident_below_5" name="tz_resident_below_5">
            </div>

            <!-- Guide Entry Fee Section -->
           
            <div class="form-group">
                <label for="guide_entry_fee">Guide Entry Fee:</label>
                <input type="number" class="form-control" id="guide_entry_fee" name="guide_entry_fee">
            </div>

            <!-- Vehicle Fee Section -->
           
            <div class="form-group">
                <label for="vehicle_fee">Vehicle Fee:</label>
                <input type="number" class="form-control" id="vehicle_fee" name="vehicle_fee">
            </div>

            <!-- Submit button -->
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>

   
</body>

</html>
