<?php
// Include the database connection file
require 'db_connection.php';

// Initialize variables for form data and error messages
$account_holder_name = $bank_name = $account_number = $bank_branch = $routing_number = $iban = $swift_bic_code = "";
$error_message = "";
$edit_bank_id = null;

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle adding or editing bank details
    if (isset($_POST["save_bank"])) {
        // Retrieve form data
        $account_holder_name = trim($_POST["account_holder_name"]);
        $bank_name = trim($_POST["bank_name"]);
        $account_number = trim($_POST["account_number"]);
        $bank_branch = trim($_POST["bank_branch"]);
        $routing_number = trim($_POST["routing_number"]);
        $iban = trim($_POST["iban"]);
        $swift_bic_code = trim($_POST["swift_bic_code"]);

        // Validate inputs
        if (empty($account_holder_name) || empty($bank_name) || empty($account_number) || empty($bank_branch) || empty($routing_number) || empty($iban) || empty($swift_bic_code)) {
            $error_message = "All fields are required. Please fill in all the details.";
        } else {
            // Check if this is an update or a new addition
            if (isset($_POST["bank_id"])) {
                // Update the existing bank details in the database
                $bank_id = $_POST["bank_id"];
                $update_query = "UPDATE bank_details SET account_holder_name = ?, bank_name = ?, account_number = ?, bank_branch = ?, routing_number = ?, iban = ?, swift_bic_code = ? WHERE id = ?";
                $stmt = $conn->prepare($update_query);
                $stmt->bind_param("sssssssi", $account_holder_name, $bank_name, $account_number, $bank_branch, $routing_number, $iban, $swift_bic_code, $bank_id);
                $stmt->execute();
                $stmt->close();
            } else {
                // Add new bank details to the database
                $insert_query = "INSERT INTO bank_details (account_holder_name, bank_name, account_number, bank_branch, routing_number, iban, swift_bic_code) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($insert_query);
                $stmt->bind_param("sssssss", $account_holder_name, $bank_name, $account_number, $bank_branch, $routing_number, $iban, $swift_bic_code);
                $stmt->execute();
                $stmt->close();
            }

            // Redirect to refresh page data
            header("Location: " . $_SERVER["REQUEST_URI"]);
            exit;
        }
    } elseif (isset($_POST["delete_bank"])) {
        // Handle deleting bank details
        $bank_id = $_POST["bank_id"];
        
        // Delete the bank details from the database
        $delete_query = "DELETE FROM bank_details WHERE id = ?";
        $stmt = $conn->prepare($delete_query);
        $stmt->bind_param("i", $bank_id);
        $stmt->execute();
        $stmt->close();
    }
}

// Retrieve existing bank details from the database
$bank_details_data = [];
$query = "SELECT * FROM bank_details";
$result = $conn->query($query);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $bank_details_data[] = $row;
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
    <title>Bank Details Management</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="bootstrap/dist/css/bootstrap.min.css">
    <!-- Custom CSS -->
    <style>
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
    </style>
</head>

