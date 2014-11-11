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

<h4>Welcome to the UWM MIOsoft IP tracker. Please register for an account so you can keep track of your IPv4 addresses today!</h4>

<?php
include ('../includes/footer.php');
?>
