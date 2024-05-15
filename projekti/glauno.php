<?php
session_start();

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
$username = $email = $password = $confirm_password = "";
$username_err = $email_err = $password_err = $confirm_password_err = $captcha_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Check if the form is for registration or login
    if (isset($_POST['register'])) {
        // Registration form submitted

        // Validate reCAPTCHA
        $captcha = $_POST['g-recaptcha-response'];
        if (!$captcha) {
            $captcha_err = "Please complete the reCAPTCHA.";
        } else {
            // Verify reCAPTCHA response
            $secretKey = "YOUR_SECRET_KEY"; // Replace with your actual reCAPTCHA secret key
            $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=" . $secretKey . "&response=" . $captcha);
            $responseKeys = json_decode($response, true);
            if (intval($responseKeys["success"]) !== 1) {
                $captcha_err = "Failed to validate reCAPTCHA.";
            }
        }

        // Continue with the existing registration code
        
        // Validate username
        if (empty(trim($_POST["username"]))) {
            $username_err = "Please enter a username.";
        } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))) {
            $username_err = "Username can only contain letters, numbers, and underscores.";
        } else {
            // Check if username already exists
            $sql = "SELECT user_id FROM users WHERE username = ?";

            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            $param_username = trim($_POST["username"]);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);

            if (mysqli_stmt_num_rows($stmt) == 1) {
                $username_err = "This username is already taken.";
            } else {
                $username = trim($_POST["username"]);
            }
            mysqli_stmt_close($stmt);
        }

        // Validate email
        if (empty(trim($_POST["email"]))) {
            $email_err = "Please enter your email.";
        } elseif (!filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL)) {
            $email_err = "Please enter a valid email address.";
        } else {
            $email = trim($_POST["email"]);
        }

        // Validate password
        if (empty(trim($_POST["password"]))) {
            $password_err = "Please enter a password.";
        } elseif (strlen(trim($_POST["password"])) < 6) {
            $password_err = "Password must have at least 6 characters.";
        } else {
            $password = trim($_POST["password"]);
        }

        // Validate confirm password
        if (empty(trim($_POST["confirm_password"]))) {
            $confirm_password_err = "Please confirm password.";
        } else {
            $confirm_password = trim($_POST["confirm_password"]);
            if (empty($password_err) && ($password != $confirm_password)) {
                $confirm_password_err = "Password did not match.";
            }
        }

        // Check input errors before inserting in database
        if (empty($username_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err) && empty($captcha_err)) {

            // Prepare an insert statement
            $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "sss", $param_username, $param_email, $param_password);
            $param_username = $username;
            $param_email = $email;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Redirect to projekt1.php after successful registration
                header("Location: http://localhost/projekti/projekt1.php");
                exit;
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    } elseif (isset($_POST['login'])) {
        // Login form submitted
        // Validate username
        if (empty(trim($_POST["username"]))) {
            $username_err = "Please enter a username.";
        } else {
            $username = trim($_POST["username"]);
        }

        // Validate password
        if (empty(trim($_POST["password"]))) {
            $password_err = "Please enter your password.";
        } else {
            $password = trim($_POST["password"]);
        }

        // Check input errors before processing login
        if (empty($username_err) && empty($password_err)) {
            // Prepare a select statement
            $sql = "SELECT user_id, username, password FROM users WHERE username = ?";

            if ($stmt = mysqli_prepare($conn, $sql)) {
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "s", $param_username);

                // Set parameters
                $param_username = $username;

                // Attempt to execute the prepared statement
                if (mysqli_stmt_execute($stmt)) {
                    // Store result
                    mysqli_stmt_store_result($stmt);

                    // Check if username exists, if yes then verify password
                    if (mysqli_stmt_num_rows($stmt) == 1) {
                        // Bind result variables
                        mysqli_stmt_bind_result($stmt, $user_id, $username, $hashed_password);
                        if (mysqli_stmt_fetch($stmt)) {
                            if (password_verify($password, $hashed_password)) {
                                // Password is correct, so start a new session
                                $_SESSION["loggedin"] = true;
                                $_SESSION["user_id"] = $user_id;
                                $_SESSION["username"] = $username;

                                // Redirect user to WhatsApp group link
                                header("Location: http://localhost/projekti/projekt1.php");
                            } else {
                                // Display an error message if password is not valid
                                $password_err = "The password you entered was not valid.";
                            }
                        }
                    } else {
                        // Display an error message if username doesn't exist
                        $username_err = "No account found with that username.";
                    }
                } else {
                    echo "Oops! Something went wrong. Please try again later.";
                }

                // Close statement
                mysqli_stmt_close($stmt);
            }
        }
    }
}
// Close connection
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up & Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
         @keyframes fadeIn {
            from {
                opacity: 0; /* Start with opacity 0 */
            }
            to {
                opacity: 1; /* End with opacity 1 */
            }
        }

        /* Apply the fade-in animation to the desired elements */
        .form-container,
        .toolbar {
            animation: fadeIn 1.5s ease-in-out; /* Apply the fade-in animation with 1.5s duration */
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
        }
        .form-container { 
            width: 360px; 
            padding: 10px; 
            bottom: 10px;
            background-color: rgba(255, 255, 255, 0.8); /* Make background semi-transparent */
            backdrop-filter: blur(10px); /* Apply blur effect inline with the background */
            border-radius: 15px; /* Add border radius */
            margin: 10px; /* Add margin */
        }

        .invalid-feedback {
            color: red; /* Color for error messages */
        }
        /* New styles for time display */
        #current-time {
            text-align: center; /* Center text horizontally */
            position: absolute; /* Position at the top of the page */
            width: 100%; /* Take full width of the page */
            top: 0; /* Align to the top */
            background-color: rgba(255, 255, 255, 0.8); /* Semi-transparent background */
            padding: 10px; /* Add padding for spacing */
        }
        
