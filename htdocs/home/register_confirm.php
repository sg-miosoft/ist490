<?php  
// Include header.php
include("../includes/header.php");
// Check if the form has been submitted.
function showForm($e,$t,$fn,$ln) { 
/*    echo '<h2>Register</h2>'; 
    echo '<form action="register_confirm.php?token='.$t.'" method="post">'; 
        echo '<p>First Name: <input type="text" name="first_name" size="15" maxlength="15" value="'.$fn.'" /></p>'; 
        echo '<p>Last Name: <input type="text" name="last_name" size="15" maxlength="30" value="'.$ln.'" /></p>'; 
        echo '<p>Email Address: <input type="text" name="email" size="20" maxlength="40" value="'.$e.'"  readonly/> </p>'; 
        echo '<p>Password: <input type="password" name="password1" size="10" maxlength="20" /></p>'; 
        echo '<p>Confirm Password: <input type="password" name="password2" size="10" maxlength="20" /></p>'; 
        echo '<p><input type="submit" name="submit" value="Register" /></p>'; 
    echo '    <input type="hidden" name="submitted" value="TRUE" />'; 
    echo '</form>'; 
} */
//start new form
echo'<br />';
echo'<div class="regContain">';
echo'<h2>Register</h2><hr />';
echo'<form action="register_confirm.php?token='.$t.'" method="post">';
	echo'<p><input type="text" class="fnameEntry" name="first_name" placeholder="First Name" size="15" maxlength="15" value="'.$fn.'" /> <input type="text" class="lnameEntry" name="last_name" placeholder="Last Name" size="15" maxlength="30" value="'.$ln.'" /></p>';
    echo'<p><input type="password" class="passEntry" name="password1" placeholder="Password" size="10" maxlength="20" /> <input type="password" class="passConEntry" name="password2" placeholder="Confirm Password" size="10" maxlength="50" /></p>';
	echo'<span style="text-align:center"><p><input type="text" class="emailEntry" name="email" placeholder="E-mail Address" size="20" maxlength="40" value="'.$e.'" readonly /></p></span>';
    echo'<span style="text-align:center"><p><input type="submit" class="submitButton" name="submit" value="Register" /></p></span>';
	echo'<input type="hidden" name="submitted" value="TRUE" />';
echo'</form>';
echo'</div>';
}
//end new form
if (isset($_GET['token']) && !isset($_POST['submitted'])){ 
    require_once ('../../mysqli_connect.php'); // Connect to the db. 
    $errors = array(); // Initialize error array. 
     
    $token = $_GET['token']; 
    $query = "SELECT * FROM pending_users WHERE token = '$token'"; 
    $result = mysqli_query($dbc,$query); 
    if (mysqli_num_rows($result)==1) { 
        while ($row=mysqli_fetch_array($result)) { 
            $time_stamp = $row['time_stamp']; 
            $time_now = date("Y-m-d H:i:s"); 
            $user_email = $row['email']; 
        } 
         
        // Check to see if link has expired 
        if(($time_now - $time_stamp)  > 86400) { 
            $errors[] = 'Invalid token.'; // Public message. 
        } 
        else{ 
            showForm($user_email,$token,null,null); 
        } 
    } 
    else { 
        $errors[] = 'Invalid token.'; // Public message. 
    } 
    mysqli_close($dbc); // Close the database connection.     
} 
elseif (isset($_POST['submitted'], $_GET['token'])) { 
    require_once ('../../mysqli_connect.php'); // Connect to the db. 
    require_once ('../../passwordLib.php'); // Connect to the db. 
    $errors = array(); // Initialize error array. 
     
    $email = $_POST['email']; 
    $token = $_GET['token']; 
    $first_name = $_POST['first_name']; 
    $last_name = $_POST['last_name']; 
         
    // Check for a first name. 
    if (empty($_POST['first_name'])) { 
        $errors[] = 'You forgot to enter your first name.'; 
    } else { 
        $first_name = mysqli_real_escape_string($dbc,$_POST['first_name']); 
    } 

    // Check for a last name. 
    if (empty($_POST['last_name'])) { 
        $errors[] = 'You forgot to enter your last name.'; 
    } else { 
        $last_name = mysqli_real_escape_string($dbc,$_POST['last_name']); 
    } 
     
     
    // Check for a password and match against the confirmed password. 
    if (!empty($_POST['password1']) && !empty($_POST['password2']) && empty($errors)) { 
         
        if ($_POST['password1'] != $_POST['password2']) { 
            $errors[] = 'Your password did not match the confirmed password.'; 
            $sForm = TRUE; 
        } else { 
            $options = [ 
                'cost' => 11, 
            ]; 
            $password = mysqli_real_escape_string($dbc,$_POST['password1']); 
            $hash = password_hash($password, PASSWORD_BCRYPT, $options); 
             
            $query = "INSERT INTO users (first_name, last_name, email, pass, registration_date)  
            VALUES ('$first_name', '$last_name', '$email', '$hash', NOW() )";         
            $result = @mysqli_query($dbc,$query); // Run the query. 
            if ($result) { // If it ran OK. 
                $query = "DELETE FROM `pending_users` WHERE email = '$email' AND token = '$token'"; 
                $result = @mysqli_query($dbc,$query); 
                echo "<p>You are now registered. Please, login to use our great service.</p>"; 
                echo "<a href=login.php>Login</a>"; 
                exit(); 
            } else { // If it did not run OK. 
                $errors[] = 'You could not be registered due to a system error. We apologize for any inconvenience.'; // Public message. 
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
    echo '<a href="register.php"> Please submit a new registration request.</a>'; 
     
} 
elseif (!empty($errors) && $sForm) { 
    echo '<h1>Error!</h1> 
    <p>The following error(s) occurred:<br />'; 
    foreach ($errors as $msg) { // Print each error. 
        echo "$msg<br />"; 
    } 
    echo '</p>'; 
    showForm($email,$token,$first_name,$last_name); 
} 
?> 




<?php 
// Include footer.php 
include("../includes/footer.php"); 
?>