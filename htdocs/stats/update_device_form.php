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
    $id=$_GET['id'];  
    $query = "SELECT id, network_id, INET_NTOA(address) AS address, device_name, note FROM device WHERE id=$id";  
    $result = @mysqli_query($dbc,$query); 
    $num = mysqli_num_rows($result); 
    if ($num > 0) { // If it ran OK, display all the records. 
        while ($row = mysqli_fetch_array($result,  MYSQLI_ASSOC)){ 
?> 
            <form action="update_device.php" method="post"> 
				Device Name: <input name="device_name" size=50 value="<?php echo $row['device_name'];?>"> <p> 
				IP Address: <input name="address" size=50 value="<?php echo $row['address'];?>"> <p> 
				<label for="network">Network:</label>
				<select id="network" name="network">
					<option value="">Please select a network...</option>
					<?php
						require_once ('../../mysqli_connect.php'); 
						$result2 = mysqli_query($dbc, "SELECT id, INET_NTOA(address) AS address FROM network");
						while($row2 = mysqli_fetch_array($result2)){
							echo "<option value='" . $row2['id'] . "'>" . $row2['address'] . "</option>";
						}
					?>
				</select><p>
				Note: <input name="note" size=50 value="<?php echo $row['note'];?>"> <p>
				<br> 
				<p> 
				<input type=submit value=update> 
				<input type=reset value=reset> 
				<input type=hidden name="id" value="<?php echo $row['id']; ?>"> 
            </form> 
<?php 
        } //end while statement 
    } //end if statement 
    mysqli_close($dbc); 
    //include the footer 
    include ("../includes/footer.php"); 
} 
?> 