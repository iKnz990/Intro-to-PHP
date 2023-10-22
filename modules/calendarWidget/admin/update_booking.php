<?php
// Include database connection and other necessary files
include '../../../core/header.php';
include '../core/functions.php';

// Get the JSON data from the AJAX request
$bookingJSON = file_get_contents('php://input');
$bookingObj = json_decode($bookingJSON, true);

// Update the database using the new data
if (updateBooking($bookingObj)) {
    echo 'Booking updated successfully';
} else {
    echo 'Failed to update booking';
}
?>
