<?php 
//check session first 
if(!isset($_SESSION['email']))
{ 
	header("Location: https://uwm-iptracker.miosoft.com/login.php");
}
elseif(!empty($_POST['search']))
{
	//includes
	include ("includes/header.php"); 
	include ("includes/functions.php");
	require_once ('../mysqli_connect.php'); 
	$searchString = $_POST['search'];
	$subnet_query = "SELECT id, 
		INET_NTOA(address) AS address, 
		subnet_name,
		INET_NTOA(mask) AS mask,
		INET_NTOA(gateway) AS gateway,
		note AS note 
		FROM subnet 
		WHERE id LIKE '%".$searchString."%'
		OR INET_NTOA(address) LIKE '%".$searchString."%'
		OR LOWER(subnet_name) LIKE LOWER('%".$searchString."%')
		OR INET_NTOA(mask) LIKE '%".$searchString."%'
		OR INET_NTOA(gateway) LIKE '%".$searchString."%'
		OR LOWER(note) LIKE LOWER('%".$searchString."%')";
	$subnet_result = mysqli_query($dbc,$subnet_query);	
	
	$device_query = "SELECT d.id, 
		INET_NTOA(d.address) AS address, 
		d.device_name, 
		d.note,
		INET_NTOA(s.mask) AS mask,
		INET_NTOA(s.gateway) AS gateway
		FROM device AS d
		LEFT OUTER JOIN subnet AS s
		ON d.subnet_id = s.id
		WHERE d.id LIKE '%".$searchString."%'
		OR INET_NTOA(d.address) LIKE '%".$searchString."%'
		OR LOWER(d.device_name) LIKE LOWER('%".$searchString."%')
		OR LOWER(d.note) LIKE LOWER('%".$searchString."%')";
	$device_result = mysqli_query($dbc,$device_query);
	
	if($_SESSION['readonly'] != 1)
	{
		whichPageMenuDisplay('index');
	}
		
	if((mysqli_num_rows($subnet_result)==0) and (mysqli_num_rows($device_result)==0))
	{
?>
		<dialog id="resultsDialog">
			<input type="button" id="closeX" value="X" onClick="document.getElementById('resultsDialog').close();">
			<h2 id="dialogH2">Sorry</h2>
			<p id="dialogP">No Results Found</p>
			<form id="dialogForm" action="index.php">
				<button id="close" form="dialogForm" type="submit">Close</button>
			</form>
		</dialog>
		<script>document.getElementById('resultsDialog').showModal();</script>
<?php
	}
	else
	{
		echo "<article>";
?>
		<script>
			function openModal(type,id,name)
			{
				var action = ('index.php?type=').concat(type);
				if(type === 'subnet')
				{
					var header1 = 'Delete the ';
					var header2 = ' subnet?';
					
					document.getElementById('dialogP').innerHTML = '<em>Note </em>: All associated devices will lose their IP addresses.';
				}
				else if(type === 'device')
				{
					var header1 = 'Delete ';
					var header2 = '?';
					
					document.getElementById('dialogP').innerHTML = '';
				}
				document.getElementById('deleteID').value = id;
				document.getElementById('dialogH2').innerText = header1.concat(name).concat(header2);
				document.getElementById('dialogForm').action = action;
				document.getElementById('deleteDialog').showModal();
			}
		</script>
		
		<dialog id="deleteDialog">
			<input type="button" id="close" value="X" onClick="document.getElementById('deleteDialog').close();">
			<h2 id="dialogH2"></h2>
			<div class="fake-hr"></div>
			<p id="dialogP"></p>
			<form action="" method="post" id="dialogForm">
				<input type="hidden" value="" id="deleteID" name="deleteID" />
			</form>
			<!--<button class="delete-dialog-delete" value="Delete">Delete</button>-->
			<button class="delete-dialog-delete" form="dialogForm" name='deleteButton' type="submit">Delete</button>
			<input type="button" class="resetButtonModal" value="Cancel" onClick="document.getElementById('deleteDialog').close();">    
		</dialog>
		
		<!--Table header-->
		<table class='ip-table' cellpadding=5 cellspacing=5 border=1><tr>
				<th class='name'>Name</th><th>Subnet / IP Address</th><th>Subnet Mask</th><th>Gateway</th><th>Notes</th><th>*</th><th>*</th></tr> 
<?php
	
		while($subnet_row = mysqli_fetch_array($subnet_result, MYSQLI_ASSOC))
		{
			echo "<tr><td class='name'>" . $subnet_row['subnet_name'] . "</td>
			<td class='table-content'>" . $subnet_row['address'] . "</td>  
			<td class='table-content'>" . $subnet_row['mask'] . "</td>  
			<td class='table-content'>" . $subnet_row['gateway'] . "</td>
			<td class='notes'>" . $subnet_row['note'] . "</td>";
			if($_SESSION['readonly'] != 1)
			{
				echo "<td class='table-content'>
					<input type='image' class='delete-img' 
						src='images/delete-icon-dark.png' 
						alt='Delete' value='Delete' 
						onmouseover=\"this.src='images/delete-icon.png'\" 
						onmouseout=\"this.src='images/delete-icon-dark.png'\" 
						onClick=\"openModal('subnet',".$subnet_row['id'].",'".$subnet_row['subnet_name']."')\" /></td>
				<td class='table-content'><a href=update.php?type=subnet&id=".$subnet_row['id']."><img class='edit-img' src='images/edit-icon.png' alt='Edit' onmouseover=\"this.src='images/edit-icon-hover.png'\" onmouseout=\"this.src='images/edit-icon.png'\"></a></td></tr>"; 
			}
			else
			{
				echo"<td class='table-content'>*</td>
					<td class='table-content'>*</td>";
			}
			echo "</tr>";  
		}	
	
		while($device_row = mysqli_fetch_array($device_result, MYSQLI_ASSOC))
		{
			echo "<tr><td class='name'>".$device_row['device_name']."</td>  
			<td class='table-content'>".$device_row['address']."</td>
			<td class='table-content'>".$device_row['mask']."</td>  
			<td class='table-content'>".$device_row['gateway']."</td>
			<td class='notes'>".$device_row['note']."</td>
			<td class='table-content'>
				<input type='image' class='delete-img' 
					src='images/delete-icon-dark.png' 
					alt='Delete' value='Delete' 
					onmouseover=\"this.src='images/delete-icon.png'\" 
					onmouseout=\"this.src='images/delete-icon-dark.png'\" 
					onClick=\"openModal('device',".$device_row['id'].",'".$device_row['device_name']."')\" /></td>
			<td class='table-content'><a href=update.php?type=device&id=".$device_row['id']."><img class='edit-img' src='images/edit-icon.png' alt='Edit' onmouseover=\"this.src='images/edit-icon-hover.png'\" onmouseout=\"this.src='images/edit-icon.png'\"></a></td></tr>"; 
		}
		
		echo "</table>"; 
		echo "</article>";
	}	
				
	mysqli_free_result($result); // Free up the resources.          
	mysqli_close($dbc); // Close the database connection. 
}
			
//include the footer 
include("includes/footer.php");
 
?> 