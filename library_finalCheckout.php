<?php
echo '<link href="library.css" rel="stylesheet">';
$servername = "localhost";
$username   = "root";
$password   = "root";
$dbname     = "library";

$conn = mysqli_connect($servername, $username, $password, $dbname);

$CardNumber = $_POST['CardNumber'];
$CardNumber = intval($CardNumber);

$sql_limit = "SELECT COUNT(*) AS MAXLIMIT FROM BOOK_LOANS WHERE Book_LOANS.Card_no =$CardNumber AND Book_LOANS.Date_in='0000-00-00' ";

$result2 = mysqli_query($conn, $sql_limit);

$Max = 0;

if (mysqli_num_rows($result2) > 0) {
    
    $row2 = mysqli_fetch_assoc($result2);
    $Max  = $row2["MAXLIMIT"];
    
    //echo "$Max";
}

if ($Max > 2) {
    echo "You have reached the maximum quantity !";
    
} else {
    
    $Bookid_v3      = $_POST['Bookid_n2'];
    $Branchid_v3    = $_POST['Branchid_n2'];
    $AvaliableCopy2 = $_POST['AvaliableCopy2'];
    $Date_out       = date("Y/m/d/");
    $Due_date       = date("Y/m/d/", strtotime($Date_out . "+13 days"));
    
    
  //  echo "$Bookid_v3 <br>";
  //  echo "$Branchid_v3 <br>";
  //  echo "$CardNumber <br>";
  //  echo "$Date_out <br>";
  //  echo "$Due_date <br>";
  
  echo "Congratulations! You have successfully checked out the book !";
    
    
    $sql_finalCheckOut = "INSERT INTO BOOK_LOANS (Book_id, Branch_id, Card_no, Date_out, Due_date, Date_in, Loan_id) 
	VALUES ('$Bookid_v3', '$Branchid_v3', '$CardNumber', '$Date_out', '$Due_date', '0000-00-00', NULL)";

}

echo " <input type='button' onclick=returnHome() name='NA' value='Return Homepage' /><br>	
		<script>
    	function returnHome(){
    	window.location.href='http://localhost:8888/library.html';
		}
		</script>";

mysqli_query($conn, $sql_finalCheckOut);
mysqli_close($conn);
?>

