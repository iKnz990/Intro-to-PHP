<?php
include 'config.php';
include 'functions.php';

// Start Session
secureSessionStart();
checkAuthorization();

//Session Testing
//echo "<pre>";
//print_r($_SESSION);
//echo "</pre>";

// For the Errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

// If logout is triggered
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    logout();
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

    <link href="<?= BASE_URL ?>assets/css/style.css" rel="stylesheet">
</head>

<nav class="navigation">
    <a href="<?= BASE_URL ?>">
        <h3>Home</h3>
    </a>
    <?php if (isLoggedIn()): ?> <!-- If user is logged in, add class to body -->
        <a href="<?= BASE_URL ?>modules/definitions/">
            <h3>1-2</h3>
        </a>
        <a href="<?= BASE_URL ?>modules/phpBasics/">
            <h3>2-1</h3>
        </a>
        <a href="<?= BASE_URL ?>modules/phpFunctions/">
            <h3>4-1</h3>
        </a>
        <a href="<?= BASE_URL ?>modules/form/">
            <h3>5-1</h3>
        </a>
        <div class="dropdown">
            <a href="<?= BASE_URL ?>modules/calendarWidget/">
                <h3>Calendar</h3>
            </a>
            <div class="dropdown-content">
                <a href="<?= BASE_URL ?>modules/calendarWidget/book_service.php">Book a Date</a>
                <a href="<?= BASE_URL ?>modules/calendarWidget/admin/">Admin Panel</a>
            </div>
        </div>
        <div class="dropdown">
            <a href="<?= BASE_URL ?>modules/youtubeVideo/">
                <h3>Video Elements</h3>
            </a>
            <div class="dropdown-content">
                <a href="<?= BASE_URL ?>modules/youtubeVideo/">YouTube Embed</a>
                <a href="<?= BASE_URL ?>modules/html5Video/">HTML5</a>
            </div>
        </div>
        <div class="user-info">
            <h3>Hello,
                <?php echo getLoggedInUser(); ?>
            </h3>
            <h3><a href="<?= BASE_URL ?>?action=logout">Logout</a></h3>
        </div>
    <?php endif; ?>
</nav>