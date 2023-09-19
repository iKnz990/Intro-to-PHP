<?php 
include '../../core/header.php';

?>


<div class="content form-container">
  <form id="form1" name="form1" method="post" action="./handler.php">
    <legend class="form-title"><h3>HTML Form Processor</h3></legend>
    
    <div class="form-group">
      <label for="first_name" class="form-label">First Name:</label> 
      <input type="text" name="first_name" id="first_name" class="form-input">
    </div>
    
    <div class="form-group">
      <label for="last_name" class="form-label">Last Name:</label> 
      <input type="text" name="last_name" id="last_name" class="form-input">
    </div>
    
    <div class="form-group">
      <label for="customer_email" class="form-label">Customer Email:</label> 
      <input type="email" name="customer_email" id="customer_email" class="form-input">
    </div>
    
    <div class="form-group radio-group">
      <label class="form-label">Academic Standing:</label>
      <div class="radio-options">
        <input type="radio" name="academic_standing" value="High School"> High School
        <input type="radio" name="academic_standing" value="Freshman"> Freshman
        <input type="radio" name="academic_standing" value="Sophomore"> Sophomore
      </div>
    </div>
    
    <div class="form-group">
      <label for="program" class="form-label">Program:</label>
      <select name="program" id="program" class="form-select">
        <option value="default">Default option</option>
        <option value="Computer Information Systems">Computer Information Systems</option>
        <option value="Graphic Design">Graphic Design</option>
        <option value="Web Development">Web Development</option>
      </select>
    </div>
    
    <div class="form-group checkbox-group">
    <span>
        <input type="checkbox" name="contact_info" value="program_info">
        Please contact me with program information
    </span>
    <span>
      <input type="checkbox" name="contact_advisor" value="program_advisor"> 
      I would like to contact a program advisor
    </span>
    </div>
    
    <div class="form-group">
      <label for="comments" class="form-label">Comments:</label>
      <textarea name="comments" id="comments" class="form-textarea"></textarea>
    </div>
    
    <div class="form-group button-group">
      <input type="submit" name="button" id="button" value="Submit" class="form-button">
      <input type="reset" name="button2" id="button2" value="Reset" class="form-button">
    </div>
    
  </form>
</div>

<?php
  include ROOT_DIR . 'core/footer.php';
   
?>

