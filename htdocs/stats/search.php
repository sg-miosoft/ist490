<?php
session_start(); 
//check session first 
if (!isset($_SESSION['email'])){ 
    echo "You are not logged in!"; 
    exit(); 
}else{ 
    //include the header 
    include ("../includes/header.php"); 
    require_once ('../../mysqli_connect.php'); 
    echo ("<html><title>Search Results</title><center>");  
    echo ("<a href=searchform.php>Another Search</a><p>");  
    echo ("<a href=index.php>home</a><p>");  
    $user_id = $_SESSION['user_id']; 
    //formulate the search query 
    if (!empty($_POST['id'])||!empty($_POST['title'])||!empty($_POST['url']) 
        ||!empty($_POST['comment'])){ 
        $id = mysqli_real_escape_string($dbc,$_POST['id']);  
        $week = mysqli_real_escape_string($dbc,$_POST['week']);  
        $mpr = mysqli_real_escape_string($dbc,$_POST['mpr']);  
        $assists = mysqli_real_escape_string($dbc,$_POST['assists']);  
        $wins = mysqli_real_escape_string($dbc,$_POST['wins']);  
        $games = mysqli_real_escape_string($dbc,$_POST['games']);  
        $hat = mysqli_real_escape_string($dbc,$_POST['hat']);  
        $mr5 = mysqli_real_escape_string($dbc,$_POST['5mr']);  
        $mr6 = mysqli_real_escape_string($dbc,$_POST['6mr']);  
        $mr7 = mysqli_real_escape_string($dbc,$_POST['7mr']);  
        $mr8 = mysqli_real_escape_string($dbc,$_POST['8mr']);  
        $mr9 = mysqli_real_escape_string($dbc,$_POST['9mr']);  
        $white_horse = mysqli_real_escape_string($dbc,$_POST['white_horse']);  
         
        $query="SELECT * FROM stats WHERE (week LIKE '%$title%') 
        OR (mpr LIKE '%$mpr%') 
        OR (assists LIKE '%$assists%') 
        OR (wins LIKE '%$wins%') 
        OR (games LIKE '%$games%') 
        OR (hat LIKE '%$hat%') 
        OR (5mr LIKE '%$mr5%') 
        OR (6mr LIKE '%$mr6%') 
        OR (7mr LIKE '%$mr7%') 
        OR (8mr LIKE '%$mr8%') 
        OR (9mr LIKE '%$mr9%') 
        OR (white_horse LIKE '%$white_horse%') 
        WHERE user_id = '$user_id'"; 
    }else { 
        $query="SELECT * FROM stats WHERE user_id = '$user_id'"; 
    } 
    $result = @mysqli_query($dbc,$query); 
    $num = mysqli_num_rows($result); 
    if ($num > 0) { // If it ran OK, display all the records. 
        echo "<p><b>Your search returns $num entries.</b></p>"; 
        echo "<table cellpadding=5 cellspacing=5 border=1><tr> 
        <th>Week</th><th>MPR</th><th>Assists</th><th>Wins</th><th>Games</th><th>Hat</th><th>5mr</th><th>6mr</th><th>7mr</th><th>8mr</th><th>9mr</th><th>White Horse</th> <th>*</th><th>*</th></tr>";  
        while ($row = mysqli_fetch_array($result,  MYSQLI_ASSOC)) { 
            echo "<tr><td>".$row['week']."</td>";  
            echo "<td>".$row['mpr']."</td>";  
            echo "<td>".$row['assists']."</td>";  
            echo "<td>".$row['wins']."</td>";  
            echo "<td>".$row['games']."</td>";  
            echo "<td>".$row['hat']."</td>";  
            echo "<td>".$row['5mr']."</td>";  
            echo "<td>".$row['6mr']."</td>";  
            echo "<td>".$row['7mr']."</td>";  
            echo "<td>".$row['8mr']."</td>";  
            echo "<td>".$row['9mr']."</td>";  
            echo "<td>".$row['white_horse']."</td>";  
            echo "<td><a href=deleteconfirm.php?id=".$row['id'].">Delete</a></td>";  
            echo "<td><a href=updateform.php?id=".$row['id'].">Update</a></td></tr>";  
        } // End of While statement 
        echo "</table>";  
        ((mysqli_free_result($result) || (is_object($result) && (get_class($result) == "mysqli_result"))) ? true : false); // Free up the resources.          
    } else { // If it did not run OK. 
        echo '<p>Your search hits no result.</p>'; 
        echo $id; 
    } 
    mysqli_close($dbc); // Close the database connection. 
    echo ("</center></html>");  
    //include the footer 
    include ("../includes/footer.php"); 
} 

?>