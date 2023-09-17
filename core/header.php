<?php 
include 'config.php';
include 'functions.php';

// Determine the base URL based on the environment
if ($_SERVER['HTTP_HOST'] == "localhost" || $_SERVER['HTTP_HOST'] == "127.0.0.1") {
    $baseURL = "/WDV341/"; // For XAMPP environment
} else {
    $baseURL = "/"; // For live subdomain
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>WDV341 Class Assignments</title>
        <link rel="icon" type="image/x-icon" href="favicon.png">
        <meta charset="utf-8">
        <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
        <meta content="WDV341 Class Assignments" name="description">
        <meta content="width=device-width, initial-scale=1" name="viewport">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=DM+Sans&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

        <link href="<?= $baseURL ?>assets/css/style.css" rel="stylesheet">
    </head>
    <nav class="navigation">
        <a href="<?php echo $baseURL; ?>">
                <h3>Home</h3>
            </a>
            <a href="<?php echo $baseURL; ?>modules/definitions/">
                <h3>1-2</h3>
            </a>
            <a href="<?php echo $baseURL; ?>modules/phpBasics/">
                <h3>2-1</h3>
            </a>
            <a href="<?php echo $baseURL; ?>modules/phpFunctions/">
                <h3>4-1</h3>
            </a>
            <a href="<?php echo $baseURL; ?>modules/form/">
                <h3>5-1</h3>
            </a>
            <div class="dropdown">
                <a href="<?php echo $baseURL; ?>modules/calendarWidget/">                 
                <h3>Calendar</h3>
                </a>
                <div class="dropdown-content">
                    <a href="<?php echo $baseURL; ?>modules/calendarWidget/book_service.php">Book a Date</a>
                    <a href="<?php echo $baseURL; ?>modules/calendarWidget/admin/">Admin Panel</a>
                </div>
            </div>
            <div class="dropdown">
                <a href="<?php echo $baseURL; ?>modules/youtubeVideo/">                 
                <h3>Video Elements</h3>
                </a>
                <div class="dropdown-content">
                    <a href="<?php echo $baseURL; ?>modules/youtubeVideo/">YouTube Embed</a>
                    <a href="<?php echo $baseURL; ?>modules/html5Video/">HTML5</a>
                </div>
            </div>
    </nav>

