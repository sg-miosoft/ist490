<?php
session_start();
//check the session
if (!isset($_SESSION['email'])){
	echo "You are not logged in!";
	exit();
}else{
	if ($_POST['id']){
		//include the header
		include ("../includes/header.php");
		require_once ('../../mysqli_connect.php');
		#execute UPDATE statement
		$id = mysqli_real_escape_string($dbc,$_POST['id']); 
		$address = mysqli_real_escape_string($dbc,$_POST['address']); 
		$subnet_name = mysqli_real_escape_string($dbc,$_POST['subnet_name']); 
		$mask = mysqli_real_escape_string($dbc,$_POST['mask']); 
		$gateway = mysqli_real_escape_string($dbc,$_POST['gateway']); 
		$note = mysqli_real_escape_string($dbc,$_POST['note']); 
		
		$query = "UPDATE subnet SET 
		address=INET_ATON('$address'),
		subnet_name='$subnet_name',
		mask=INET_ATON('$mask'),
		gateway=INET_ATON('$gateway'),
		note='$note',
		WHERE id='$id'"; 
		$result = @mysqli_query($dbc,$query); 
		if ($result){
			echo "<center><p><b>The selected record has been updated.</b></p>"; 
			echo "<a href=index.php>home</a></center>"; 
		}else {
			echo "<p>The record could not be updated due to a system error: " . mysqli_error($dbc) . "</p>"; 
		}
		mysqli_close($dbc);
		//include the footer
		include ("../includes/footer.php");
	}
	else{echo "<p>The form was not submitted correctly. Try again. " . mysqli_error($db) . "</p>";}
}

?>
