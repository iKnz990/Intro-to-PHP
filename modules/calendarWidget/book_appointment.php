<?php
include '../../core/header.php';
include 'core/functions.php';

$services = getAllServices();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $serviceIds = $_POST['service_id'];
    $userName = htmlspecialchars($_POST['user_name'], ENT_QUOTES, 'UTF-8');
    $userEmail = htmlspecialchars($_POST['user_email'], ENT_QUOTES, 'UTF-8');
    $bookingDate = $_POST['booking_date'];
    $bookingTime = $_POST['booking_time'];

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
                            data-price="<?= $service['price'] ?>">
                        <?= $formattedServiceInfo ?>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
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
            <label class="form-label">Total Price:</label>
            <span id="price">$0.00</span>
        </div>


        <div class="form-group">
            <input class="form-button" type="submit" value="Book Now">
        </div>
    </form>
</div>

<script>
    // Get all checkboxes
    const checkboxes = document.querySelectorAll('input[type="checkbox"][name="service_id[]"]');

    // Calculate total price on service selection
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            let total = 0;
            checkboxes.forEach(box => {
                if (box.checked) {
                    total += parseFloat(box.getAttribute('data-price'));
                }
            });
            document.getElementById('price').textContent = `$${total.toFixed(2)}`;
        });
    });
</script>


<?php
include '../../core/footer.php';
?>