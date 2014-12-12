<?php 
// Send NOTHING to the Web browser prior to the session_start() line! 
// Check if the form has been submitted. 
if (isset($_SESSION['email']))
{ 
	header("Location: https://uwm-iptracker.miosoft.com/index.php"); 
}
elseif(isset($_POST['submitted'])) 
{
?>
	<script>
		function openModal(message)
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
			
			document.getElementById('dialogH2').innerText = header;
			document.getElementById('statusDialog').showModal();
		}
	</script>

	<dialog id="statusDialog">
		<input type="button" id="closeX" value="X" onClick="document.getElementById('statusDialog').close();">
		<h2 id="dialogH2"></h2>
		
		<div id="dialogDiv"></div>
		<button id="close" type="submit" onClick="document.getElementById('statusDialog').close();">Close</button>
	</dialog>
<?php 
    require_once ('../mysqli_connect.php'); // Connect to the db. 
    require_once ('../passwordLib.php'); // Connect to the db. 
    $errors = array(); // Initialize error array. 
    // Check for an email address. 
    if (empty($_POST['email']))
	{ 
        $errors[] = 'You forgot to enter your email address.'; 
    }
	else 
	{ 
        $email = mysqli_real_escape_string($dbc,trim($_POST['email']));
		if(!filter_var($email, FILTER_VALIDATE_EMAIL))
		{
			$errors[] = "Not a valid email address.";
		}
    } 
    // Check for a password. 
    if(empty($_POST['password']))
	{ 
        $errors[] = 'You forgot to enter your password.'; 
    }  
    elseif(empty($errors))
	{ 
        $options = [ 
                'cost' => 11, 
        ]; 
        $password = mysqli_real_escape_string($dbc,$_POST['password']); 
        $hash = password_hash($password, PASSWORD_BCRYPT, $options); 
         
        $query = "SELECT * FROM users WHERE email= '". $email . "'";
		$result = @mysqli_query($dbc,$query); // Run the query. 
        $row = mysqli_fetch_array($result,  MYSQLI_NUM); 
        if($row)
		{ 	
            //Set the session data: 
            if(password_verify($p,$row[4])) 
			{ 
                //Set the session data: 
                session_start();  
                $_SESSION['user_id'] = $row[0]; 
                $_SESSION['first_name'] = $row[1]; 
                $_SESSION['last_name'] = $row[2]; 
                $_SESSION['email'] = $row[3];
				$_SESSION['readonly'] = $row[6];
                header("Location: https://uwm-iptracker.miosoft.com/index.php"); 
            } 
            else
			{ 
                $errors[] = "Incorrect Password."; // Public message. 
            } 
        } 
        else
		{ 
            $errors[] = "The email address is not registered."; // Public message. 
        } 
    }

	if(!empty($errors))
	{ 
		echo "<script>var errors = ";
		echo json_encode($errors) . ';</script>';
		echo "<script>openModal(errors)</script>";
	} //else already logged in.
	 
    mysqli_close($dbc); // Close the database connection. 
} 
else
{ // Form has not been submitted. 
    $errors = NULL; 
} // End of the main Submit conditional. 

// Begin the page now. 
include ('includes/header.php'); 


// Create the form. 
?> 
<div class="form-contain">
<h2>Please, login here.</h2>
	<hr>
	<form action="login.php" method="post">
		<p><input type="text" required class="emailEntry" name="email" size="30" maxlength="40" value="" placeholder="Email Address" /></p>
		<p><input type="password" required class="passEntry" name="password" size="30" maxlength="20" value="" placeholder="Password" /></p>
		<span><p><input type="submit" class="submit-button" name="submit" value="Login" /></p></span>
		<input type="hidden" name="submitted" value="TRUE" />
	</form>
</div>

<?php
include ('includes/footer.php');
?>
