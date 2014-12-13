<?php   
#code to deal with forgot password  
include ("includes/header.php");  

function showForm($u,$t) 
{ 
    echo "<div class='form-contain'>
		<h2>Reset Password</h2> 
		<hr />
        <form action=reset.php?token='" . $t . "' method='post'>
			<p><input required type='password' name='password1' class='passEntry' size='10' maxlength='20' placeholder='Password' required></p>
			<p><input required type='password' name='password2' class='passEntry' size='10' maxlength='20' placeholder='Confirm Password' required></p>
			<p><input type='submit' class='submit-button' name='submit' value='Submit' /></p>
			<input type='hidden' name='submitted' value='TRUE' />
			<input type='hidden' name='user_id' value='".$u."' /> 
    </form>
	</div>"; 
	include("includes/footer.php");
} 

if(isset($_GET["token"]) && !isset($_POST["submitted"])){ 
    require_once ("../mysqli_connect.php"); // Connect to the db. 
    $errors = array(); // Initialize error array. 
     
    $token = $_GET["token"];
	$query = "SELECT * FROM reset_users WHERE token ='" . $token . "'";
	$result = mysqli_query($dbc,$query); 
    if(mysqli_num_rows($result)==1)
	{ 
        while ($row=mysqli_fetch_array($result))
		{ 
            $time_stamp = $row['time_stamp']; 
            $user_id = $row["user_id"]; 
		}                 
         
        if( time() - strtotime($time_stamp) > 86400)
		{ 
		    $errors[] = 'Time expired. Please try again.'; // Public message. 
        } 
        else
		{ 
            showForm($user_id,$token); 
        } 
    } 
    else
	{ 
        $errors[] = "We do not have a reset request for you."; // Public message. 
    } 
    mysqli_close($dbc); // Close the database connection.     
} 
elseif (isset($_POST['submitted'], $_GET['token']))
{ 
	$user_id = $_POST['user_id']; 
    $token = $_GET['token']; 
    require_once ("../mysqli_connect.php"); // Connect to the db. 
    require_once ("../passwordLib.php"); // Connect to the db. 
    $errors = array(); // Initialize error array. 
     
    // Check for a password and match against the confirmed password. 
    if(!empty($_POST['password1']) and !empty($_POST['password2']))
	{
		if ($_POST['password1'] != $_POST['password2'])
		{ 
            $errors[] = "Your password did not match the confirmed password."; 
            $sForm = TRUE; 
        }
		else
		{ 
            $options = [ 
                "cost" => 11, 
            ]; 
            $password = mysqli_real_escape_string($dbc,$_POST['password1']); 
            $hash = password_hash($password, PASSWORD_BCRYPT, $options); 
             
            //$query = "UPDATE users JOIN reset_users ON users.user_id = reset_users.user_id SET pass = '" . $hash . "' WHERE reset_users.user_id = " . $user_id . " AND reset_users.token = '" . $token . "'";
            $set_query = "UPDATE users SET pass = '" . $hash . "' WHERE user_id = " . $user_id; 
            $set_result = @mysqli_query($dbc,$set_query); // Run the query. 
            if($set_result)
			{ // If it ran OK. 
                $del_query = "DELETE FROM reset_users WHERE user_id = " . $user_id . " AND token = ". $token; 
                $del_result = @mysqli_query($dbc,$del_query);
				$showSuccess = True;
			}
			else
			{ // If it did not run OK. 
                $errors[] = "Your password could not be changed due to a system error.";
                $errors[] = mysqli_error($dbc); // MySQL error message. 
            } 
        } 
    }
	else 
	{
		$errors[] = "You forgot to enter your password."; 
        $sForm = TRUE;     
    } 
    mysqli_close($dbc); // Close the database connection.     
} 
else
{ 
    $errors[] = "The form was not submitted properly."; 
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
	showForm($user_id,$token); 
}
elseif($showSuccess)
{
	echo "<button id=\"close\" onClick=\"window.location.href='login.php';\">Close</button>
		</dialog>";
	echo "<script>openModal('success','Password changed!')</script>";
} 

include("includes/footer.php");
?>