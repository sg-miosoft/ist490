<?php
//check session first
if(!isset($_SESSION['email']))
{
	header("Location: https://uwm-iptracker.miosoft.com/login.php");
}
else
{
	//include the header 
    include ("includes/header.php"); 
    require_once ('..mysqli_connect.php'); 
    $type=$_GET['type'];
	$id=$_GET['id'];
	if(strcmp($type,"subnet") == 0)
	{
		$query = "SELECT subnet_name, INET_NTOA(address) AS address, note FROM subnet WHERE id=$id";  
		$result = @mysqli_query($dbc,$query); 
		$num = mysqli_num_rows($result); 
		if($num > 0)
		{ // If it ran OK, display all the records. 
			while ($row = mysqli_fetch_array($result,  MYSQLI_ASSOC)) 
			{ 
				echo "<div class='deleteContainConfirm'><span style='color:#FFF; text-align:center;'>";
				echo $row['subnet_name']."<br>".$row['address']."<p></span>";
			} // End of While statement 
			echo "Are you sure that you want to delete this record?<br>";
			echo "<center><a href=delete.php?id=".$id.">YES</a> 
				<a href=index.php>NO</a></center></div>"; 
			mysqli_free_result($result); // Free up the resources.          
		}
		else
		{ // If it did not run OK. 
			echo '<p>There is no such record.</p>'; 
		} 
	}
	elseif(strcmp($typ,"device") == 0)
	{
	
	}
}
?>