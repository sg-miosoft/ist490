<?php
session_start();

function is_subnet($str = "") 
{
	$showstuff = false;
	if ($str == "") 
	{
		$str = $_SERVER['REQUEST_URI'];
	}
	if (stripos($str,'addNetwork.php') !== false) 
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
		$network_name = mysqli_real_escape_string($dbc,$_POST['network_name']); 
		$address = mysqli_real_escape_string($dbc,$_POST['address']); 
		$mask = mysqli_real_escape_string($dbc,$_POST['mask']); 
		$gateway = mysqli_real_escape_string($dbc,$_POST['gateway']); 
		$note = mysqli_real_escape_string($dbc,$_POST['note']); 
		
		$u_id=$_SESSION['user_id'];
				
		$query="INSERT INTO network (note,network_name,address,mask,gateway) 
			Values ('$note','$network_name',INET_ATON($address),INET_ATON('$mask'),INET_ATON('$gateway'))"; 
		$result=@mysqli_query($dbc,$query); 
		$network_id = mysqli_insert_id($dbc);
		if ($result){
			echo "<div class='successAdd'>";
			echo "<p style='color:#000;'><b>A new network (id: '$network_id') has been added.</b></p>"; 
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
	if(is_subnet())
	{
		echo ("<a class='add-subnet-active' href='addDevice.php' onmouseover='addSubnetdark();' onmouseout='addSubnetdefault();'><p class='subnet-active'><img id='subnet-only' class='subnet-img' src='../images/add-subnet-img.png' onmouseover=\"this.src='../images/dark-add-subnet.png'\"\ onmouseout=\"this.src='../images/add-subnet-img.png'\"\><span class='subnet-contain-active'>Add <strong>Subnet</strong></span></p></a>");
	} 
	else 
	{
		echo "<p class='bottom-space'><a class='add-subnet' href='addNetwork.php' onmouseover='addSubnetdark();' onmouseout='addSubnetdefault();'><img id='subnet-only' class='subnet-img' src='../images/add-subnet-img.png' onmouseover=\"this.src='../images/dark-add-subnet.png'\"\ onmouseout=\"this.src='../images/add-subnet-img.png'\"\><span class='subnet-contain'>Add <strong>Subnet</strong></span></a></p>";
	}
	echo "<p><a class='add-device' href='addDevice.php' onmouseover='addDevicedark();' onmouseout='addDevicedefault();'><img id='device-only' class='device-img' src='../images/add-device-img.png' onmouseover=\"this.src='../images/dark-add-device.png'\"\ onmouseout=\"this.src='../images/add-device-img.png'\"\><span class='device-contain'>Add <strong>Device</strong></span></a></p>";
	echo "</div><br>";

?>

	
	<div class="addDeviceContain">
		<div class="add-device-head"><p><strong>Add</strong> <span class="dev-text">Subnet</span></p></div>
		<div class="add-form-contain">
			<form class="add-device-form" action="addNetwork.php" method="post">
			<span class="align-form-text-name">Subnet Name </span><input type="text" class="addURL" placeholder="DMZ Zone" name="network_name" size=50><span class="required-text">REQUIRED</span><p></p>
			<span class="align-form-text-sub">IP Address </span><input type="text" class="addBookmarkTitle" placeholder="192.168.0.1" name="address" size=50 maxlength=15 value="<?php echo $row['address'];?>"><span class="required-text">REQUIRED</span><p></p>
			<span class="align-form-text-sub">Subnet Mask </span><input type="text" class="addBookmarkTitle" placeholder="255.255.255.0" name="mask" size=50 maxlength=15 value="<?php echo $row['mask'];?>"><span class="required-text">REQUIRED</span><p></p>
			<span class="align-form-text-sub">Gateway </span><input type="text" class="addBookmarkTitle" placeholder="192.168.1.1" name="gateway" size=50 maxlength=15 value="<?php echo $row['gateway'];?>"><span class="required-text">REQUIRED</span><p></p>
			<p></p>
			<span class="align-form-text-notes">Notes </span><textarea name="note" class="add-device-notes" rows=2 cols=100></textarea>
			<p></p>
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



