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
		$id=$_GET['id'];  
		$query = "SELECT id, subnet_id, INET_NTOA(address) AS address, device_name, note FROM device WHERE id=$id";  
		$result = @mysqli_query($dbc,$query); 
		$num = mysqli_num_rows($result);
		
		if ($num > 0) 
		{ // If it ran OK, display all the records. 
			while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
			{ 
?> 				
				<div class="add-contain">
				<div class="add-head"><p><strong>Edit</strong> <span class="dev-text">Device</span></p></div>
				<div>
					<form class="add-form" action="update_device.php" method="post">
					<ul>
						<li>
							<label>Subnet</label>
							<select id="subnet" name="subnet">
							<option value="">Please select a subnet...</option>
							<?php
								require_once ('../../mysqli_connect.php'); 
								$result2 = mysqli_query($dbc, "SELECT id, INET_NTOA(address) AS address FROM subnet");
								while($row2 = mysqli_fetch_array($result2)){
									echo "<option value='" . $row2['id'] . "'>" . $row2['address'] . "</option>";
								}
							?>
							</select>
							<span class="required-text">REQUIRED</span><p></p>
						</li>
						<li>
							<label>IP Address</label><input type="text" name="address" size=50 maxlength=15 value="<?php echo $row['address'];?>"><span class="required-text">REQUIRED</span>
						</li>
						<li>
							<label>Name</label><input type="text" name="device_name" size=50 maxlength=40 value="<?php echo $row['device_name'];?>"><span class="required-text">REQUIRED</span>
						</li>
						<li>
							<label>Notes</label><textarea name="note" rows=2 cols=100></textarea>
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