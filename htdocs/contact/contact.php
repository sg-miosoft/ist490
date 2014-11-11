<?
session_start();
//check session first
if (!isset($_SESSION['email'])){
	echo "You are not logged in!";
	exit();
}else{

	include ("../includes/header.php");
?>

	<h2>Contact us</h2>
	<p> Spencer George </p>
	<p> Student - Online</p>
	<p> University of Wisconsin - Milwaukee </p>
	<p> Madison, WI 53703 </p>
	<p> 608-210-1182 </p>
	<p> george3@uwm.edu </p>

<?
	include ("../includes/footer.php");
}
?>
