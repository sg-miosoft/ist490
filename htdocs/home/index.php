<?php 
//check session first 
if (!isset($_SESSION['email']))
{ 
	header("Location: https://uwm-iptracker.miosoft.com/home/login.php"); 
}
else
{
	//includes
	include ("../includes/header.php"); 
	include ("../includes/functions.php");
	require_once ('../../mysqli_connect.php'); 
	
	$type=$_GET['type'];
	
	if(strcmp($type,"subnet") == 0) //delete a subnet
	{
		if($_POST['deleteID'])
		{
			$id = @mysqli_real_escape_string($dbc,$_POST['deleteID']);
			$del_subnet_query = "DELETE FROM subnet WHERE id=$id";
			$del_subnet_result = @mysqli_query($dbc,$del_subnet_query);
			if($del_subnet_result)
			{
				header("Location: https://uwm-iptracker.miosoft.com/home/index.php"); 
			}
			else
			{
				echo "The selected device could not be deleted.";
				echo "<p><a href=index.php>Home</a>"; 
				mysqli_close($dbc);
			}
		}
		else
		{
			echo 'Post not submitted properly';
		}
	}
	elseif(strcmp($type,"device") == 0) //delete a device
	{
		if($_POST['deleteID'])
		{
			$id = @mysqli_real_escape_string($dbc,$_POST['deleteID']);
			$deldevice_query = "DELETE FROM device WHERE id=$id";
			$deldevice_result = @mysqli_query($dbc,$deldevice_query);
			if($deldevice_result)
			{
				header("Location: https://uwm-iptracker.miosoft.com/home/index.php"); 
			}
			else
			{
				echo "The selected device could not be deleted.";
				echo "<p><a href=index.php>Home</a>"; 
				mysqli_close($dbc);
			}
		}
		else
		{
			echo 'Post not submitted properly';
		}
	}
	else //Display all records
	{
		//Set the number of records to display per page 
		$display = 5; 

		//Check if the number of required pages has been determined 
		if(isset($_GET['p'])&&is_numeric($_GET['p']))
		{
			//Already been determined 
			$pages = $_GET['p']; 
		}
		else
		{//Need to determine 
			//Count the number of records; 
			//$u_id=$_SESSION['user_id']; 
			$query = "SELECT COUNT(id) FROM subnet"; 
			$result = @mysqli_query($dbc,$query);  
			$row = @mysqli_fetch_array($result,  MYSQLI_NUM);
			$query1 = "SELECT COUNT(id) FROM device"; 
			$result1 = @mysqli_query($dbc,$query1);  
			$row1 = @mysqli_fetch_array($result1,  MYSQLI_NUM); 		
			$records = $row[0] + $row1[0]; //get the number of records 
			//Calculate the number of pages ... 
			if($records > $display)
			{//More than 1 page is needed 
				$pages = ceil($records/$display); 
			}
			else
			{ 
				$pages = 1; 
			} 
		}// End of p IF. 

		//Determine where in the database to start returning results ... 
		if(isset($_GET['s'])&&is_numeric($_GET['s']))
		{ 
			$start = $_GET['s']; 
		}
		else
		{ 
			$start = 0; 
		} 
		$subnet_query = "SELECT id, 
			INET_NTOA(address) AS subnetAddress,
			INET_NTOA(mask) AS mask,
			INET_NTOA(gateway) AS gateway,
			subnet_name,
			note AS subnetNote
			FROM subnet
			ORDER BY subnetAddress
			LIMIT $start, $display";
		$subnet_result = mysqli_query($dbc,$subnet_query);	
		
		whichPageMenuDisplay('index');
			
		if($subnet_result)
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
				<td class='table-content'>" . $subnet_row['subnetAddress'] . "</td>  
				<td class='table-content'>" . $subnet_row['mask'] . "</td>  
				<td class='table-content'>" . $subnet_row['gateway'] . "</td>
				<td class='notes'>" . $subnet_row['subnetNote'] . "</td>
				<td class='table-content'>
					<input type='image' class='delete-img' 
						src='../images/delete-icon-dark.png' 
						alt='Delete' value='Delete' 
						onmouseover=\"this.src='../images/delete-icon.png'\" 
						onmouseout=\"this.src='../images/delete-icon-dark.png'\" 
						onClick=\"openModal('subnet',".$subnet_row['id'].",'".$subnet_row['subnet_name']."')\" /></td>
				<td class='table-content'><a href=update.php?type=subnet&id=".$subnet_row['id']."><img class='edit-img' src='../images/edit-icon.png' alt='Edit' onmouseover=\"this.src='../images/edit-icon-hover.png'\" onmouseout=\"this.src='../images/edit-icon.png'\"></a></td></tr>"; 
				/*onClick=\"document.getElementById('deleteSubnet').showModal()\" /></td>*/
				
				$device_query = "SELECT id,
					subnet_id,
					INET_NTOA(address) AS deviceAddress,
					device_name,
					note AS deviceNote
					FROM device WHERE subnet_id =" . $subnet_row['id'];				
				
				$device_result = mysqli_query($dbc,$device_query);
				
				if($device_result)
				{
					while($device_row = mysqli_fetch_array($device_result, MYSQLI_ASSOC))
					{
						echo "<tr><td class='name'>".$device_row['device_name']."</td>  
						<td class='table-content'>".$device_row['deviceAddress']."</td>
						<td class='table-content'>*</td>
						<td class='table-content'>*</td>
						<td class='notes'>".$device_row['deviceNote']."</td>
						<td class='table-content'>
							<input type='image' class='delete-img' 
								src='../images/delete-icon-dark.png' 
								alt='Delete' value='Delete' 
								onmouseover=\"this.src='../images/delete-icon.png'\" 
								onmouseout=\"this.src='../images/delete-icon-dark.png'\" 
								onClick=\"openModal('device',".$device_row['id'].",'".$device_row['device_name']."')\" /></td>
						<td class='table-content'><a href=update.php?type=device&id=".$device_row['id']."><img class='edit-img' src='../images/edit-icon.png' alt='Edit' onmouseover=\"this.src='../images/edit-icon-hover.png'\" onmouseout=\"this.src='../images/edit-icon.png'\"></a></td></tr>"; 

						//echo "<td class='table-content'><a href=delete.php?type=device?id=".$device_row['id']."><img class='delete-img' src='../images/delete-icon-dark.png' alt='Delete' onmouseover=\"this.src='../images/delete-icon.png'\" onmouseout=\"this.src='../images/delete-icon-dark.png'\"></a></td>"; 
						/*onClick=\"document.getElementById('deleteDialog').showModal()\" /></td>";*/
					}
				}
			}
			echo "</table>"; 
		}
		else
		{
			echo "<p>The record could not be added due to a system error: " . mysqli_error($dbc) . "</p>"; 
		}	
		
			
		mysqli_free_result($result); // Free up the resources.          
		mysqli_close($dbc); // Close the database connection. 
			
		//Make the links to other pages if necessary.
		if($pages>1)
		{
			echo '<div class="display-results">
				<select>
				  <option value="5">5</option>
				  <option value="10">10</option>
				  <option value="15">15</option>
				  <option value="20">20</option>
				</select> <span class="results-per-page">Results Per Page</span></div>';
			echo '<table class="pageNumbers"><tr>';
			//Determine what page the script is on:
			$current_page = ($start/$display) + 1;
			//If it is not the first page, make a Previous button:
			if($current_page != 1)
			{
				echo '<td><a class="prev-anchor" href="index.php?s='. ($start - $display) . '&p=' . $pages. '"><img src="../images/prev-button.png" alt="Previous" onmouseover="this.src=\'../images/prev-dark.png\'" onmouseout="this.src=\'../images/prev-button.png\'"> </a></td>';
			}
			//Make all the numbered pages:
			for($i = 1; $i <= $pages; $i++){
				if($i != $current_page){ // if not the current pages, generates links to that page
					echo '<td><a class="other-item" href="index.php?s='. (($display*($i-1))). '&p=' . $pages .'"> ' . $i . '</a> | </td>';
				}else{ // if current page, print the page number
					echo '<td><span class="current-item">'. $i. '</span> | </td>';
				}
			} //End of FOR loop
			//If it is not the last page, make a Next button:
			if($current_page != $pages){
				echo '<td><a class="next-anchor" href="index.php?s=' .($start + $display). '&p='. $pages. '"><img src="../images/next-button.png" alt="Next" class="next-but" onmouseover="this.src=\'../images/next-dark.png\'" onmouseout="this.src=\'../images/next-button.png\'"> </a></td>';
			}
			
			echo '</tr></table>';  //Close the table.
			?>
			<div class="clear"></div>
			</article>
			<?php
		}//End of pages links
	}
	//include the footer 
    include ("../includes/footer.php"); 
} 
?> 