<?php 
include './globals/global_header.php'; 
include './data/dbConnect.php';

?>

        <div class="content">
            <h1>4-1: PHP Functions</h1>
            <?php
            // Test the functions
            echo "<p><strong>Date in MM/DD/YYYY:</strong> " . formatDate_mm_dd_yyyy(time()) . "</p><br>";
            echo "<p><strong>Date in DD/MM/YYYY:</strong> " . formatDate_dd_mm_yyyy(time()) . "</p><br>";

            echo "<p><strong>String Length:</strong> " . $stringInfo['length'] . "</p><br>";
            echo "<p><strong>Trimmed String:</strong> " . $stringInfo['trimmed'] . "</p><br>";
            echo "<p><strong>Lowercase String:</strong> " . $stringInfo['lowercase'] . "</p><br>";
            echo "<p><strong>Contains DMACC:</strong> " . $stringInfo['containsDMACC'] . "</p><br>";

            echo "<p><strong>Formatted Phone Number:</strong> " . formatPhoneNumber($phoneNumber) . "</p><br>";
            echo "<p><strong>Formatted Currency:</strong> " . formatCurrency($assignedCurrency) . "</p><br>";          
            ?>
        </div>
        
<?php include 
'./globals/global_footer.php'; 

?>