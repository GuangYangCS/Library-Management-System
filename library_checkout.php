<?php
echo '<link href="library.css" rel="stylesheet">';
$servername = "localhost";
$username   = "root";
$password   = "root";
$dbname     = "library";

$conn = mysqli_connect($servername, $username, $password, $dbname);

$Bookid_v2     = $_POST['Bookid_n1'];
$Branchid_v2   = $_POST['Branchid_n1'];
$AvaliableCopy = $_POST['AvaliableCopy'];


echo "Book id is " . "$Bookid_v2" . " , branch id is " . "$Branchid_v2" . " and avaliable copy is " . "$AvaliableCopy" . ".";


echo "<form style='display:inline' action='library_finalCheckout.php' method='post'> 
		Card Number: <input type='text' name='CardNumber'>
		<input type='submit' name='finalCheckOutButton' value='Confirm Check Out' />
		<input type='hidden' name='Bookid_n2' value='$Bookid_v2' />
		<input type='hidden' name='Branchid_n2' value='$Branchid_v2' />
		<input type='hidden' name='AvaliableCopy2' value='$AvaliableCopy' /> 		
		</form>";

echo " <input type='button' onclick=returnHome() name='NA' value='Return Homepage' /><br>	
		<script>
    	function returnHome(){
    	window.location.href='http://localhost:8888/library.html';
		}
		</script>";

mysqli_close($conn);
?>

