<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include 'db_connection.php';

// Initialize variables to store plane ID and name
$plane_id = null;
$plane_name = null;

// Retrieve plane details based on ID and name from URL parameters
if (isset($_GET['id']) && isset($_GET['name'])) {
    $plane_id = $_GET['id'];
    $plane_name = $_GET['name'];
}

// Process form data when submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action']) && $_POST['action'] == 'add_rate') {
        // Plane rate form submitted
        // Retrieve form data
        $season = htmlspecialchars(trim($_POST['season']));
        $season_start = htmlspecialchars(trim($_POST['season_start']));
        $season_end = htmlspecialchars(trim($_POST['season_end']));
        $source = htmlspecialchars(trim($_POST['source']));
        $destination = htmlspecialchars(trim($_POST['destination']));
        $adults_price = $_POST['adults_price'];
        $children_age = htmlspecialchars(trim($_POST['children_age']));
        $children_price = htmlspecialchars(trim($_POST['custom_children_price']));

        // Insert into database
        $sql = "INSERT INTO plane_rates (plane_id, season, season_start, season_end, source, destination, adults_price, children_price, children_age) 
                VALUES ('$plane_id', '$season', '$season_start', '$season_end', '$source', '$destination', '$adults_price', '$children_price', '$children_age')";

        if ($conn->query($sql) === TRUE) {
            // Redirect to the same page with plane ID and name
            header("Location: add_flightrates.php?id=$plane_id&name=$plane_name");
            exit;
        } else {
            $error_message = "Error adding rate: " . $conn->error;
        }
    } elseif (isset($_POST['action']) && $_POST['action'] == 'update_rate') {
        // Plane rate update form submitted
        // Retrieve form data
        $rate_id = $_POST['rate_id'];
        $season = htmlspecialchars(trim($_POST['season']));
        $season_start = htmlspecialchars(trim($_POST['season_start']));
        $season_end = htmlspecialchars(trim($_POST['season_end']));
        $source = htmlspecialchars(trim($_POST['source']));
        $destination = htmlspecialchars(trim($_POST['destination']));
        $adults_price = $_POST['adults_price'];
        $children_age = htmlspecialchars(trim($_POST['children_age']));
        $children_price = htmlspecialchars(trim($_POST['custom_children_price']));

        // Update rate details in the database
        $update_sql = "UPDATE plane_rates SET season='$season', season_start='$season_start', season_end='$season_end', 
                        source='$source', destination='$destination', adults_price='$adults_price', 
                        children_price='$children_price', children_age='$children_age' WHERE id=$rate_id";

        if ($conn->query($update_sql) === TRUE) {
            // Redirect to the same page with plane ID and name
            header("Location: add_flightrates.php?id=$plane_id&name=$plane_name");
            exit;
        } else {
            $error_message = "Error updating rate details: " . $conn->error;
        }
    } elseif (isset($_POST['action']) && $_POST['action'] == 'delete_rate') {
        // Plane rate deletion form submitted
        // Retrieve rate ID
        $rate_id = $_POST['rate_id'];

        // Delete rate from the database
        $delete_sql = "DELETE FROM plane_rates WHERE id = $rate_id";

        if ($conn->query($delete_sql) === TRUE) {
            // Redirect to the same page with plane ID and name
            header("Location: add_flightrates.php?id=$plane_id&name=$plane_name");
            exit;
        } else {
            $error_message = "Error deleting rate: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Flight Rates</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .back-to-flights {
            margin-top: 20px;
            margin-left: 7%;
        }

        .back-to-flights a {
            text-decoration: none;
            color: #fff;
            background-color: #007bff;
            padding: 10px 20px;
            border-radius: 5px;
            display: inline-block;
            transition: background-color 0.3s ease;
        }

        .back-to-flights a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<?php include('header.php'); ?>

<div class="back-to-flights">
    <a href="flights.php" class="btn btn-secondary">Back to Flights</a>
</div>

<div class="container mt-5">
    <h2>Rates for <?php echo $plane_name; ?></h2>
    <!-- Add Rate Form -->
    <form id="addRateForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?id=<?php echo $plane_id; ?>&name=<?php echo $plane_name; ?>" method="post">
        <input type="hidden" name="action" value="add_rate">
        <input type="hidden" name="plane_id" value="<?php echo $plane_id; ?>">
        <div class="form-group">
            <label for="season">Season:</label>
            <input type="text" class="form-control" id="season" name="season" required>
        </div>
        <div class="form-group">
            <label for="season_start">Season Start:</label>
            <input type="date" class="form-control" id="season_start" name="season_start" required>
        </div>
        <div class="form-group">
            <label for="season_end">Season End:</label>
            <input type="date" class="form-control" id="season_end" name="season_end" required>
        </div>
        <div class="form-group">
            <label for="source">Source:</label>
            <input type="text" class="form-control" id="source" name="source" required>
        </div>
        <div class="form-group">
            <label for="destination">Destination:</label>
            <input type="text" class="form-control" id="destination" name="destination" required>
        </div>
        <div class="form-group">
            <label for="adults_price">Adults Price:</label>
            <input type="number" min="0" step="0.01" class="form-control" id="adults_price" name="adults_price" required>
        </div>
        <div class="form-group">
            <label for="children_age">Children Age:</label>
            <input type="text" class="form-control" id="children_age" name="children_age" required>
        </div>
        <div class="form-group">
            <label for="custom_children_price">Custom Children Price:</label>
            <input type="number" min="0" step="0.01" class="form-control" id="custom_children_price" name="custom_children_price" required>
        </div>

        <button type="submit" class="btn btn-primary">Add Rate</button>
    </form>

    <hr>

    <h2>Registered Rates</h2>
    <!-- Display Registered Rates in a Table -->
    <table class="table">
        <thead>
            <tr>
                <th>Season</th>
                <th>Season Start</th>
                <th>Season End</th>
                <th>Source</th>
                <th>Destination</th>
                <th>Adults Price</th>
                <th>Children Age</th>
                <th>Custom Children Price</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php
            // Retrieve rates data from database
            $sql = "SELECT * FROM plane_rates WHERE plane_id = $plane_id";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['season']}</td>
                            <td>{$row['season_start']}</td>
                            <td>{$row['season_end']}</td>
                            <td>{$row['source']}</td>
                            <td>{$row['destination']}</td>
                            <td>{$row['adults_price']}</td>
                            <td>{$row['children_age']}</td>
                            <td>{$row['children_price']}</td>
                            <td>
                                <button class='btn btn-sm btn-primary' onclick='editRate({$row['id']}, \"{$row['season']}\", \"{$row['season_start']}\", \"{$row['season_end']}\", \"{$row['source']}\", \"{$row['destination']}\", \"{$row['adults_price']}\", \"{$row['children_age']}\", \"{$row['children_price']}\")'>Edit</button>
                                <form action='' method='post' style='display: inline;'>
                                    <input type='hidden' name='action' value='delete_rate'>
                                    <input type='hidden' name='rate_id' value='{$row['id']}'>
                                    <button type='submit' class='btn btn-sm btn-danger' onclick='return confirm(\"Are you sure you want to delete this rate?\")'>Delete</button>
                                </form>
                            </td>
                        </tr>";
                }
            } else {
                echo "<tr><td colspan='9'>No rates registered</td></tr>";
            }
        ?>
        </tbody>
    </table>

    <?php
    // Display success or error messages
    if (isset($success_message)) {
        echo "<div class='alert alert-success'>$success_message</div>";
    }
    if (isset($error_message)) {
        echo "<div class='alert alert-danger'>$error_message</div>";
    }
    ?>  

</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

<script>
    function editRate(rateId, season, seasonStart, seasonEnd, source, destination, adultsPrice, childrenAge, customChildrenPrice) {
        // Populate edit form fields
        document.getElementById('addRateForm').action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?id=<?php echo $plane_id; ?>&name=<?php echo $plane_name; ?>";
        document.getElementById('addRateForm').innerHTML += '<input type="hidden" name="action" value="update_rate">';
        document.getElementById('addRateForm').innerHTML += '<input type="hidden" name="rate_id" value="' + rateId + '">';
        document.getElementById('season').value = season;
        document.getElementById('season_start').value = seasonStart;
        document.getElementById('season_end').value = seasonEnd;
        document.getElementById('source').value = source;
        document.getElementById('destination').value = destination;
        document.getElementById('adults_price').value = adultsPrice;
        document.getElementById('children_age').value = childrenAge;
        document.getElementById('custom_children_price').value = customChildrenPrice;
    }
</script>
</body>
</html>

<?php
// Close database connection
$conn->close();
?>
