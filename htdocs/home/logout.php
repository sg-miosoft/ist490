<?php 

// If no session variable exists, redirect the user.
if (!isset($_SESSION['email'])) {
	header("Location: index.php");
	exit(); // Quit the script.
} else { // Cancel the session.
	$_SESSION = array(); // Destroy the variables.
	session_destroy(); // Destroy the session itself.
	header("Location: login.php");
}

// Include the header code.
include ('../includes/header.php');

// Print a customized message.
echo "<div class='loggedOutContain'><h1>Logged Out!</h1>
<p style='text-align:center;'>You are now logged out!</p>
</div>";

include ('../includes/footer.php');
?>
