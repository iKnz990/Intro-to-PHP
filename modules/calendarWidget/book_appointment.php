<?php
include '../../core/header.php';
include 'core/functions.php';

$services = getAllServices();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $serviceId = $_POST['service_id'];
    $userName = htmlspecialchars($_POST['user_name'], ENT_QUOTES, 'UTF-8');
    $userEmail = htmlspecialchars($_POST['user_email'], ENT_QUOTES, 'UTF-8');
    $bookingDate = $_POST['booking_date'];
    $bookingTime = $_POST['booking_time'];

    if (checkAvailability($serviceId, $bookingDate, $bookingTime)) {
        bookService($serviceId, $userName, $userEmail, $bookingDate, $bookingTime);
        echo "Appointment booked successfully!";
    } else {
        echo "The selected slot is already booked. Please choose a different time.";
    }
}
//This is the code for the book_appointment.php page. It is the page that the user will see when they click on the book appointment button. It will display a form that will allow the user to book an appointment. The form will have a drop down menu that will allow the user to select a service. The form will also have a text box for the user to enter their name, a text box for the user to enter their email, a date picker for the user to select a date, and a time picker for the user to select a time. The form will also have a submit button that will allow the user to submit the form. The form will be submitted to the book_appointment.php page. The book_appointment.php page will check to see if the form has been submitted. If the form has been submitted, the book_appointment.php page will check to see if the selected time slot is available. If the selected time slot is available, the book_appointment.php page will book the appointment and display a success message. If the selected time slot is not available, the book_appointment.php page will display an error message.
?>

<h2>Book an Appointment</h2>

<div class="form-container">
    <form action="book_appointment.php" method="post">
        <div class="form-group">
            <label class="form-label" for="service">Choose a service:</label>
            <select class="form-select" name="service_id" id="service">
                <?php foreach ($services as $service): ?>
                    <?php if ($service['service_id'] != -1): // Skip the placeholder service ?>
                        <option value="<?= $service['service_id'] ?>">
                            <?= $service['service_name'] ?> (
                            <?= $service['service_duration'] ?> mins)
                        </option>
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