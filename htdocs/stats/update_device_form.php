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
    $query = "SELECT id, network_id, INET_NTOA(address) AS address, device_name, note FROM device WHERE id=$id";  
    $result = @mysqli_query($dbc,$query); 
    $num = mysqli_num_rows($result);
	
	echo ("<br />");
	echo ("<div class='bookmarkMenu-add'>");
	echo ("<p class='bottom-space'><a class='add-subnet' href='addSubnet.php' onmouseover='addSubnetdark();' onmouseout='addSubnetdefault();'><img id='subnet-only' class='subnet-img' src='../images/add-subnet-img.png' onmouseover=\"this.src='../images/dark-add-subnet.png'\"\ onmouseout=\"this.src='../images/add-subnet-img.png'\"\><span class='subnet-contain'>Add <strong>Subnet</strong></span></a></p>");
		
	if (is_chamber())
	{
		echo ("<a class='add-device-active' href='addDevice.php' onmouseover='addDevicedark();' onmouseout='addDevicedefault();'><p class='device-active' onmouseover='addDevicedark();' onmouseout='addDevicedefault();'><img id='device-only' class='device-img' src='../images/add-device-img.png' onmouseover=\"this.src='../images/dark-add-device.png'\"\ onmouseout=\"this.src='../images/add-device-img.png'\"\><span class='device-contain-active'>Add <strong>Device</strong></span></p></a>");
	}
	else
	{
		echo ("<p><a class='add-device' href='addDevice.php' onmouseover='addDevicedark();' onmouseout='addDevicedefault();'><img id='device-only' class='device-img' src='../images/add-device-img.png' onmouseover=\"this.src='../images/dark-add-device.png'\"\ onmouseout=\"this.src='../images/add-device-img.png'\"\><span class='device-contain'>Add <strong>Device</strong></span></a></p>");
	}
	echo ("</div><br>");	
	
    if ($num > 0) { // If it ran OK, display all the records. 
        while ($row = mysqli_fetch_array($result,  MYSQLI_ASSOC)){ 
?> 
            <div class="addDeviceContain">
		<div class="add-device-head"><p><strong>Add</strong> <span class="dev-text">Device</span></p></div>
		<div class="add-form-contain">
			<form class="add-device-form" action="<? echo $PHP_SELF;?>" method="post">
			<span class="align-form-text-sub">Network </span>
				<select id="network" name="network">
				<option value="">Please select a network...</option>
				<?php
					require_once ('../../mysqli_connect.php'); 
					$result = mysqli_query($dbc, "SELECT id, INET_NTOA(address) AS address FROM network");
					while($row = mysqli_fetch_array($result)){
						echo "<option value='" . $row['id'] . "'>" . $row['address'] . "</option>";
					}
				?>
				</select>
				<span class="required-text">REQUIRED</span><p></p>
				<!--name="Answer" for ip address and name-->
			<span class="align-form-text-ip">IP Address </span><input type="text" class="addURL" name="address" size=50 maxlength=15 value="<?php echo $row['address'];?>"><span class="required-text">REQUIRED</span><p></p>
			<span class="align-form-text-name">Name </span><input type="text" class="addURL" name="device_name" size=50 maxlength=40 value="<?php echo $row['device_name'];?>"><span class="required-text">REQUIRED</span><p></p>
			<p></p>
			<!--below span: name="Comment"-->
			<span class="align-form-text-notes">Notes </span><textarea name="notes"  class="add-device-notes" rows=2 cols=100></textarea>
			<p></p>
			<hr>
				<dialog id="myDialogDevice">
					<input type="button" id="close" value="X" onClick="document.getElementById('myDialogDevice').close();">
					<h2>Delete the "DMZ Zone" subnet?</h2>
					<div class="fake-hr"></div>
					<p><em>Note </em>: All associated devices will lose their IP addresses.</p>
					<input type="button" class="deleteButtonModal" value="Delete">
					<input type="button" class="resetButtonModal" value="Cancel" onClick="document.getElementById('myDialogDevice').close();">    
				</dialog>
        
				<input type="button" class="deleteButton" value="Delete" onClick="document.getElementById('myDialogDevice').showModal();">
				<input type=submit value=update> 
				<input type=reset value=reset> 
				<input type=hidden name="id" value="<?php echo $row['id']; ?>"> 
            </form> 
			</div>
		</div>
<?php 
        } //end while statement 
    } //end if statement 
    mysqli_close($dbc); 
    //include the footer 
    include ("../includes/footer.php"); 
} 
?> 