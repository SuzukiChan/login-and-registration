<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>URL Shortener</title>
    <link rel='stylesheet' href='css/style.css' />
    <style>
        @keyframes fadeIn {
            from {
                opacity: 0; /* Start with opacity 0 */
            }
            to {
                opacity: 1; /* End with opacity 1 */
            }
        }

        body {
            font: 14px sans-serif;
            background-image: url('https://mrwallpaper.com/images/hd/4k-beach-nature-view-s0jgy1y9poz5zg54.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: 0 0;
            animation: fadeIn 1s ease-in-out; /* Apply fade-in animation */
            color: #007bff; /* Changed color to blue */
            height: 900px;
            margin: 0;
            font-size: 15px;
        }

        .form_block {
            width: 400px;
            display: table;
            margin-top: 10%;
            margin-left: auto;
            margin-right: auto;
            background-color: rgba(0, 0, 0, .1);
            padding: 5px;
            border-radius: 5px;
        }

        .form_block > #title {
            background: #007bff; /* Changed to blue */
            padding: 10px;
            color: #fff;
            font-weight: bold;
            font-size: 20px;
            text-align: center;
            border-top-right-radius: 5px;
            border-top-left-radius: 5px;
            text-shadow: -1px -1px 0 rgba(0, 0, 0, .2);
        }

        .form_block > .body {
            background: #FFFFFF;
            padding: 10px;
        }

        input[type='text'] {
            height: 35px;
            width: 100%;
            margin-bottom: 10px;
            box-sizing: border-box;
            padding: 4px 5px;
            background: #ffffff; /* Changed background color to white */
            border: 1px solid #d0d0d0;
            border-radius: 3px;
            color: #000; /* Changed text color to black */
        }

        input[type='submit'] {
            height: 35px;
            box-sizing: border-box;
            padding: 4px 10px;
            background: #007bff; /* Changed to blue */
            color: #fff; /* Changed text color to white */
            font-weight: bold;
            border: 0;
            border-radius: 3px;
            cursor: pointer;
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
<?php
require_once(__DIR__ . '/core/url.Class.php');
$URLShortener = new URLShortener;
?>
<div id="current-time">
    <!-- The JavaScript code will update this content -->
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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>URL Shortener</title>
</head>
<body>
<?php
echo $URLShortener -> mainForm();
?>
</body>
</html>
