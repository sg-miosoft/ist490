<?php 
//check session first 
if (!isset($_SESSION['email']))
{ 
	header("Location: https://uwm-iptracker.miosoft.com/home/login.php"); 
}
else
{
	$type=$_GET['type'];
	if(strcmp($type,"subnet") == 0)
	{
		echo "here";
		if($_POST['id'])
		{
			echo "here 1";
			$id = mysqli_real_escape_string($dbc,$_POST['id']);
			$query = "DELETE FROM subnet WHERE id=$id";
			$result = @mysqli_query($dbc,$query);
			if ($result)
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
	}
	elseif(strcmp($type,"device") == 0)
	{
		echo "here 2";
		if($_POST['id'])
		{
			echo "here 3";
			$id = mysqli_real_escape_string($dbc,$_POST['id']);
			$query = "DELETE FROM device WHERE id=$id";
			$result = @mysqli_query($dbc,$query);
			if ($result)
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
	}
	else	
	{
		//includes
		include ("../includes/header.php"); 
		include ("../includes/functions.php");
		require_once ('../../mysqli_connect.php'); 
		
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
		$subnetQuery = "SELECT id, 
			INET_NTOA(address) AS subnetAddress,
			INET_NTOA(mask) AS mask,
			INET_NTOA(gateway) AS gateway,
			subnet_name,
			note AS subnetNote
			FROM subnet
			ORDER BY subnetAddress
			LIMIT $start, $display";
		$subnetResult = mysqli_query($dbc,$subnetQuery);	
		
		whichPageMenuDisplay('index');
			
		if($subnetResult)
		{
			echo "<article>";
?>
			<script>
				function openModal(type,id)
				{
					if(type === 'subnet')
					{
						document.getElementById('deleteSubnetID').value = id;
						document.getElementById('deleteSubnet').showModal();
					}
					elseif(type === 'device')
					{
						document.getElementById('deleteDeviceID').value = id;
						document.getElementById('deleteDevice').showModal();
					}
				}
			</script>
			
			<dialog id="deleteSubnet">
				<input type="button" id="close" value="X" onClick="document.getElementById('deleteDialog').close();">
				<h2>Delete the <?php echo $subnetRow['subnet_name'];?> subnet?</h2>
				<div class="fake-hr"></div>
				<p><em>Note </em>: All associated devices will lose their IP addresses.</p>
				<form action="index.php?subnet" action="post">
					<input type="submit" value="delete">Delete</input>
					<input type="hidden" value="" id="deleteSubnetID" />
				</form>
				<!--<button class="delete-dialog-delete" value="Delete">Delete</button>-->
				<input type="button" class="resetButtonModal" value="Cancel" onClick="document.getElementById('deleteSubnet').close();">    
			</dialog>
			
			<dialog id="deleteDevice">
				<input type="button" id="close" value="X" onClick="document.getElementById('deleteDialog').close();">
				<h2>Delete the <?php echo $subnetRow['device_name'];?> device?</h2>
				<div class="fake-hr"></div>
				<form action="index.php?device" action="post">
					<input type="submit" value="delete">Delete</input>
					<input type="hidden" value="" id="deleteDeviceID"/>
				</form>
				<!--<button class="delete-dialog-delete" value="Delete">Delete</button>-->
				<input type="button" class="resetButtonModal" value="Cancel" onClick="document.getElementById('deleteDevice').close();">    
			</dialog>			
			
			<!--Table header-->
			<table class='ip-table' cellpadding=5 cellspacing=5 border=1><tr>
					<th class='name'>Name</th><th>Subnet / IP Address</th><th>Subnet Mask</th><th>Gateway</th><th>Notes</th><th>*</th><th>*</th></tr> 		
<?php		
			while($subnetRow = mysqli_fetch_array($subnetResult, MYSQLI_ASSOC))
			{
				echo "<tr><td class='name'>" . $subnetRow['subnet_name'] . "</td>
				<td class='table-content'>" . $subnetRow['subnetAddress'] . "</td>  
				<td class='table-content'>" . $subnetRow['mask'] . "</td>  
				<td class='table-content'>" . $subnetRow['gateway'] . "</td>
				<td class='notes'>" . $subnetRow['subnetNote'] . "</td>
				<td class='table-content'>
					<input type='image' class='delete-img' 
						src='../images/delete-icon-dark.png' 
						alt='Delete' value='Delete' 
						onmouseover=\"this.src='../images/delete-icon.png'\" 
						onmouseout=\"this.src='../images/delete-icon-dark.png'\" 
						onClick=\"openModal('subnet',".$subnetRow['id'].")\" /></td>
				<td class='table-content'><a href=update.php?type=subnet&id=".$subnetRow['id']."><img class='edit-img' src='../images/edit-icon.png' alt='Edit' onmouseover=\"this.src='../images/edit-icon-hover.png'\" onmouseout=\"this.src='../images/edit-icon.png'\"></a></td></tr>"; 
				/*onClick=\"document.getElementById('deleteSubnet').showModal()\" /></td>*/
				
				$deviceQuery = "SELECT id,
					subnet_id,
					INET_NTOA(address) AS deviceAddress,
					device_name,
					note AS deviceNote
					FROM device WHERE subnet_id =" . $subnetRow['id'];				
				
				$deviceResult = mysqli_query($dbc,$deviceQuery);
				
				if($deviceResult)
				{
					while($deviceRow = mysqli_fetch_array($deviceResult, MYSQLI_ASSOC))
					{
						echo "<tr><td class='name'>" . $deviceRow['device_name'] . "</td>  
						<td class='table-content'>" . $deviceRow['deviceAddress'] . "</td>
						<td class='table-content'>*</td>
						<td class='table-content'>*</td>
						<td class='notes'>" . $deviceRow['deviceNote'] . "</td>
						<td class='table-content'>
							<input type='image' class='delete-img' 
								src='../images/delete-icon-dark.png' 
								alt='Delete' value='Delete' 
								onmouseover=\"this.src='../images/delete-icon.png'\" 
								onmouseout=\"this.src='../images/delete-icon-dark.png'\" 
								onClick=\"openModal('device',".$deviceRow['id'].")\" /></td>
						<td class='table-content'><a href=update.php?type=device&id=".$deviceRow['id']."><img class='edit-img' src='../images/edit-icon.png' alt='Edit' onmouseover=\"this.src='../images/edit-icon-hover.png'\" onmouseout=\"this.src='../images/edit-icon.png'\"></a></td></tr>"; 

						//echo "<td class='table-content'><a href=delete.php?type=device?id=".$deviceRow['id']."><img class='delete-img' src='../images/delete-icon-dark.png' alt='Delete' onmouseover=\"this.src='../images/delete-icon.png'\" onmouseout=\"this.src='../images/delete-icon-dark.png'\"></a></td>"; 
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