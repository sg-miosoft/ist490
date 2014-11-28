<?php  
// Include header.php
include("includes/header.php");
// Check if the form has been submitted.
function showForm($e,$t,$fn,$ln) 
{
	echo"<br />
	<div class='form-contain'>
		<h2>Register</h2><hr />
		<form action='register_confirm.php?token=".$t."' method='post'>
			<p><input type='text' class='fnameEntry' name='first_name' placeholder='First Name' size='15' maxlength='15' value='".$fn."' /> <input type='text' class='lnameEntry' name='last_name' placeholder='Last Name' size='15' maxlength='30' value='".$ln."' /></p>
			<p><input type='password' class='passEntry' name='password1' placeholder='Password' size='10' maxlength='20' /> <input type='password' class='passConEntry' name='password2' placeholder='Confirm Password' size='10' maxlength='50' /></p>
			<span style='text-align:center'><p><input type='text' class='emailEntry' name='email' placeholder='E-mail Address' size='20' maxlength='40' value='".$e."' readonly /></p></span>
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
            $time_now = date("Y-m-d H:i:s"); 
            $user_email = $row['email']; 
        } 
         
        // Check to see if link has expired 
        if(($time_now - $time_stamp)  > 86400)
		{ 
            $errors[] = 'Invalid token.'; // Public message. 
        } 
        else
		{ 
            showForm($user_email,$token,null,null); 
        } 
    } 
    else
	{ 
        $errors[] = 'Invalid token.'; // Public message. 
    } 
    mysqli_close($dbc); // Close the database connection.     
} 
elseif(isset($_POST['submitted'], $_GET['token'])) 
{ 
    require_once ('../mysqli_connect.php'); // Connect to the db. 
    require_once ('.../passwordLib.php'); // Connect to the db. 
    $errors = array(); // Initialize error array. 
     
    $email = $_POST['email']; 
    $token = $_GET['token']; 
    $first_name = $_POST['first_name']; 
    $last_name = $_POST['last_name']; 
         
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
    }else
	{ 
        $last_name = mysqli_real_escape_string($dbc,$_POST['last_name']); 
    } 
     
    // Check for a password and match against the confirmed password. 
    if(!empty($_POST['password1']) && !empty($_POST['password2']) && empty($errors))
	{
        if($_POST['password1'] != $_POST['password2'])
		{ 
            $errors[] = 'Your password did not match the confirmed password.'; 
            $sForm = TRUE; 
        }
		else
		{ 
            $options = [ 
                'cost' => 11, 
            ]; 
            $password = mysqli_real_escape_string($dbc,$_POST['password1']); 
            $hash = password_hash($password, PASSWORD_BCRYPT, $options); 
             
            $query = "INSERT INTO users (first_name, last_name, email, pass, registration_date)  
            VALUES ('$first_name', '$last_name', '$email', '$hash', NOW() )";         
            $result = @mysqli_query($dbc,$query); // Run the query. 
            if($result) 
			{ 
				header("Location: https://uwm-iptracker.miosoft.com/home/login.php"); 
			}
			else 
			{ // If it did not run OK. 
                $errors[] = 'You could not be registered due to a system error. We apologize for any inconvenience.'; // Public message. 
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

// Begin the page now. 
if(!empty($errors) && !$sForm)
{ // Print any error messages. 
    echo '<h1>Error!</h1> 
    <p>The following error(s) occurred:<br />'; 
    foreach ($errors as $msg) 
	{ // Print each error. 
        echo "$msg<br />"; 
    } 
    echo '</p>'; 
    echo '<a href="register.php"> Please submit a new registration request.</a>'; 
 } 
elseif(!empty($errors) && $sForm)
{ 
    echo '<h1>Error!</h1> 
    <p>The following error(s) occurred:<br />'; 
    foreach ($errors as $msg)
	{ // Print each error. 
        echo "$msg<br />"; 
    } 
    echo '</p>'; 
    showForm($email,$token,$first_name,$last_name); 
} 
?> 




<?php 
// Include footer.php 
include("includes/footer.php");
?>