<body>
    <!-- Include header.php file for the header -->
    <?php include 'header.php'; ?>

    <!-- Bank details form and display section -->
    <div class="container">
        <h2>Bank Details Management</h2>
        <!-- Display error messages if any -->
        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>
        
        <!-- Form to add or edit bank details -->
        <form method="POST">
            <div class="form-group">
                <label for="account_holder_name">Account Holder's Name:</label>
                <input type="text" class="form-control" id="account_holder_name" name="account_holder_name" value="<?php echo htmlspecialchars($account_holder_name); ?>">
            </div>
            <div class="form-group">
                <label for="bank_name">Bank Name:</label>
                <input type="text" class="form-control" id="bank_name" name="bank_name" value="<?php echo htmlspecialchars($bank_name); ?>">
            </div>
            <div class="form-group">
                <label for="account_number">Account Number:</label>
                <input type="text" class="form-control" id="account_number" name="account_number" value="<?php echo htmlspecialchars($account_number); ?>">
            </div>
            <div class="form-group">
                <label for="bank_branch">Bank Branch:</label>
                <input type="text" class="form-control" id="bank_branch" name="bank_branch" value="<?php echo htmlspecialchars($bank_branch); ?>">
            </div>
            <div class="form-group">
                <label for="routing_number">Routing Number:</label>
                <input type="text" class="form-control" id="routing_number" name="routing_number" value="<?php echo htmlspecialchars($routing_number); ?>">
            </div>
            <div class="form-group">
                <label for="iban">IBAN:</label>
                <input type="text" class="form-control" id="iban" name="iban" value="<?php echo htmlspecialchars($iban); ?>">
            </div>
            <div class="form-group">
                <label for="swift_bic_code">SWIFT/BIC Code:</label>
                <input type="text" class="form-control" id="swift_bic_code" name="swift_bic_code" value="<?php echo htmlspecialchars($swift_bic_code); ?>">
            </div>
            
            <!-- If editing, include a hidden input for the bank_id -->
            <?php if ($edit_bank_id): ?>
                <input type="hidden" name="bank_id" value="<?php echo $edit_bank_id; ?>">
            <?php endif; ?>
            
            <button type="submit" class="btn btn-primary" name="save_bank">Save Bank Details</button>
        </form>

        <!-- Display existing bank details -->
        <h3>Existing Bank Details</h3>
        <div class="row">
            <?php foreach ($bank_details_data as $index => $bank_details): ?>
                <div class="col-md-4">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($bank_details["account_holder_name"]); ?></h5>
                            <p><strong>Bank Name:</strong> <?php echo htmlspecialchars($bank_details["bank_name"]); ?></p>
                            <p><strong>Account Number:</strong> <?php echo htmlspecialchars($bank_details["account_number"]); ?></p>
                            <p><strong>Bank Branch:</strong> <?php echo htmlspecialchars($bank_details["bank_branch"]); ?></p>
                            <p><strong>Routing Number:</strong> <?php echo htmlspecialchars($bank_details["routing_number"]); ?></p>
                            <p><strong>IBAN:</strong> <?php echo htmlspecialchars($bank_details["iban"]); ?></p>
                            <p><strong>SWIFT/BIC Code:</strong> <?php echo htmlspecialchars($bank_details["swift_bic_code"]); ?></p>
                          <div class="btn-group" role="group">
            
            <!-- Delete button -->
            <form method="POST" style="display: inline-block;">
                <input type="hidden" name="bank_id" value="<?php echo $bank_details["id"]; ?>">
                <button type="submit" class="btn btn-sm btn-danger" name="delete_bank">Delete</button>
            </form>
        </div>
        <!-- Edit button -->
        <button class="btn btn-sm btn-secondary" style="padding: 0.25rem 0.5rem;" onclick="editBank('<?php echo $bank_details["id"]; ?>', '<?php echo addslashes($bank_details["account_holder_name"]); ?>', '<?php echo addslashes($bank_details["bank_name"]); ?>', '<?php echo addslashes($bank_details["account_number"]); ?>', '<?php echo addslashes($bank_details["bank_branch"]); ?>', '<?php echo addslashes($bank_details["routing_number"]); ?>', '<?php echo addslashes($bank_details["iban"]); ?>', '<?php echo addslashes($bank_details["swift_bic_code"]); ?>')">Edit</button>


                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

  
    <!-- Custom JS -->
    <script>
        function editBank(bankId, accountHolderName, bankName, accountNumber, bankBranch, routingNumber, iban, swiftBicCode) {
            // Set form values to the existing bank details
            document.getElementById("account_holder_name").value = accountHolderName;
            document.getElementById("bank_name").value = bankName;
            document.getElementById("account_number").value = accountNumber;
            document.getElementById("bank_branch").value = bankBranch;
            document.getElementById("routing_number").value = routingNumber;
            document.getElementById("iban").value = iban;
            document.getElementById("swift_bic_code").value = swiftBicCode;
            
            // Add hidden input for bank_id
            var bankIdInput = document.createElement("input");
            bankIdInput.type = "hidden";
            bankIdInput.name = "bank_id";
            bankIdInput.value = bankId;
            
            // Append hidden input to the form
            var form = document.querySelector("form");
            form.appendChild(bankIdInput);
            
            // Scroll to the form for user convenience
            form.scrollIntoView({ behavior: "smooth" });
        }
    </script>
</body>

</html>
