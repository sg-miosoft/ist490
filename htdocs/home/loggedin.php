<?php
session_start(); 
include ("../includes/header.php"); // Include the header file.
// Print a customized message:
if (!isset($_SESSION['email'])){
	echo "<h1>You have not logged in yet!</h1>";
} else {
	echo "<h1>Logged In!</h1><p>You are now logged in " . $_SESSION['first_name'] ." ".  $_SESSION['last_name'] ."!</p>
	<p>You can now enjoy our services for logged in users</p>
	";
} 
include ('../includes/footer.php'); // Include the footer file.
?>