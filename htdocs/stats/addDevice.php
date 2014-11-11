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
		$device_name = mysqli_real_escape_string($dbc,$_POST['device_name']); 
		$address = mysqli_real_escape_string($dbc,$_POST['address']); 
		$network = mysqli_real_escape_string($dbc,$_POST['network']); 
		$note = mysqli_real_escape_string($dbc,$_POST['note']); 
		
		$u_id=$_SESSION['user_id'];
		
	
		$query="INSERT INTO device (network_id,address,device_name,note) 
			Values ('$network',INET_ATON('$address'),'$device_name','$note')"; 
		$result=@mysqli_query($dbc,$query); 
		$device_id = mysqli_insert_id($dbc);
		if ($result){
			echo "<center><p><b>A new record (id: '$device_id') has been added.</b></p>"; 
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
		Device Name: <input name="device_name" size=50 value="<?php echo $row['device_name'];?>"> <p>
		IP Address: <input name="address" size=50 value="<?php echo $row['address'];?>"> <p>
		<label for="network">Network:</label>
		<select id="network" name="network">
			<option value="">Please select a network...</option>
			<?php
				require_once ('../../mysqli_connect.php'); 
				$result = mysqli_query($dbc, "SELECT id, INET_NTOA(address) AS address FROM network");
				while($row = mysqli_fetch_array($result)){
					echo "<option value='" . $row['id'] . "'>" . $row['address'] . "</option>";
				}
			?>
		</select><p>
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



