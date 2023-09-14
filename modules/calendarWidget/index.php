<?php
include '../../core/header.php';
include 'core/functions.php';
$services = getAllServices();
?>
<div class="content">
<h2>Book a Service</h2>

<form action="book_service.php" method="post">
    <label for="service">Choose a service:</label>
    <select name="service_id" id="service">
        <?php foreach ($services as $service): ?>
            <option value="<?= $service['service_id'] ?>"><?= $service['service_name'] ?> (<?= $service['service_duration'] ?> mins)</option>
        <?php endforeach; ?>
    </select><br><br>

    <label for="username">Your Name:</label>
    <input type="text" id="username" name="user_name" required><br><br>

    <label for="email">Your Email:</label>
    <input type="email" id="email" name="user_email" required><br><br>

    <label for="date">Date:</label>
    <input type="date" id="date" name="booking_date" required><br><br>

    <label for="time">Time:</label>
    <input type="time" id="time" name="booking_time" required><br><br>

    <input type="submit" value="Book Now">
</form>
</div>
<?php
include '../../core/footer.php';
?>
