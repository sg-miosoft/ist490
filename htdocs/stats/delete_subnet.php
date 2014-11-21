<?php
session_start();
//check session first
if (!isset($_SESSION['email'])){
	echo "You are not logged in!";
	exit();
}else{
	//include the header
	include ("../includes/header.php");
	require_once ('../../mysqli_connect.php');
	$id=$_GET['id']; 
	$query = "DELETE FROM subnet WHERE id=$id"; 
	$result = @mysqli_query($dbc,$query);
	if ($result){
		echo "The selected subnet has been deleted."; 
	}else {
		echo "The selected subnet could not be deleted."; 
	}
	echo "<p><a href=index.php>Home</a>"; 
	mysqli_close($dbc);
	//include the footer
	include ("../includes/footer.php");
}

?>
