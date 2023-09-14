<?php
include '../../../core/header.php';
include '../core/functions.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $serviceName = $_POST['service_name'];
    $serviceDuration = $_POST['service_duration'];
    $description = $_POST['description'];

    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO services (service_name, service_duration, description) VALUES (?, ?, ?)");
    $stmt->execute([$serviceName, $serviceDuration, $description]);

    echo "Service added successfully!";
}

include '../../../core/footer.php';
?>
