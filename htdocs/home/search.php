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
	require_once("../../mysqli_connect.php");
	$string = "192"
	
	$subnet_query = "SELECT id, 
		subnet_id, 
		INET_NTOA(address) AS address, 
		subnet_name,
		INET_NTOA(mask),
		INET_NTOA(gateway),
		note 
		FROM device 
		WHERE id LIKE '%".$string."%'
		OR address LIKE '%".$string."%'
		OR subnet_name LIKE '%".$string."%'
		OR mask LIKE '%".$string."%'
		OR gateway LIKE '%".$string."%'
		OR note LIKE '%".$string."%'";
	
	$subnet_result = mysqli_query($dbc,$subnet_query);	
	
	if($subnet_result)
	{
		
?>		
	<!--Table header-->
			<table class='ip-table' cellpadding=5 cellspacing=5 border=1><tr>
					<th class='name'>Name</th><th>Subnet / IP Address</th><th>Subnet Mask</th><th>Gateway</th><th>Notes</th><th>*</th><th>*</th></tr> 		
<?php		
			while($subnet_row = mysqli_fetch_array($subnet_result, MYSQLI_ASSOC))
			{
			}
	}
	$device_query = "SELECT id, 
		subnet_id, 
		INET_NTOA(address) AS address, 
		device_name, 
		note 
		FROM device 
		WHERE id LIKE '%".$string."%'
		OR address LIKE '%".$string."%'
		OR device_name LIKE '%".$string."%'
		OR note LIKE '%".$string."%'";  
	
	$device_result = mysqli_query($dbc,$device_query);
				
	if($device_result)
	{
		while($device_row = mysqli_fetch_array($device_result, MYSQLI_ASSOC))
		{}
	}
	
	//include the footer
	include ("../includes/footer.php");
}
?>