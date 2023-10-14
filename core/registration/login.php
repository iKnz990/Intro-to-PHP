<?php
include '../config.php';


// Function to authenticate user
function authenticateUser($username, $password)
{
    global $pdo;

    // Check for excessive login attempts
    if (!checkLoginAttempts($username)) {
        return ['status' => false, 'message' => 'Too many login attempts. Please wait 15 minutes and try again.'];
    }

    $stmt = $pdo->prepare("SELECT user_id, password, role FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row && verifyPassword($password, $row['password'])) {
        $_SESSION['role'] = $row['role'];
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;
        return ['status' => true, 'message' => 'Login successful!'];
    } else {
        // Record the failed login attempt
        recordLoginAttempt(null, $username);
        return ['status' => false, 'message' => 'Invalid username or password.'];
    }
}

// If login form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $username = sanitizeInput($_POST['username']);
    $password = $_POST['password']; // Don't sanitize password input to avoid altering characters

    $result = authenticateUser($username, $password);
    if ($result['status']) {
        header("Location: ../../index.php"); // Redirect after login
        exit;
    } else {
        $errorMessage = $result['message'];
    }
}

?>