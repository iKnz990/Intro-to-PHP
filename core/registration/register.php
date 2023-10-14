<?php

// Function to register a new user
function registerUser($data)
{
    global $pdo;

    // Check if username or email already exists
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$data['username'], $data['email']]);
    $count = $stmt->fetchColumn();

    if ($count > 0) {
        return ['status' => false, 'message' => 'Username or email already exists.'];
    }

    // Hash the password
    $data['password'] = hashPassword($data['password']);

    // Insert the new user into the database with a role
    $stmt = $pdo->prepare("INSERT INTO users (username, password, email, role) VALUES (?, ?, ?, ?)");
    $stmt->execute([$data['username'], $data['password'], $data['email'], 'user']);


    return ['status' => true, 'message' => 'Registration successful!'];
}

// If registration form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $data = [
        'username' => sanitizeInput($_POST['username']),
        'password' => $_POST['password'],
        // Don't sanitize password input to avoid altering characters
        'email' => sanitizeInput($_POST['email'])
    ];

    $result = registerUser($data);
    if ($result['status']) {
        header("Location: userLogin.php?message=Registration Completed!"); // Redirect to login page after successful registration
        exit;
    } else {
        $errorMessage = $result['message'];
    }
}

?>