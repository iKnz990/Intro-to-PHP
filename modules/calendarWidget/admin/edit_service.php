<?php
include '../../../core/header.php';
include '../core/functions.php';

$serviceId = $_GET['id'];
$service = $pdo->query("SELECT * FROM services WHERE service_id = $serviceId")->fetch();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $serviceName = $_POST['service_name'];
    $serviceDuration = $_POST['service_duration'];
    $description = $_POST['description'];

    global $pdo;
    $stmt = $pdo->prepare("UPDATE services SET service_name = ?, service_duration = ?, description = ? WHERE service_id = ?");
    $stmt->execute([$serviceName, $serviceDuration, $description, $serviceId]);

    echo "Service updated successfully!";
}

?>
<div class="content">

<form action="edit_service.php?id=<?= $serviceId ?>" method="post">
    <label for="serviceName">Service Name:</label>
    <input type="text" id="serviceName" name="service_name" value="<?= $service['service_name'] ?>" required><br><br>

    <label for="duration">Duration (in mins):</label>
    <input type="number" id="duration" name="service_duration" value="<?= $service['service_duration'] ?>" required><br><br>

    <label for="description">Description:</label>
    <textarea id="description" name="description"><?= $service['description'] ?></textarea><br><br>

    <input type="submit" value="Update Service">
</form>
</div>
<?php
include '../../../core/footer.php';
?>
