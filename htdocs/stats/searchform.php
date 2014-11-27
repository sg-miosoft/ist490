<?php 
//check session first
if(!isset($_SESSION['email']))
{
	header("Location: https://uwm-iptracker.miosoft.com/home/login.php"); 
}
else
{
	//include the header
	include ("../includes/header.php");
?>

	<table>
	<form action="search.php" method="post">
	<tr>
		<td>Week: <input name="week" size=50 value="<?php echo $row['week'];?>"></td>
	</tr>
	<tr>
		<td>MPR: <input name="mpr" size=50 value="<?php echo $row['mpr'];?>"></td>
	</tr>
	<tr>
		<td>Assists: <input name="assists" size=50 value="<?php echo $row['assists'];?>"></td>
	</tr>
	<tr>
		<td>Wins: <input name="wins" size=50 value="<?php echo $row['wins'];?>"></td>
	</tr>
	<tr>
		<td>Games: <input name="games" size=50 value="<?php echo $row['games'];?>"></td>
	</tr>
	<tr>
		<td>Hat: <input name="hat" size=50 value="<?php echo $row['hat'];?>"></td>
	</tr>
	<tr>
		<td>5mr: <input name="5mr" size=50 value="<?php echo $row['5mr'];?>"></td>
	</tr>
	<tr>
		<td>6mr: <input name="6mr" size=50 value="<?php echo $row['6mr'];?>"></td>
	</tr>
	<tr>
		<td>7mr: <input name="7mr" size=50 value="<?php echo $row['7mr'];?>"></td>
	</tr>
	<tr>
		<td>8mr: <input name="8mr" size=50 value="<?php echo $row['8mr'];?>"></td>
	</tr>
	<tr>
		<td>9mr: <input name="9mr" size=50 value="<?php echo $row['9mr'];?>"></td>
	</tr>
	<tr>
		<td>White Horse: <input name="white_horse" size=50 value="<?php echo $row['white_horse'];?>"></td>
	</tr>
	<tr>
		<td><input type=submit value=search>
		<input type=reset value=reset></td>
	</tr>
	<input type=hidden name="id" value="<? echo $row['id'];?>">
	</form>
	</table>

<?php
	//include the footer
	include ("../includes/footer.php");
}
?>