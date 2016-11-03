<?php
echo '<link href="library.css" rel="stylesheet">';
session_start();

$servername = "localhost";
$username   = "root";
$password   = "root";
$dbname     = "library";


if (mysqli_connect_errno()) {
    echo "Failed to connect to MYSQL:" . mysqli_connect_error() . "<br/>";
}
$conn = mysqli_connect($servername, $username, $password, $dbname);

$Card_no3 = $_POST['Card_no3'];

$sql_pay = "SELECT Date_in,fine_amt, Book_id,Branch_id
 				FROM BOOK_LOANS , FINES 
 				Where BOOK_LOANS.Loan_id = FINES.Loan_id AND Card_no = $Card_no3 AND paid = 0 ";

$result_pay = mysqli_query($conn, $sql_pay);
$total      = 0;
// Use flag to prevent the user from paying the fines when there is still books not returned after due date
$flag       = 0;
if (mysqli_num_rows($result_pay) > 0) {
    while ($row = mysqli_fetch_assoc($result_pay)) {
        $Book_id   = $row["Book_id"];
        $Branch_id = $row["Branch_id"];
        $in        = $row["Date_in"];
        $amt       = $row["fine_amt"];
        
        if ($in != '0000-00-00') {
            
            $total = $total + $amt;
            
            echo "Book ID is : " . $Book_id . ", Branch ID is : " . $Branch_id . ", Date in is : " . $in . ", and fine amount is : " . $amt . "<br>";
        } else {
            $flag = 1;
            echo "Book ID is : " . $Book_id . ", Branch ID is : " . $Branch_id . " ,This book has not been returned and estimate fine amount is : " . $amt . "<br>";
            
        }
        
        
    }
    if ($flag == 1) {
        echo "<script LANGUAGE='JavaScript'>
   	 			window.alert('You must check in your borrowed book to pay the fines!');
    			window.location.href='http://localhost:8888/library.html';
    			</script>";
    }
    
    
} else {
    echo "Search result: 0 result." . "<br>";
    echo "You have no Fines currently! ";
}
echo "Total fine amount is : $" . $total;
;
echo "<form style='display:inline' action='library_finalPay.php' method='post'> 
		<input type='submit' name='finalPayFinesButton' value='Confirm Payment' />
		<input type='hidden' name='Card_no4' value='$Card_no3' />		
		</form>";


echo " <input type='button' onclick=returnHome() name='NA' value='Return Homepage' /><br>	
		<script>
    	function returnHome(){
    	window.location.href='http://localhost:8888/library.html';
		}
		</script>";


//	echo "We have receive your payment, and current balance is 0. <br>";
mysqli_close($conn);
?>

    
