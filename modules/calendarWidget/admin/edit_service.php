<?php
include '../../../core/header.php';
include '../core/functions.php';

$serviceId = $_GET['id'];
$service = $pdo->query("SELECT * FROM services WHERE service_id = $serviceId")->fetch();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $serviceName = $_POST['service_name'];
    $serviceDuration = $_POST['service_duration'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    global $pdo;
    $stmt = $pdo->prepare("UPDATE services SET service_name = ?, service_duration = ?, description = ?, price = ? WHERE service_id = ?");
    $stmt->execute([$serviceName, $serviceDuration, $description, $price, $serviceId]);


    // Redirect to manage_services.php after updating the service, message script located in manage_services.php
    header("Location: manage_services.php?message=Service updated successfully!");
    exit;
}
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

        <div class="form-container">
            <h3 class="form-title">Edit Service</h3>
            <form action="edit_service.php?id=<?= $serviceId ?>" method="post">
                <div class="form-group">
                    <label for="serviceName" class="form-label">Service Name:</label>
                    <input type="text" id="serviceName" name="service_name" value="<?= $service['service_name'] ?>"
                        class="form-input" required>
                </div>
                <div class="form-group">
                    <label for="price" class="form-label">Price:</label>
                    <input type="number" id="price" name="price" class="form-input" value="<?= $service['price'] ?>"
                        required>
                </div>
                <div class="form-group">
                    <label for="duration" class="form-label">Duration (in mins):</label>
                    <input type="number" id="duration" name="service_duration"
                        value="<?= $service['service_duration'] ?>" class="form-input" required>
                </div>

                <div class="form-group">
                    <label for="description" class="form-label">Description:</label>
                    <textarea id="description" name="description"
                        class="form-textarea"><?= $service['description'] ?></textarea>
                </div>

                <input type="submit" value="Update Service" class="form-button">
            </form>
        </div>
    </div>
</div>
<?php
include '../../../core/footer.php';
?>