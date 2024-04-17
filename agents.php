<?php
// Include the database connection file
require 'db_connection.php';

// Initialize variables for form data and error messages
$agent_name = $agent_address = $agent_email = $agent_phone = $agent_logo = "";
$error_message = "";
$edit_agent_id = null;

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle adding or editing an agent
    if (isset($_POST["save_agent"])) {
        // Retrieve form data
        $agent_name = trim($_POST["agent_name"]);
        $agent_address = trim($_POST["agent_address"]);
        $agent_email = trim($_POST["agent_email"]);
        $agent_phone = trim($_POST["agent_phone"]);

        // Handle file upload for agent logo
        if (isset($_FILES["agent_logo"]) && $_FILES["agent_logo"]["error"] == UPLOAD_ERR_OK) {
            $allowed_types = ["image/png", "image/jpeg"];
            $file_info = finfo_open(FILEINFO_MIME_TYPE);
            $file_type = finfo_file($file_info, $_FILES["agent_logo"]["tmp_name"]);
            finfo_close($file_info);
            
            if (in_array($file_type, $allowed_types) && $_FILES["agent_logo"]["size"] <= 10 * 1024 * 1024) {
                // Save the file in the logos folder
                $logo_filename = "agentslogos/" . basename($_FILES["agent_logo"]["name"]);
                move_uploaded_file($_FILES["agent_logo"]["tmp_name"], $logo_filename);
                $agent_logo = $logo_filename;
            } else {
                $error_message = "Invalid file type or file size exceeds the limit.";
            }
        }

        if (empty($error_message)) {
            // Check if this is an update or a new addition
            if (isset($_POST["agent_id"])) {
                // Update the existing agent in the database
                $agent_id = $_POST["agent_id"];
                $update_query = "UPDATE agents SET agent_name = ?, agent_address = ?, agent_email = ?, agent_phone = ?, agent_logo = COALESCE(NULLIF(?, ''), agent_logo) WHERE id = ?";
                $stmt = $conn->prepare($update_query);
                $stmt->bind_param("sssssi", $agent_name, $agent_address, $agent_email, $agent_phone, $agent_logo, $agent_id);
                $stmt->execute();
                $stmt->close();
            } else {
                // Add a new agent to the database
                $insert_query = "INSERT INTO agents (agent_name, agent_address, agent_email, agent_phone, agent_logo) VALUES (?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($insert_query);
                $stmt->bind_param("sssss", $agent_name, $agent_address, $agent_email, $agent_phone, $agent_logo);
                $stmt->execute();
                $stmt->close();
            }

            // Redirect to refresh page data
            header("Location: " . $_SERVER["REQUEST_URI"]);
            exit;
        }
    } elseif (isset($_POST["delete_agent"])) {
        // Handle deleting an agent
        $agent_id = $_POST["agent_id"];
        
        // Retrieve the logo file path for the agent
        $query = "SELECT agent_logo FROM agents WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $agent_id);
        $stmt->execute();
        $stmt->bind_result($agent_logo);
        $stmt->fetch();
        $stmt->close();
        
        // Delete the logo file from the server if it exists
        if (!empty($agent_logo) && file_exists($agent_logo)) {
            unlink($agent_logo);
        }
        
        // Delete the agent from the database
        $delete_query = "DELETE FROM agents WHERE id = ?";
        $stmt = $conn->prepare($delete_query);
        $stmt->bind_param("i", $agent_id);
        $stmt->execute();
        $stmt->close();
    }
}

