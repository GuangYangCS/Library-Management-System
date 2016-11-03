<?php
echo '<link href="library.css" rel="stylesheet">';
$servername = "localhost";
$username   = "root";
$password   = "root";
$dbname     = "library";

$conn = mysqli_connect($servername, $username, $password, $dbname);

$Card_no4 = $_POST['Card_no4'];

$sql_setPaid = "UPDATE BOOK_LOANS AS B, FINES AS F
					SET paid= 1
					WHERE B.Loan_id = F.Loan_id AND B.Card_no = $Card_no4;";

$result_setPaid = mysqli_query($conn, $sql_setPaid);
echo "Payment success!";
echo " <input type='button' onclick=returnHome() name='NA' value='Return Homepage' /><br>	
		<script>
    	function returnHome(){
    	window.location.href='http://localhost:8888/library.html';
		}
		</script>";

mysqli_query($conn, $sql_finalCheckOut);
mysqli_close($conn);
?>

