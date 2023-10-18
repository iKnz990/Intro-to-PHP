<?php
include '../../../core/header.php';
include '../core/functions.php';

$serviceAdded = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $serviceName = $_POST['service_name'];
    $serviceDuration = $_POST['service_duration'];
    $description = $_POST['description'];
    $price = $_POST['price']; // New price field

    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO services (service_name, service_duration, description, price) VALUES (?, ?, ?, ?)");
    $stmt->execute([$serviceName, $serviceDuration, $description, $price]); // Include price in the query

    $serviceAdded = true;
    header("Location: manage_services.php?message=Service added successfully!");
    exit;
}

checkUserRole('admin');
?>


<?php
include '../../../core/footer.php';
?>