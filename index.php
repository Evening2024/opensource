<?php
session_start();
// Include database connection
include 'db_connection.php';

// Function to safely escape input values
function sanitize_input($input) {
    return htmlspecialchars(strip_tags($input));
}

// Function to update system status
function update_system_status($conn, $export_required) {
    // Get current date and time in MySQL format
    $current_time = date('Y-m-d H:i:s');

    // Prepare and execute the SQL update statement
    $stmt = $conn->prepare("UPDATE system_status SET export_required = ?, last_reset = ?");
    $stmt->bind_param("is", $export_required, $current_time);
    $stmt->execute();

    // Check if update was successful
    if ($stmt->affected_rows > 0) {
        echo "System status updated successfully.";
    } else {
        echo "Failed to update system status.";
    }

    // Close the statement
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate empty fields
    if (empty($_POST['email']) || empty($_POST['password'])) {
        $error_message = "Both email and password are required.";
    } else {
        $email = sanitize_input($_POST['email']);
        $password = sanitize_input($_POST['password']);

        // Prepare a SQL statement to fetch user data based on email
        $stmt = $conn->prepare("SELECT id, email, role, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {
            // Authentication successful, set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['user_role'] = $user['role'];

            // Check if export is required and time since last reset
            $stmt = $conn->prepare("SELECT export_required, last_reset FROM system_status WHERE id = 1");
            $stmt->execute();
            $result = $stmt->get_result();
            $status = $result->fetch_assoc();

            $current_time = new DateTime();
            $last_reset_time = new DateTime($status['last_reset']);
            $time_diff = $current_time->getTimestamp() - $last_reset_time->getTimestamp();
            $minutes_diff = round($time_diff / 60);

            if ($status['export_required'] || $minutes_diff >= 2) {
                // Update system status
                update_system_status($conn, 1); // Set export_required to 1
                header("Location: export.php");
                exit(); // Terminate script after redirection
            } else {
                // Redirect user to the specified URL
                header("Location: header.php");
                exit(); // Terminate script after redirection
            }
        } else {
            $error_message = "Invalid email or password";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAFARI</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="bootstrap/dist/css/bootstrap.min.css">
    <!-- Particles.js Library -->
    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f8f9fa;
            margin: 0;
            overflow: hidden;
        }

        #particles-js {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: -1;
        }

        .container {
            position: relative;
            width: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            z-index: 1;
        }

        .login-form {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0px 0px 20px 0px rgba(0,0,0,0.1);
            padding: 40px;
            width: 80%;
            text-align: center;
        }

        .login-header {
            color: #007bff;
            font-size: 24px;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-control {
            border-radius: 5px;
        }

        .btn-primary {
            width: 100%;
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .profile-pic {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            margin: 0 auto 20px auto;
            display: block;
        }

        .toggle-password {
            cursor: pointer;
            position: absolute;
            right: 15px;
            top: 10px;
            z-index: 2;
        }

        .error-message {
            color: #dc3545;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <!-- Particles Container -->
    <div id="particles-js"></div>

    <div class="container">
        <div class="login-form">
            <img src="picha/logo.webp" alt="Profile Picture" class="profile-pic">
            <h2 class="login-header">Welcome back!</h2>
            <?php if (isset($error_message)) : ?>
                <div class="alert alert-danger" role="alert">
                    <?= $error_message ?>
                </div>
                <?php header("refresh:3;url=http://localhost/safari/"); ?>
            <?php endif; ?>
            <form method="post">
                <div class="form-group">
                    <input type="email" class="form-control" id="email" name="email" placeholder="Email">
                </div>
                <div class="form-group position-relative">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                    <span class="toggle-password" onclick="togglePassword()">👁️</span>
                </div>
                <button type="submit" class="btn btn-primary">Login</button>
                <div class="text-center mt-3">
                    Forgot password? <a href="forgot_password.php" style="color: green; text-decoration: none;">Reset here</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS (including Popper.js) -->
    <script src="bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Particles.js Initialization -->
    <script>
       particlesJS('particles-js', {
  "particles": {
    "number": {
      "value": 20,
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


        function togglePassword() {
            const passwordField = document.getElementById('password');
            const passwordFieldType = passwordField.getAttribute('type');
            if (passwordFieldType === 'password') {
                passwordField.setAttribute('type', 'text');
            } else {
                passwordField.setAttribute('type', 'password');
            }
        }
    </script>
</body>
</html>
