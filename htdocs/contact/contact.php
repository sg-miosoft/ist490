<?php
session_start();
//check session first
if (!isset($_SESSION['email'])){
	echo "You are not logged in!";
	exit();
}else{

	include ("../includes/header.php");
?>

	<div class="contactContain">
	<h2>Contact Us</h2><hr />
	<p><strong>Owner: </strong> MIOsoft Corporation & UWM Team Wolf IST 490</p>
    <p><strong>E-mail Address: </strong> george3@uwm.edu</p>
	<p><strong>Phone: </strong> 608-210-1182</p>

	<a href="http://www4.uwm.edu">
      
    	<img src="http://s.c.lnkd.licdn.com/scds/common/u/img/webpromo/btn_viewmy_160x33.png" width="175" height="36" border="0" alt="View Ryan Kethcart's profile on LinkedIn">
        
    </a><p></p>
    </div>
	
<?php
	include ("../includes/footer.php");
}
?>
