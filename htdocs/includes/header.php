<html>
<head>
<title>MIOsoft IP Tracker</title>
<link rel="stylesheet" type="text/css" href="../css/style.css">
<link href='http://fonts.googleapis.com/css?family=Lato:300,400,700,900' rel='stylesheet' type='text/css'>
</head>
<body>

<div class="header-contain">
	<a href="https://uwm-iptracker.miosoft.com"><img src="../images/miosoft_website.png" id="logo" alt="MIOsoft Logo" title="MIOsoft Logo">
    <div class="product-line">
    	IP ADDRESS TRACKER
    </div></a>
    
    <form class="header-search" method="post"> 
        <input type="search" id="search-input" name="ipsearch" placeholder="Search">
    </form>
</div>

<center>
<table width="800" cellpadding="10"><td width="100" valign="top">

<?php
if (!isset($_SESSION['email'])){
	echo ("<div class='vertNavContain'>");
	echo ("<a class='homeNav' href=../home/index.php>Home</a>");
	echo ("<a class='loginNav' href=../home/login.php>Login</a>");
	echo ("<a class='regNav' href=../home/register.php>Register</a>");
	echo ("<a class='forgotNav' href=../home/forgot.php>Forgot Password?</a>");
	echo ("</div>");  
}else {
	echo ("<div class='vertNavContainLogged'>");
	echo ("<a class='homeNav' href=../home/index.php>Home</a>");
	echo ("<a class='regNav' href=../stats/index.php>My IP's</a>"); 
	echo ("<a class='regNav' href=../contact/contact.php>Contact us</a>");
	echo ("<a class='logoutNav' href=../home/logout.php>Logout</a>");
	echo ("</div>");
} 
?>

</td><td valign="top">