// Retrieve existing agents from the database
$agents_data = [];
$query = "SELECT * FROM agents";
$result = $conn->query($query);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $agents_data[] = $row;
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
    <title>Agents Management</title>
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

        .logo-preview {
            width: 100px;
            height: 100px;
            object-fit: cover;
            margin-bottom: 10px;
        }
        
        /* Displaying agent details */
        .agent-info {
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <!-- Include header.php file for the header -->
    <?php include 'header.php'; ?>

    <!-- Agents form and display section -->
    <div class="container">
        <h2>Agents Management</h2>
        <!-- Display error messages if any -->
        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>
        
        <!-- Form to add or edit an agent -->
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="agent_name">Agent Name:</label>
                <input type="text" class="form-control" id="agent_name" name="agent_name" value="<?php echo htmlspecialchars($agent_name); ?>">
            </div>
            <div class="form-group">
                <label for="agent_address">Agent Address:</label>
                <input type="text" class="form-control" id="agent_address" name="agent_address" value="<?php echo htmlspecialchars($agent_address); ?>">
            </div>
            <div class="form-group">
                <label for="agent_email">Agent Email:</label>
                <input type="email" class="form-control" id="agent_email" name="agent_email" value="<?php echo htmlspecialchars($agent_email); ?>">
            </div>
            <div class="form-group">
                <label for="agent_phone">Agent Phone:</label>
                <input type="text" class="form-control" id="agent_phone" name="agent_phone" value="<?php echo htmlspecialchars($agent_phone); ?>">
            </div>
            <div class="form-group">
                <label for="agent_logo">Agent Logo:</label>
                <?php if (!empty($agent_logo)): ?>
                    <img src="<?php echo htmlspecialchars($agent_logo); ?>" alt="Agent Logo" class="logo-preview">
                <?php endif; ?>
                <input type="file" class="form-control" id="agent_logo" name="agent_logo" accept=".png, .jpeg">
            </div>
            
            <!-- If editing, include a hidden input for the agent_id -->
            <?php if ($edit_agent_id): ?>
                <input type="hidden" name="agent_id" value="<?php echo $edit_agent_id; ?>">
            <?php endif; ?>
            
            <button type="submit" class="btn btn-primary" name="save_agent">Save Agent</button>
        </form>

        <!-- Display existing agents -->
        <h3>Existing Agents</h3>
        <div class="row">
            <?php foreach ($agents_data as $index => $agent): ?>
                <div class="col-md-3">
                    <div class="card mb-3">
                        <img src="<?php echo htmlspecialchars($agent["agent_logo"]); ?>" alt="Agent Logo" class="card-img-top logo-preview">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($agent["agent_name"]); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($agent["agent_address"]); ?></p>
                            <p class="card-text"><?php echo htmlspecialchars($agent["agent_email"]); ?></p>
                            <p class="card-text"><?php echo htmlspecialchars($agent["agent_phone"]); ?></p>
                          
                                <!-- Edit button -->
                              

                                
                                <!-- Delete button -->
                                <form method="POST" style="display: inline-block;">
                                    <input type="hidden" name="agent_id" value="<?php echo $agent["id"]; ?>">
                                    <button type="submit" class="btn btn-danger" name="delete_agent">Delete</button>
                                </form>
                            
                            <button class="btn btn-sm btn-secondary" style="padding: 0.25rem 0.5rem;" onclick="editAgent('<?php echo $agent["id"]; ?>', '<?php echo addslashes($agent["agent_name"]); ?>', '<?php echo addslashes($agent["agent_address"]); ?>', '<?php echo addslashes($agent["agent_email"]); ?>', '<?php echo addslashes($agent["agent_phone"]); ?>', '<?php echo htmlspecialchars($agent["agent_logo"]); ?>')">Edit</button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

   

    <!-- JavaScript function to handle the editing of an agent -->
    <script>
        function editAgent(agentId, agentName, agentAddress, agentEmail, agentPhone, agentLogo) {
            // Set form values with agent data
            document.getElementById("agent_name").value = agentName;
            document.getElementById("agent_address").value = agentAddress;
            document.getElementById("agent_email").value = agentEmail;
            document.getElementById("agent_phone").value = agentPhone;
            
            // Set hidden input for agent ID (indicating an edit operation)
            var agentIdInput = document.createElement("input");
            agentIdInput.type = "hidden";
            agentIdInput.name = "agent_id";
            agentIdInput.value = agentId;
            document.querySelector("form").appendChild(agentIdInput);

            // Update the logo preview if agentLogo is provided
            if (agentLogo) {
                var logoPreview = document.querySelector(".logo-preview");
                logoPreview.src = agentLogo;
                logoPreview.style.display = "block";
            }

            // Scroll to the form
            document.querySelector("form").scrollIntoView();
        }
    </script>
</body>

</html>
