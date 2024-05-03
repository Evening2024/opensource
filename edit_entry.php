<?php


// Include your database connection file
include 'db_connection.php';

// Get the ID from the query parameter
$id = isset($_GET['id']) ? filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT) : '';

// Function to get a specific row based on the ID
function getRowById($conn, $id) {
    $stmt = $conn->prepare("SELECT * FROM national_park_details WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    $stmt->close();
    return $row;
}

// Get the row details based on the ID
$row = getRowById($conn, $id);

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve updated values from the form
    $seasonname = filter_var($_POST['seasonname'], FILTER_SANITIZE_STRING);
    $seasonduration = filter_var($_POST['seasonduration'], FILTER_SANITIZE_STRING);
    $ea_citizen_adult = filter_var($_POST['ea_citizen_adult'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $ea_citizen_children = filter_var($_POST['ea_citizen_children'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $ea_citizen_below_5 = filter_var($_POST['ea_citizen_below_5'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $non_ea_citizen_adult = filter_var($_POST['non_ea_citizen_adult'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $non_ea_citizen_children = filter_var($_POST['non_ea_citizen_children'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $non_ea_citizen_below_5 = filter_var($_POST['non_ea_citizen_below_5'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $tz_resident_above_16 = filter_var($_POST['tz_resident_above_16'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $tz_resident_children = filter_var($_POST['tz_resident_children'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $tz_resident_below_5 = filter_var($_POST['tz_resident_below_5'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $guide_entry_fee = filter_var($_POST['guide_entry_fee'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $vehicle_fee = filter_var($_POST['vehicle_fee'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    
    // Prepare an update statement
    $stmt = $conn->prepare("UPDATE national_park_details SET seasonname = ?, seasonduration = ?, ea_citizen_adult = ?, ea_citizen_children = ?, ea_citizen_below_5 = ?, non_ea_citizen_adult = ?, non_ea_citizen_children = ?, non_ea_citizen_below_5 = ?, tz_resident_above_16 = ?, tz_resident_children = ?, tz_resident_below_5 = ?, guide_entry_fee = ?, vehicle_fee = ? WHERE id = ?");
    
    // Bind the parameters
    $stmt->bind_param("ssdddddddddddi", $seasonname, $seasonduration, $ea_citizen_adult, $ea_citizen_children, $ea_citizen_below_5, $non_ea_citizen_adult, $non_ea_citizen_children, $non_ea_citizen_below_5, $tz_resident_above_16, $tz_resident_children, $tz_resident_below_5, $guide_entry_fee, $vehicle_fee, $id);
    
    // Execute the update statement
    $stmt->execute();
    
    // Close the statement
    $stmt->close();
    // Redirect the user to viewRates.php with the park name
$park_name = urlencode($row['park_name']); // Assuming the park name is stored in the row array as 'park_name'
header("Location: viewRates.php?park_name={$park_name}");
exit();

}

// If the form is not submitted, display the form with the current row details
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Entry</title>
    <link rel="stylesheet" href="style.css"> <!-- Optional: link to your stylesheet -->
</head>
<body>


<?php 

// Include the header file
include 'header.php';

?>
<!-- Display the form with the current row details -->
<form method="POST" action="edit_entry.php?id=<?php echo htmlspecialchars($row['id']); ?>" class="edit-form">
    <div class="form-group">
        <label for="seasonname">Season Name:</label>
        <input type="text" name="seasonname" value="<?php echo htmlspecialchars($row['seasonname']); ?>" required>
    </div>
    <div class="form-group">
        <label for="seasonduration">Season Duration:</label>
        <input type="text" name="seasonduration" value="<?php echo htmlspecialchars($row['seasonduration']); ?>" required>
    </div>
    <div class="form-group">
        <label for="ea_citizen_adult">EA Citizen Adult Fee:</label>
        <input type="number" step="0.01" name="ea_citizen_adult" value="<?php echo htmlspecialchars($row['ea_citizen_adult']); ?>" required>
    </div>
    <div class="form-group">
        <label for="ea_citizen_children">EA Citizen Children Fee:</label>
        <input type="number" step="0.01" name="ea_citizen_children" value="<?php echo htmlspecialchars($row['ea_citizen_children']); ?>" required>
    </div>
    <div class="form-group">
        <label for="ea_citizen_below_5">EA Citizen Below 5 Fee:</label>
        <input type="number" step="0.01" name="ea_citizen_below_5" value="<?php echo htmlspecialchars($row['ea_citizen_below_5']); ?>" required>
    </div>
    <div class="form-group">
        <label for="non_ea_citizen_adult">Non-EA Citizen Adult Fee:</label>
        <input type="number" step="0.01" name="non_ea_citizen_adult" value="<?php echo htmlspecialchars($row['non_ea_citizen_adult']); ?>" required>
    </div>
    <div class="form-group">
        <label for="non_ea_citizen_children">Non-EA Citizen Children Fee:</label>
        <input type="number" step="0.01" name="non_ea_citizen_children" value="<?php echo htmlspecialchars($row['non_ea_citizen_children']); ?>" required>
    </div>
    <div class="form-group">
        <label for="non_ea_citizen_below_5">Non-EA Citizen Below 5 Fee:</label>
        <input type="number" step="0.01" name="non_ea_citizen_below_5" value="<?php echo htmlspecialchars($row['non_ea_citizen_below_5']); ?>" required>
    </div>
    <div class="form-group">
        <label for="tz_resident_above_16">TZ Resident Above 16 Fee:</label>
        <input type="number" step="0.01" name="tz_resident_above_16" value="<?php echo htmlspecialchars($row['tz_resident_above_16']); ?>" required>
    </div>
    <div class="form-group">
        <label for="tz_resident_children">TZ Resident Children Fee:</label>
        <input type="number" step="0.01" name="tz_resident_children" value="<?php echo htmlspecialchars($row['tz_resident_children']); ?>" required>
    </div>
    <div class="form-group">
        <label for="tz_resident_below_5">TZ Resident Below 5 Fee:</label>
        <input type="number" step="0.01" name="tz_resident_below_5" value="<?php echo htmlspecialchars($row['tz_resident_below_5']); ?>" required>
    </div>
    <div class="form-group">
        <label for="guide_entry_fee">Guide Entry Fee:</label>
        <input type="number" step="0.01" name="guide_entry_fee" value="<?php echo htmlspecialchars($row['guide_entry_fee']); ?>" required>
    </div>
    <div class="form-group">
        <label for="vehicle_fee">Vehicle Fee:</label>
        <input type="number" step="0.01" name="vehicle_fee" value="<?php echo htmlspecialchars($row['vehicle_fee']); ?>" required>
    </div>
    <div class="form-buttons">
        <input type="submit" value="Save Changes">
        <?php
// Assume $park_name contains the park name for the current entry
$park_name = urlencode($row['park_name']); // Assuming the park name is stored in the row array as 'park_name'
?>

<a href="viewRates.php?park_name=<?php echo $park_name; ?>" class="cancel-button">Cancel</a>

    </div>
</form>

</body>
</html>

<style>
/* Add styles for the form */
.edit-form {
    max-width: 600px;
    margin: auto;
    padding: 20px;
    border: 1px solid #ccc;
    border-radius: 5px;
    background-color: #f9f9f9;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}

.form-group input[type="text"],
.form-group input[type="number"],
.form-group input[type="number"],
.form-group input[type="email"],
.form-group input[type="tel"] {
    width: 100%;
    padding: 8px;
    box-sizing: border-box;
    border: 1px solid #ccc;
    border-radius: 4px;
    outline: none;
}

.form-group input[type="text"]:focus,
.form-group input[type="number"]:focus {
    border-color: #007BFF;
}

.form-buttons {
    text-align: right;
}

.form-buttons input[type="submit"] {
    background-color: #007BFF;
    color: #fff;
    padding: 8px 16px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.form-buttons input[type="submit"]:hover {
    background-color: #0056b3;
}

.form-buttons .cancel-button {
    padding: 8px 16px;
    border: none;
    border-radius: 4px;
    background-color: #ccc;
    color: #000;
    text-decoration: none;
    cursor: pointer;
}

.form-buttons .cancel-button:hover {
    background-color: #b3b3b3;
}
</style>
