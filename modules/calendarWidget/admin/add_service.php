<?php
include '../../../core/header.php';
include '../core/functions.php';

$serviceAdded = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $serviceName = $_POST['service_name'];
    $serviceDuration = $_POST['service_duration'];
    $description = $_POST['description'];

    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO services (service_name, service_duration, description) VALUES (?, ?, ?)");
    $stmt->execute([$serviceName, $serviceDuration, $description]);

    $serviceAdded = true;
        // Redirect to manage_services.php after updating the service, message script located in manage_services.php
    header("Location: manage_services.php?message=Service added successfully!");
        exit;
}
?>


<?php
include '../../../core/footer.php';
?>
