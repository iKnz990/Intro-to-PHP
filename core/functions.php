<?php
// Determine the base URL based on the environment
define('BASE_URL', ($_SERVER['HTTP_HOST'] == "localhost" || $_SERVER['HTTP_HOST'] == "127.0.0.1" || $_SERVER['HTTP_HOST'] == "nocturnalmarketing.local") ? "/WDV341/" : "/");
// Define the root directory for the project
define('ROOT_DIR', $_SERVER['DOCUMENT_ROOT'] . '/WDV341/');


// *****2-1: PHP Basics***** \\
// Create a variable called yourName.  Assign it a value of your name.
$yourName = "Alexander Kelly";

// Variables for 
$number1 = 10;
$number2 = 20;
$total = $number1 + $number2;

// PHP array
$phpArray = ['PHP', 'HTML', 'Javascript'];

//***** 4-1 PHP Functions *****\\
$phoneNumber = "7192130553"; // My Phone Number
$assignedCurrency = "8675309"; // Jenny's Bank Account Balance
$stringInfo = manipulateString("Hello DMACC"); // Test String

// Function to format timestamp string into mm/dd/yyyy
function formatDate_mm_dd_yyyy($timestamp)
{
    $unixTimestamp = strtotime($timestamp); // Convert to Unix timestamp
    return date("m/d/Y", $unixTimestamp);
}

// Function to format timestamp into dd/mm/yyyy for international dates
function formatDate_dd_mm_yyyy($timestamp)
{
    return date("d/m/Y", $timestamp);
}

// Function to manipulate string $stringInfo
function manipulateString($inputString)
{
    $length = strlen($inputString);
    $trimmedString = trim($inputString);
    $lowercaseString = strtolower($trimmedString);
    $containsDMACC = (stripos($lowercaseString, 'dmacc') !== false) ? 'Yes' : 'No';

    return [
        'length' => $length,
        'trimmed' => $trimmedString,
        'lowercase' => $lowercaseString,
        'containsDMACC' => $containsDMACC
    ];
}

// Function to format number as phone number
function formatPhoneNumber($number)
{
    return preg_replace("/^(\d{3})(\d{3})(\d{4})$/", "$1-$2-$3", $number);
}

// Function to format number as US currency
function formatCurrency($number)
{
    return '$' . number_format($number, 2);
}

// Function to format time
function formatTime($time)
{
    return date('h:i-A', strtotime($time));
}

// Function to sanitize output for XSS prevention
function sanitizeOutput($data)
{
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}
// Function to sanitize input for XSS prevention
function sanitizeInput($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
// Function to hash password
function hashPassword($password)
{
    return password_hash($password, PASSWORD_DEFAULT);
}
// Function to verify password
function verifyPassword($password, $hashedPassword)
{
    return password_verify($password, $hashedPassword);
}
// Function to generate token
function generateToken()
{
    if (!isset($_SESSION['token'])) {
        $_SESSION['token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['token'];
}
// Function to validate token
function validateToken($token)
{
    if (isset($_SESSION['token']) && $token === $_SESSION['token']) {
        unset($_SESSION['token']);
        return true;
    }
    return false;
}
// Function to check if user is logged in
function checkLoginAttempts($user_id)
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM login_attempts WHERE user_id = ? AND timestamp > DATE_SUB(NOW(), INTERVAL 15 MINUTE)");
    $stmt->execute([$user_id]);
    $attempts = $stmt->fetchColumn();
    return $attempts < 5; // Allow up to 5 attempts in 15 minutes
}
// Function to record login attempt
function recordLoginAttempt($userId = null, $username = null)
{
    global $pdo;
    try {
        if ($userId) {
            // Record successful login attempt
            $stmt = $pdo->prepare("INSERT INTO login_attempts (user_id, timestamp) VALUES (?, NOW())");
            $stmt->execute([$userId]);
        } else if ($username) {
            // Record failed login attempt
            $stmt = $pdo->prepare("INSERT INTO login_attempts (attempted_username, timestamp) VALUES (?, NOW())");
            $stmt->execute([$username]);
        }
    } catch (PDOException $e) {
        // Log the error or handle it as needed
        error_log($e->getMessage());
    }
}
// Function to start secure session
function secureSessionStart()
{
    $session_name = 'secure_session';
    $secure = false; // set to true if using HTTPS
    $httponly = true; // makes session cookie 'http-only'

    ini_set('session.use_only_cookies', 1);
    $cookieParams = session_get_cookie_params();
    session_set_cookie_params($cookieParams["lifetime"], $cookieParams["path"], $cookieParams["domain"], $secure, $httponly);
    session_name($session_name);
    session_start();
    session_regenerate_id(true); // regenerate session ID to prevent session fixation attacks
}

// Function to check logged in
function isLoggedIn()
{
    return isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
}
//Function to get logged in user for nav
function getLoggedInUser()
{
    if (isLoggedIn()) {
        return $_SESSION['username'];
    }
    return null;
}
// Function to log out
function logout()
{
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit;
}
// Function to Redirect to Index -- Only allows excluded files
function checkAuthorization()
{
    $currentFile = $_SERVER['PHP_SELF'];
    $excludedFiles = [
        BASE_URL . 'index.php',
        // root index.php
        BASE_URL . 'core/registration/userRegister.php',
        BASE_URL . 'core/registration/userLogin.php'
    ];

    if (!isLoggedIn() && !in_array($currentFile, $excludedFiles)) {
        header("Location: " . BASE_URL . "index.php"); // Redirect to project's root index.php
        exit;
    }
}

// Social Media Links
function getSocialMediaProfiles()
{
    return array(
        'Facebook' => 'https://www.facebook.com/rednaxelanoctis/',
        'Instagram' => 'https://www.instagram.com/nocturnalxmarketing/',
        'Discord' => 'https://discord.gg/wjQyjHwz',
        'GitHub' => 'https://github.com/iKnz990',
        'LinkedIn' => 'https://www.linkedin.com/in/alexander-kelly-4a801b245/'
    );
}
// Check User Role
function checkUserRole($requiredRole)
{
    if ($_SESSION['role'] !== $requiredRole) {
        header("Location: " . BASE_URL . "index.php?message=You do not have permission - Contact the Administrator.");
        exit;
    }
}


// *****End of the Line / Fill***** \\

?>