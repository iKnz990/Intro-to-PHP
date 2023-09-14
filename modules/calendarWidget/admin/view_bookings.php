<?php
include '../../../core/header.php';
include '../core/functions.php';

$bookings = getAllBookings();
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
                    <tr>
                        <td>
                            <?= ($booking['service_id'] == -1) ? "Service Deleted" : $booking['service_name'] ?>
                        </td>
                        <td><?= $booking['user_name'] ?></td>
                        <td><?= $booking['user_email'] ?></td>
                        <td><?= $booking['booking_date'] ?></td>
                        <td><?= $booking['booking_time'] ?></td>
                        <td><?= $booking['service_duration'] ?></td>
                        <td><?= date("H:i", strtotime($booking['booking_time'] . ' + ' . $booking['service_duration'] . ' minutes')) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php
include '../../../core/footer.php';
?>
