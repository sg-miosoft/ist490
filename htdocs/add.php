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
		function openModal(status,message,action)
		{
			
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
			document.getElementById('addDialog').showModal();
		}
	</script>
	
	<dialog id="addDialog">
		<input type="button" id="closeX" value="X" onClick="document.getElementById('addDialog').close();">
		<h2 id="dialogH2"></h2>
		
		<p id="dialogP"></p>
		<!--<form id="dialogForm">-->
			<button id="btnClose" class="idClose" form="dialogForm" type="submit" onClick="document.getElementById('addDialog').close();">Close</button>
			<button id="btnIndex" class="idClose" form="dialogForm" type="submit" onClick="window.location.href='index.php'">Close</button>
		<!--</form>-->
	</dialog>
<?php

	if(strcmp($type,"subnet") == 0)
	{
		if ($_POST['submitted'])
		{
			$subnet_name = mysqli_real_escape_string($dbc,$_POST['subnet_name']); 
			$address = mysqli_real_escape_string($dbc,$_POST['address']);
				if(!filter_var($address, FILTER_VALIDATE_IP))
				{
					echo "<script>openModal('fail','IP Address: " . $address . " is not a valid IP address\.','close')";
				}
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
			
			$subnet_query="INSERT INTO subnet (note,subnet_name,address,mask,gateway) 
				Values ('" . $note . "','" . $subnet_name . "',INET_ATON('" . $address . "'),INET_ATON('" . $mask . "'),INET_ATON('" . $gateway . "'))"; 
			$subnet_result=@mysqli_query($dbc,$subnet_query); 
			$subnet_id = mysqli_insert_id($dbc);
			if($subnet_result)
			{
				$message = "The " . $subnet_name . " subnet has been added!";
				echo "<script>openModal('success','" . $message . "','index');</script>";
			}
			else
			{
				echo "<script>openModal('fail','" . mysqli_real_escape_string($dbc,mysqli_error($dbc)) . "','close');</script>";
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
			if(!filter_var($address, FILTER_VALIDATE_IP))
				{
					echo "<script>openModal('fail','IP Address: " . $address . " is not a valid IP address\.','close')";
				}
			$subnet = mysqli_real_escape_string($dbc,$_POST['subnet']); 
			$note = mysqli_real_escape_string($dbc,$_POST['note']); 		
					
			$device_query="INSERT INTO device (subnet_id,address,device_name,note) 
				Values ('" . $subnet . "',INET_ATON('" . $address . "'),'" . $device_name . "','" . $note . "')"; 
			$device_result=@mysqli_query($dbc,$device_query); 
			$device_id = mysqli_insert_id($dbc);
			if($device_result)
			{
				$message = $device_name . " has been added!";
				echo "<script>openModal('success','" . $message . "','index');</script>";
			}
			else
			{
				echo "<script>openModal('fail','" . mysqli_real_escape_string($dbc,mysqli_error($dbc)) . "','close');</script>";
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