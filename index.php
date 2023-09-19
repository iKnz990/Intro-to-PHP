<?php 
include 'core/header.php';
?>


<div class="content-container">


<?php if (!isLoggedIn()): ?>
<h3>
    <a href="<?php echo $baseURL;?>core/registration/userLogin.php">Login</a>
 or 
    <a href="<?php echo $baseURL;?>core/registration/userRegister.php">Register</a> 
 to Continue
</h3>
<?php endif; ?>

</div>

<?php 
include 'core/footer.php';
?>