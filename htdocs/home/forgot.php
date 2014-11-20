<?php   
#code to deal with forgot password  
include ('../includes/header.php');  
function sendEmail($e,$t) {  
    // Send an email, if desired.  
    $to=$e;   
    $subject="UWM IPtracker Password Reset";  
    $body="  
    Thank you very much for being a member of the UWM IPtracker!\n\n  
    Click the link below to reset your password.\n\n  
    https://uwm-iptracker.miosoft.com/Project/htdocs/home/reset.php?token=$t.\n\n  
    Thanks again!\n\n  
    https://uwm-iptracker.miosoft.com/Project/htdocs/home/index.php";   
    $headers="From: Spencer George <smtp.sender@us.msn.main.miosoft.com>\n";  // <-- Replace this to your email address!!!  
    mail ($to, $subject, $body, $headers); // SEND the message!    

    // Print a message.  
    echo '<h1 id="mainhead">Thank you!</h1>  
    <p>Please check your email to get your password reset link.</p>';   

    // Include the footer and quit the script (to not show the form).  
    include ('../includes/footer.php');  
    exit();      
}  
// Check if the form has been submitted.  
if (isset($_POST['submitted'])) {  
    require_once ('../../mysqli_connect.php'); // Connect to the db.  
    $errors = array(); // Initialize error array. 

    // Check for an email address. 
    if (empty($_POST['email'])) { 
        $errors[] = 'You forgot to enter your email address.'; 
    } else { 
        $user_email = mysqli_real_escape_string($dbc,$_POST['email']); 
    } 

    if (empty($errors)) { // If everything's okay. 
        // Check for previous registration. 
        $query = "SELECT * FROM users WHERE email='$user_email'";  
        $result = mysqli_query($dbc,$query); 
        if (mysqli_num_rows($result)==1) { 
            while ($row=mysqli_fetch_array($result)){ 
                $user_id=$row[0]; 
            }                                 
            $token = uniqid(mt_rand(), true); 
            //Insert pending user information into database 
            $query = "SELECT `token` FROM `reset_users` WHERE `user_id` = '$user_id'"; 
            $result = mysqli_query($dbc,$query); 
            if (mysqli_num_rows($result) == 0) { // if there is no such user pending 
                $query = "INSERT INTO reset_users (user_id, token, time_stamp) VALUES ('$user_id', '$token', NOW() )"; 
                $result = mysqli_query($dbc,$query); 
                if($result) //If it ran okay 
                { 
                    sendEmail($user_email,$token); 
                } 
                else{ // If it did not run OK. 
                    $errors[] = 'Your password could not be changed due to a system error. We apologize for any inconvenience.'; // Public message. 
                    $errors[] = mysqli_error($dbc); // MySQL error message. 
                } 
            } 
            else { // User already pending 
                 
                $query = "UPDATE `reset_users` SET `token`='$token', `time_stamp`= NOW() WHERE user_id = '$user_id'"; 
                $result = mysqli_query($dbc,$query); 
                if($result) //If it ran okay 
                { 
                    sendEmail($user_email,$token); 
                } 
                else{ // If it did not run OK. 
                    $errors[] = 'Your password could not be changed due to a system error. We apologize for any inconvenience.'; // Public message. 
                    $errors[] = mysqli_error($dbc); // MySQL error message. 
                } 
            } 
        } else { // Not registered. For security don't tell them it failed. 
            echo '<h1 id="mainhead">Thank you!</h1> 
            <p>Please, check your email for a password reset link.</p>';  
            include ('../includes/footer.php'); 
            exit(); 
        } 
    } else { // Report the errors. 
         
    } // End of if (empty($errors)) IF. 

    mysqli_error($dbc); // Close the database connection. 
} // End of the main Submit conditional. 

if(!empty($errors)) { 
    echo '<font color=red><h4>Error!</h4> 
    <p>The following error(s) occurred:<br />'; 
    foreach ($errors as $msg) { // Print each error. 
        echo " - $msg<br />\n"; 
    } 
    echo '</p><p>Please try again.</p><p><br /></p></font>'; 
} 

?> 
<div class="forgotContain">
<h2 style="text-align:center">Forgot username or password?</h2>
<form action="forgot.php" method="post">
	<input type="text" class="emailEntry" name="email" size="20" placeholder="E-mail Address" maxlength="40" value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>"  />
	<input type="submit" class="submitButton" name="submit" value="Submit" />
	<input type="hidden" name="submitted" value="TRUE" />
</form>
</div>
<p>

<?php 
include ('../includes/footer.php'); 
?> 