<!DOCTYPE html>
<html>
<head>
<title>MIOsoft IP Tracker</title>
	<link rel="stylesheet" type="text/css" href="../css/style.css">
	<link href='https://fonts.googleapis.com/css?family=Lato:300,400,700,900' rel='stylesheet' type='text/css'>
<script>
	function addSubnetdark() {
		document.getElementById("subnet-only").src='../images/dark-add-subnet.png';
	}
	
	function addSubnetdefault() {
		document.getElementById("subnet-only").src='../images/add-subnet-img.png';
	}
	
	function addDevicedark() {
		document.getElementById("device-only").src='../images/dark-add-device.png';
	}
	
	function addDevicedefault() {
		document.getElementById("device-only").src='../images/add-device-img.png';
	}
</script>
</head>
<body>

<header>
	<a href="https://uwm-iptracker.miosoft.com"><img src="../images/miosoft_website.png" id="logo" alt="MIOsoft Logo" title="MIOsoft Logo">
    <div class="product-line">
    	IP ADDRESS TRACKER
    </div></a>
    
    <form class="header-search" method="post"> 
        <input type="search" id="search-input" name="ipsearch" placeholder="Search">
    </form>
</header>



<?php
if (!isset($_SESSION['email'])){
	echo ("<nav>");
	echo ("<ul>");
	echo ("<li><a href=../home/index.php>Home</a></li>");
	echo ("<li><a href=../home/login.php>Login</a></li>");
	echo ("<li><a href=../home/register.php>Register</a></li>");
	echo ("<li><a href=../home/forgot.php>Forgot Password?</a></li>");
	echo ("</ul>");
	echo ("</nav>");  
}else {
	echo ("<nav>");
	echo ("<ul>");
	echo ("<li><a class='homeNav' href=../home/index.php>Home</a></li>");
	echo ("<li><a class='regNav' href=../stats/index.php>My IP's</a></li>"); 
	echo ("<li><a class='regNav' href=../contact/contact.php>Contact us</a></li>");
	echo ("<li><a class='logoutNav' href=../home/logout.php>Logout</a></li>");
	echo ("</ul>");
	echo ("</nav>");
} 
?>
<section>




