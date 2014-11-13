<?php # index.php
session_start();
//check session first
if (!isset($_SESSION['email'])){
include ('../includes/header.php');
}else
{
include ('../includes/header.php');
}
?>

<div class="gameRules">
<h1>MioSoft IP Address Tracker</h1>
	
    <h3 style="color:#FFF; padding-top:12px; font-weight:lighter; text-align:center; border-top:2px solid #FFFFFF">Instructions</h3>
    <p></p>
    <ul>
    <li class="ruleItem">Welcome to the UWM MIOsoft IP tracker. Please register for an account so you can keep track of your IPv4 addresses today!</li>
    
    </ul>
</div>

<?php
include ('../includes/footer.php');
?>
