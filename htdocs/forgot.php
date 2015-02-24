<?php   
#code to deal with forgot password  
include ('includes/header.php');  
function sendEmail($e,$t)
{  
    // Send an email, if desired.  
    $to=$e;   
    $subject="UWM IPtracker Password Reset";  
    $body="  
    Thank you very much for being a member of the UWM IPtracker!\n\n  
    Click the link below to reset your password.\n\n  
    https://iptracker.msn.miosoft.com/reset.php?token=" . $t . "\n\n  
    Thanks again!\n\n  
    https://iptracker.msn.miosoft.com/index.php";   
    $headers="From: MIOsoft IP Tracker <smtp.sender@us.msn.main.miosoft.com>\n";  // <-- Replace this to your email address!!!  
    mail ($to, $subject, $body, $headers); // SEND the message!    

    // Print a message.  
   /* echo '<h1 id="mainhead">Thank you!</h1>  
    <p>Please check your email to get your password reset link.</p>';   

    // Include the footer and quit the script (to not show the form).  
    include ('includes/footer.php');  
    exit();      */
}  
// Check if the form has been submitted.  
if (isset($_SESSION['email']))
{ 
	header("Location: https://iptracker.msn.miosoft.com/index.php"); 
}
elseif(isset($_POST['submitted']))
{  
?>
	<script>
		function openModal(status,message,action)
		{
			if(status === 'success')
			{
				var header = 'Success!';
				var para = document.createElement("p");
				var text = document.createTextNode("Email sent!"); 
				para.appendChild(text);
				document.getElementById('dialogDiv').appendChild(para);
				document.getElementById('statusDialog').className = 'success-dialog';
			}
			else if(status === 'fail')
			{
				var header = 'Error!';
				
				document.getElementById('statusDialog').className = 'fail-dialog';
				document.getElementById('close').onclick = action;
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
	</script>

	<dialog id="statusDialog">
		<input type="button" id="closeX" value="X" onClick="document.getElementById('statusDialog').close();">
		<h2 id="dialogH2"></h2>
		
		<div id="dialogDiv"></div>
		<form id="dialogForm">
			<button id="close" form="dialogForm" type="submit">Close</button>
		</form>
	</dialog>
<?php

	require_once ('../mysqli_connect.php'); // Connect to the db.  
    $errors = array(); // Initialize error array. 

    // Check for an email address. 
    if(empty($_POST['email']))
	{ 
        $errors[] = 'You forgot to enter your email address.'; 
    }
	else
	{ 
        $user_email = mysqli_real_escape_string($dbc,$_POST['email']);
			if(!filter_var($user_email, FILTER_VALIDATE_EMAIL))
			{
				$errors[] = 'Not a valid email.';
			}
    } 

    if(empty($errors))
	{ // If everything's okay. 
        // Check for previous registration. 
        $query = "SELECT * FROM users WHERE email = '" . $user_email . "'";  
        $result = mysqli_query($dbc,$query); 
        if(mysqli_num_rows($result)==1)
		{ 
            while ($row=mysqli_fetch_array($result))
			{ 
                $user_id=$row[0]; 
            }                                 
            $token = uniqid(mt_rand(), true); 
            //Insert pending user information into database 
            $query = "SELECT token FROM reset_users WHERE user_id = " . $user_id; 
            $result = mysqli_query($dbc,$query); 
            if(mysqli_num_rows($result) == 0)
			{ // if there is no such user pending 
                $query = "INSERT INTO reset_users (user_id, token, time_stamp) VALUES (" . $user_id .", '" . $token . "', NOW() )"; 
                $result = mysqli_query($dbc,$query); 
                if($result) //If it ran okay 
                { 
                    sendEmail($user_email,$token); 
                } 
                else //Could not insert into reset_users new user reset request.
				{ 
                    $errors[] = 'Your password could not be changed due to a system error. We apologize for any inconvenience.'; // Public message. 
                    $errors[] = mysqli_error($dbc); // MySQL error message. 
                } 
            } 
            else //User reset already pending 
			{ 
                $query = "UPDATE reset_users SET token = '" . $token . "', time_stamp = NOW() WHERE user_id = " . $user_id; 
                $result = mysqli_query($dbc,$query); 
                if($result) //If it succeeded 
                { 
                    sendEmail($user_email,$token); 
                } 
                else //Could not update reset_users tables.
				{
					$errors[] = "Your password could not be changed due to a system error.";
                    $errors[] = mysqli_error($dbc);
                } 
            } 
        }
		else
		{ 
			// Not registered. For security don't tell them it failed.
			// ^^^IS this good practice? I've stopped this.
            $errors[] = "No user found with matching email."; 
		} 
    }
	else{}//Email couldn't be derived from post. Error already posted.
	// End of if (empty($errors)) IF. 

    mysqli_error($dbc); // Close the database connection. 
	
	if(!empty($errors))
	{ 
		echo "<script>var errors = ";
		echo json_encode($errors) . ';</script>';
		echo "<script>openModal('fail',errors,'forgot.php')</script>";
	}
	else
	{
		echo "<script>var errors = " . json_encode($errors) . ";</script>";
		echo "<script>openModal('success','Please check your email for a password reset link.','login.php')</script>";
	} 
} // End of the main Submit conditional. 

?> 
<div class="form-contain">
	<h2>Forgot password?</h2>
	<hr>
	<form action="forgot.php" method="post">
		<p><input type="text" class="emailEntry" name="email" size="20" placeholder="E-mail Address" maxlength="40" value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>"  required></p>
		<p><input type="submit" class="submit-button" name="submit" value="Submit" /></p>
		<span><input type="hidden" name="submitted" value="TRUE" /></span>
	</form>
</div>

<?php 
include("includes/footer.php");
?> 