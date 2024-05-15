<?php
session_start();

// Include database connection
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = ""; // Assuming no password is set for the root user
$dbname = "your_database"; // Name of the database created from your SQL file

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Define variables and initialize with empty values
$email = $email_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter your email.";
    } elseif (!filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL)) {
        $email_err = "Please enter a valid email address.";
    } else {
        $email = trim($_POST["email"]);

        // Check if email exists in the database
        $sql_check_email = "SELECT email FROM users WHERE email = ?";
        if ($stmt_check_email = mysqli_prepare($conn, $sql_check_email)) {
            mysqli_stmt_bind_param($stmt_check_email, "s", $param_email);
            $param_email = $email;

            if (mysqli_stmt_execute($stmt_check_email)) {
                mysqli_stmt_store_result($stmt_check_email);

                if (mysqli_stmt_num_rows($stmt_check_email) == 0) {
                    $email_err = "Email address not found in our records.";
                }
            } else {
                echo "Error executing SQL query: " . mysqli_error($conn);
            }

            mysqli_stmt_close($stmt_check_email);
        } else {
            echo "Error preparing SQL statement: " . mysqli_error($conn);
        }
    }

    // Check input errors before sending email
    if (empty($email_err)) {

        // Generate a random token
        $token = bin2hex(random_bytes(32)); // Generate a 64-character random string (32 bytes)

        // Set expiration time for the token (e.g., 1 hour)
        $expiration = date("Y-m-d H:i:s", strtotime('+1 hour'));

        // Insert token into database along with user's email
        $sql = "INSERT INTO password_reset (email, token, expiration) VALUES (?, ?, ?)";
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "sss", $param_email, $param_token, $param_expiration);
            $param_email = $email;
            $param_token = password_hash($token, PASSWORD_DEFAULT); // Hash the token before storing
            $param_expiration = $expiration;

            if (mysqli_stmt_execute($stmt)) {
                // Send password reset email
                $to = $email;
                $subject = "Password Reset";
                $message = "Click the following link to reset your password:https://localhost/projekti/passowrd_reset.php?token=$token";
                $headers = "From: yourwebsite@example.com";

                if (mail($to, $subject, $message, $headers)) {
                    // Email sent successfully
                    echo "A password reset link has been sent to your email.";
                } else {
                    echo "Error sending email. Please try again later.";
                }
            } else {
                echo "Error: " . mysqli_error($conn);
            }

            mysqli_stmt_close($stmt);
        }
    }

    // Close connection
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
        body {
            font: 14px sans-serif;
            background-image: url('https://mrwallpaper.com/images/hd/4k-beach-nature-view-s0jgy1y9poz5zg54.jpg');
            background-size: cover;
            background-repeat: no-repeat;
        }
        .wrapper { 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            height: 100vh; 
            animation: fadeIn 1.5s ease-in-out; /* Apply fade-in animation */
        }
        .form-container { 
            width: 360px; 
            padding: 20px; 
            background-color: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            margin: 10px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-control {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        .btn-primary {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            background-color: #007bff;
            border: none;
            color: #fff;
            cursor: pointer;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .btn-link {
            color: #007bff;
            text-decoration: none;
        }
        .btn-link:hover {
            text-decoration: underline;
        }
        #current-time {
            text-align: center; /* Center text horizontally */
            position: absolute; /* Position at the top of the page */
            width: 100%; /* Take full width of the page */
            top: 0; /* Align to the top */
            background-color: rgba(255, 255, 255, 0.8); /* Semi-transparent background */
            padding: 10px; /* Add padding for spacing */
        }
    </style>
</head>
<body>
<div id="current-time">
    <!-- The JavaScript code will update this content -->
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Function to update the time every second
        function updateTime() {
            // Get the current time
            var currentTime = new Date();
            // Format the time
            var hours = currentTime.getHours();
            var minutes = currentTime.getMinutes();
            var seconds = currentTime.getSeconds();
            var meridiem = "AM"; // Default to AM
            // Convert to 12-hour format
            if (hours > 12) {
                hours = hours - 12;
                meridiem = "PM";
            }
            // Add leading zeros if necessary
            hours = (hours < 10 ? "0" : "") + hours;
            minutes = (minutes < 10 ? "0" : "") + minutes;
            seconds = (seconds < 10 ? "0" : "") + seconds;
            // Update the content of the div
            $("#current-time").html("Current time: " + hours + ":" + minutes + ":" + seconds + " " + meridiem);
        }
        // Call updateTime initially
        updateTime();
        // Call updateTime every second
        setInterval(updateTime, 1000);
    });
</script>
    <div class="wrapper">
        <div class="form-container">
            <h2>Forgot Password</h2>
            <p>Please enter your email address to receive a password reset link.</p>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                    <label>Email Address</label>
                    <input type="text" name="email" class="form-control" value="<?php echo $email; ?>">
                    <span class="help-block"><?php echo $email_err; ?></span>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Submit">
                    <a class="btn btn-link" href="https://localhost/projekti/glauno.php">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
