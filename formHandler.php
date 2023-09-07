<?php

include './data/dbConnect.php';
// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Sanitize and capture form data
    $first_name = filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_STRING);
    $last_name = filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_STRING);
    $customer_email = filter_input(INPUT_POST, 'customer_email', FILTER_SANITIZE_EMAIL);
    $academic_standing = filter_input(INPUT_POST, 'academic_standing', FILTER_SANITIZE_STRING);
    $program = filter_input(INPUT_POST, 'program', FILTER_SANITIZE_STRING);
    $contact_info = isset($_POST['contact_info']) ? 'Yes' : 'No';
    $contact_advisor = isset($_POST['contact_advisor']) ? 'Yes' : 'No';
    $comments = filter_input(INPUT_POST, 'comments', FILTER_SANITIZE_STRING);

    // TODO: Store the sanitized data into the database
    $sql = "INSERT INTO form_data (first_name, last_name, customer_email, academic_standing, program, contact_info, contact_advisor, comments) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssss", $first_name, $last_name, $customer_email, $academic_standing, $program, $contact_info, $contact_advisor, $comments);
    $stmt->execute();


?>

<!-- No global headers for handler pages... -->
<head>
<link href="./css/style.css" rel="stylesheet">
<meta http-equiv="refresh" content="5;url=form.php">
</head>

<body>
  <h1>Form Submitted Successfully</h1>
  <p>Thank you, <?php echo $first_name . ' ' . $last_name; ?>. Your information has been received.</p>
  <p>You will be redirected in 5 seconds...</p>
</body>

<?php
  } else {
    // Redirect to form page if form is not submitted
    header("Location: form.php");
  }

  include './globals/global_footer.php';
?>