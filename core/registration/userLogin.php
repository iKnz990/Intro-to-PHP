<?php
include '../header.php';
include 'login.php';

?>
<div class="content-container">
    <div class="form-container">
        <h1>
            <div id="messageContainer"></div>
        </h1>
        <h2 class="form-title">Login</h2>
        <?php if (isset($errorMessage))
            echo "<p class='error'>$errorMessage</p>"; ?>
        <form action="" method="post">
            <div class="form-group">
                <label class="form-label" for="username">Username:</label>
                <input class="form-input" type="text" name="username" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="password">Password:</label>
                <input class="form-input" type="password" name="password" required>
            </div>

            <input class="form-button" type="submit" name="login" value="Login">
        </form>
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
include '../footer.php';


?>