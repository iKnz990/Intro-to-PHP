<?php
// Include database connection and other necessary files
include '../../../core/header.php';
include '../core/functions.php';

// Get the JSON data from the AJAX request
$bookingJSON = file_get_contents('php://input');
$bookingObj = json_decode($bookingJSON, true);
error_log("Received data: " . print_r($bookingJSON, true));

// Check if the required fields are set in the received object
if (!isset($bookingObj['bookingId']) || 
    !isset($bookingObj['price']) || 
    !isset($bookingObj['userName']) || 
    !isset($bookingObj['userEmail']) || 
    !isset($bookingObj['bookingDate']) || 
    !isset($bookingObj['bookingTime'])) {
    echo 'Incomplete data received';
    exit;
}

// Update the database using the new data
if (updateBooking($bookingObj)) {
    echo 'Booking updated successfully';
} else {
    echo 'Failed to update booking';
}
