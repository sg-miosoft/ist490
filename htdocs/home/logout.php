<?php 

// This page lets the user logout.
session_start(); 

// If no session variable exists, redirect the user.
if (!isset($_SESSION['email'])) {
	header("Location: index.php");
	exit(); // Quit the script.
} else { // Cancel the session.
	$_SESSION = array(); // Destroy the variables.
	session_destroy(); // Destroy the session itself.
}

// Include the header code.
include ('../includes/header.php');

// Print a customized message.
echo "<h1>Logged Out!</h1>
<p>You are now logged out!</p>
<p><br /><br /></p>";

include ('../includes/footer.php');
?>
