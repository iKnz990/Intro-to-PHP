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
                    // Fetch services for each booking -- before creating the Booking object [... the syntax of doom]
                    $stmt = $pdo->prepare("SELECT service_id FROM booking_services WHERE booking_id = ?");
                    $stmt->execute([$booking['booking_id']]);
                    $serviceData = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    $serviceIds = array_column($serviceData, 'service_id');

                    $bookingObj = new Booking(
                        $booking['booking_id'],
                        $booking['user_name'],
                        $booking['user_email'],
                        $booking['booking_date'],
                        $booking['booking_time'],
                        $booking['price'],
                        $serviceIds  // This is the array of service IDs
                    );

                    // Convert the Booking object to JSON (if needed)
                    $bookingJSON = $bookingObj->toJSON();

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
                        <td data-field="serviceName">
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
        <div id="modalContent">
            <label for="newValue">New Value:</label>
            <input type="text" id="newValue">
            </div>
            <button id="updateBtn">Update</button>
        </div>
    </div>
</div>



<script>
// Function to show the modal and handle the update
function showModal(bookingObj, field, cell) {
    const modal = document.getElementById('editModal');
    const modalContent = document.getElementById('modalContent');
    const updateBtn = document.getElementById('updateBtn');
    const availableServices = <?= $servicesJson ?>;
    //set input value
    let input;

    // Clear existing content and Show the modal
    modalContent.innerHTML = '';
    modal.classList.remove('hidden');

    if (field === "serviceName") {
        availableServices.forEach(service => {
            const checkbox = document.createElement("input");
            checkbox.type = "checkbox";
            checkbox.value = service.service_id;
            checkbox.id = "service_" + service.service_id;

            const label = document.createElement("label");
            label.htmlFor = "service_" + service.service_id;
            label.appendChild(document.createTextNode(service.service_name));

            if (bookingObj.services && bookingObj.services.includes(service.service_id)) {
                checkbox.checked = true;
            }

            modalContent.appendChild(checkbox);
            modalContent.appendChild(label);
            modalContent.appendChild(document.createElement("br"));
        });
    } else {
        input = document.createElement('input');
        input.value = bookingObj[field];
        modalContent.appendChild(input);
        //console.log(input);
    }

    updateBtn.onclick = function() {
        //console.log(field);
        switch (field) {
            case 'serviceName':
                const checkedServices = Array.from(modalContent.querySelectorAll('input[type="checkbox"]:checked'))
                    .map(checkbox => parseInt(checkbox.value));
                bookingObj.services = checkedServices;
                break;
            case 'userName':
                bookingObj.userName = input.value;
                //console.log(bookingObj);
                break;
            case 'userEmail':
                bookingObj.userEmail = input.value;
                break;
            case 'bookingDate':
                bookingObj.bookingDate = input.value;
                break;
            case 'bookingTime':
                bookingObj.bookingTime = input.value;
                break;
            case 'price':
                bookingObj.price = input.value;
                break;
            default:
                console.error('Unknown field:', field);
                return;
        }

        const updatedBookingJSON = JSON.stringify(bookingObj);
        updateDatabase(updatedBookingJSON, cell, field, bookingObj);
        modal.classList.add('hidden');
    };
    
}

// Function to update the database using AJAX
function updateDatabase(updatedBookingJSON, cell, field, bookingObj) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'update_booking.php', true);
    xhr.setRequestHeader('Content-Type', 'application/json;charset=UTF-8');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            const updatedBookingObj = JSON.parse(updatedBookingJSON);
            switch (field) {
                case 'serviceName':
                    cell.textContent = convertServiceIdsToNames(updatedBookingObj.services).join(', ');
                    break;
                case 'userName':
                    cell.textContent = updatedBookingObj.userName;
                    //console.log(bookingObj);
                    break;
                case 'userEmail':
                    cell.textContent = updatedBookingObj.userEmail;
                    break;
                case 'bookingDate':
                    cell.textContent = updatedBookingObj.bookingDate;
                    break;
                case 'bookingTime':
                    cell.textContent = updatedBookingObj.bookingTime;
                    break;
                case 'price':
                    cell.textContent = updatedBookingObj.price;
                    break;
                default:
                    console.error('Unknown field:', field);
                    return;
            }
            //console.log(bookingObj);
           //console.log(xhr.responseText);

            const parentRow = cell.parentNode;
            parentRow.setAttribute('data-booking-json', updatedBookingJSON);
        }
    };
    xhr.send(updatedBookingJSON);
}

// Listen for a click event on each table cell
document.querySelectorAll('tbody td').forEach(cell => {
    cell.addEventListener('click', function() {
        const bookingJSON = this.parentNode.getAttribute('data-booking-json');
        const bookingObj = JSON.parse(bookingJSON);
        const field = this.getAttribute('data-field');
        showModal(bookingObj, field, cell);
    });
});

// Function to convert service IDs to names
function convertServiceIdsToNames(serviceIds) {
    const availableServices = <?= $servicesJson ?>;
    return serviceIds.map(id => {
        const service = availableServices.find(s => s.service_id === id);
        return service ? service.service_name : 'Unknown Service';
    });
}
</script>


<?php
include '../../../core/footer.php';
?>