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
	$errors = array();

	if($_POST['user_id'])
	{
		$user_id = mysqli_real_escape_string($dbc,$_POST['user_id']); 
		$first_name = mysqli_real_escape_string($dbc,$_POST['first_name']); 
		$last_name = mysqli_real_escape_string($dbc,$_POST['last_name']); 
		$email = mysqli_real_escape_string($dbc,$_POST['email']);
			if(!filter_var($email, FILTER_VALIDATE_EMAIL))
			{
				$errors[] = $email . " is not a valid email";
			}
		$readonly = mysqli_real_escape_string($dbc,$_POST['readonly']); 
		
		if($readonly != 1)
		{
			$readonly = 0;
		}
		
		if(empty($errors))
		{
			$query = "UPDATE users SET 
			first_name='" . $first_name . "',
			last_name='" . $last_name . "',
			email='" . $email . "',
			readonly=" . $readonly . "
			WHERE user_id=" . $user_id; 

			$result = @mysqli_query($dbc,$query); 

			if($result and empty($errors))
			{
				$showSuccess = True;
			}
			else 
			{
				$errors[] = mysqli_error($dbc);				
			}
		}
	}
	else
	{

		$user_id=$_GET['user_id'];  
		$query = "SELECT * FROM users WHERE user_id=" . $user_id;  
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
									<input type="text" placeholder="John" name="first_name" size=50 value="<?php echo $num['first_name'];?>" required>
								</li>


								<li>
									<label>Last Name</label>
									<input type="text" placeholder="Doe" name="last_name" size=50 maxlength=15 value="<?php echo $num['last_name'];?>" required>
								</li>
								<li>
									<label>Email</label>
									<input type="text" placeholder="name@example.com" name="email" size=50 maxlength=100 value="<?php echo $num['email'];?>" required>
								</li>
								<li>
									<label for="readonly">Readonly</label>
									<input type="checkbox" name="readonly" value="1" <?php if($num['readonly'] == 1){echo 'checked';}?>>
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
		else
		{
			$errors[] = mysqli_error($dbc);
		}
	}
	mysqli_close($dbc);
?>
	<script>
		function openModal(status,message)
		{
			if(status === 'success')
			{
				var header = 'Success!';
				document.getElementById('statusDialog').className = 'success-dialog';
				var para = document.createElement("p");
				var text = document.createTextNode(message);
				para.appendChild(text);
				document.getElementById('dialogDiv').appendChild(para);
			}
			else if(status === 'fail')
			{
				var header = 'Error!';
				document.getElementById('statusDialog').className = 'fail-dialog';
				errors.forEach(function(obj)
				{
					var para = document.createElement("p");
					var text = document.createTextNode(obj);
					para.appendChild(text);
					document.getElementById('dialogDiv').appendChild(para);
				});
			}
			
			document.getElementById('dialogH2').innerText = header;
			document.getElementById('statusDialog').showModal();
		}
		var errors = <?php echo json_encode($errors) ?>
	</script>

	<dialog id="statusDialog">
		<input type="button" id="closeX" value="X" onClick="window.location.href='admin.php'">
		<h2 id="dialogH2"></h2>
		<div id="dialogDiv"></div>
		
<?php
	if(!empty($errors))
	{ 
		echo "<button id=\"close\" onClick=\"window.location.href='admin.php';\">Close</button>
			</dialog>";
		echo "<script>openModal('fail',errors)</script>";
	}
	elseif($showSuccess)
	{
		echo "<button id=\"close\" onClick=\"window.location.href='admin.php';\">Close</button>
			</dialog>";
		echo "<script>openModal('success','User updated!')</script>";
	}
	else
	{
		echo "</dialog>";
	}

}

//include the footer
include("includes/footer.php");

?>