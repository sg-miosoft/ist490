<?php 
// Send NOTHING to the Web browser prior to the session_start() line! 
// Check if the form has been submitted. 
if (isset($_POST['submitted'])) { 
    require_once ('../../mysqli_connect.php'); // Connect to the db. 
    require_once ('../../passwordLib.php'); // Connect to the db. 
    $errors = array(); // Initialize error array. 
    // Check for an email address. 
    if (empty($_POST['email'])) { 
        $errors[] = 'You forgot to enter your email address.'; 
    } else { 
        $e = mysqli_real_escape_string($dbc,trim($_POST['email'])); 
    } 
    // Check for a password. 
    if (empty($_POST['password'])) { 
        $errors[] = 'You forgot to enter your password.'; 
    }  
    else { 
        $options = [ 
                'cost' => 11, 
        ]; 
        $p = mysqli_real_escape_string($dbc,$_POST['password']); 
        $hash = password_hash($p, PASSWORD_BCRYPT, $options); 
         
        $query = "SELECT * FROM users WHERE email='$e'";  
        $result = @mysqli_query($dbc,$query); // Run the query. 
        $row = mysqli_fetch_array($result,  MYSQLI_NUM); 
        if ($row) { // A record was pulled from the database. 
            //Set the session data: 
            if(password_verify($p,$row[4])) { 
                //Set the session data: 
                session_start();  
                $_SESSION['user_id'] = $row[0]; 
                $_SESSION['first_name'] = $row[1]; 
                $_SESSION['last_name'] = $row[2]; 
                $_SESSION['email'] = $row[3];  
                header("Location: https://uwm-iptracker.miosoft.com/home/index.php"); 
                exit(); // Quit the script. 
            } 
            else{ 
                $errors[] = 'The email address and password entered do not match those on file.'; // Public message. 
            } 
        } 
        else{ 
            $errors[] = 'The email address is not registered. <a href="register.php">You can register Here.</a>'; // Public message. 
        } 
    } 
     
    mysqli_close($dbc); // Close the database connection. 
} else { // Form has not been submitted. 
    $errors = NULL; 
} // End of the main Submit conditional. 

// Begin the page now. 
$page_title = 'Login'; 
include ('../includes/header.php'); 
if (!empty($errors)) { // Print any error messages. 
    echo '<h1 id="mainhead">Error!</h1> 
    <p class="error">The following error(s) occurred:<br />'; 
    foreach ($errors as $msg) { // Print each error. 
        echo " - $msg<br />\n"; 
    } 
    echo '</p><p>Please try again.</p>'; 
} 

// Create the form. 
?> 
<div class="form-contain">
<h2>Please, login here.</h2>
	<hr>
	<form action="login.php" method="post">
		<p><input type="text" class="emailEntry" name="email" size="30" maxlength="40" value="" placeholder="Email Address" /></p>
		<p><input type="password" class="passEntry" name="password" size="30" maxlength="20" value="" placeholder="Password" /></p>
		<span><p><input type="submit" class="submit-button" name="submit" value="Login" /></p></span>
		<input type="hidden" name="submitted" value="TRUE" />
	</form>
</div>

<?php
include ('../includes/footer.php');
?>
