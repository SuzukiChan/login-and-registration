<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Link Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>About My First Project</title>
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
        .fadeIn {
            animation: fadeIn 1.5s ease-in-out; /* Apply the fade-in animation with 1.5s duration */
        }
        
        body {
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f8f8f8; /* Default white background */
            color: #333; /* Default black text */
            transition: background-color 0.5s, color 0.5s; /* Smooth transition for dark mode */
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff; /* Default white background for container */
            color: #333; /* Default black text for container */
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: background-color 0.5s, color 0.5s; /* Smooth transition for dark mode */
        }

        /* Dark mode styles */
        body.dark-mode {
            background-color: #000; /* Black background for dark mode */
            color: #fff; /* White text for dark mode */
        }

        body.dark-mode .container {
            background-color: #000; /* Black background for container in dark mode */
            color: #fff; /* White text for container in dark mode */
        }

        h1 {
            text-align: center;
            font-family: "Oswald", sans-serif;
            font-size: clamp(1.5rem, 1rem + 18vw, 15rem);
            font-weight: 700;
            text-transform: uppercase;
            margin: 0;
            color: var(--text-color);
        }

        p {
            margin-bottom: 20px;
            font-style: Arial;
        }

        ul, li {
            font-style: Arial;
        }

        /* CSS styles for About My First Project */
        section.wrapper {
            display: grid;
            place-content: center;
            background-color: var(--background-color);
            min-height: 100vh;
            font-family: "Oswald", sans-serif;
            font-size: clamp(1.5rem, 1rem + 18vw, 15rem);
            font-weight: 700;
            text-transform: uppercase;
            color: var(--text-color);
        }

        section.wrapper > div {
            grid-area: 1/1/-1/-1;
        }

        .top {
            clip-path: polygon(0% 0%, 100% 0%, 100% 48%, 0% 58%);
        }

        .bottom {
            clip-path: polygon(0% 60%, 100% 50%, 100% 100%, 0% 100%);
            color: transparent;
            background: -webkit-linear-gradient(177deg, black 53%, var(--text-color) 65%);
            background: linear-gradient(177deg, black 53%, var(--text-color) 65%);
            background-clip: text;
            -webkit-background-clip: text;
            transform: translateX(-0.02em);
        }
    </style>
</head>
<body>

<div class="container fadeIn">
    <!-- Use Bootstrap classes for styling -->
    <h1 class="display-4">About My First Project</h1>
    <p class="lead">Welcome to my first project! This project served as a learning experience for me to explore various aspects of web development, including PHP, JSON, JavaScript, CSS, and HTML.</p>

    <h2>Features Implemented:</h2>
    <ul>
        <li>Login and Registration Form: Implemented a secure login and registration system to allow users to create accounts and log in.</li>
        <li>Styling with CSS and HTML: Explored CSS and HTML styling techniques to enhance the visual appearance of the website.</li>
        <li>Contact Form with FormSubmit: Integrated a contact form using FormSubmit for easy submission and management of user inquiries.</li>
        <li>Counting Clock: Developed a counting clock feature to display real-time information to users.</li>
        <li>Dark Mode: Implemented a dark mode feature to provide users with a different viewing experience.</li>
        <li>Captcha for Registration Form: Implemented a captcha system to prevent spam registrations.</li>
        <li>...and more: Explored various other functionalities and experimented with different technologies.</li>
    </ul>

    <p>This project has been an exciting journey for me, allowing me to dive into the world of web development and learn new skills along the way. I look forward to continuing to explore and expand my knowledge in this field.</p>
    <p>Want to check out my GitHub profile? <a href="https://github.com/SuzukiChan" target="_blank">Click here</a>.</p>

    <!-- Dark Mode Toggle Button -->
    <button id="darkModeToggle">Toggle Dark Mode</button>
</div>

<!-- Include Bootstrap JavaScript dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- Dark Mode Script -->
<script>
    // Dark mode toggle function
    function toggleDarkMode() {
        const body = document.body;
        body.classList.toggle('dark-mode');
    }

    // Dark mode toggle button event listener
    document.getElementById('darkModeToggle').addEventListener('click', toggleDarkMode);
</script>

</body>
</html>
