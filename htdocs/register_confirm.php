<?php  
// Include header.php
include("includes/header.php");
// Check if the form has been submitted.
function showForm($e,$t,$fn,$ln) 
{
	echo"<div class='form-contain'>
		<h2>Register</h2><hr />
		<form action='register_confirm.php?token=".$t."' method='post' required>
			<p><input type='text' class='fnameEntry' name='first_name' placeholder='First Name' size='15' maxlength='15' value='".$fn."' required> <input type='text' class='lnameEntry' name='last_name' placeholder='Last Name' size='15' maxlength='30' value='".$ln."' required></p>
			<p><input type='password' class='passEntry' name='password1' placeholder='Password' size='10' maxlength='20' required> <input type='password' class='passConEntry' name='password2' placeholder='Confirm Password' size='10' maxlength='50' required></p>
			<span style='text-align:center'><p><input type='text' class='emailEntry' name='email' placeholder='E-mail Address' size='20' maxlength='40' value='".$e."' readonly ></p></span>
			<span style='text-align:center'><p><input type='submit' class='submit-button' name='submit' value='Register' /></p></span>
			<input type='hidden' name='submitted' value='TRUE' />
		</form>
	</div>";
}
//end new form
if (isset($_GET['token']) && !isset($_POST['submitted']))
{ 
    require_once('../mysqli_connect.php'); // Connect to the db. 
    $errors = array(); // Initialize error array. 
     
    $token = $_GET['token']; 
    $query = "SELECT * FROM pending_users WHERE token = '$token'"; 
    $result = mysqli_query($dbc,$query); 
    if(mysqli_num_rows($result)==1)
	{
		while ($row=mysqli_fetch_array($result))
		{
		    $time_stamp = $row['time_stamp']; 
            $user_email = $row['email'];
			$readonly = $row['readonly'];
        } 
        
		if( time() - strtotime($time_stamp) > 86400)
		{ 
		    $errors[] = 'Time expired. Please try again.'; // Public message. 
        } 
        else
		{
		    showForm($user_email,$token,null,null); 
        } 
    } 
    else
	{
		$errors[] = 'We do not have a request to register for your email.'; 
		$errors[] = 'Please try again.'; // Public message. 
    } 
    mysqli_close($dbc); // Close the database connection.     
} 
elseif(isset($_POST['submitted'], $_GET['token'])) 
{ 
    require_once ('../mysqli_connect.php'); // Connect to the db. 
    require_once ('../passwordLib.php'); // Connect to the db. 
    $errors = array(); // Initialize error array. 
    
    $email = $_POST['email']; 
    $token = $_GET['token']; 
    $first_name = $_POST['first_name']; 
    $last_name = $_POST['last_name']; 
    
	$query = "SELECT readonly FROM pending_users WHERE token = '" . $token . "'";  
	$result = mysqli_query($dbc,$query); 
    if(mysqli_num_rows($result)==1)
	{
		while ($row=mysqli_fetch_array($result))
		{
			$readonly = $row['readonly'];
		}
	}
	
    // Check for a first name. 
    if(empty($_POST['first_name']))
	{ 
        $errors[] = 'You forgot to enter your first name.'; 
    }
	else
	{ 
        $first_name = mysqli_real_escape_string($dbc,$_POST['first_name']); 
    } 

    // Check for a last name. 
    if(empty($_POST['last_name']))
	{ 
        $errors[] = 'You forgot to enter your last name.'; 
    }
	else
	{ 
        $last_name = mysqli_real_escape_string($dbc,$_POST['last_name']); 
    } 
     
    // Check for a password and match against the confirmed password. 
    if(!empty($_POST['password1']) && !empty($_POST['password2']) && empty($errors))
	{
        if($_POST['password1'] != $_POST['password2'])
		{ 
            $errors[] = 'Passwords do not match.'; 
            $sForm = TRUE; 
        }
		else
		{ 
            $options = [ 
                'cost' => 11, 
            ]; 
            $password = mysqli_real_escape_string($dbc,$_POST['password1']); 
            $hash = password_hash($password, PASSWORD_BCRYPT, $options); 
             
            $query = "INSERT INTO users (first_name, last_name, email, pass, registration_date, readonly)  
            VALUES ('" . $first_name . "', '" . $last_name . "', '" . $email . "', '" . $hash . "', NOW()," . $readonly . ")";
			
            $result = @mysqli_query($dbc,$query); // Run the query. 
            if($result) 
			{ 
				$query = "DELETE FROM pending_users WHERE email = '" . $email . "'";
				$result = @mysqli_query($dbc,$query);
				$showSuccess = True;
			}
			else 
			{ 
				// If it did not run OK. 
                $errors[] = mysqli_error($dbc); // MySQL error message. 
            } 
        } 
    } 
	else 
	{ 
        $errors[] = 'You forgot to enter your password.'; 
        $sForm = TRUE;     
    } 
    mysqli_close($dbc); // Close the database connection.     
} 
else
{ 
    $errors[] = 'The form was not submitted properly.'; 
}
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
	<input type="button" id="closeX" value="X" onClick="document.getElementById('statusDialog').close();">
	<h2 id="dialogH2"></h2>
	
	<div id="dialogDiv"></div>
	
<?php
// Begin the page now. 
if(!empty($errors) && !$sForm)
{
	echo '<button id="close" onClick="window.location.href=\'forgot.php\';">Close</button>
		</dialog>';
	echo "<script>openModal('fail',errors)</script>";
} 
elseif(!empty($errors) && $sForm)
{ 
	echo '<button id="close" onClick="document.getElementById(\'statusDialog\').close();">Close</button>
		</dialog>';
	echo '<script>openModal("fail",errors)</script>';
	showForm($email,$token,$first_name,$last_name); 
} 
elseif($showSuccess)
{
	echo "<button id=\"close\" onClick=\"window.location.href='login.php';\">Login</button>
		</dialog>";
	echo "<script>openModal('success','Please Login.')</script>";
} 

// Include footer.php 
include("includes/footer.php");
?>