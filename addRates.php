<?php

// Include the database connection file
require 'db_connection.php';

// Initialize variables for form data and error messages
$park_name = $region = $high_season = $low_season = "";
$error_message = "";
$fees_data = [];

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $park_name = trim($_POST["park_name"]);
    $region = trim($_POST["region"]);
    $high_season = trim($_POST["high_season"]);
    $low_season = trim($_POST["low_season"]);

    // Retrieve fees data
    $fees_data = [
        "ea_citizen_adult" => $_POST["ea_citizen_adult"],
        "ea_citizen_children" => $_POST["ea_citizen_children"],
        "ea_citizen_below_5" => $_POST["ea_citizen_below_5"],
        "non_ea_citizen_adult" => $_POST["non_ea_citizen_adult"],
        "non_ea_citizen_children" => $_POST["non_ea_citizen_children"],
        "non_ea_citizen_below_5" => $_POST["non_ea_citizen_below_5"],
        "tz_resident_above_16" => $_POST["tz_resident_above_16"],
        "tz_resident_children" => $_POST["tz_resident_children"],
        "tz_resident_below_5" => $_POST["tz_resident_below_5"],
        "hotel_concession_fees" => $_POST["hotel_concession_fees"],
        "guide_entry_fee" => $_POST["guide_entry_fee"],
        "vehicle_fee" => $_POST["vehicle_fee"]
    ];

    // Check for empty inputs
    if (empty($park_name) || empty($region) || empty($high_season) || empty($low_season)) {
        $error_message = "Please fill in all required fields.";
    } else {
        // Insert the data into the database
        $insert_query = "INSERT INTO national_parks (park_name, region, high_season, low_season) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("ssss", $park_name, $region, $high_season, $low_season);
        $stmt->execute();
        $park_id = $stmt->insert_id; // Retrieve the last inserted ID
        $stmt->close();

        // Insert fees data
        foreach ($fees_data as $key => $value) {
            $insert_fee_query = "INSERT INTO park_fees (park_id, fee_type, amount) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($insert_fee_query);
            $stmt->bind_param("isi", $park_id, $key, $value);
            $stmt->execute();
            $stmt->close();
        }

        // Redirect to refresh the page data
        header("Location: " . $_SERVER["REQUEST_URI"]);
        exit;
    }
}
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
            <!-- National Park Information -->
            
            <div class="form-group">
                <label for="region">Season name  </label>
                <input type="text" class="form-control" id="region" name="region" value="<?php echo htmlspecialchars($region); ?>">
            </div>

            <div class="form-group">
                <label for="region">Season duration  </label>
                <input type="text" class="form-control" id="region" name="region" value="<?php echo htmlspecialchars($region); ?>">
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
