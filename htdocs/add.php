<?php
//check session first
if($_SESSION['readonly'] == 1 or !isset($_SESSION['email']))
{
	header("Location: https://uwm-iptracker.miosoft.com/index.php");
}
else
{
	//include the header
	include ("includes/header.php");
	include ("includes/functions.php");
	require_once ('../mysqli_connect.php'); 
	$type=$_GET['type']; 

?>
	<script>
		function openModal(status,message)
		{
			var action = 'index.php';
			if(status === 'success')
			{
				var header = 'Success!';
				document.getElementById('addDialog').className = 'success-dialog';
			}
			else if(status === 'fail')
			{
				var header = 'Error!';
				document.getElementById('addDialog').className = 'fail-dialog';
			}
			document.getElementById('dialogP').innerHTML = message;
			document.getElementById('dialogH2').innerText = header;
			document.getElementById('dialogForm').action = action;
			document.getElementById('addDialog').showModal();
		}
	</script>
	
	<dialog id="addDialog">
		<input type="button" id="closeX" value="X" onClick="document.getElementById('addDialog').close();">
		<h2 id="dialogH2"></h2>
		
		<p id="dialogP"></p>
		<form id="dialogForm">
			<button id="close" form="dialogForm" type="submit">Close</button>
		</form>
	</dialog>
<?php

	if(strcmp($type,"subnet") == 0)
	{
		if ($_POST['submitted'])
		{
			$subnet_name = mysqli_real_escape_string($dbc,$_POST['subnet_name']); 
			$address = mysqli_real_escape_string($dbc,$_POST['address']); 
			$mask = mysqli_real_escape_string($dbc,$_POST['mask']); 
			$gateway = mysqli_real_escape_string($dbc,$_POST['gateway']); 
			$note = mysqli_real_escape_string($dbc,$_POST['note']); 
			
			$query="INSERT INTO subnet (note,subnet_name,address,mask,gateway) 
				Values ('$note','$subnet_name',INET_ATON('$address'),INET_ATON('$mask'),INET_ATON('$gateway'))"; 
			$result=@mysqli_query($dbc,$query); 
			$subnet_id = mysqli_insert_id($dbc);
			if ($result)
			{
				$message = "The " . $subnet_name . " subnet has been added!";
				echo "<script>openModal('success','" . $message . "');</script>";
			}
			else
			{
				echo "<script>openModal('fail','" . mysqli_real_escape_string($dbc,mysqli_error($dbc)) . "');</script>";
			}

			// only if submitted by the form
			mysqli_close($dbc);
		}
		
		whichPageMenuDisplay($type);
?>		
		<div class="add-contain">
			<div class="add-head"><p><strong>Add</strong> <span class="dev-text">Subnet</span></p></div>
			<div>
				<form class="add-form" action="add.php?type=subnet" method="post">
				<ul>
					<li>
						<label for="subnet_name">Subnet Name</label>
						<input type="text" required placeholder="DMZ Zone" name="subnet_name" size=50>
					</li>
					<li>
						<label for="address">IP Address</label>
						<input type="text" required placeholder="192.168.0.1" name="address" size=50 maxlength=15>
					</li>
					<li>
						<label for="mask">Subnet Mask</label>
						<input type="text" required placeholder="255.255.255.0" name="mask" size=50 maxlength=15>
					</li>
					<li>
						<label for="gateway">Gateway</label>
						<input type="text" placeholder="192.168.1.1" name="gateway" size=50 maxlength=15>
					</li>
					<li>
						<label for="note">Notes</label>
						<textarea name="note" rows=2 cols=100></textarea>
					</li>
					<hr>
				</ul>
				<input type="submit" class="submit-button" value="Save">
				<input type="reset" class="reset-button" value="Cancel">
				<input type="hidden" name="submitted" value="true">
				</form>
			</div>
		</div>
<?php
	}
	elseif(strcmp($type,"device") == 0)
	{
		if($_POST['submitted'])
		{
			$device_name = mysqli_real_escape_string($dbc,$_POST['device_name']); 
			$address = mysqli_real_escape_string($dbc,$_POST['address']); 
			$subnet = mysqli_real_escape_string($dbc,$_POST['subnet']); 
			$note = mysqli_real_escape_string($dbc,$_POST['note']); 		
					
			$query="INSERT INTO device (subnet_id,address,device_name,note) 
				Values ('$subnet',INET_ATON('$address'),'$device_name','$note')"; 
			$result=@mysqli_query($dbc,$query); 
			$device_id = mysqli_insert_id($dbc);
			if ($result)
			{
				$message = $device_name . " has been added!";
				echo "<script>openModal('success','" . $message . "');</script>";
			}
			else
			{
				echo "<script>openModal('fail','" . mysqli_real_escape_string($dbc,mysqli_error($dbc)) . "');</script>";
			}
			// only if submitted by the form
			mysqli_close($dbc);
		}
		whichPageMenuDisplay($type);
?>
		<div class="add-contain">
			<div class="add-head"><p><strong>Add</strong> <span class="dev-text">Device</span></p></div>
			<div>
				<form class="add-form" action="add.php?type=device" method="post">
				<ul>
					<li>
						<label>Subnet</label>
						<select id="subnet" required name="subnet">
						<option value="">Please select a subnet...</option>
						<?php
							require_once ('../mysqli_connect.php'); 
							$result = mysqli_query($dbc, "SELECT id, INET_NTOA(address) AS address, subnet_name FROM subnet");
							while($row = mysqli_fetch_array($result)){
								echo "<option value='" . $row['id'] . "'>" . $row['address'] . ' - ' . $row['subnet_name'] . "</option>";
							}
						?>
						</select>
					</li>
					<li>
						<label>IP Address</label>
						<input type="text" required name="address" placeholder="192.168.0.1" size=50 maxlength=15>
					</li>
					<li>
						<label>Name</label>
						<input type="text" required name="device_name" placeholder="Switch A" size=50 maxlength=40>
					</li>
					<li>		
						<label>Notes</label>
						<textarea name="notes" rows=2 cols=100></textarea>
					</li>
				</ul>
				<hr>
				<input type="submit" class="submit-button" value="Save" onClick=\"document.getElementById('result').showModal()\">
				<input type="reset" class="reset-button" value="Reset">
				<input type="hidden" name="submitted" value="true">
				</form>
			</div>
		</div>
<?php
	}
	//include the footer
	include("includes/footer.php");
}


?>