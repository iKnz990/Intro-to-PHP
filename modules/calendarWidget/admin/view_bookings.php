<?php
include '../../../core/header.php';
include '../core/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sort_order = $_POST['sort_order'];
} else {
    $sort_order = 'approaching'; // Default sort order
}
$bookings = getAllBookings($sort_order);

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
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bookings as $booking): ?>
                    <?php
                    $currentDate = new DateTime('now', new DateTimeZone('UTC'));
                    $currentDate->setTime(0, 0, 0);

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
                    <tr class="<?= $highlight ?>">
                        <td>
                            <?= ($booking['service_id'] == -1) ? "Service Deleted" : $booking['service_name'] ?>
                        </td>
                        <td>
                            <?= $booking['user_name'] ?>
                        </td>
                        <td>
                            <?= $booking['user_email'] ?>
                        </td>
                        <td>
                            <?= $booking['booking_date'] ?>
                        </td>
                        <td>
                            <?= $booking['booking_time'] ?>
                        </td>
                        <td>
                            <?= $booking['service_duration'] ?>
                        </td>
                        <td>
                            <?= date("H:i", strtotime($booking['booking_time'] . ' + ' . $booking['service_duration'] . ' minutes')) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>


<?php
include '../../../core/footer.php';
?>