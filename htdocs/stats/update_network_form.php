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
    $query = "SELECT id, INET_NTOA(address) AS address, network_name, INET_NTOA(mask) AS mask, INET_NTOA(gateway) AS gateway, note FROM network WHERE id=$id";  
    $result = @mysqli_query($dbc,$query); 
    $num = mysqli_num_rows($result); 
    if ($num > 0) { // If it ran OK, display all the records. 
        while ($row = mysqli_fetch_array($result,  MYSQLI_ASSOC)){ 
?> 
            <form action="update_network.php" method="post"> 
				Network Name: <input name="network_name" size=50 value="<?php echo $row['network_name'];?>"> <p> 
				IP Address: <input name="address" size=50 value="<?php echo $row['address'];?>"> <p> 
				Subnet Mask: <input name="address" size=50 value="<?php echo $row['mask'];?>"> <p> 
				Default Gateway: <input name="address" size=50 value="<?php echo $row['gateway'];?>"> <p> 
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