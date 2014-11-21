<?php
session_start();

function whichObject($str = "") 
{
	$showstuff = false;
	if ($str == "") 
	{
		$str = $_SERVER['REQUEST_URI'];
	}
	if (stripos($str,'add_subnet.php') !== false) 
	{
		$showstuff = true;
	}
	return $showstuff;
}
	
//check session first
if (!isset($_SESSION['email'])){
	echo "You are not logged in!";
	exit();
}else{
	//include the header
	include ("../includes/header.php");
	require_once ('../../mysqli_connect.php'); 
	if ($_POST['submitted']){
		$subnet_name = mysqli_real_escape_string($dbc,$_POST['subnet_name']); 
		$address = mysqli_real_escape_string($dbc,$_POST['address']); 
		$mask = mysqli_real_escape_string($dbc,$_POST['mask']); 
		$gateway = mysqli_real_escape_string($dbc,$_POST['gateway']); 
		$note = mysqli_real_escape_string($dbc,$_POST['note']); 
		
		$query="INSERT INTO subnet (note,subnet_name,address,mask,gateway) 
			Values ('$note','$subnet_name',INET_ATON('$address'),INET_ATON('$mask'),INET_ATON('$gateway'))"; 
		$result=@mysqli_query($dbc,$query); 
		$subnet_id = mysqli_insert_id($dbc);
		if ($result){
			echo "<div class='successAdd'>";
			echo "<p style='color:#000;'><b>A new subnet (id: '$subnet_id') has been added.</b></p>"; 
			echo "<a href=index.php>Show All Records</a></center>"; 
			echo "</div>";			
		}
		else{
			echo "<div class='failAdd'>";
			echo "<p><b>The record could not be added due to a system error: " . mysqli_error($dbc) . "</b></p>";  
			echo "</div>";			
		}

		// only if submitted by the form
		mysqli_close($dbc);
	}
	
	echo "<br />";
	
	echo "<div class='bookmarkMenu-add'>";
	if(whichObject())
	{
		echo ("<a class='add-subnet-active' href='add_device.php' onmouseover='addSubnetdark();' onmouseout='addSubnetdefault();'><p class='subnet-active'><img id='subnet-only' class='subnet-img' src='../images/add-subnet-img.png' onmouseover=\"this.src='../images/dark-add-subnet.png'\"\ onmouseout=\"this.src='../images/add-subnet-img.png'\"\><span class='subnet-contain-active'>Add <strong>Subnet</strong></span></p></a>");
	} 
	else 
	{
		echo "<p class='bottom-space'><a class='add-subnet' href='add_subnet.php' onmouseover='addSubnetdark();' onmouseout='addSubnetdefault();'><img id='subnet-only' class='subnet-img' src='../images/add-subnet-img.png' onmouseover=\"this.src='../images/dark-add-subnet.png'\"\ onmouseout=\"this.src='../images/add-subnet-img.png'\"\><span class='subnet-contain'>Add <strong>Subnet</strong></span></a></p>";
	}
	echo "<p><a class='add-device' href='add_device.php' onmouseover='addDevicedark();' onmouseout='addDevicedefault();'><img id='device-only' class='device-img' src='../images/add-device-img.png' onmouseover=\"this.src='../images/dark-add-device.png'\"\ onmouseout=\"this.src='../images/add-device-img.png'\"\><span class='device-contain'>Add <strong>Device</strong></span></a></p>";
	echo "</div><br>";

?>

	
	<div class="add-contain">
		<div class="add-head"><p><strong>Add</strong> <span class="dev-text">Subnet</span></p></div>
		<div>
			<form class="add-form" action="add_subnet.php" method="post">
			<ul>
				<li>
					<label for="subnet_name">Subnet Name</label>
					<input type="text" placeholder="DMZ Zone" name="subnet_name" size=50><span class="required-text">REQUIRED</span>
				</li>
				<li>
					<label for="address">IP Address</label>
					<input type="text" placeholder="192.168.0.1" name="address" size=50 maxlength=15 value="<?php echo $row['address'];?>"><span class="required-text">REQUIRED</span>
				</li>
				<li>
					<label for="mask">Subnet Mask</label>
					<input type="text" placeholder="255.255.255.0" name="mask" size=50 maxlength=15 value="<?php echo $row['mask'];?>"><span class="required-text">REQUIRED</span>
				</li>
				<li>
					<label for="gateway">Gateway</label>
					<input type="text" placeholder="192.168.1.1" name="gateway" size=50 maxlength=15 value="<?php echo $row['gateway'];?>"><span class="required-text">REQUIRED</span>
				</li>
				
				<li>
					<label for="note">Notes</label>
					<textarea name="note" rows=2 cols=100></textarea>
				</li>
				<hr>
			</ul>
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



