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
    echo ("<center>");  
    echo ("<h3>Stats</h3><p>"); 
    echo ("<a href=addNetwork.php>Add a network</a> &nbsp <a href=addDevice.php>Add a device</a> &nbsp <a href=searchform.php>Search records</a>");  
	echo ("</center>");
    //Set the number of records to display per page 
    $display = 5; 

	//Check if the number of required pages has been determined 
    if(isset($_GET['p'])&&is_numeric($_GET['p'])){//Already been determined 
        $pages = $_GET['p']; 
    }else{//Need to determine 
        //Count the number of records; 
        //$u_id=$_SESSION['user_id']; 
        $query = "SELECT COUNT(id) FROM device"; 
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
	echo "<div id='content'>";
	$networkQuery = "SELECT id, 
		INET_NTOA(address) AS networkAddress,
		INET_NTOA(mask) AS mask,
		INET_NTOA(gateway) AS gateway,
		network_name,
		note AS networkNote
		FROM network
		ORDER BY networkAddress";
	$networkResult = mysqli_query($dbc,$networkQuery);	
	
	if($networkResult)
	{
		while($networkRow = mysqli_fetch_array($networkResult, MYSQLI_ASSOC))
		{
			echo ("<h3>" . "&nbsp" . $networkRow['network_name'] 
			. "&nbsp" . $networkRow['networkAddress'] 
			. "&nbsp" . $networkRow['mask'] 
			. "&nbsp" . $networkRow['gateway'] 
			. "&nbsp" . $networkRow['networkNote'] 
			. "&nbsp <a href=delete_network_confirm.php?id=".$networkRow['id'].">Delete</a>"
			. "&nbsp <a href=update_network_form.php?id=".$networkRow['id'].">Update</a>"
			. "</h3>");
			echo "<ul>";
			
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
					echo ("<li>" . $deviceRow['device_name'] 
					. "&nbsp" . $deviceRow['deviceAddress'] 
					. "&nbsp" . $deviceRow['deviceNote'] 
					. "&nbsp <a href=delete_device_confirm.php?id=".$deviceRow['id'].">Delete</a>"
					. "&nbsp <a href=update_device_form.php?id=".$deviceRow['id'].">Update</a>"
					. "</li>");
				}
			}
			echo "</ul>";
		}
	}
	else
	{
		echo "<p>The record could not be added due to a system error: " . mysqli_error($dbc) . "</p>"; 
	}	
	
	echo "</div>";
	
	((mysqli_free_result($result) || (is_object($result) && (get_class($result) == "mysqli_result"))) ? true : false); // Free up the resources.          
    mysqli_close($dbc); // Close the database connection. 
	echo "<center>";
    
	//Make the links to other pages if necessary. 
    if($pages>1)
	{ 
        echo '<br/><table><tr>'; 
        //Determine what page the script is on: 
        $current_page = ($start/$display) + 1; 
        //If it is not the first page, make a Previous button: 
        if($current_page != 1)
		{ 
            echo '<td><a href="index.php?s='. ($start - $display) . '&p=' . $pages. '"> Previous </a></td>'; 
        } 
        //Make all the numbered pages: 
        for($i = 1; $i <= $pages; $i++)
		{ 
            if($i != $current_page)
			{ // if not the current pages, generates links to that page 
                echo '<td><a href="index.php?s='. (($display*($i-1))). '&p=' . $pages .'"> ' . $i . ' </a></td>'; 
            }
			else
			{ // if current page, print the page number 
                echo '<td>'. $i. '</td>'; 
            } 
        } //End of FOR loop 
        //If it is not the last page, make a Next button: 
        if($current_page != $pages)
		{ 
            echo '<td><a href="index.php?s=' .($start + $display). '&p='. $pages. '"> Next </a></td>'; 
        } 
         
        echo '</tr></table>';  //Close the table. 
    }//End of pages links 

	//include the footer 
    include ("../includes/footer.php"); 
} 
?> 