.wrapper {
    position: relative; /* Set parent container to relative */
}
.toolbar {
    position: absolute;
    bottom: 190px;
    left: 50%; /* Adjust this value to move the toolbar more to the left */
    transform: translateX(-50%);
    background-color: rgba(255, 255, 255, 0.8);
    backdrop-filter: blur(10px);
    color: #000;
    padding: 50px;
    width: 360px;
    z-index: 9999;
    border-radius: 15px;
    margin-top: 20px;
    border: 1px solid #ddd;
}


.toolbar ul {
    list-style-type: none;
    padding: 0;
    margin: 0;
}

.toolbar ul li {
    margin-bottom: 15px;
    position: relative;
}

.toolbar ul li a {
    color: #000;
    text-decoration: none;
    font-weight: bold;
}

.toolbar ul li a:hover {
    text-decoration: underline;
}


.toolbar ul li {
    position: absolute;
    display: inline-block;
    margin-right: 20px;
}

.toolbar .home-icon {
    width: 20px;
    height: auto;
    margin-right: 5px;
}

/* Adjust the positioning of the "Home" and "About" links */
.toolbar ul li:nth-child(1) {
    top: 10%; /* Position the "Home" link at the top */
    margin-left: -9%
}

.toolbar ul li:nth-child(2) {
    top: 40px; /* Position the "About" link below the "Home" link */
    margin-left: -9%

}

.toolbar ul li:nth-child(3) {
    top: 60px; /* Position the "About" link below the "Home" link */
    margin-left: -9%

}


    </style>
    <!-- Include the Google reCAPTCHA API script -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
<!-- Display current time -->
<div id="current-time">
    <!-- The JavaScript code will update this content -->
</div>

<!-- Cookie Acceptance Modal -->
<div class="modal fade" id="cookieModal" tabindex="-1" role="dialog" aria-labelledby="cookieModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cookieModalLabel">Cookie Consent</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                This website uses cookies to ensure you get the best experience on our website.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="declineCookies()">Decline</button>
                <button type="button" class="btn btn-primary" onclick="acceptCookies()">Accept</button>
            </div>
        </div>
    </div>
</div>


<div class="wrapper">
    <div class="form-container">
        <h2>Sign Up</h2>
        <p>Please fill this form to create an account.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="hidden" name="register">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="text" name="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
                <span class="invalid-feedback"><?php echo $email_err; ?></span>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
                <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
            </div>
            <!-- Add reCAPTCHA checkbox here -->
            <div class="form-group">
                <div class="g-recaptcha" data-sitekey="ENTER_DATASITEKEY"></div>
                <span class="invalid-feedback"><?php echo $captcha_err; ?></span>
            </div>
            <!-- End of reCAPTCHA checkbox -->
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Sign Up">
                <input type="reset" class="btn btn-secondary ml-2" value="Reset">
            </div>
        </form>
    </div>
    <div class="wrapper">
    <div class="login-container">
    <div class="form-container">
        <h2>Login</h2>
        <p>Please fill in your credentials to log in.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="hidden" name="login">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control">
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
            </div>
            <p>Forgot your password? <a href="passowrd_reset.php">Reset Password</a></p>

        </form>
    </div>
</div>
<div class="toolbar">
    <ul>
        <li>
            <a href="https://localhost/projekti/url-short.php" style="display: flex; align-items: center;">
                <img class="home-icon" src="https://cdn-icons-png.flaticon.com/512/7347/7347153.png" alt="URL-Shortener
" style="margin-right: 5px;">
                
URL-Shortener

            </a>
        </li>
        <li>
            <a href="https://localhost/projekti/glauno.php" style="display: flex; align-items: center;">
                <img class="home-icon" src="https://cdn-icons-png.freepik.com/512/9073/9073243.png" alt="Home" style="margin-right: 5px;">
                Home
            </a>
        </li>
        <li style="margin-top: 10px;">
            <a href="https://localhost/projekti/about.php" style="display: flex; align-items: center;">
                <img class="home-icon" src="https://cdn-icons-png.freepik.com/512/6325/6325609.png" alt="About" style="margin-right: 5px;">
                About
            </a>
        </li>
    </ul>
</div>



<!-- jQuery CDN -->
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

<!-- jQuery and Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    $(document).ready(function(){
        // Check if the cookie has been accepted
        if (!getCookie("cookieAccepted")) {
            $('#cookieModal').modal('show'); // Show the cookie modal if not accepted
        }
    });

    function acceptCookies() {
        $('#cookieModal').modal('hide'); // Hide the cookie modal
        setCookie("cookieAccepted", true, 30); // Set the cookie for 30 days
    }

    function declineCookies() {
        $('#cookieModal').modal('hide'); // Hide the cookie modal
        // You may add additional functionality here for handling declined cookies
    }

    function setCookie(name, value, days) {
        var expires = "";
        if (days) {
            var date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = "; expires=" + date.toUTCString();
        }
        document.cookie = name + "=" + (value || "") + expires + "; path=/";
    }

    function getCookie(name) {
        var nameEQ = name + "=";
        var ca = document.cookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) === ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
    }
</script>
</body>
</html>
