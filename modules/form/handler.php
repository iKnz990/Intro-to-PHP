<?php

include '../../core/header.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Sanitize and capture form data using the sanitizeInput function
  $first_name = sanitizeInput($_POST['first_name']);
  $last_name = sanitizeInput($_POST['last_name']);
  $customer_email = filter_input(INPUT_POST, 'customer_email', FILTER_SANITIZE_EMAIL);
  $academic_standing = sanitizeInput($_POST['academic_standing']);
  $program = sanitizeInput($_POST['program']);
  $contact_info = isset($_POST['contact_info']) ? 'Yes' : 'No';
  $contact_advisor = isset($_POST['contact_advisor']) ? 'Yes' : 'No';
  $comments = sanitizeInput($_POST['comments']);

  // Store the sanitized data into the database using PDO
  $sql = "INSERT INTO form_data (first_name, last_name, customer_email, academic_standing, program, contact_info, contact_advisor, comments) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$first_name, $last_name, $customer_email, $academic_standing, $program, $contact_info, $contact_advisor, $comments]);

?>


<head>
<meta http-equiv="refresh" content="15;<?= BASE_URL ?>modules/form/">
</head>

<body>
  <div class="content">
  <div class="content form-container">
    <h1 class="form-title">Form Submitted Successfully</h1>
    
    <div class="form-group">
      <p>Dear <?php echo $first_name; ?>,</p>
    </div>
    
    <div class="form-group">
      <p>Thank you for your interest in DMACC.</p>
    </div>
    
    <div class="form-group">
      <p>We have you listed as a <?php echo $academic_standing; ?> starting this fall.</p>
    </div>
    
    <div class="form-group">
      <p>You have declared <?php echo $program; ?> as your major.</p>
    </div>
    
    <div class="form-group">
      <p>Based upon your responses we will provide the following information in our confirmation email to you at <?php echo $customer_email; ?>.</p>
    </div>
    
    <div class="form-group checkbox-group">
      <ul>
        <?php if(isset($contact_info)) { echo "<p>Based on your respose we will send additional information to $customer_email.</p>"; } ?><br>
        <?php if(isset($contact_advisor)) { echo "<p>Based on your respose a $program advisor will reach out to you.</p>"; } ?>
      </ul>
    </div>
    
    <div class="form-group">
      <p>You have shared the following comments which we will review:</p>
      <p><?php echo $comments; ?></p>
    </div>
    
    <div class="form-group">
      <p>You will be redirected in 15 seconds...</p>
    </div>
  </div>
  </div>
</body>


<?php
  } else {
    // Redirect to form page if form is not submitted
    header("Location: ./form.php");
  }

  include '../../core/footer.php';
  ?>