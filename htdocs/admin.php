<?php  
// Include header.php 
include("includes/header.php"); 

function sendEmail($e,$t)
{ 
    // Send an email, if desired. 
    $to=$e; 
    $subject="UWM IPTracker"; 
    $body=" 
    Thank you very much for being a member of http://iptracker.msn.miosoft.com\n\n 
    Click the link below to confirm your registration.\n\n 
    http://iptracker.msn.miosoft.com/register_confirm.php?token=$t.\n\n 
    Thanks again!\n\n 
    http://iptracker.msn.miosoft.com";  
    $headers="From: MIOsoft IP Tracker <smtp.sender@us.msn.main.miosoft.com>\n";  // <-- Replace this to your email address!!! 
    if(mail ($to, $subject, $body, $headers))
	{
		return True;
	}
	else
	{
		return False;
	}
} 

// Check if the form has been submitted. 
if($_SESSION['readonly'] == 1 or !isset($_SESSION['email']))
{
	header("Location: https://iptracker.msn.miosoft.com/index.php");
}
elseif(isset($_POST['registered']))
{
	require_once ('../mysqli_connect.php'); // Connect to the db. 
    require_once ('../passwordLib.php'); // Connect to the db. 
    $errors = array(); // Initialize error array. 
?>
	<script>
		function registerModal(status,message)
		{
			if(status === 'success')
			{
				var header = 'Success!';
				document.getElementById('registerP').innerHTML = message;
				document.getElementById('registerDialog').className = 'success-dialog';
			}
			else if(status === 'fail')
			{
				var header = 'Error!';
				document.getElementById('registerP').innerHTML = message;
				document.getElementById('registerDialog').className = 'fail-dialog';
			}
			document.getElementById('registerH2').innerText = header;
			document.getElementById('registerDialog').showModal();
		}
	</script>
	<dialog id="registerDialog">
		<input type="button" id="closeX" value="X" onClick="window.location.href='admin.php'">
		<h2 id="registerH2"></h2>
		<p id="registerP"></p>
		<button id="close" type="submit" onClick="window.location.href='admin.php'">Close</button>
	</dialog>
<?php

    // Check for an email address. 
    if(empty($_POST['email']))
	{ 
        $errors[] = 'You forgot to enter your email address.'; 
    }
	else
	{ 
        $email = mysqli_real_escape_string($dbc,$_POST['email']);
		
		if(!filter_var($email, FILTER_VALIDATE_EMAIL))
		{
			$errors[] = 'Not a valid email.';
		}
    }

	if(empty($errors))
	{ 
        // Register the user in the database. 
        // Check for previous registration. 
        $query = "SELECT user_id FROM users WHERE email='" . $email . "'"; 
        $result = mysqli_query($dbc,$query); 
        if(mysqli_num_rows($result) == 0) // If there is no matching email in users
		{ 
			$query = "SELECT email FROM pending_users WHERE email='$email'"; 
            $result = mysqli_query($dbc,$query); 
            if(mysqli_num_rows($result) == 0) //If there is no matching email in pending_users. New registration
			{ 
                $readonly = mysqli_real_escape_string($dbc,$_POST['readonly']);
				
				if($readonly != 1)
				{
					$readonly = 0;
				}

				$token = uniqid(mt_rand(), true); 

                // Make the query. 
                $query = "INSERT INTO pending_users (email, token, time_stamp, readonly)  
                VALUES ('" . $email . "', '" . $token . "', NOW(), " . $readonly . ")";
					
                $result = @mysqli_query($dbc,$query); // Run the query. 
                if($result) //If the query was successful to inster into pending_users
				{ 
					if(sendEmail($email,$token))
					{
						echo "<script>registerModal('success','Email sent successfully!');</script>";
					} 
                    
                }
				else
				{ 
					$errors[] = 'You could not be registered due to a system error. ';
					$errors[] = mysqli_error($dbc); // MySQL error message. 
                } 
            } 
            else
			{ 
                $token = uniqid(mt_rand(), true); 
                $query = "UPDATE `pending_users` SET `token`='$token', `time_stamp`= NOW() WHERE email = '$email'"; 
                $result = mysqli_query($dbc,$query); 
                if($result) //If it ran okay 
                { 
                    sendEmail($email,$token); 
                } 
                else
				{  
                    $errors[] = 'Your password could not be changed due to a system error.';
                    $errors[] = mysqli_error($dbc); // MySQL error message. 
                } 
            } 
		}
		else // Email address is already taken.
		{  
            $errors[] = 'The email address has already been registered.'; 
		} 
    } // End of if (empty($errors)) IF. 
	
    mysqli_close($dbc); // Close the database connection. 
}
elseif(isset($_POST['deleteID']))
{
	require_once ('../mysqli_connect.php'); // Connect to the db. 
	$errors = array(); // Initialize error array. 
	$user_id = @mysqli_real_escape_string($dbc,$_POST['deleteID']);
	$del_user_query = "DELETE FROM users WHERE user_id=" . $user_id;
	$del_user_result = @mysqli_query($dbc,$del_user_query);
	
	if($del_user_result)
	{
		$showSuccess = True;
	}
	else
	{
		$errors[] = mysqli_error($dbc);
	}
	mysqli_close($dbc);
}
else // Form has not been submitted.
{
	$errors = NULL; 
} // End of the main Submit conditional. 

 
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
	echo "<script>openModal('success','User deleted!')</script>";
}
else
{
	echo "</dialog>";
}

