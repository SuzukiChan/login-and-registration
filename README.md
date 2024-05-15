Login-and-registration

Welcome to my first php project! This repository contains code for a web application that offers enhanced user authentication with Captcha, seamless URL shortening, and additional features. Please read the following instructions to set up the project and replace the necessary keys.
Getting Started

To get started with this project, follow the steps below:
Prerequisites

Installation

    Clone this repository to your local machine:

    bash

    git clone https://github.com/your-username/project-name.git

    Create a database and import the SQL file provided in the database folder to set up the required tables.

    Rename config.example.php to config.php and replace YOUR_SECRET_KEY with your reCAPTCHA secret key. You can obtain this key by signing up for reCAPTCHA at Google reCAPTCHA.

    Replace YOUR_FORUMSUBMIT_KEY in the config.php file with the API key for FormSubmit. This key is required for submitting forms. You can obtain it by signing up at FormSubmit.

Usage

    Login and Registration: Navigate to login.php and register.php to access the login and registration pages. Users need to complete the Captcha verification to proceed.

    URL Shortener: Use the URL shortening functionality by providing a long URL to shorten.php. The application will generate a shortened URL for you.

    Form Submission: To submit a form, use the form provided in formsubmit.php. Fill out the required fields and submit the form. The data will be stored in the database.
