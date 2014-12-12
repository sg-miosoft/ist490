<?php
//check session first
if($_SESSION['readonly'] == 1 or !isset($_SESSION['email']))
{
	header("Location: https://uwm-iptracker.miosoft.com/index.php");
}
else
{
	//include the header
	include ("includes/header.php");
	require_once ('../mysqli_connect.php'); 
?>
	<script>
		function openModal(status,message)
		{
			var action = 'admin.php';
			if(status === 'success')
			{
				var header = 'Success!';
				document.getElementById('dialogP').innerHTML = message;
				document.getElementById('updateDialog').className = 'success-dialog';
			}
			else if(status === 'fail')
			{
				var header = 'Error!';
				document.getElementById('dialogP').innerHTML = message;
				document.getElementById('updateDialog').className = 'fail-dialog';
			}
			document.getElementById('dialogH2').innerText = header;
			document.getElementById('dialogForm').action = action;
			document.getElementById('updateDialog').showModal();
		}
	</script>
	
	<dialog id="updateDialog">
		<input type="button" id="closeX" value="X" onClick="document.getElementById('updateDialog').close();">
		<h2 id="dialogH2"></h2>
		
		<p id="dialogP"></p>
		<form id="dialogForm">
			<button id="close" form="dialogForm" type="submit">Close</button>
		</form>
	</dialog>
<?php

	if($_POST['user_id'])
	{
		$user_id = mysqli_real_escape_string($dbc,$_POST['user_id']); 
		$first_name = mysqli_real_escape_string($dbc,$_POST['first_name']); 
		$last_name = mysqli_real_escape_string($dbc,$_POST['last_name']); 
		$email = mysqli_real_escape_string($dbc,$_POST['email']); 
		$readonly = mysqli_real_escape_string($dbc,$_POST['readonly']); 
		
		if($readonly != 1)
		{
			$readonly = 0;
		}
		
		$query = "UPDATE users SET 
		first_name='$first_name',
		last_name='$last_name',
		email='$email',
		readonly='$readonly'
		WHERE user_id='$user_id'"; 

		$result = @mysqli_query($dbc,$query); 

		if($result)
		{
			$message = $first_name . ' ' . $last_name . " has been updated!";
			echo "<script>openModal('success','" . $message . "');</script>";				
		}
		else 
		{
			echo "<script>openModal('fail','" . mysqli_real_escape_string($dbc,mysqli_error($dbc)) . "');</script>";				
		}
		
		mysqli_close($dbc);
	}

	else
	{
		$user_id=$_GET['user_id'];  
		$query = "SELECT * FROM users WHERE user_id=$user_id";  
		$result = @mysqli_query($dbc,$query); 
		$num = mysqli_num_rows($result); 

		if($num > 0) 
		{ // If it ran OK, display all the records. 
			while ($num = mysqli_fetch_array($result,  MYSQLI_ASSOC))
			{ 
?> 
				<div class="add-contain">
				<div class="add-head"><p><strong>Update</strong> <span class="dev-text">User</span></p></div>
				<div>
					<form class="add-form" action="admin_update.php" method="post">
						<ul>
							<li>
								<label>First Name</label>
								<input type="text" placeholder="John" name="first_name" size=50 value="<?php echo $num['first_name'];?>">
							</li>


							<li>
								<label>Last Name</label>
								<input type="text" placeholder="Doe" name="last_name" size=50 maxlength=15 value="<?php echo $num['last_name'];?>">
							</li>
							<li>
								<label>Email</label>
								<input type="text" placeholder="name@example.com" name="email" size=50 maxlength=15 value="<?php echo $num['email'];?>">
							</li>
							<li>
								<label for="readonly">Readonly</label>
								<input type="checkbox" name="readonly" size=50 maxlength=15 value="1" <?php if($num['readonly'] == 1){echo 'checked';}?>>
							</li>
						</ul>
						<hr>
						
						<input type="submit" class="submit-button" value="Update"> 
						<input type="reset" class="reset-button" value="Reset">
						<input type="hidden" name="user_id" value="<?php echo $num['user_id']; ?>">
					</form>
				</div>
			</div>
<?php 
			}
		}
		mysqli_close($dbc);			
	}
}

//include the footer
include("includes/footer.php");

?>