<?php
// Function to check if the user is accessing from Spain
function isUserInSpain($ip) {
    // Assuming IP geolocation is implemented correctly, return true if the user is in Spain
    return true; // Placeholder, replace with actual logic
}

// Function to check if the user's IP address belongs to a known VPN provider
function isIPFromVPN($ip) {
    // Assuming VPN detection is implemented correctly, return true if user is using a VPN
    return false; // Placeholder, replace with actual logic
}

// Function to check if a message contains spam
function isSpam($message) {
    // Add your spam detection logic here
    // For demonstration, let's assume any message containing "spam" in the message is considered spam
    return strpos($message, 'spam') !== false;
}

// Function to store spam reports in MySQL database
function storeSpamReport($conn, $senderId, $message) {
    // Your existing implementation remains the same
}

// Function to get country code based on IP address
function iptocountry($ip) {
    $numbers = preg_split( "/\./", $ip);
    include("ip_files/".$numbers[0].".php");
    $code = ($numbers[0] * 16777216) + ($numbers[1] * 65536) + ($numbers[2] * 256) + ($numbers[3]);
    foreach($ranges as $key => $value){
        if($key <= $code){
            if($ranges[$key][0] >= $code){
                return $ranges[$key][1];
            }
        }
    }
    return "unknown";
}

// Function to check if a message contains cursing
function containsCursing($message) {
    // Add your cursing filter logic here
    // For demonstration, let's assume any message containing common curse words is considered cursing
    $curseWords = array("curse1", "curse2", "curse3"); // Add your list of curse words here
    foreach ($curseWords as $curse) {
        if (stripos($message, $curse) !== false) {
            return true;
        }
    }
    return false;
}

// Read JSON file
$jsonData = file_get_contents('C:/xampp/htdocs/projekti/jshonspammsg.json');

// Decode JSON data to associative array
$messages = json_decode($jsonData, true);

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

// Array to store message history for spam detection
$messageHistory = array();

// Loop through messages
foreach ($messages as $message) {
    $senderId = $message['sender_id'];
    $messageContent = $message['message'];
    
    // Check if message is spam
    if (isSpam($messageContent) || containsCursing($messageContent)) {
        // Insert data into MySQL table for spam reports
        storeSpamReport($conn, $senderId, $messageContent);
        // Additional actions you may take for blocking, like notifying moderators
        // You can implement more advanced actions here, such as banning the user from future messages
    }
    
    // Add message to message history for spam detection
    if (!isset($messageHistory[$senderId])) {
        $messageHistory[$senderId] = array();
    }
    $messageHistory[$senderId][] = array(
        "message" => $messageContent,
        "timestamp" => time() // Store current timestamp
    );
    
    // Check for message repetition (3 times or more in 10 minutes)
    $messageCount = count($messageHistory[$senderId]);
    if ($messageCount >= 3) {
        $lastMessageTime = $messageHistory[$senderId][$messageCount - 1]['timestamp'];
        $firstMessageTime = $messageHistory[$senderId][$messageCount - 3]['timestamp'];
        if (($lastMessageTime - $firstMessageTime) <= 600) { // 10 minutes = 600 seconds
            // Block user for spamming
            echo "User with ID $senderId is blocked for spamming.\n";
            // You can implement additional actions here, such as banning the user
        }
    }
}

// Close MySQL connection
mysqli_close($conn);

