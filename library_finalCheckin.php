<?php
echo '<link href="library.css" rel="stylesheet">';
$servername = "localhost";
$username   = "root";
$password   = "root";
$dbname     = "library";

$conn = mysqli_connect($servername, $username, $password, $dbname);



$B_id     = $_POST['B_id'];
$Br_id    = $_POST['Br_id'];
$C_no     = $_POST['C_no'];
$D_out    = $_POST['D_out'];
$Due_date = $_POST['Due_date'];
$D_in     = $_POST['D_in'];
$Year     = $_POST['Year'];
$Month    = $_POST['Month'];
$Day      = $_POST['Day'];


$date          = "$Year" . "-" . "$Month" . "-" . "$Day";
$to_mysql_date = date('Y-m-d', strtotime($date));


$sql_finalCheckIn = "UPDATE BOOK_LOANS SET Date_in='$to_mysql_date' 
	WHERE Book_id='$B_id' AND Branch_id ='$Br_id' AND Card_no ='$C_no' AND Date_out='$D_out' AND Due_date='$Due_date' AND Date_in ='0000-00-00' ";

//echo "Book_id : " . "$B_id <br>";
//echo "Branch_id : " . "$Br_id <br>";
//echo "Card_no : " . "$C_no <br>";
//echo "Date_out : " . "$D_out <br>";
//echo "Due_date : " . "$Due_date <br>";
//echo "Date_in : " . "$D_in <br>";
//echo "Year : " . "$Year <br>";
//echo "Month : " . "$Month <br>";
//echo "Day : " . "$Day <br>";
//echo "Date : " . "$to_mysql_date <br>";

echo "Congratulations! You have successfully returned the book !";

echo " <input type='button' onclick=returnHome() name='NA' value='Return Homepage' /><br>	
			<script>
    		function returnHome(){
    		window.location.href='http://localhost:8888/library.html';
			}
			</script>";

mysqli_query($conn, $sql_finalCheckIn);
mysqli_close($conn);
?>

