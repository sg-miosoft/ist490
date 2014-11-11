<html><body><center>
<!--<img src="../includes/dartboard.jpg" alt="Midnight Crazy Darts. Nice Shot!" title="Midnight Crazy Logo" /><br>-->
<h1>UWM IPTracker</h1> 
<h2>Team Wolf | IST 490 Senior Capstone Project</h2> 
<Table width="1000" cellpadding="10"><tr><td align="right">

<?php
if (!isset($_SESSION['email'])){
	echo ("<a href=../home/login.php>Login</a> | ");
	echo ("<a href=../home/register.php>Register</a> | "); 
	echo ("<a href=../home/forgot.php>Forgot Password?</a>");  
} else {
	echo ("<a href=../home/logout.php>Logout</a>"); 
} 
?>

<p></td></tr></table>

<table width="1000" cellpadding="10"><td width="100" valign="top">

<?php
if (!isset($_SESSION['email'])){
	echo ("<a href=../home/index.php>Home</a><p>");
	echo ("<a href=../home/link.php>Links</a>");  
}else {
	echo ("<a href=../home/index.php>Home</a><p>");
	echo ("<a href=../stats/index.php>My IP's</a><p>"); 
	echo ("<a href=../contact/contact.php>Contact us</a><p>"); 
} 
?>

</td><td valign="top">

