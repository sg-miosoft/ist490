<html><body><center> 
<img src="../includes/dartboard.jpg" alt="Midnight Crazy Darts. Nice Shot!" title="Midnight Crazy Logo" /><br> 
<h1>UWM IPTracker</h1>  
<h2>Spencer George | 490 Senior Capstone Project</h2>  
<Table width="1000" cellpadding="10"><tr><td align="right"> 

<p></td></tr></table> 
<table width="1000" cellpadding="10"><td width="100" valign="top"> 
</td><td valign="top"> 
<?php  
function showForm($u,$t) { 
    echo '<h2>Reset Password</h2>'; 
        echo '<form action="reset.php?token='.$t.'" method="post">'; 
        echo '<p>New Password: <input type="password" name="password1" size="10" maxlength="20" /></p>'; 
        echo '<p>Confirm Password: <input type="password" name="password2" size="10" maxlength="20" /></p>'; 
        echo '<p><input type="submit" name="submit" value="Submit" /></p>'; 
        echo '<input type="hidden" name="submitted" value="TRUE" />'; 
        echo '<input type="hidden" name="user_id" value="'.$u.'" />'; 
    echo '</form>'; 
} 

if (isset($_GET['token']) && !isset($_POST['submitted'])){ 
    require_once ('../../mysqli_connect.php'); // Connect to the db. 
    $errors = array(); // Initialize error array. 
     
    $token = $_GET['token']; 
    $query = "SELECT * FROM reset_users WHERE token = '$token'"; 
    $result = mysqli_query($dbc,$query); 
    if (mysqli_num_rows($result)==1) { 
        while ($row=mysqli_fetch_array($result)) { 
            $time_stamp = $row['time_stamp']; 
            $time_now = date("Y-m-d H:i:s"); 
            $user_id = $row['user_id']; 
            //echo $user_id; 
        }                 
         
        // Check to see if link has expired 
        if(($time_now - $time_stamp)  > 86400) { 
            $errors[] = 'Invalid token.'; // Public message. 
        } 
        else{ 
            showForm($user_id,$token); 
        } 
    } 
    else { 
        $errors[] = 'Invalid token.'; // Public message. 
    } 
    mysqli_close($dbc); // Close the database connection.     
} 
elseif (isset($_POST['submitted'], $_GET['token'])) { 
    $user_id = $_POST['user_id']; 
    $token = $_GET['token']; 
    require_once ('../../mysqli_connect.php'); // Connect to the db. 
    require_once ('../../passwordLib.php'); // Connect to the db. 
    $errors = array(); // Initialize error array. 
     
    // Check for a password and match against the confirmed password. 
    if (!empty($_POST['password1']) && !empty($_POST['password2'])) { 
         
        if ($_POST['password1'] != $_POST['password2']) { 
            $errors[] = 'Your password did not match the confirmed password.'; 
            $sForm = TRUE; 
        } else { 
            $options = [ 
                'cost' => 11, 
            ]; 
            $password = mysqli_real_escape_string($dbc,$_POST['password1']); 
            $hash = password_hash($password, PASSWORD_BCRYPT, $options); 
             
            $query = "UPDATE `users` JOIN `reset_users` ON users.user_id = reset_users.user_id SET `pass` = '$hash' WHERE reset_users.user_id = '$user_id' AND reset_users.token = '$token'";
            // $query = "UPDATE users SET pass = '$hash' WHERE user_id = '$user_id'"; 
            $result = @mysqli_query($dbc,$query); // Run the query. 
            if ($result) { // If it ran OK. 
                $query = "DELETE FROM `reset_users` WHERE user_id = '$user_id' AND token = '$token'"; 
                $result = @mysqli_query($dbc,$query); 
                echo "<p>Your password has been changed. Please, login to use our great service.</p>"; 
                echo "<a href=login.php>Login</a>"; 
                exit(); 
            } else { // If it did not run OK. 
                $errors[] = 'Your password could not be changed due to a system error. We apologize for any inconvenience.'; // Public message. 
                $errors[] = mysqli_error($dbc); // MySQL error message. 
            } 
        } 
    } else { 
        $errors[] = 'You forgot to enter your password.'; 
        $sForm = TRUE;     
    } 
    mysqli_close($dbc); // Close the database connection.     
} 
else{ 
    $errors[] = 'The form was not submitted properly.'; 
} 

// Begin the page now. 
if (!empty($errors) && !$sForm) { // Print any error messages. 
    echo '<h1>Error!</h1> 
    <p>The following error(s) occurred:<br />'; 
    foreach ($errors as $msg) { // Print each error. 
        echo "$msg<br />"; 
    } 
    echo '</p>'; 
    echo '<a href="forgot.php"> Please submit a new password reset request.</a>'; 
     
} 
elseif (!empty($errors) && $sForm) { 
    echo '<h1>Error!</h1> 
    <p>The following error(s) occurred:<br />'; 
    foreach ($errors as $msg) { // Print each error. 
        echo "$msg<br />"; 
    } 
    echo '</p>'; 
    showForm($user_id,$token); 
} 
?> 




<?php 
// Include footer.php 
include("../includes/footer.php"); 
?>