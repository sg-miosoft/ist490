<?php
session_start();

function whichObject($str = "") 
{
	$showstuff = false;
	if ($str == "") 
	{
		$str = $_SERVER['REQUEST_URI'];
	}
	if (stripos($str,'add_device.php') !== false) 
	{
		$showstuff = true;
	}
	return $showstuff;
}
	
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
	
	echo "<br />";
	echo "<div class='bookmarkMenu-add'>";
	echo "<p class='bottom-space'><a class='add-subnet' href='add_subnet.php' onmouseover='addSubnetkdark();' onmouseout='addSubnetdefault();'><img id='subnet-only' class='subnet-img' src='../images/add-subnet-img.png' onmouseover=\"this.src='../images/dark-add-subnet.png'\"\ onmouseout=\"this.src='../images/add-subnet-img.png'\"\><span class='subnet-contain'>Add <strong>Subnet</strong></span></a></p>";
		
	if(whichObject())
	{
		echo "<a class='add-device-active' href='add_device.php' onmouseover='addDevicedark();' onmouseout='addDevicedefault();'><p class='device-active' onmouseover='addDevicedark();' onmouseout='addDevicedefault();'><img id='device-only' class='device-img' src='../images/add-device-img.png' onmouseover=\"this.src='../images/dark-add-device.png'\"\ onmouseout=\"this.src='../images/add-device-img.png'\"\><span class='device-contain-active'>Add <strong>Device</strong></span></p></a>";
	}
	else
	{
		echo "<p><a class='add-device' href='add_device.php' onmouseover='addDevicedark();' onmouseout='addDevicedefault();'><img id='device-only' class='device-img' src='../images/add-device-img.png' onmouseover=\"this.src='../images/dark-add-device.png'\"\ onmouseout=\"this.src='../images/add-device-img.png'\"\><span class='device-contain'>Add <strong>Device</strong></span></a></p>";
	}
	echo ("</div><br>");
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
					<span class="required-text">REQUIRED</span><p></p>
				</li>
				<li>
					<label>IP Address</label>
					<input type="text" class="addURL" name="address" size=50 maxlength=15 value="<?php echo $row['address'];?>"><span class="required-text">REQUIRED</span>
				</li>
				<li>
					<label>Name</label>
					<input type="text" class="addURL" name="device_name" size=50 maxlength=40 value="<?php echo $row['device_name'];?>"><span class="required-text">REQUIRED</span>
				</li>
				<li>		
					<label>Notes</label><textarea name="notes"  class="add-device-notes" rows=2 cols=100></textarea>
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



