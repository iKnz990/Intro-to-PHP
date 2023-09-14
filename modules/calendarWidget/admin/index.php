<?php
include '../../../core/header.php';
include '../core/functions.php';
$services = getAllServices();
?>
<div class="content">

<h2>Admin Panel</h2>

<h3>Manage Services</h3>

<table border="1">
    <thead>
        <tr>
            <th>Service Name</th>
            <th>Duration (mins)</th>
            <th>Description</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($services as $service): ?>
            <tr>
                <td><?= $service['service_name'] ?></td>
                <td><?= $service['service_duration'] ?></td>
                <td><?= $service['description'] ?></td>
                <td>
                    <a href="edit_service.php?id=<?= $service['service_id'] ?>">Edit</a> |
                    <a href="delete_service.php?id=<?= $service['service_id'] ?>">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<h3>Add New Service</h3>

<form action="add_service.php" method="post">
    <label for="serviceName">Service Name:</label>
    <input type="text" id="serviceName" name="service_name" required><br><br>

    <label for="duration">Duration (in mins):</label>
    <input type="number" id="duration" name="service_duration" required><br><br>

    <label for="description">Description:</label>
    <textarea id="description" name="description"></textarea><br><br>

    <input type="submit" value="Add Service">
</form>
        </div>
<?php
include '../../../core/footer.php';
?>
