<?php

include '../../../core/header.php';
include '../core/functions.php';
$services = getAllServices();
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
    <div id="messageContainer"></div> <!-- Message container for displaying success/error messages -->
        <div class="form-container">
            <h3 class="form-title">Add New Service</h3>
            <form action="add_service.php" method="post">
                <div class="form-group">
                    <label for="serviceName" class="form-label">Service Name:</label>
                    <input type="text" id="serviceName" name="service_name" class="form-input" required>
                </div>
                
                <div class="form-group">
                    <label for="duration" class="form-label">Duration (in mins):</label>
                    <input type="number" id="duration" name="service_duration" class="form-input" required>
                </div>
                
                <div class="form-group">
                    <label for="description" class="form-label">Description:</label>
                    <textarea id="description" name="description" class="form-textarea"></textarea>
                </div>
                
                <input type="submit" value="Add Service" class="form-button">
            </form>

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
                            <?php if ($service['service_id'] != -1): // Skip the placeholder service ?>
                                <tr>
                                    <td><?= $service['service_name'] ?></td>
                                    <td><?= $service['service_duration'] ?></td>
                                    <td><?= $service['description'] ?></td>
                                    <td>
                                        <a href="edit_service.php?id=<?= $service['service_id'] ?>">Edit</a> |
                                        <a href="delete_service.php?id=<?= $service['service_id'] ?>">Delete</a>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
        </div>
    </div>
</div>
<!-- Script to display a message if one is present in the URL -->
<script>
    // Function to get the value of a URL parameter
    function getUrlParam(paramName) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(paramName);
    }

    // Check if the 'message' parameter exists in the URL
    const message = getUrlParam('message');

    // Check if a message is present and display it in the message container
    if (message) {
        const messageContainer = document.getElementById('messageContainer');
        messageContainer.innerText = message;
    }
</script>

<?php
include '../../../core/footer.php';
?>
