<?php
session_start();

// Assume you have a way to set the user role when the user logs in
// For demonstration purposes, let's set it manually
// $_SESSION['user_role'] = 'general'; // Possible values: 'reservation_officer', 'accounts', 'general'

// Get the user role from the session
$user_role = $_SESSION['user_role'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decorative Menu Bar</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="bootstrap/dist/css/bootstrap.min.css">
    <!-- Particles.js Library -->
    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    <!-- Custom CSS -->
    <style>
        /* Customize the navbar appearance */
        .navbar {
            background-color: #333;
            width: 100%;
            margin: 0;
            padding: 0;
        }

        /* Customize menu items */
        .navbar-nav .nav-link {
            color: #fff;
            margin-right: 80px;
            margin-left: 80px;
            font-size: 16px;
        }

        .navbar-nav .nav-link:hover {
            color: #f0f0f0;
        }

        /* Customize dropdown menus */
        .dropdown-menu {
            background-color: #444;
            min-width: 200px;
        }

        .dropdown-menu .dropdown-item {
            color: #fff;
            padding: 10px 15px;
        }

        .dropdown-menu .dropdown-item:hover {
            background-color: #555;
        }

        /* Remove padding and margin from container */
        .container-fluid {
            padding: 0;
            margin: 0;
        }

        /* Background image */
        body {
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            position: relative;
            margin: 0;
            padding: 0;
            height: 100vh;
        }

        /* Reduce visibility of the background image */
        body::after {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.7); /* Adjust the color and opacity as needed */
            z-index: -1; /* Ensure it's behind other content */
        }

        /* Particles container */
        #particles-js {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: -2;
        }
    </style>
</head>

<body>
    <!-- Particles Container -->
    <div id="particles-js"></div>

    <!-- Header with decorative menu bar -->
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">Safari</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <!-- Menu Items for My Account -->
                        <?php if ($user_role == 'general') : ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="accountDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    My Account
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="accountDropdown">
                                    <li><a class="dropdown-item" href="account_details.php">Company Details</a></li>
                                    <li><a class="dropdown-item" href="bank_details.php">Bank Details</a></li>
                                    <li><a class="dropdown-item" href="partners.php">Partners</a></li>
                                    <li><a class="dropdown-item" href="agents.php">Agent</a></li>
                                    <li><a class="dropdown-item" href="create_user.php">Users</a></li>
                                </ul>
                            </li>
                        <?php endif; ?>

                        <!-- Menu Items for Setup -->
                        <?php if ($user_role == 'reservation_officer' || $user_role == 'general') : ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="setupDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Setup
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="setupDropdown">
                                    <li><a class="dropdown-item" href="parks.php">National Parks</a></li>
                                    <li><a class="dropdown-item" href="accomodations.php">Accomodations</a></li>
                                    <li><a class="dropdown-item" href="flights.php">Flights</a></li>
                                    <li><a class="dropdown-item" href="transport.php">Transport</a></li>
                                    <li><a class="dropdown-item" href="policies.php">Policies</a></li>
                                </ul>
                            </li>
                        <?php endif; ?>

                        <!-- Menu Items for Safari -->
                        <?php if ($user_role == 'reservation_officer' || $user_role == 'general') : ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="safariDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Safari
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="safariDropdown">
                                    <li><a class="dropdown-item" href="CreateSafari.php">Create Safari</a></li>
                                    <li><a class="dropdown-item" href="ViewAllSafaris.php">All safaris</a></li>
                                    <li><a class="dropdown-item" href="Enquiries.php">Enquiry</a></li>
                                    <li><a class="dropdown-item" href="provisional.php">Provisional</a></li>
                                    <li><a class="dropdown-item" href="confirmed.php">Confirmed</a></li>
                                    <li><a class="dropdown-item" href="paid.php">Paid</a></li>
                                </ul>
                            </li>
                        <?php endif; ?>

                        <!-- Menu Items for Accounts -->
                        <?php if ($user_role == 'accounts' || $user_role == 'general') : ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="accountsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Accounts
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="accountsDropdown">
                                    <li><a class="dropdown-item" href="invoice.php">Invoices</a></li>
                                </ul>
                            </li>
                        <?php endif; ?>

                          <!-- Menu Items for Reports -->
                          <?php if ($user_role == 'general') : ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="accountsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Reports
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="accountsDropdown">
                                    <li><a class="dropdown-item" href="totaltourists.php">Tourists by months</a></li>
                                    <li><a class="dropdown-item" href="mostchoosenproperty.php">Most choosen properties</a></li>
                                </ul>
                            </li>
                        <?php endif; ?>

                        <!-- Logout Button -->
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Logout</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <!-- Add your main content here -->

    <!-- Bootstrap JS (including Popper.js) -->
    <script src="bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Particles.js Initialization -->
    <script>
        particlesJS('particles-js', {
            "particles": {
                "number": {
                    "value": 80,
                    "density": {
                        "enable": true,
                        "value_area": 800
                    }
                },
                "color": {
                    "value": "#007bff"
                },
                "shape": {
                    "type": "circle",
                    "stroke": {
                        "width": 0,
                        "color": "#000000"
                    },
                    "polygon": {
                        "nb_sides": 5
                    },
                    "image": {
                        "src": "img/github.svg",
                        "width": 100,
                        "height": 100
                    }
                },
                "opacity": {
                    "value": 0.5,
                    "random": false,
                    "anim": {
                        "enable": false,
                        "speed": 1,
                        "opacity_min": 0.1,
                        "sync": false
                    }
                },
                "size": {
                    "value": 5,
                    "random": true,
                    "anim": {
                        "enable": false,
                        "speed": 40,
                        "size_min": 0.1,
                        "sync": false
                    }
                },
                "line_linked": {
                    "enable": true,
                    "distance": 150,
                    "color": "#007bff",
                    "opacity": 0.4,
                    "width": 1
                },
                "move": {
                    "enable": true,
                    "speed": 6,
                    "direction": "none",
                    "random": false,
                    "straight": false,
                    "out_mode": "out",
                    "bounce": false,
                    "attract": {
                        "enable": false,
                        "rotateX": 600,
                        "rotateY": 1200
                    }
                }
            },
            "interactivity": {
                "detect_on": "canvas",
                "events": {
                    "onhover": {
                        "enable": true,
                        "mode": "grab"
                    },
                    "onclick": {
                        "enable": true,
                        "mode": "push"
                    },
                    "resize": true
                },
                "modes": {
                    "grab": {
                        "distance": 140,
                        "line_linked": {
                            "opacity": 1
                        }
                    },
                    "bubble": {
                        "distance": 400,
                        "size": 40,
                        "duration": 2,
                        "opacity": 8,
                        "speed": 3
                    },
                    "repulse": {
                        "distance": 200,
                        "duration": 0.4
                    },
                    "push": {
                        "particles_nb": 4
                    },
                    "remove": {
                        "particles_nb": 2
                    }
                }
            },
            "retina_detect": true
        });
    </script>
</body>

</html>
