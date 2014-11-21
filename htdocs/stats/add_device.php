<?php
session_start();

//check session first
if(!isset($_SESSION['email']))
{
	echo "You are not logged in!";
	exit();
}
else
{
	//include the header
	include ("../includes/header.php");
	include ("../includes/functions.php");
	require_once ('../../mysqli_connect.php'); 
	if($_POST['submitted'])
	{
		$device_name = mysqli_real_escape_string($dbc,$_POST['device_name']); 
		$address = mysqli_real_escape_string($dbc,$_POST['address']); 
		$subnet = mysqli_real_escape_string($dbc,$_POST['subnet']); 
		$note = mysqli_real_escape_string($dbc,$_POST['note']); 		
		$u_id=$_SESSION['user_id'];
	
		$query="INSERT INTO device (subnet_id,address,device_name,note) 
			Values ('$subnet',INET_ATON('$address'),'$device_name','$note')"; 
		$result=@mysqli_query($dbc,$query); 
		$device_id = mysqli_insert_id($dbc);
		if ($result)
		{
			echo "<div class='successAdd'>";
			echo "<p style='color:#000;'><b>A new record (id: '$device_id') has been added.</b></p>"; 
			echo "<a href=index.php>Show All Records</a><br />";
			echo "</div>";
		}
		else
		{
			echo "<div class='failAdd'>";
			echo "<p><b>The record could not be added due to a system error: " . mysqli_error($dbc) . "</b></p>"; 
			echo "</div>";
		}
		
		// only if submitted by the form
		mysqli_close($dbc);
	}
		
	whichPageMenuDisplay();

?>

	<div class="add-contain">
		<div class="add-head"><p><strong>Add</strong> <span class="dev-text">Device</span></p></div>
		<div>
			<form class="add-form" action="add_device.php" method="post">
			<ul>
				<li>
					<label>Subnet</label>
					<select id="subnet" name="subnet">
					<option value="">Please select a subnet...</option>
					<?php
						require_once ('../../mysqli_connect.php'); 
						$result = mysqli_query($dbc, "SELECT id, INET_NTOA(address) AS address FROM subnet");
						while($row = mysqli_fetch_array($result)){
							echo "<option value='" . $row['id'] . "'>" . $row['address'] . "</option>";
						}
					?>
					</select>
				</li>
				<li>
					<label>IP Address</label>
					<input type="text" name="address" placeholder="192.168.0.1" size=50 maxlength=15>
				</li>
				<li>
					<label>Name</label>
					<input type="text" name="device_name" placeholder="Switch A" size=50 maxlength=40>
				</li>
				<li>		
					<label>Notes</label>
					<textarea name="notes" rows=2 cols=100></textarea>
				</li>
			</ul>
			<hr>
			<input type="submit" class="submitButton" value="Save">
			<input type="reset" class="resetButton" value="Cancel">
			<input type="hidden" name="submitted" value="true">
			</form>
		</div>
	</div>

<?php
	//include the footer
	include ("../includes/footer.php");
}
?>


