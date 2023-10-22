<?php
include '../../../core/header.php';
include '../core/functions.php';
include '../core/BookingClass.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sort_order = $_POST['sort_order'];
} else {
    $sort_order = 'approaching'; // Default sort order
}
$bookings = getAllBookings($sort_order);
$services = getAllServices();
$servicesJson = json_encode($services);

checkUserRole('admin');
?>


<div class="admin-container">
    <aside class="admin-nav">
        <h3>Navigation</h3>
        <ul>
            <li><a href="index.php">Dashboard</a></li>
            <li><a href="view_bookings.php">View Bookings</a></li>
            <li><a href="manage_services.php">Manage Services</a></li>
        </ul>
    </aside>
    <div class="content">
        <h2>View Bookings</h2>
        <form id="sortForm" method="post" action="">
            <select name="sort_order" id="sort_order">
                <option value="approaching">Approaching Appointments</option>
                <option value="past">Past Appointments</option>
                <option value="asc">Ascending</option>
                <option value="desc">Descending</option>
            </select>
        </form>

        <table border="1">
            <thead>
                <tr>
                    <th>Service Name</th>
                    <th>User Name</th>
                    <th>User Email</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Duration (mins)</th>
                    <th>End Time</th>
                    <th>Price</th>

                </tr>
            </thead>
            <tbody>
                <?php foreach ($bookings as $booking): ?>
                    <?php
                    // Create an instance of the Booking class
                    $bookingObj = new Booking(
                        $booking['booking_id'],
                        $booking['user_name'],
                        $booking['user_email'],
                        $booking['booking_date'],
                        $booking['booking_time'],
                        $booking['price']
                    );

                    // Convert the Booking object to JSON (if needed)
                    $bookingJSON = $bookingObj->toJSON();

                    // Fetch services for each booking
                    $stmt = $pdo->prepare("SELECT service_id FROM booking_services WHERE booking_id = ?");
                    $stmt->execute([$booking['booking_id']]);
                    $services = $stmt->fetchAll();

                    $currentDate = new DateTime('now', new DateTimeZone('UTC'));
                    $currentDate->setTime(0, 0, 0);
                    // Services
                    $serviceNames = $booking['service_names'] ? explode(',', $booking['service_names']) : [];
                    $serviceDurations = $booking['service_durations'] ? explode(',', $booking['service_durations']) : [];
                    $serviceIds = $booking['service_ids'] ? explode(',', $booking['service_ids']) : [];


                    $bookingDate = new DateTime($booking['booking_date'], new DateTimeZone('UTC'));
                    $bookingDate->setTime(0, 0, 0);

                    $interval = $currentDate->diff($bookingDate)->days;

                    $highlight = '';
                    if ($sort_order === 'past' && $bookingDate < $currentDate) {
                        $highlight = 'highlight-past';
                    } elseif ($sort_order === 'approaching' && $interval < 3 && $bookingDate >= $currentDate) {
                        $highlight = 'highlight-row';
                    }

                    ?>
                        <tr class="<?= $highlight ?>" data-booking-json='<?= $bookingJSON ?>'>
                        <td>
                            <?= implode(', ', $serviceNames) ?>
                        </td>
                        <td data-field="userName">
                            <?= $booking['user_name'] ?>
                        </td>
                        <td data-field="userEmail">
                            <?= $booking['user_email'] ?>
                        </td>
                        <td data-field="bookingDate">
                            <?= formatDate_mm_dd_yyyy($booking['booking_date']) ?>
                        </td>
                        <td data-field="bookingTime">
                            <?= formatTime($booking['booking_time']) ?>
                        </td>
                        <td>
                            <?= array_sum($serviceDurations) ?>
                        </td>
                        <td>
                            <?= formatTime(date("H:i", strtotime($booking['booking_time'] . ' + ' . array_sum($serviceDurations) . ' minutes'))) ?>
                        </td>
                        <td data-field="price">
                            <?= $booking['price'] ?>
                        </td>

                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div id="editModal" class="hidden">
            <label for="newValue">New Value:</label>
            <input type="text" id="newValue">
            <button id="updateBtn">Update</button>
        </div>
    </div>
</div>



<script>
    // Function to show the modal and handle the update
    function showModal(bookingObj, field) {
        const modal = document.getElementById('editModal');
        const input = document.getElementById('newValue');
        const updateBtn = document.getElementById('updateBtn');
        const availableServices = <?= $servicesJson ?>;

        // Show the modal
        modal.classList.remove('hidden');

        // Set the input value to the current field value
        input.value = bookingObj[field];

        // Handle the update button click
        updateBtn.onclick = function() {
            // Update the booking object
            bookingObj[field] = input.value;

            // Convert the object back to JSON
            const updatedBookingJSON = JSON.stringify(bookingObj);

            // Send the updated JSON to the server using AJAX
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'update_booking.php', true);
            xhr.setRequestHeader('Content-Type', 'application/json;charset=UTF-8');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    // Hide the modal
                    modal.classList.add('hidden');
                }
            };
            xhr.send(updatedBookingJSON);
        };
    }

    // Listen for a click event on each table cell
    document.querySelectorAll('tbody td').forEach(cell => {
        cell.addEventListener('click', function() {
            // Highlight the selected cell
            cell.classList.add('selected-cell');

            // Retrieve the JSON data from the parent row
            const bookingJSON = this.parentNode.getAttribute('data-booking-json');
            const bookingObj = JSON.parse(bookingJSON);

            // Get the field to be edited
            const field = this.getAttribute('data-field');

            // Show the modal to edit the field
            showModal(bookingObj, field);

            // Remove the highlight from the selected cell
            cell.classList.remove('selected-cell');
        });
    });
</script>


<?php
include '../../../core/footer.php';
?>