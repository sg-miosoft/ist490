<?php
session_start(); 
include ("../includes/header.php"); // Include the header file.
// Print a customized message:
if (!isset($_SESSION['email'])){
	echo "<h1>You have not logged in yet!</h1>";
} else {
	echo "<div class='loggedinContain'><h1>Logged In!</h1><hr /><p style='color:#FFF; text-align:center;'>You are now logged in <br />" . $_SESSION['first_name'] .' ' .  $_SESSION['last_name'] ."!</p>
	<p style='color:#FFF; text-align:center;'>You can now enjoy our services for registered members.</p></div>
	";
} 
include ('../includes/footer.php'); // Include the footer file.
?>