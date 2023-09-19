<?php 
include '../header.php';
include 'login.php'; 

?>
<div class="content-container">
<div class="form-container">
        <h2 class="form-title">Login</h2>
        <?php if (isset($errorMessage)) echo "<p class='error'>$errorMessage</p>"; ?>
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
<?php 
include '../footer.php';


?>