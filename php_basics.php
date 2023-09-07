<?php
include './globals/global_header.php';
include './data/dbConnect.php';
?>

<div class="content">
    <h1>2-1: PHP Basics</h1>

    <!-- Use HTML to put an h2 element on the page -->
    <h2><?php echo $yourName; ?></h2>

    <!-- Display the value of each variable and the total -->
    <p>Number 1: <?php echo $number1; ?></p>
    <p>Number 2: <?php echo $number2; ?></p>
    <p>Total: <?php echo $total; ?></p>

</br>
<script>
    // Create a JavaScript array from the PHP array
    const jsArray = <?php echo json_encode($phpArray); ?>;

    // Display the values of the array on the page
    jsArray.forEach(element => {
        document.write(`<p>${element}</p>`);
    });
</script>

</div>

<?php 
include './globals/global_footer.php'; 

?>
