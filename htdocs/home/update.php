<?php
//check session first
if(!isset($_SESSION['email']))
{
	header("Location: https://uwm-iptracker.miosoft.com/home/login.php"); 
}
else
{
	//include the header
	include ("../includes/header.php");
	require_once ('../../mysqli_connect.php'); 
	$type=$_GET['type'];
	if(strcmp($type,"subnet") == 0)
	{
		if($_POST['id'])
		{
			$id = mysqli_real_escape_string($dbc,$_POST['id']); 
			$address = mysqli_real_escape_string($dbc,$_POST['address']); 
			$subnet_name = mysqli_real_escape_string($dbc,$_POST['subnet_name']); 
			$mask = mysqli_real_escape_string($dbc,$_POST['mask']); 
			$gateway = mysqli_real_escape_string($dbc,$_POST['gateway']); 
			$note = mysqli_real_escape_string($dbc,$_POST['note']); 
			
			$post_subnet_query = "UPDATE subnet SET 
			address=INET_ATON('$address'),
			subnet_name='$subnet_name',
			mask=INET_ATON('$mask'),
			gateway=INET_ATON('$gateway'),
			note='$note'
			WHERE id='$id'"; 
			$post_subnet_result = @mysqli_query($dbc,$post_subnet_query); 
			if($post_subnet_result)
			{
				echo "<center><p><b>The selected record has been updated.</b></p>"; 
				echo "<a href=index.php>home</a></center>"; 
			}
			else 
			{
				echo "<p>The record could not be updated due to a system error: " . mysqli_error($dbc) . "</p>"; 
			}
			mysqli_close($dbc);
		}
		else
		{
			$id=$_GET['id'];  
			$get_subnet_query = "SELECT id, INET_NTOA(address) AS address, subnet_name, INET_NTOA(mask) AS mask, INET_NTOA(gateway) AS gateway, note FROM subnet WHERE id=$id";  
			$get_subnet_result = @mysqli_query($dbc,$get_subnet_query); 
			$get_subnet_num = mysqli_num_rows($get_subnet_result); 
			if($get_subnet_num > 0) 
			{ // If it ran OK, display all the records. 
				while ($get_subnet_row = mysqli_fetch_array($get_subnet_result,  MYSQLI_ASSOC))
				{ 
?> 
					<div class="add-contain">
					<div class="add-head"><p><strong>Edit</strong> <span class="dev-text">Subnet</span></p></div>
					<div>
						<form class="add-form" action="update.php?type=subnet" method="post">
							<ul>
								<li>
									<label>Subnet Name</label>
									<input type="text" placeholder="DMZ Zone" name="subnet_name" size=50 value="<?php echo $get_subnet_row['subnet_name'];?>">
								</li>
								<li>
									<label>IP Address</label>
									<input type="text" placeholder="192.168.0.1" name="address" size=50 maxlength=15 value="<?php echo $get_subnet_row['address'];?>">
								</li>
								<li>
									<label>Subnet Mask</label>
									<input type="text" placeholder="255.255.255.0" name="mask" size=50 maxlength=15 value="<?php echo $get_subnet_row['mask'];?>">
								</li>
								<li>
									<label>Gateway</label>
									<input type="text" placeholder="192.168.1.1" name="gateway" size=50 maxlength=15 value="<?php echo $get_subnet_row['gateway'];?>">
								</li>
								<li>
									<label>Notes</label>
									<textarea name="note" rows=2 cols=100><?php echo $get_subnet_row['note'];?></textarea>
								</li>
							</ul>
							<hr>
							
							<dialog id="myDialog">
								<input type="button" id="close" value="X" onClick="document.getElementById('myDialog').close();">
								<h2>Delete the "DMZ Zone" subnet?</h2>
								<div class="fake-hr"></div>
								<p><em>Note </em>: All associated devices will lose their IP addresses.</p>
								<input type="button" class="deleteButtonModal" value="Delete">
								<input type="button" class="resetButtonModal" value="Cancel" onClick="document.getElementById('myDialog').close();">    
							</dialog>
					
							<input type="button" class="deleteButton" value="Delete" onClick="document.getElementById('myDialog').showModal();">
							<input type="submit" class="submitButton" value="update"> 
							<input type="reset" class="resetButton" value="Cancel">
							<input type="hidden" name="id" value="<?php echo $get_subnet_row['id']; ?>">
						</form>
					</div>
				</div>
<?php 
				}
			}
			mysqli_close($dbc);			
		}
	}
	elseif(strcmp($type,"device") == 0)
	{
		if($_POST['id'])
		{
			$id = mysqli_real_escape_string($dbc,$_POST['id']); 
			$device_name = mysqli_real_escape_string($dbc,$_POST['device_name']); 
			$note = mysqli_real_escape_string($dbc,$_POST['note']); 
			$address = mysqli_real_escape_string($dbc,$_POST['address']); 
			$subnet_id = mysqli_real_escape_string($dbc,$_POST['subnet']); 
					
			$post_device_query = "UPDATE device SET 
			device_name='$device_name',
			subnet_id='$subnet_id',
			note='$note',
			address=INET_ATON('$address')	
			WHERE id='$id'"; 
			$post_device_result = @mysqli_query($dbc,$post_device_query); 
			if($post_device_result)
			{
				echo "<center><p><b>The selected record has been updated.</b></p>"; 
				echo "<a href=index.php>home</a></center>"; 
			}
			else
			{
				echo "<p>The record could not be updated due to a system error: " . mysqli_error($dbc) . "</p>"; 
			}
			mysqli_close($dbc);
		}
		else
		{
			$id=$_GET['id'];  
			$get_device_query = "SELECT id, subnet_id, INET_NTOA(address) AS address, device_name, note FROM device WHERE id=$id";  
			$get_device_result = @mysqli_query($dbc,$get_device_query); 
			$get_device_num = mysqli_num_rows($get_device_result);
			
			if($get_device_num > 0) 
			{ // If it ran OK, display all the records. 
				while ($get_device_row = mysqli_fetch_array($get_device_result, MYSQLI_ASSOC))
				{ 
?> 				
					<div class="add-contain">
					<div class="add-head"><p><strong>Edit</strong> <span class="dev-text">Device</span></p></div>
					<div>
						<form class="add-form" action="update.php?type=device" method="post">
						<ul>
							<li>
								<label>Subnet</label>
								<select id="subnet" name="subnet">
								<option value="">Please select a subnet...</option>
								<?php
									//require_once ('../../mysqli_connect.php'); 
									$result2 = mysqli_query($dbc, "SELECT id, INET_NTOA(address) AS address FROM subnet");
									while($get_device_row2 = mysqli_fetch_array($result2)){
										echo "<option value='" . $get_device_row2['id'] . "'>" . $get_device_row2['address'] . "</option>";
									}
								?>
								</select>
							</li>
							<li>
								<label>IP Address</label>
								<input type="text" name="address" size=50 maxlength=15 value="<?php echo $get_device_row['address'];?>">
							</li>
							<li>
								<label>Name</label>
								<input type="text" name="device_name" size=50 maxlength=40 value="<?php echo $get_device_row['device_name'];?>">
							</li>
							<li>
								<label>Notes</label><textarea name="note" rows=2 cols=100><?php echo $get_device_row['note'];?></textarea>
							</li>
						</ul>
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
							<input type="submit" class="submitButton" value="update"> 
							<input type="reset" class="resetButton" value="Cancel">
							<input type="hidden" name="id" value="<?php echo $get_device_row['id']; ?>"> 
						</form> 
						</div>
					</div>
<?php 
				} //end while statement 
			}
			mysqli_close($dbc);
		}
	}
	//include the footer
	include ("../includes/footer.php");
}
?>