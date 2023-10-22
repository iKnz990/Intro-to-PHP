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
            <tbody id="bookingTableBody">
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
                        <td data-field="duration">
                            <?= array_sum($serviceDurations) ?>
                        </td>
                        <td data-field="endTime">
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

        // Skip the service with ID -1
        if (service.service_id === -1) {
            return;
        }
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
            // Recalculate End Time and Price if bookingTime or serviceName is changed
            if (field === 'bookingTime' || field === 'serviceName') {

                // Get selected services (assuming you have them in an array)
                const selectedServices = bookingObj.services;

                // Calculate total duration
                const totalDuration = calculateTotalDuration(selectedServices, availableServices);

                // Calculate new end time and price
                const newEndTime = calculateNewEndTime(bookingObj.bookingTime, totalDuration);
                const newPrice = calculateNewPrice(selectedServices, availableServices);

                // Update the booking object
                bookingObj.endTime = newEndTime;
                bookingObj.price = newPrice;
        }
        const updatedBookingJSON = JSON.stringify(bookingObj);
        updateDatabase(updatedBookingJSON, cell, field, bookingObj);
        modal.classList.add('hidden');
    };
    
}

// Function to update the database using AJAX
function updateDatabase(updatedBookingJSON, cell, field, bookingObj) {
    console.log("Debug: Updated bookingObj before sending:", bookingObj);

    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'update_booking.php', true);
    xhr.setRequestHeader('Content-Type', 'application/json;charset=UTF-8');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            const updatedBookingObj = JSON.parse(updatedBookingJSON);
            const parentRow = cell.parentNode;
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

                // Recalculate End Time and Price
                if (field === 'bookingTime' || field === 'serviceName') {
                const totalDuration = getTotalDuration(bookingObj.services);
                const newEndTime = calculateNewEndTime(bookingObj.bookingTime, totalDuration); 
                const newPrice = getTotalPrice(bookingObj.services);

                // Update the DOM elements for End Time, Duration, and Price
                updateDOMElement('bookingTime', formatTime(bookingObj.bookingTime), parentRow);
                updateDOMElement('endTime', newEndTime, parentRow);
                updateDOMElement('duration', totalDuration, parentRow);
                updateDOMElement('price', newPrice.toFixed(2), parentRow);

            }
            //console.log(bookingObj);
            //console.log(xhr.responseText);

            parentRow.setAttribute('data-booking-json', updatedBookingJSON);
        }
    };
    console.log("Debug: Sending updated booking JSON:", updatedBookingJSON);
    xhr.send(updatedBookingJSON);
}

// Listen for a click event on each table cell
document.querySelectorAll('tbody td').forEach(cell => {
    cell.addEventListener('click', function() {
        const field = this.getAttribute('data-field');
        
        // Skip cells with data-field of 'duration', 'endTime', or 'price'
        if (field === 'duration' || field === 'endTime' || field === 'price') {
            return;
        }

        const bookingJSON = this.parentNode.getAttribute('data-booking-json');
        const bookingObj = JSON.parse(bookingJSON);
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

// Function to get the total duration of all selected services
function getTotalDuration(selectedServices) {
    const availableServices = <?= $servicesJson ?>;
    let totalDuration = 0;
    selectedServices.forEach(serviceId => {
        const service = availableServices.find(s => s.service_id === parseInt(serviceId));
        if (service) {
            totalDuration += service.service_duration; 
        }
    });
    return totalDuration;
}


// Function to calculate the new end time based on the booking time and total duration
function calculateNewEndTime(bookingTime, totalDuration) {
    console.log("Debug: bookingTime =", bookingTime, "totalDuration =", totalDuration);  // Debugging line

    // Check if bookingTime and totalDuration are valid
    if (!bookingTime || isNaN(totalDuration)) {
        console.error("Invalid bookingTime or totalDuration");
        return null;
    }

    try {
        const timeParts = bookingTime.split(":");
        let hours = parseInt(timeParts[0]);
        let minutes = parseInt(timeParts[1]);

        // Add the total duration to the booking time
        minutes += totalDuration;

        // Handle overflow of minutes
        while (minutes >= 60) {
            minutes -= 60;
            hours++;
        }

        // Handle overflow of hours
        hours = hours % 24;

        // Convert back to time string
        const newEndTime = `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:00`;

        return newEndTime;
    } catch (e) {
        console.error("Error in calculating new end time:", e);
        return null;
    }
}



// Function to calculate total duration based on selected services
function calculateTotalDuration(selectedServices, availableServices) {
// Debug: Print available services and selected services
console.log("Debug: availableServices = ", availableServices);
console.log("Debug: selectedServices = ", selectedServices);

let totalDuration = 0;

selectedServices.forEach(serviceId => {
    const service = availableServices.find(s => s.service_id === parseInt(serviceId));
    if (service) {
        console.log("Debug: Found service for ID ", serviceId, " with duration ", service.service_duration);
        totalDuration += service.service_duration;
    } else {
        console.log("Debug: No service found for ID ", serviceId);
    }
});

console.log("Debug: totalDuration = ", totalDuration);
    return totalDuration; // Return the total duration
}


// Function to calculate total price based on selected services
function calculateNewPrice(selectedServices, availableServices) {
    let totalPrice = 0; // Initialize total price to 0

    // Loop through each selected service
    selectedServices.forEach(serviceId => {
        // Find the corresponding service object from availableServices
        const service = availableServices.find(s => s.service_id === serviceId);

        // Add the service's price to the total price
        if (service && service.price) {
            totalPrice += parseFloat(service.price);
        }
    });

    return totalPrice; // Return the total price
}

function getTotalPrice(selectedServices) {
    const availableServices = <?= $servicesJson ?>;
    let totalPrice = 0.0;

    //Debugging to make sure services populate
    console.log("Debug: availableServices in getTotalPrice =", availableServices);
    console.log("Debug: selectedServices in getTotalPrice =", selectedServices);

    if (!selectedServices || !Array.isArray(selectedServices)) {
        console.error("Invalid selectedServices array");
        return null;
    }

    selectedServices.forEach(serviceId => {
        const service = availableServices.find(s => s.service_id === parseInt(serviceId));
        if (service) {
            totalPrice += parseFloat(service.price);  // Corrected to price
        }
    });

    console.log("Debug: Calculated totalPrice =", totalPrice);  // Debugging line for total price
    return totalPrice;
}





// Function to update the DOM element based on the field and new value
function updateDOMElement(field, newValue, parentRow) {
    const cell = parentRow.querySelector(`[data-field="${field}"]`);
    if (cell) {
        if (field === 'endTime') {
            cell.textContent = formatTime(newValue);
        } else {
            cell.textContent = newValue;
        }
    }
}

// Function to format time in JavaScript
function formatTime(time) {
    const timeArray = time.split(":");
    let hour = parseInt(timeArray[0]);
    const minute = timeArray[1];
    const ampm = hour >= 12 ? 'PM' : 'AM';

    // Convert to 12-hour format
    hour = hour % 12;
    hour = hour ? hour : 12; // the hour '0' should be '12'

    return `${hour.toString().padStart(2, '0')}:${minute} ${ampm}`;
}




</script>


<?php
include '../../../core/footer.php';
?>