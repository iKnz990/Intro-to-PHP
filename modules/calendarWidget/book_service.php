<?php
include '../../core/header.php';
include 'core/functions.php';

$services = getAllServices();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $serviceId = $_POST['service_id'];
    $userName = $_POST['user_name'];
    $userEmail = $_POST['user_email'];
    $bookingDate = $_POST['booking_date'];
    $bookingTime = $_POST['booking_time'];

    if (checkAvailability($serviceId, $bookingDate, $bookingTime)) {
        bookService($serviceId, $userName, $userEmail, $bookingDate, $bookingTime);
        echo "Service booked successfully!";
    } else {
        echo "The selected slot is already booked. Please choose a different time.";
    }
}
?>
<h2>Book a Service</h2>

<div class="form-container">
    <form action="book_service.php" method="post">
        <div class="form-group">
            <label class="form-label" for="service">Choose a service:</label>
            <select class="form-select" name="service_id" id="service">
                <?php foreach ($services as $service): ?>
                    <?php if ($service['service_id'] != -1): // Skip the placeholder service ?>
                    <option value="<?= $service['service_id'] ?>"><?= $service['service_name'] ?> (<?= $service['service_duration'] ?> mins)</option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label class="form-label" for="username">Your Name:</label>
            <input class="form-input" type="text" id="username" name="user_name" required>
        </div>

        <div class="form-group">
            <label class="form-label" for="email">Your Email:</label>
            <input class="form-input" type="email" id="email" name="user_email" required>
        </div>

        <div class="form-group">
            <label class="form-label" for="date">Date:</label>
            <input class="form-input" type="date" id="date" name="booking_date" required>
        </div>

        <div class="form-group">
            <label class="form-label" for="time">Time:</label>
            <input class="form-input" type="time" id="time" name="booking_time" required>
        </div>

        <div class="form-group">
            <input class="form-button" type="submit" value="Book Now">
        </div>
    </form>
</div>


<?php
include '../../core/footer.php';
?>
