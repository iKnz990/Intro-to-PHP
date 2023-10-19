<?php
include '../../core/header.php';
include 'core/functions.php';

$services = getAllServices();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $serviceIds = $_POST['service_id'];
    $userName = htmlspecialchars($_POST['user_name'], ENT_QUOTES, 'UTF-8');
    $userEmail = htmlspecialchars($_POST['user_email'], ENT_QUOTES, 'UTF-8');
    $bookingDate = $_POST['booking_date'];
    $bookingTime = formatTime($_POST['booking_time']);

    // Calculate total price
    $totalPrice = 0;
    foreach ($serviceIds as $serviceId) {
        $price = getServicePriceById($serviceId);
        $totalPrice += $price;
    }

    if (checkAvailability($serviceIds, $bookingDate, $bookingTime)) {
        // Book the service and get the last inserted booking ID
        $bookingId = bookService($serviceIds, $userName, $userEmail, $bookingDate, $bookingTime, $totalPrice);

        echo "Appointment booked successfully!";
    } else {
        echo "The selected slot is already booked. Please choose a different time.";
    }
}



?>

<h2>Book an Appointment</h2>

<div class="form-container">
    <form action="book_appointment.php" method="post">
        <div class="form-group">
            <label class="form-label" for="service">Choose your services:</label>
            <!-- Services -->
            <div class="form-group">
                <?php foreach ($services as $service): ?>
                    <?php if ($service['service_id'] != -1): // Skip the placeholder service ?>
                        <?php
                        $durationInMinutes = $service['service_duration'];
                        $hours = floor($durationInMinutes / 60);
                        $minutes = $durationInMinutes % 60;

                        if ($hours > 0) {
                            $formattedServiceInfo = sprintf(
                                '%s (%d hours %d minutes, $%.2f)',
                                $service['service_name'],
                                $hours,
                                $minutes,
                                $service['price']
                            );
                        } else {
                            $formattedServiceInfo = sprintf(
                                '%s (%d minutes, $%.2f)',
                                $service['service_name'],
                                $minutes,
                                $service['price']
                            );
                        }
                        ?>
                        <input type="checkbox" name="service_id[]" value="<?= $service['service_id'] ?>"
                            data-price="<?= $service['price'] ?>" data-duration="<?= $service['service_duration'] ?>">

                        <?= $formattedServiceInfo ?>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label" for="username">Your Name:</label>
            <input class="form-input" type="text" id="username" name="user_name"
                value="<?php echo isset($_SESSION['username']) ? $_SESSION['username'] : ''; ?>" required>
        </div>

        <div class="form-group">
            <label class="form-label" for="email">Your Email:</label>
            <input class="form-input" type="email" id="email" name="user_email"
                value="<?php echo isset($_SESSION['email']) ? $_SESSION['email'] : ''; ?>" required>
            <!-- Sets Email based on the session -->
        </div>



        <div class="form-group">
            <label class="form-label" for="date">Date:</label>
            <input class="form-input" type="date" id="date" name="booking_date" required>
        </div>

        <div class="form-group">
            <label class="form-label" for="time">Time:</label>
            <select class="form-input" id="time" name="booking_time" required>
                <!--  This dropdown populates dynamically with JavaScript -->
            </select>
        </div>

        <div class="form-group">
            <label class="form-label">Total Price:</label>
            <span id="price">$0.00</span>
        </div>
        <div class="form-group">
            <label class="form-label">Total Time:</label>
            <span id="totalTime">0 minutes</span>
        </div>
        <div class="form-group">
            <label class="form-label">Ending Time:</label>
            <span id="endTime">N/A</span>
        </div>
        <div class="form-group">
            <input class="form-button" type="submit" value="Book Now">
        </div>
    </form>
</div>

<script>

    // Function to populate time slots in the dropdown
    function populateTimeSlots() {
        const timeSelect = document.getElementById('time');
        for (let i = 9; i <= 21; i++) { // Assuming time slots from 9 AM to 9 PM
            const option = document.createElement('option');
            option.value = `${i}:00`; // 24-hour format for the value
            const ampm = i >= 12 ? 'PM' : 'AM';
            const hour12 = i > 12 ? i - 12 : i;
            option.text = `${hour12}:00 ${ampm}`; // 12-hour format with AM/PM for the text
            timeSelect.appendChild(option);
        }
    }


    // Function to calculate total price, total time, and ending time
    function calculateTotal() {
        let total = 0;
        let totalTime = 0;
        checkboxes.forEach(box => {
            if (box.checked) {
                total += parseFloat(box.getAttribute('data-price'));
                totalTime += parseInt(box.getAttribute('data-duration'));
            }
        });
        document.getElementById('price').textContent = `$${total.toFixed(2)}`;
        document.getElementById('totalTime').textContent = `${totalTime} minutes`;

    // Calculate ending time based on selected time and total duration
    const selectedTime = document.getElementById('time').value;
    if (selectedTime) {
        const [hour, minute] = selectedTime.split(':').map(Number);
        const endTime = new Date(0, 0, 0, hour, minute + totalTime);
        const endHour24 = endTime.getHours();
        const endMinute = endTime.getMinutes();

        // Convert to 12-hour format with AM/PM
        const ampm = endHour24 >= 12 ? 'PM' : 'AM';
        const endHour12 = endHour24 > 12 ? endHour24 - 12 : (endHour24 === 0 ? 12 : endHour24);

        document.getElementById('endTime').textContent = `${endHour12}:${endMinute < 10 ? '0' : ''}${endMinute} ${ampm}`;
    }
}

    // Populate time slots when the page loads
    document.addEventListener('DOMContentLoaded', populateTimeSlots);

    // Get all checkboxes
    const checkboxes = document.querySelectorAll('input[type="checkbox"][name="service_id[]"]');

    // Attach event listeners
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', calculateTotal);
    });
    document.getElementById('time').addEventListener('change', calculateTotal);

</script>


<?php
include '../../core/footer.php';
?>