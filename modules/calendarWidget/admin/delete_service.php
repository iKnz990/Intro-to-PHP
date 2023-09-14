<?php
include '../../../core/header.php';
include '../core/functions.php';

$serviceId = $_GET['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM services WHERE service_id = ?");
    $stmt->execute([$serviceId]);

    echo "Service deleted successfully!";
}

?>
<div class="content">

<form action="delete_service.php?id=<?= $serviceId ?>" method="post">
    <p>Are you sure you want to delete this service?</p>
    <input type="submit" value="Delete Service">
</form>
</div>
<?php
include '../../../core/footer.php';
?>
