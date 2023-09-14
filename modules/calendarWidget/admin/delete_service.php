<?php
include '../../../core/header.php';
include '../core/functions.php';

$serviceId = $_GET['id'];
$serviceDeleted = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    global $pdo;

    // Set the service_id in bookings to the ID of the placeholder service
    $placeholderServiceId = -1;
    $stmt = $pdo->prepare("UPDATE bookings SET service_id = :placeholder_id WHERE service_id = :service_id");
    $stmt->execute(['placeholder_id' => $placeholderServiceId, 'service_id' => $serviceId]);

    // Delete the service
    $stmt = $pdo->prepare("DELETE FROM services WHERE service_id = :service_id");
    $stmt->execute(['service_id' => $serviceId]);

    $serviceDeleted = true;

    // Redirect to manage_services.php after updating the service, message script located in manage_services.php
    header("Location: manage_services.php?message=Service deleted successfully!");
exit;

}
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


            <?php if (!$serviceDeleted): ?>
            <form action="delete_service.php?id=<?= $serviceId ?>" method="post" onsubmit="this.querySelector('input[type=submit]').disabled = true;">
                <p>Are you sure you want to delete this service?</p>
                <input type="submit" value="Delete Service">
            </form>
            <?php else: ?>
            <p>Service deleted successfully!</p>
            <?php endif; ?>
    </div>
</div>

<?php
include '../../../core/footer.php';
?>
