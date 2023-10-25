<?php
include 'core/header.php';
?>


<div class="content-container">
   <?php
   $message = $_GET['message'] ?? null;
   if ($message) {
      echo "<h3>$message</h3>";
   }
   ?>

   <?php if (!isLoggedIn()) : ?>
      <h3>
         <a href="<?= BASE_URL ?>core/registration/userLogin.php">Login</a>
         or
         <a href="<?= BASE_URL ?>core/registration/userRegister.php">Register</a>
         to Continue
      </h3>
   <?php endif; ?>

</div>

<?php
include 'core/footer.php';
?>