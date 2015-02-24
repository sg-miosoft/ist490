<?php
//check session first
if($_SESSION['readonly'] == 1 or !isset($_SESSION['email']))
{
	header("Location: https://iptracker.msn.miosoft.com/index.php");
}
else
{
	//include the header
	include ("includes/header.php");
	require_once ('../mysqli_connect.php'); 
	$type=$_GET['type'];
?>
	<script>
		function openModal(status,message,action)
		{
			
			if(status === 'success')
			{
				var header = 'Success!';
				document.getElementById('updateDialog').className = 'success-dialog';
			}
			else if(status === 'fail')
			{
				var header = 'Error!';
				document.getElementById('updateDialog').className = 'fail-dialog';
			}
			
			if(action === 'index')
			{
				document.getElementById('btnIndex').style.display = 'display';
				document.getElementById('btnIndex').disabled = false;
				document.getElementById('btnClose').style.display = 'none';
				document.getElementById('btnClose').disabled = true;
			}
			else if(action === 'close')
			{
				document.getElementById('btnIndex').style.display = 'none';
				document.getElementById('btnIndex').disabled = true;
				document.getElementById('btnClose').style.display = 'display';
				document.getElementById('btnClose').disabled = false;
			}
			document.getElementById('dialogP').innerHTML = message;
			document.getElementById('dialogH2').innerText = header;
			document.getElementById('updateDialog').showModal();
		}
	</script>
	
	<dialog id="updateDialog">
		<input type="button" id="closeX" value="X" onClick="window.location.href='index.php'">
		<h2 id="dialogH2"></h2>
		
		<p id="dialogP"></p>
		<!--<form id="dialogForm">-->
			<button id="btnClose" class="idClose" form="dialogForm" type="submit" onClick="window.location.href='index.php'">Close</button>
			<button id="btnIndex" class="idClose" form="dialogForm" type="submit" onClick="window.location.href='index.php'">Close</button>
		<!--</form>-->
	</dialog>
<?php

	if(strcmp($type,"subnet") == 0)
	{
		if($_POST['id'])
		{
			$id = mysqli_real_escape_string($dbc,$_POST['id']); 
			$address = mysqli_real_escape_string($dbc,$_POST['address']);
				if(!filter_var($address, FILTER_VALIDATE_IP))
				{
					echo "<script>openModal('fail','IP Address: " . $address . " is not a valid IP address\.','close')";
				}
			$subnet_name = mysqli_real_escape_string($dbc,$_POST['subnet_name']);
			$mask = mysqli_real_escape_string($dbc,$_POST['mask']); 
				if(!filter_var($mask, FILTER_VALIDATE_IP))
				{
					echo "<script>openModal('fail','Subnet mask: " . $mask . " is not a valid IP address\.','close')";
				}
			$gateway = mysqli_real_escape_string($dbc,$_POST['gateway']);
				if(!filter_var($gateway, FILTER_VALIDATE_IP))
				{
					echo "<script>openModal('fail','Gateway: " . $gateway . " is not a valid IP address\.','close')";
				}
			$note = mysqli_real_escape_string($dbc,$_POST['note']); 
			
			$post_subnet_query = "UPDATE subnet SET 
			address=INET_ATON('" . $address . "'),
			subnet_name='" . $subnet_name . "',
			mask=INET_ATON('" . $mask . "'),
			gateway=INET_ATON('" . $gateway . "'),
			note='" . $note . "'
			WHERE id='" . $id . "'"; 
			$post_subnet_result = @mysqli_query($dbc,$post_subnet_query); 
			if($post_subnet_result)
			{
				$message = "The " . $subnet_name . " subnet has been updated!";
				echo "<script>openModal('success','" . $message . "','index');</script>";				
			}
			else 
			{
				echo "<script>openModal('fail','" . mysqli_real_escape_string($dbc,mysqli_error($dbc)) . "','close');</script>";				
			}
			mysqli_close($dbc);
		}
		else
		{
			$id=$_GET['id'];  
			$get_subnet_query = "SELECT id, INET_NTOA(address) AS address, subnet_name, INET_NTOA(mask) AS mask, INET_NTOA(gateway) AS gateway, note FROM subnet WHERE id=" . $id;  
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
							
							<input type="submit" class="submit-button" value="Update"> 
							<input type="reset" class="reset-button" value="Reset">
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
				if(!filter_var($address, FILTER_VALIDATE_IP))
				{
					echo "<script>openModal('fail','IP Address: " . $address . " is not a valid IP address\.','close')";
				}
			$subnet_id = mysqli_real_escape_string($dbc,$_POST['subnet']); 
					
			$post_device_query = "UPDATE device SET 
			device_name='" . $device_name . "',
			subnet_id='" . $subnet_id . "',
			note='" . $note . "',
			address=INET_ATON('" . $address . "')	
			WHERE id='" . $id . "'"; 
			$post_device_result = @mysqli_query($dbc,$post_device_query); 
			if($post_device_result)
			{
				$message = $device_name . " has been updated!";
				echo "<script>openModal('success','" . $message . "','index');</script>";
			}
			else
			{
				echo "<script>openModal('fail','" . mysqli_real_escape_string($dbc,mysqli_error($dbc)) . "','close');</script>"; 
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
									$result2 = mysqli_query($dbc, "SELECT id, INET_NTOA(address) AS address FROM subnet WHERE id = " . $get_device_row['subnet_id']);
									while($get_device_row2 = mysqli_fetch_array($result2))
									{
										echo "<option selected value='" . $get_device_row2['id'] . "'>" . $get_device_row2['address'] . "</option>";
									}
									$result3 = mysqli_query($dbc, "SELECT id, INET_NTOA(address) AS address FROM subnet WHERE id != " . $get_device_row['subnet_id']);
									while($get_device_row3 = mysqli_fetch_array($result3))
									{
										echo "<option value='" . $get_device_row3['id'] . "'>" . $get_device_row3['address'] . "</option>";
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
							
							<input type="submit" class="submit-button" value="Update"> 
							<input type="reset" class="reset-button" value="Cancel">
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
	include("includes/footer.php");
}
?>