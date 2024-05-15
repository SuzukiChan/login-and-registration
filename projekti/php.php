<?php 
	
// Checking valid form is submit or not 
if (isset($_POST['submit_btn'])) { 
	
	// Storing name in $name variable 
	$name = $_POST['name']; 
	
	// Storing google recaptcha response 
	// in $recaptcha variable 
	$recaptcha = $_POST['g-recaptcha-response']; 
} 
?>
