<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(to bottom, #ffffff, #f0f0f0);
        }

        .container-fluid {
            padding: 20px;
        }

        .dashboard-header {
            margin-bottom: 20px;
        }

        .card {
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0px 2px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.2);
        }

        .system-logs {
            margin-top: 20px;
        }

        .navbar {
            background-color: #fff;
            box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-weight: bold;
            color: #333;
        }

        .navbar-nav .nav-item .nav-link {
            color: #333;
        }

        .navbar-nav .nav-item .nav-link:hover {
            color: #000;
        }

        /* Style for View Logs link */
        .view-logs-link {
            color: #007bff;
            cursor: pointer;
        }

        .view-logs-link:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <!-- Navigation bar -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="#">Permit System Admin Dashboard</a>
            <!-- Navbar links -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#">Logout</a>
                </li>
            </ul>
        </nav>

        <!-- Main content area -->
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-3">
                <!-- Sidebar content -->
                <!-- Add sidebar content here -->
            </div>
            <!-- Dashboard content -->
            <div class="col-lg-9">
                <div class="dashboard-header">
                    <h2 style="font-family: 'Arial Black', sans-serif;">Dashboard Overview</h2>
                </div>
                <div class="row">
                    <!-- Display total users -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Total Users</h5>
                                <p class="card-text">Count: <?php echo $totalUsers; ?></p>
                            </div>
                        </div>
                    </div>
                    <!-- Display officer count -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Officers</h5>
                                <p class="card-text">Count: <?php echo $officerCount; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- System logs section -->
                <div class="system-logs">
                    <h3>System Logs</h3>
                    <!-- Display login and logout logs here -->
                    <p><a href="#" class="view-logs-link" data-toggle="modal" data-target="#logsModal">View Logs</a></p>
                    <!-- Modal -->
                    <div class="modal fade" id="logsModal" tabindex="-1" role="dialog" aria-labelledby="logsModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-scrollable" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="logsModalLabel">System Logs</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <!-- Display system logs fetched from the database -->
                                    <ul>
                                        <!-- Example PHP code to fetch and display logs -->
                                        <?php
                                        // Assume $logs is an array containing system logs data fetched from the database
                                        foreach ($logs as $log) :
                                            // Format and display log data
                                            $formattedLog = sprintf(
                                                "User: %s | Action: %s | Time: %s",
                                                $log['username'],
                                                $log['action'],
                                                $log['timestamp']
                                            );
                                        ?>
                                        <li><?php echo $formattedLog; ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
