<!DOCTYPE html>
<html>
<head>
	<title>MIOsoft IP Tracker</title>
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="stylesheet" type="text/css" href="css/style2.css">
	<link href='https://fonts.googleapis.com/css?family=Lato:300,400,700,900' rel='stylesheet' type='text/css'>
	
</head>
<body>

<header>
	<div id="logo">
		<a href="https://iptracker.msn.miosoft.com"><img src="images/miosoft_website.png" alt="MIOsoft Logo" title="MIOsoft Logo" />IP ADDRESS TRACKER</a>
	</div>
	

<?php
if(!isset($_SESSION['email'])) //user is not logged in
{
	echo "</header>
	<nav>
		<ul>
			<li><a href=forgot.php>Forgot Password?</a></li>
			<li><a href=login.php>Login</a></li>
		</ul>
	</nav>";
}
elseif($_SESSION['readonly'] == 1)
{
	echo "<div id='search'>
		<form method='post' action='search.php'> 
			<input type='search' id='search-input' name='search' placeholder='Search'>
		</form>
	</div>
	</header>
	<nav>
		<ul>
			<li><a href='index.php'>Home</a></li>
			<li><a href='logout.php'>Logout</a></li>
			<li>Hello: " . $_SESSION['first_name'] . " " . $_SESSION['last_name'] . " (" . $_SESSION['email'] . ")</li>
		</ul>
	</nav>";
}
else
{
	echo "<div id='search'>
		<form method='post' action='search.php'> 
			<input type='search' id='search-input' name='search' placeholder='Search'>
		</form>
	</div>
	</header>
	<nav>
		<ul>
			<li><a href='index.php'>Home</a></li>
			<li><a href='admin.php'>Admin</a></li>
			<li><a href='logout.php'>Logout</a></li>
			<li>Hello: " . $_SESSION['first_name'] . " " . $_SESSION['last_name'] . " (" . $_SESSION['email'] . ")</li>
		</ul>
	</nav>";
	
} 
?>

<section>




