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
	
	
	/*
	$query = "SELECT network.id AS networkID,
			network.network_name AS network_name,
			INET_NTOA(network.address) AS networkAddress,
			network.mask AS mask,
			network.gateway AS gateway,
			network.note AS networkNote,
			device.device_name AS device_name,
			INET_NTOA(device.address) AS deviceAddress,
			device.note AS deviceNote,
			IFNULL(device.network_id,') AS deviceNetwork_id,
			IFNULL(device.id,') AS deviceID
		FROM network
			LEFT JOIN device ON network.id=device.network_id
		ORDER BY device.network_id";

	$result = mysqli_query($dbc,$query);	

	if ($result){
		echo "<p> It worked apparently. </p>"; 			
	}
	else{
		echo "<p>The record could not be added due to a system error: " . mysqli_error($dbc) . "</p>"; 
	}	
	while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
		echo "<h3>" . $row['networkID'] . "&nbsp" . $row['network_name'] . "&nbsp" . $row['networkAddress'] . "&nbsp" . $row['mask'] . "&nbsp" . $row['gateway'] . "&nbsp" . $row['networkNote'] . "</h3>";
		echo "<ul>";
			echo "<li>" . $row['device_name'] . "&nbsp" . $row['deviceAddress'] . "&nbsp" . $row['deviceNote'] . "</li>";
		echo "</ul>";
	}
	*/
	echo "<div id='content'>";
	 $networkQuery = "SELECT id, 
		INET_NTOA(address) AS networkAddress,
		mask,
		gateway,
		network_name,
		note AS networkNote
		FROM network
		ORDER BY networkAddress";
	$networkResult = mysqli_query($dbc,$networkQuery);	
	
	if($networkResult)
	{
		while($networkRow = mysqli_fetch_array($networkResult, MYSQLI_ASSOC))
		{
			echo "<h3>" . $networkRow['id'] . "&nbsp" . $networkRow['network_name'] . "&nbsp" . $networkRow['networkAddress'] . "&nbsp" . $networkRow['mask'] . "&nbsp" . $networkRow['gateway'] . "&nbsp" . $networkRow['networkNote'] . "</h3>";
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
					echo "<li>" . $deviceRow['device_name'] . "&nbsp" . $deviceRow['deviceAddress'] . "&nbsp" . $deviceRow['deviceNote'] . "</li>";
				}
			}
			else
			{
				echo "<p>The record could not be added due to a system error: " . mysqli_error($dbc) . "</p>"; 
			}
			echo "</ul>";
		}
	}
	else
	{
		echo "<p>The record could not be added due to a system error: " . mysqli_error($dbc) . "</p>"; 
	}	
	
	echo "</div>";
	
	
		
	
	//include the footer 
    include ("../includes/footer.php"); 
}
?>