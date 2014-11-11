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
		$device_name = mysqli_real_escape_string($dbc,$_POST['device_name']); 
		$note = mysqli_real_escape_string($dbc,$_POST['note']); 
		$address = mysqli_real_escape_string($dbc,$_POST['address']); 
		$network_id = mysqli_real_escape_string($dbc,$_POST['network']); 
				
		$query = "UPDATE device SET 
		device_name='$device_name',
		network_id='$network_id',
		note='$note',
		address=INET_ATON('$address')	
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
