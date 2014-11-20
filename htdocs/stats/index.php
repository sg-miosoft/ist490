<?php 
session_start(); 
//check session first 
if (!isset($_SESSION['email']))
{ 
	echo "You are not logged in!"; 
    exit(); 
}
else
{ 
	
    //include the header 
    include ("../includes/header.php"); 
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
        $query = "SELECT COUNT(id) FROM network"; 
        $result = @mysqli_query($dbc,$query);  
        $row = @mysqli_fetch_array($result,  MYSQLI_NUM);
		$query1 = "SELECT COUNT(id) FROM device"; 
        $result1 = @mysqli_query($dbc,$query1);  
        $row1 = @mysqli_fetch_array($result1,  MYSQLI_NUM); 		
        $records = $row[0] + $row1[0]; //get the number of records 
		//Calculate the number of pages ... 
        if($records > $display){//More than 1 page is needed 
            $pages = ceil($records/$display); 
        }else{ 
            $pages = 1; 
        } 
    }// End of p IF. 

    //Determine where in the database to start returning results ... 
    if(isset($_GET['s'])&&is_numeric($_GET['s'])){ 
        $start = $_GET['s']; 
    }else{ 
        $start = 0; 
    } 
	$networkQuery = "SELECT id, 
		INET_NTOA(address) AS networkAddress,
		INET_NTOA(mask) AS mask,
		INET_NTOA(gateway) AS gateway,
		network_name,
		note AS networkNote
		FROM network
		ORDER BY networkAddress";
	$networkResult = mysqli_query($dbc,$networkQuery);	
	
	include("../includes/aside.php");
	
	if($networkResult)
	{
		echo "<article>";
		//Table header:
		echo "<table class='bookmarksTable' cellpadding=5 cellspacing=5 border=1><tr>
				<th>Name</th><th>Network / IP Address</th><th>Subnet Mask</th><th>Gateway</th><th>Notes</th><th>*</th><th>*</th></tr>"; 		
		
		while($networkRow = mysqli_fetch_array($networkResult, MYSQLI_ASSOC))
		{
			echo "<tr><td class='bookmarkInfo'>" . $networkRow['network_name'] . "</td>";  
			echo "<td class='bookmarkInfo'>" . $networkRow['networkAddress'] . "</td>";  
			echo "<td class='bookmarkInfo'>" . $networkRow['mask'] . "</td>";  
			echo "<td class='bookmarkInfo'>" . $networkRow['gateway'] . "</td>"; 
			echo "<td class='notes'>" . $networkRow['networkNote'] . "</td>"; 
			echo "<td class='bookmarkInfo'><a href=delete_network_confirm.php?id=".$networkRow['id']."><img class='delete-img' src='../images/delete-icon-dark.png' alt='Delete' onmouseover=\"this.src='../images/delete-icon.png'\" onmouseout=\"this.src='../images/delete-icon-dark.png'\"></a></td>"; 
			echo "<td class='bookmarkInfo'><a href=update_network_form.php?id=".$networkRow['id']."><img class='edit-img' src='../images/edit-icon.png' alt='Edit' onmouseover=\"this.src='../images/edit-icon-hover.png'\" onmouseout=\"this.src='../images/edit-icon.png'\"></a></td></tr>"; 
			
			$deviceQuery = "SELECT id,
				network_id,
				INET_NTOA(address) AS deviceAddress,
				device_name,
				note AS deviceNote
				FROM device WHERE network_id =" . $networkRow['id'];				
			
			$deviceResult = mysqli_query($dbc,$deviceQuery);
			
			if($deviceResult)
			{
				while($deviceRow = mysqli_fetch_array($deviceResult, MYSQLI_ASSOC))
				{
					echo "<tr><td class='bookmarkInfo'>" . $deviceRow['device_name'] . "</td>";  
					echo "<td class='bookmarkInfo'>" . $deviceRow['deviceAddress'] . "</td>";  
					echo "<td class='bookmarkInfo'>*</td>";
					echo "<td class='bookmarkInfo'>*</td>";
					echo "<td class='notes'>" . $deviceRow['deviceNote'] . "</td>";  
					echo "<td class='bookmarkInfo'><a href=delete_device_confirm.php?id=".$deviceRow['id']."><img class='delete-img' src='../images/delete-icon-dark.png' alt='Delete' onmouseover=\"this.src='../images/delete-icon.png'\" onmouseout=\"this.src='../images/delete-icon-dark.png'\"></a></td>"; 
					echo "<td class='bookmarkInfo'><a href=update_device_form.php?id=".$deviceRow['id']."><img class='edit-img' src='../images/edit-icon.png' alt='Edit' onmouseover=\"this.src='../images/edit-icon-hover.png'\" onmouseout=\"this.src='../images/edit-icon.png'\"></a></td></tr>"; 
				}
			}
		}
		echo "</table>"; 
	}
	else
	{
		echo "<p>The record could not be added due to a system error: " . mysqli_error($dbc) . "</p>"; 
	}	
	
		
	((mysqli_free_result($result) || (is_object($result) && (get_class($result) == "mysqli_result"))) ? true : false); // Free up the resources.          
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
		if($current_page != 1){
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

	//include the footer 
    include ("../includes/footer.php"); 
} 
?> 