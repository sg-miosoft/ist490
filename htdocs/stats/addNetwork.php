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
	if ($_POST['submitted']){
		$network_name = mysqli_real_escape_string($dbc,$_POST['network_name']); 
		$address = mysqli_real_escape_string($dbc,$_POST['address']); 
		$mask = mysqli_real_escape_string($dbc,$_POST['mask']); 
		$gateway = mysqli_real_escape_string($dbc,$_POST['gateway']); 
		$note = mysqli_real_escape_string($dbc,$_POST['note']); 
		
		$u_id=$_SESSION['user_id'];
				
	
		$query="INSERT INTO network (note,network_name,address,mask,gateway) 
			Values ('$note','$network_name',INET_ATON('$address'),INET_ATON('$mask'),INET_ATON('$gateway'))"; 
		$result=@mysqli_query($dbc,$query); 
		$network_id = mysqli_insert_id($dbc);
		if ($result){
			echo "<center><p><b>A new record (id: '$network_id') has been added.</b></p>"; 
			echo "<a href=index.php>Show All Records</a></center>"; 			
		}
		else{
			echo "<p>The record could not be added due to a system error: " . mysqli_error($dbc) . "</p>"; 
		}

		// only if submitted by the form
		mysqli_close($dbc);
	}
?>
	<form action="<?php echo $PHP_SELF;?>" method="post">
		Network Name: <input name="network_name" size=50 value="<?php echo $row['network_name'];?>"> <p>
		IP Address: <input name="address" size=50 value="<?php echo $row['address'];?>"> <p>
		Subnet Mask: <input name="mask" size=50 value="<?php echo $row['mask'];?>"> <p>
		Default Gateway: <input name="gateway" size=50 value="<?php echo $row['gateway'];?>"> <p>
		Note: <input name="note" size=50 value="<?php echo $row['note'];?>"> <p>
		<br>
		<p>
		<input type=submit value=submit>
		<input type=reset value=reset>
		<input type=hidden name=submitted value=true>
	</form>

<?php
	//include the footer
	include ("../includes/footer.php");
}
?>



