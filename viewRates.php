<?php
// Include your database connection file
include 'db_connection.php';

// Function to get rows for a given park name
function getRowsForPark($conn, $park_name) {
    $stmt = $conn->prepare("SELECT * FROM national_park_details WHERE park_name = ?");
    $stmt->bind_param("s", $park_name);
    $stmt->execute();
    
    $result = $stmt->get_result();
    $rows = [];
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
    
    $stmt->close();
    return $rows;
}

// Get park name from query parameter
$park_name = isset($_GET['park_name']) ? filter_var($_GET['park_name'], FILTER_SANITIZE_STRING) : '';

// Retrieve rows for the given park name
$rows = getRowsForPark($conn, $park_name);

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Park Details</title>
    <style>
        /* Add some basic styling for the table */
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
          
        }
        
        .styled-table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 20px;
        }
        
        .styled-table thead {
            background-color: gray;
            color: white;
        }
        
        .styled-table th,
        .styled-table td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        
        .styled-table th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
        }
        
        .styled-table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

          /* Style for the back button */
          .back-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        
        .back-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<!-- Include header.php file for the header -->
<?php include 'header.php'; ?> </br>


<a href="parks.php" class="btn btn-secondary back-button">Back to Parks</a>

    <!-- Display the rows in a styled table -->
    <h2>Park Details: <?php echo htmlspecialchars($park_name); ?></h2>
    <table class="styled-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Season Name</th>
                <th>Season Duration</th>
                <th>EA Citizen Adult Fee</th>
                <th>EA Citizen Children Fee</th>
                <th>EA Citizen Below 5 Fee</th>
                <th>Non-EA Citizen Adult Fee</th>
                <th>Non-EA Citizen Children Fee</th>
                <th>Non-EA Citizen Below 5 Fee</th>
                <th>TZ Resident Above 16 Fee</th>
                <th>TZ Resident Children Fee</th>
                <th>TZ Resident Below 5 Fee</th>
                <th>Guide Entry Fee</th>
                <th>Vehicle Fee</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rows as $row) : ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['seasonname']; ?></td>
                    <td><?php echo $row['seasonduration']; ?></td>
                    <td><?php echo $row['ea_citizen_adult']; ?></td>
                    <td><?php echo $row['ea_citizen_children']; ?></td>
                    <td><?php echo $row['ea_citizen_below_5']; ?></td>
                    <td><?php echo $row['non_ea_citizen_adult']; ?></td>
                    <td><?php echo $row['non_ea_citizen_children']; ?></td>
                    <td><?php echo $row['non_ea_citizen_below_5']; ?></td>
                    <td><?php echo $row['tz_resident_above_16']; ?></td>
                    <td><?php echo $row['tz_resident_children']; ?></td>
                    <td><?php echo $row['tz_resident_below_5']; ?></td>
                    <td><?php echo $row['guide_entry_fee']; ?></td>
                    <td><?php echo $row['vehicle_fee']; ?></td>
                    <td><a href="edit_entry.php?id=<?php echo $row['id']; ?>">Edit</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
