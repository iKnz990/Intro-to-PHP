<?php
include '../header.php';
include 'register.php';

?>
<div class="content-container">
    <div class="form-container">

        <h2 class="form-title">Register</h2>
        <?php if (isset($errorMessage))
            echo "<p class='error'>$errorMessage</p>"; ?>
        <form action="" method="post">
            <div class="form-group">
                <label class="form-label" for="username">Username:</label>
                <input class="form-input" type="text" name="username" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="email">Email:</label>
                <input class="form-input" type="email" name="email" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="password">Password:</label>
                <input class="form-input" type="password" name="password" required>
            </div>

            <input class="form-button" type="submit" name="register" value="Register">
        </form>
    </div>
</div>
<?php
include '../footer.php';


?>