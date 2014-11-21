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
    require_once ('../../mysqli_connect.php'); 
    $id=$_GET['id'];   
    $query = "SELECT device_name, INET_NTOA(address) AS address FROM device WHERE id=$id";  
    $result = @mysqli_query($dbc,$query); 
    $num = mysqli_num_rows($result); 
    if ($num > 0) { // If it ran OK, display all the records. 
        while ($row = mysqli_fetch_array($result,  MYSQLI_ASSOC)) { 
            echo "<div class='deleteContainConfirm'><span style='color:#FFF; text-align:center;'>";
			echo $row['device_name']."<br>".$row['address']."<p></span>"; 
        } // End of While statement 
        echo "Are you sure that you want to delete this record?<br>";
		echo "<center><a href=delete_device.php?id=".$id.">YES</a> 
			<a href=index.php>NO</a></center></div>"; 
        ((mysqli_free_result($result) || (is_object($result) && (get_class($result) == "mysqli_result"))) ? true : false); // Free up the resources.          
    }else{ // If it did not run OK. 
        echo '<p>There is no such record.</p>'; 
    } 
    mysqli_close($dbc); // Close the database connection. 
    //include the footer 
        include ("../includes/footer.php"); 
} 

?> 