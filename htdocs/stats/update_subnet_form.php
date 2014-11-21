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
    $query = "SELECT id, INET_NTOA(address) AS address, subnet_name, INET_NTOA(mask) AS mask, INET_NTOA(gateway) AS gateway, note FROM subnet WHERE id=$id";  
    $result = @mysqli_query($dbc,$query); 
    $num = mysqli_num_rows($result); 
    if ($num > 0) { // If it ran OK, display all the records. 
        while ($row = mysqli_fetch_array($result,  MYSQLI_ASSOC)){ 
?> 
			<div class="addDeviceContain">
			<div class="add-device-head"><p><strong>Add</strong> <span class="dev-text">Subnet</span></p></div>
			<div class="add-form-contain">
				<form class="add-device-form" action="update_subnet.php" method="post">
				<span class="align-form-text-name">Subnet Name </span><input type="text" class="addURL" placeholder="DMZ Zone" name="Answer" size=50 value="<?php echo $row['subnet_name'];?>"><span class="required-text">REQUIRED</span><p></p>
				<span class="align-form-text-sub">IP Address </span><input type="text" class="addBookmarkTitle" placeholder="192.168.0.1" name="address" size=50 maxlength=15 value="<?php echo $row['address'];?>"><span class="required-text">REQUIRED</span><p></p>
				<span class="align-form-text-sub">Subnet Mask </span><input type="text" class="addBookmarkTitle" placeholder="255.255.255.0" name="mask" size=50 maxlength=15 value="<?php echo $row['mask'];?>"><span class="required-text">REQUIRED</span><p></p>
				<span class="align-form-text-sub">Gateway </span><input type="text" class="addBookmarkTitle" placeholder="192.168.1.1" name="gateway" size=50 maxlength=15 value="<?php echo $row['gateway'];?>"><span class="required-text">REQUIRED</span><p></p>
				<p></p>
				<span class="align-form-text-notes">Notes </span><textarea name="note" class="add-device-notes" rows=2 cols=100></textarea>
				<p></p>
				<hr>
				
				<dialog id="myDialog">
					<input type="button" id="close" value="X" onClick="document.getElementById('myDialog').close();">
					<h2>Delete the "DMZ Zone" subnet?</h2>
					<div class="fake-hr"></div>
					<p><em>Note </em>: All associated devices will lose their IP addresses.</p>
					<input type="button" class="deleteButtonModal" value="Delete">
					<input type="button" class="resetButtonModal" value="Cancel" onClick="document.getElementById('myDialog').close();">    
				</dialog>
		
				<input type="button" class="deleteButton" value="Delete" onClick="document.getElementById('myDialog').showModal();">
				<input type="submit" class="submitButton" value="Save">
				<input type="reset" class="resetButton" value="Cancel">
				<input type="hidden" name="id" value="<?php echo $row['id']; ?>">
				</form>
			</div>
		</div>
<?php 
        } //end while statement 
    } //end if statement 
    mysqli_close($dbc); 
    //include the footer 
    include ("../includes/footer.php"); 
} 
?> 