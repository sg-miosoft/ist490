<?php  
// Include header.php 
include("../includes/header.php"); 

function sendEmail($e,$t) { 
    // Send an email, if desired. 
    $to=$e; 
    $subject="UWM IPTracker"; 
    $body=" 
    Thank you very much for being a member of http://uwm-iptracker.miosoft.com/Project/htdocs/home/index.php!\n\n 
    Click the link below to confirm your registration.\n\n 
    http://uwm-iptracker.miosoft.com/Project/htdocs/home/register_confirm.php?token=$t.\n\n 
    Thanks again!\n\n 
    http://uwm-iptracker.miosoft.com/Project/htdocs/home/index.php";  
    $headers="From: Spencer George <smtp.sender@us.msn.main.miosoft.com>\n";  // <-- Replace this to your email address!!! 
    mail ($to, $subject, $body, $headers); // SEND the message!   

    // Print a message. 
    echo '<h1 id="mainhead">Thank you!</h1> 
    <p>Please check your email to get your registration confirmation link.</p>';  

    // Include the footer and quit the script (to not show the form). 
    include ('../includes/footer.php'); 
    exit();     
} 

// Check if the form has been submitted. 
if (isset($_POST['submitted'])) { 
    require_once ('../../mysqli_connect.php'); // Connect to the db. 
    require_once ('../../passwordLib.php'); // Connect to the db. 
    $errors = array(); // Initialize error array. 

    // Check for an email address. 
    if (empty($_POST['email'])) { 
        $errors[] = 'You forgot to enter your email address.'; 
    } else { 
        $email = mysqli_real_escape_string($dbc,$_POST['email']); 
    } 

    if (empty($errors)) { // If everything's OK. 
         
        // Register the user in the database. 
        // Check for previous registration. 
        $query = "SELECT user_id FROM users WHERE email='$email'"; 
        $result = mysqli_query($dbc,$query); 
        if (mysqli_num_rows($result) == 0) { // if there is no such email address 
            $query = "SELECT email FROM pending_users WHERE email='$email'"; 
            $result = mysqli_query($dbc,$query); 
            if (mysqli_num_rows($result) == 0) { // if there is no such email address 
                $token = uniqid(mt_rand(), true); 
                // Make the query. 
                $query = "INSERT INTO pending_users (email, token, time_stamp)  
                VALUES ('$email', '$token', NOW() )";         
                $result = @mysqli_query($dbc,$query); // Run the query. 
                if ($result) { // If it ran OK. 
                    sendEmail($email,$token); 
                    echo "<p>Please check your email for a confirmation link.</p>"; 
                    echo "<a href=login.php>Login</a>"; 
                    exit(); 
                } else { // If it did not run OK. 
                    $errors[] = 'You could not be registered due to a system error. We apologize for any inconvenience.'; // Public message. 
                    $errors[] = mysqli_error($dbc); // MySQL error message. 
                } 
            } 
            else { 
                $token = uniqid(mt_rand(), true); 
                $query = "UPDATE `pending_users` SET `token`='$token', `time_stamp`= NOW() WHERE email = '$email'"; 
                $result = mysqli_query($dbc,$query); 
                if($result) //If it ran okay 
                { 
                    sendEmail($email,$token); 
                } 
                else{ // If it did not run OK. 
                    $errors[] = 'Your password could not be changed due to a system error. We apologize for any inconvenience.'; // Public message. 
                    $errors[] = mysqli_error($dbc); // MySQL error message. 
                } 
            } 

        } else { // Email address is already taken. 
            $errors[] = 'The email address has already been registered.'; 
            $errors[] = '<a href="forgot.php">If you forgot your password you can reset it here.</a>'; 
        } 

    } // End of if (empty($errors)) IF. 

    mysqli_close($dbc); // Close the database connection. 

} else { // Form has not been submitted. 
    $errors = NULL; 
} // End of the main Submit conditional. 

// Begin the page now. 
if (!empty($errors)) { // Print any error messages. 
    echo '<h1>Error!</h1> 
    <p>The following error(s) occurred:<br />'; 
    foreach ($errors as $msg) { // Print each error. 
        echo "$msg<br />"; 
    } 
    echo '</p>'; 
    echo '<p>Please try again.</p>'; 
} 

// Create the form. 
?> 
<h2>Register</h2> 
    <form action="register.php" method="post"> 
        <p>Email Address: <input type="text" name="email" size="20" maxlength="40" value="<?php echo $_POST['email']; ?>"  /> </p> 
        <p><input type="submit" name="submit" value="Register" /></p> 
        <input type="hidden" name="submitted" value="TRUE" /> 
    </form> 

<?php 
// Include footer.php 
include("../includes/footer.php"); 
?> 