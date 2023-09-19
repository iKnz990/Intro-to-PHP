<?php

// Function to fetch all services
function getAllServices() {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM services");
    $stmt->execute();
    return $stmt->fetchAll();
}

// Function to book a service
function bookService($service_id, $user_name, $user_email, $booking_date, $booking_time) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO bookings (service_id, user_name, user_email, booking_date, booking_time) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$service_id, htmlspecialchars($user_name, ENT_QUOTES, 'UTF-8'), htmlspecialchars($user_email, ENT_QUOTES, 'UTF-8'), $booking_date, $booking_time]);
}

// Function to check availability
function checkAvailability($service_id, $booking_date, $booking_time) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM bookings WHERE service_id = ? AND booking_date = ? AND booking_time = ?");
    $stmt->execute([$service_id, $booking_date, $booking_time]);
    return $stmt->rowCount() == 0; // return true if available, false otherwise
}

// Fetch all bookings
function getAllBookings() {
    global $pdo;
    $stmt = $pdo->prepare("SELECT bookings.*, services.service_name, services.service_duration FROM bookings JOIN services ON bookings.service_id = services.service_id ORDER BY booking_date, booking_time");
    $stmt->execute();
    return $stmt->fetchAll();
}

?>
