<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decorative Menu Bar</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="bootstrap/dist/css/bootstrap.min.css">
    <!-- Custom CSS -->
    <style>
        /* Customize the navbar appearance */
        .navbar {
            background-color: #333;
            width: 100%; /* Full width */
            margin: 0; /* Remove any margin */
            padding: 0; /* Remove any padding */
        }

        /* Customize menu items */
        .navbar-nav .nav-link {
            color: #fff;
            margin-right: 80px;
            margin-left: 80px; /* Increase spacing between menu items */
            font-size: 16px; /* Adjust font size as needed */
        }

        .navbar-nav .nav-link:hover {
            color: #f0f0f0;
        }

        /* Customize dropdown menus */
        .dropdown-menu {
            background-color: #444;
            min-width: 200px; /* Set minimum width for dropdowns */
        }

        .dropdown-menu .dropdown-item {
            color: #fff;
            padding: 10px 15px; /* Add padding for better size */
        }

        .dropdown-menu .dropdown-item:hover {
            background-color: #555;
        }
        
        /* Remove padding and margin from container */
        .container-fluid {
            padding: 0;
            margin: 0;
        }
    </style>
</head>

<body>
    <!-- Header with decorative menu bar -->
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">Safari</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <!-- Menu Items -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="setupDropdown" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                My Account
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="setupDropdown">
                                <li><a class="dropdown-item" href="account_details.php">Company Details</a></li>
                                <li><a class="dropdown-item" href="bank_details.php">Bank Details</a></li>
                                <li><a class="dropdown-item" href="partners.php">Partners</a></li>
                                <li><a class="dropdown-item" href="agents.php">Agent</a></li>
                                <li><a class="dropdown-item" href="#">Users</a></li>
                               
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="setupDropdown" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                Setups
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="setupDropdown">
                                <li><a class="dropdown-item" href="#">National Parks</a></li>
                                <li><a class="dropdown-item" href="#">Accomodations</a></li>
                                <li><a class="dropdown-item" href="#">Flights</a></li>
                                <li><a class="dropdown-item" href="#">Transport</a></li>
                            </ul>
                        </li>  
                        <li class="nav-item">
                            <a class="nav-link" href="#">Safari</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Operations</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="accountsDropdown" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                Accounts
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="accountsDropdown">
                                <li><a class="dropdown-item" href="#">Company Details</a></li>
                                <li><a class="dropdown-item" href="#">Partners</a></li>
                                <li><a class="dropdown-item" href="#">VAT</a></li>
                                <li><a class="dropdown-item" href="#">Agent</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Reports</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <!-- Add your main content here -->

    <!-- Bootstrap JS (including Popper.js) -->
    <script src="bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
