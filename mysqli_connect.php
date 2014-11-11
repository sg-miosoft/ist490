<?php # Script - mysqli_connect.php

//This file contains the database access information.
//This file also establishes a connection to MySQL

//Defining database connection parameters
DEFINE('DB_USER','iptracker');
DEFINE('DB_PASSWORD','7ZzxA5TVZzBvuqtw');
DEFINE('DB_HOST','localhost');
DEFINE('DB_NAME','iptracker');

//Make the connection. 
$dbc = @mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD,DB_NAME) 
OR die ('Could not connect to MySQL or select database: ' . mysqli_error($dbc));


?>