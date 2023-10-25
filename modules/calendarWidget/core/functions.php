<?php

// Function to fetch all services
function getAllServices()
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT service_id, service_name, service_duration, description, price FROM services");
    $stmt->execute();
    return $stmt->fetchAll();
}

// Function to book a service// Function to book a service
function bookService($serviceIds, $userName, $userEmail, $bookingDate, $bookingTime, $price)
{
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO bookings (user_name, user_email, booking_date, booking_time, price) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([htmlspecialchars($userName, ENT_QUOTES, 'UTF-8'), htmlspecialchars($userEmail, ENT_QUOTES, 'UTF-8'), $bookingDate, $bookingTime, $price]);

    $bookingId = $pdo->lastInsertId();

    $stmt = $pdo->prepare("INSERT INTO booking_services (booking_id, service_id) VALUES (?, ?)");
    foreach ($serviceIds as $serviceId) {
        $stmt->execute([$bookingId, $serviceId]);
    }

    return $bookingId; // Return the last inserted ID
}


// Function to get the price of a service by its ID
function getServicePriceById($service_id)
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT price FROM services WHERE service_id = ?");
    $stmt->execute([$service_id]);
    $result = $stmt->fetch();
    return $result ? $result['price'] : 0;
}


// Function to check availability
function checkAvailability($serviceIds, $booking_date, $booking_time)
{
    global $pdo;
    $available = true;

    foreach ($serviceIds as $serviceId) {
        $stmt = $pdo->prepare("SELECT bookings.* FROM bookings 
                                JOIN booking_services ON bookings.booking_id = booking_services.booking_id
                                WHERE booking_services.service_id = ? AND bookings.booking_date = ? AND bookings.booking_time = ?");
        $stmt->execute([$serviceId, $booking_date, $booking_time]);

        if ($stmt->rowCount() > 0) {
            $available = false;
            break;
        }
    }

    return $available;
}


// Fetch all bookings based on sort order
function getAllBookings($sort_order = 'asc')
{
    global $pdo;

    $query = "SELECT bookings.*, GROUP_CONCAT(services.service_name ORDER BY services.service_name ASC) as service_names,
              GROUP_CONCAT(services.service_duration ORDER BY services.service_name ASC) as service_durations,
              GROUP_CONCAT(services.service_id ORDER BY services.service_name ASC) as service_ids
              FROM bookings 
              JOIN booking_services ON bookings.booking_id = booking_services.booking_id 
              JOIN services ON booking_services.service_id = services.service_id";

    if ($sort_order === 'desc') {
        $query .= " GROUP BY bookings.booking_id ORDER BY booking_date DESC, booking_time";
    } elseif ($sort_order === 'past') {
        $query .= " WHERE booking_date < CURDATE() GROUP BY bookings.booking_id ORDER BY booking_date, booking_time";
    } elseif ($sort_order === 'approaching') {
        $query .= " WHERE booking_date >= CURDATE() AND booking_date <= DATE_ADD(CURDATE(), INTERVAL 3 DAY) GROUP BY bookings.booking_id ORDER BY booking_date, booking_time";
    } else {
        $query .= " GROUP BY bookings.booking_id ORDER BY booking_date ASC, booking_time";
    }

    $stmt = $pdo->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll();
}

function updateBooking($bookingObj) {
    global $pdo;

    // Check if the required fields are set
    if (!isset($bookingObj['bookingId']) || 
        !isset($bookingObj['price']) || 
        !isset($bookingObj['userName']) || 
        !isset($bookingObj['userEmail']) || 
        !isset($bookingObj['bookingDate']) || 
        !isset($bookingObj['bookingTime'])) {
        return false;
    }

    try {
        // Start transaction
        $pdo->beginTransaction();

        // Prepare and execute the main UPDATE query
        $sql = "UPDATE bookings SET 
                price = :price, 
                user_name = :userName, 
                user_email = :userEmail, 
                booking_date = :bookingDate, 
                booking_time = :bookingTime
                WHERE booking_id = :bookingId";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':price', $bookingObj['price'], PDO::PARAM_STR);
        $stmt->bindParam(':userName', $bookingObj['userName'], PDO::PARAM_STR);
        $stmt->bindParam(':userEmail', $bookingObj['userEmail'], PDO::PARAM_STR);
        $stmt->bindParam(':bookingDate', $bookingObj['bookingDate'], PDO::PARAM_STR);
        $stmt->bindParam(':bookingTime', $bookingObj['bookingTime'], PDO::PARAM_STR);
        $stmt->bindParam(':bookingId', $bookingObj['bookingId'], PDO::PARAM_INT);
        $stmt->execute();

        // Delete old services for this booking
        $stmt = $pdo->prepare("DELETE FROM booking_services WHERE booking_id = ?");
        $stmt->execute([$bookingObj['bookingId']]);

        // Insert new services for this booking
        $stmt = $pdo->prepare("INSERT INTO booking_services (booking_id, service_id) VALUES (?, ?)");
        foreach ($bookingObj['services'] as $serviceId) {
            $stmt->execute([$bookingObj['bookingId'], $serviceId]);
        }

        // Commit the transaction
        $pdo->commit();
        error_log("Transaction committed.");

        return true;
    } catch (Exception $e) {
        // Rollback the transaction in case of an error
        $pdo->rollback();
        error_log("Failed to update booking: " . $e->getMessage());
        return false;
    }
}