?>
<script>
	function deleteModal(id,name)
	{
		var action = ('admin.php');
		var header1 = 'Delete ';
		var header2 = '?';
		document.getElementById('deleteID').value = id;
		document.getElementById('deleteH2').innerText = header1.concat(name).concat(header2);
		document.getElementById('dialogForm').action = action;
		document.getElementById('deleteDialog').showModal();
	}
</script>

<dialog id="deleteDialog">
	<input type="button" id="closeX" value="X" onClick="document.getElementById('deleteDialog').close();">
	<h2 id="deleteH2"></h2>
	<div class="fake-hr" style="margin-bottom:10px;"></div>
	
	<form method="post" id="dialogForm">
		<input type="hidden" value="YES" id="deleteID" name="deleteID" />
	</form>
	<button class="delete-dialog-delete" form="dialogForm" name='deleteButton' type="submit">Delete</button>
	<input type="button" class="delete-dialog-cancel" value="Cancel" onClick="document.getElementById('deleteDialog').close();">    
</dialog>

<div class="form-contain">
	<h2>Add User</h2>
	<hr>
	<form action="admin.php" method="post">
		<span><p><input required type="text" style="margin-left:0px;" class="emailEntry" name="email" placeholder="E-mail Address" size="20" maxlength="40" /></p></span>
		<label id="add-user-label" for="readonly">Readonly</label>
		<input type="checkbox" name="readonly" size=50 maxlength=15 value="1">
		<span><p><input type="submit" class="submit-button" name="submit" value="Add" /></p></span>
		
		<input type="hidden" name="registered" value="TRUE" />
	</form>
	<h4 style="color:#fff;">User will be sent an email with an invitation link</h4>
</div>
<!--Table header-->
<table class='ip-table' cellpadding=5 cellspacing=5 border=1>
	<tr>
		<th class='name'>Name</th><th>E-mail</th><th>Registration Date</th><th>Readonly</th><th>*</th><th>*</th>
	</tr> 		
<?php
require_once ('../mysqli_connect.php'); // Connect to the db. 

$user_query = "SELECT user_id, CONCAT(first_name,  ' ', last_name) AS name, email, registration_date, readonly FROM users";				
$user_result = mysqli_query($dbc,$user_query);

if($user_result)
{
	while($user_row = mysqli_fetch_array($user_result, MYSQLI_ASSOC))
	{
		if($user_row['readonly'] == 1)
		{
			$tableReadonly = "Readonly";
		}
		else
		{
			$tableReadonly = "";
		}
		echo "<tr><td class='name'>" . $user_row['name'] . "</td>  
		<td class='table-content'>" . $user_row['email'] . "</td>
		<td class='table-content'>" . $user_row['registration_date'] . "</td>
		<td class='table-content'>" . $tableReadonly . "</td>
		<td class='table-content'>
			<input type='image' class='delete-img' 
				src='images/delete-icon-dark.png' 
				alt='Delete' value='Delete' 
				onmouseover=\"this.src='images/delete-icon.png'\" 
				onmouseout=\"this.src='images/delete-icon-dark.png'\" 
				onClick=\"deleteModal(" . $user_row['user_id'] . ",'" . $user_row['name'] . "');\" /></td>
		<td class='table-content'><a href=admin_update.php?user_id=" . $user_row['user_id'] . "><img class='edit-img' src='images/edit-icon.png' alt='Edit' onmouseover=\"this.src='images/edit-icon-hover.png'\" onmouseout=\"this.src='images/edit-icon.png'\"></a></td></tr>"; 
	}
}
echo "</table>";
mysqli_close($dbc);

// Include footer.php 
include("includes/footer.php");
?> 