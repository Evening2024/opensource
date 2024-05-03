<?php
// Include the header file
include 'header.php';

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

// Display the rows in a styled table
echo '<table class="styled-table">';
echo '<thead>';
echo '<tr>';
echo '<th>ID</th>';
echo '<th>Season Name</th>';
echo '<th>Season Duration</th>';
echo '<th>EA Citizen Adult Fee</th>';
echo '<th>EA Citizen Children Fee</th>';
echo '<th>EA Citizen Below 5 Fee</th>';
echo '<th>Non-EA Citizen Adult Fee</th>';
echo '<th>Non-EA Citizen Children Fee</th>';
echo '<th>Non-EA Citizen Below 5 Fee</th>';
echo '<th>TZ Resident Above 16 Fee</th>';
echo '<th>TZ Resident Children Fee</th>';
echo '<th>TZ Resident Below 5 Fee</th>';
echo '<th>Guide Entry Fee</th>';
echo '<th>Vehicle Fee</th>';
echo '<th>Actions</th>';
echo '</tr>';
echo '</thead>';
echo '<tbody>';

foreach ($rows as $row) {
    echo '<tr>';
    echo '<td>' . $row['id'] . '</td>';
    echo '<td>' . $row['seasonname'] . '</td>';
    echo '<td>' . $row['seasonduration'] . '</td>';
    echo '<td>' . $row['ea_citizen_adult'] . '</td>';
    echo '<td>' . $row['ea_citizen_children'] . '</td>';
    echo '<td>' . $row['ea_citizen_below_5'] . '</td>';
    echo '<td>' . $row['non_ea_citizen_adult'] . '</td>';
    echo '<td>' . $row['non_ea_citizen_children'] . '</td>';
    echo '<td>' . $row['non_ea_citizen_below_5'] . '</td>';
    echo '<td>' . $row['tz_resident_above_16'] . '</td>';
    echo '<td>' . $row['tz_resident_children'] . '</td>';
    echo '<td>' . $row['tz_resident_below_5'] . '</td>';
    echo '<td>' . $row['guide_entry_fee'] . '</td>';
    echo '<td>' . $row['vehicle_fee'] . '</td>';
    echo '<td><a href="edit_entry.php?id=' . $row['id'] . '">Edit</a></td>';
    echo '</tr>';
}

echo '</tbody>';
echo '</table>';

// Close the database connection
$conn->close();
?>

<style>
/* Add some basic styling for the table */
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


</style>