// Check user's eligibility and display appropriate content
$userIP = $_SERVER['REMOTE_ADDR']; // Get the user's IP address
if (!isUserInSpain($userIP) || isIPFromVPN($userIP)) {
    // User is not in Spain or using a VPN
    // Deny access to the system
    echo "<!DOCTYPE html>
<html lang=\"en\">
<head>
<meta charset=\"UTF-8\">
<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
<title>Access Denied</title>
<style>
    body {
        font-family: Arial, sans-serif;
        text-align: center;
    }
    .denied-message {
        margin-top: 100px;
        font-size: 24px;
        color: red;
    }    
</style>
</head>
<body>
    <h1>Access Denied</h1>
    <p class=\"denied-message\">Sorry, access to the website is denied.</p>
</body>
</html>";
} else {
    // User meets criteria, display modified WhatsApp group link
    $whatsappGroupLink = "https://example.com/whatsapp-group"; // Original WhatsApp group link
    $modifiedLink = modifyWhatsAppLink($whatsappGroupLink);
    
    // Check if dark mode is enabled
    $darkMode = isset($_COOKIE['dark_mode']) && $_COOKIE['dark_mode'] === 'enabled';
    
    // Set CSS variables based on dark mode
    $backgroundColor = $darkMode ? '#333' : '#f2f2f2';
    $textColor = $darkMode ? '#f2f2f2' : '#333';
    
    echo "<!DOCTYPE html>
<html lang=\"en\">
<head>
<meta charset=\"UTF-8\">
<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
<title>WhatsApp Group</title>
<style>
body {
    font: 14px sans-serif;
    background-image: url('https://mrwallpaper.com/images/hd/4k-beach-nature-view-s0jgy1y9poz5zg54.jpg');
    background-size: cover;
    background-repeat: no-repeat;
    background-attachment: fixed;
    text-align: center;
}

    .whatsapp-link {
        margin-top: 100px;
        font-size: 24px;
    }
</style>
</head>
<body>    
    <input type=\"checkbox\" id=\"dark-mode-switch\" onchange=\"toggleDarkMode()\" ";
    if ($darkMode) {
        echo "checked";
    }
    echo ">";
    
    // Now let's add the QR codes section
    echo '<!DOCTYPE html>
    <html lang="en">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
    .qr-code-container {
        display: flex;
        justify-content: center;
        margin-top: 50px;
    }
    .qr-code {
        margin: 20px;
        border: 2px solid $textColor;
        border-radius: 10px;
        padding: 10px;
        background-color: rgba(255, 255, 255, 0.7); /* Transparent background */
    }
    .qr-code img {
        width: 200px;
        height: 200px;
    }
    </style>
    </head>
    <body>
    <div class="qr-code-container">
    <div class="qr-code">
        <img src="website.png" alt="Facebook QR Code">
        <p>Scan Facebook QR Code</p>
    </div>
    <div class="qr-code">
        <img src="instagram.png" alt="Instagram QR Code">
        <p>Scan Instagram QR Code</p>
    </div>
    <div class="qr-code img">
        <img src="facebook.png" alt="Website QR Code">
        <p>Scan Website QR Code</p>
    </div>
    </div>
    </body>
    </html>';
    
    // Now let's add the styled form
    echo '<!-- Form for Contact -->
    <div class="container">
      <h1>FormSubmit Demo</h1>
      <form target="_blank" action="https://formsubmit.co/YOUR_FORUMSUBMIT_KEY " method="POST">
        <div class="form-group">
            <div class="form-row">
                <div class="col">
                    <input type="text" name="name" class="form-control" placeholder="Full Name" required>
                </div>
                <div class="col">
                    <input type="email" name="email" class="form-control" placeholder="Email" required>
                </div>
            </div>
        </div>
        <div class="form-group">
            <textarea placeholder="Your Message" class="form-control" name="message" rows="10" required></textarea>
        </div>
        <button type="submit" class="btn btn-lg btn-dark btn-block">Submit Form</button>
    </form>
    
    </div>
    <!-- End of Form -->';
}

// Function to modify WhatsApp group link
function modifyWhatsAppLink($link) {
    // Implement logic to modify the link
    // For demonstration, this function will append a query parameter
    return $link . "?access=123";
}
?>

<script>
function toggleDarkMode() {
    const darkModeSwitch = document.getElementById('dark-mode-switch');
    if (darkModeSwitch.checked) {
        document.body.style.backgroundColor = '#333';
        document.body.style.color = '#f2f2f2';
        document.querySelectorAll('.qr-code').forEach(qrCode => {
            qrCode.style.backgroundColor = '#333';
            qrCode.style.color = '#f2f2f2';
        });
    } else {
        document.body.style.backgroundColor = '#f2f2f2';
        document.body.style.color = '#333';
        document.querySelectorAll('.qr-code').forEach(qrCode => {
            qrCode.style.backgroundColor = '#f2f2f2';
            qrCode.style.color = '#333';
        });
    }
}
</script>
