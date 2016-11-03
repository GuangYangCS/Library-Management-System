<?php
echo '<link href="library.css" rel="stylesheet">';
$servername = "localhost";
$username   = "root";
$password   = "root";
$dbname     = "library";

$conn = mysqli_connect($servername, $username, $password, $dbname);

date_default_timezone_set('America/Chicago');
$Today = date("Y-m-d");

echo "Today is " . $Today . "<br>";

$sql_traverse = "SELECT Book_id, Branch_id,Card_no, Date_out, Due_date, Date_in, Loan_id 
	FROM BOOK_LOANS WHERE (Date_in = '0000-00-00' AND Due_date < curdate()) OR (Date_in <> '0000-00-00' AND Date_in > Due_date)";

$result_loans = mysqli_query($conn, $sql_traverse);

if (mysqli_num_rows($result_loans) > 0) {

echo "<table style='width:100%'><tr><th>Date Out</th><th>Due Date</th><th>Loan ID</th></tr>";

    while ($row = mysqli_fetch_assoc($result_loans)) {
        $in = $row["Date_in"];
        $out = $row["Date_out"];
        $due = $row["Due_date"];
        $lid = $row["Loan_id"];
        
        //echo "<br>";
        //echo "Date out is : " . "$out <br>";
        //echo "Due date is : " . "$due <br>";
        //echo "Loan id is : " . "$lid <br>";
        //echo "<br>";
        //echo "Date in is :".$in."<br>";
        echo "<tr><td>".$out."</td><td>".$due."</td><td>".$lid."</td></tr>";
        
        // initialize the amount of the $
        $amt = 0;
        
        if ($row["Date_in"] == '0000-00-00') {
            $today1 = date_create($Today);
            $out1   = date_create($out);
            $due1   = date_create($due);
            $sub1   = date_diff($today1, $due1);
            $days1  = $sub1->format("%a");
            $amt    = $days1 * 0.25;
        } else {
            $today1 = date_create($Today);
            $in2    = date_create($in);
            $out2   = date_create($out);
            $due2   = date_create($due);
            $sub2   = date_diff($in2, $due2);
            $days2  = $sub2->format("%a");
            $amt    = $days2 * 0.25;
        }
        
        //echo "The amount of money is : ".$amt;
        
        
        $sql4 = "SELECT * FROM FINES WHERE Loan_id =$lid";
        $result_fines = mysqli_query($conn, $sql4);
        
        //echo "Flag3 <br>";
        
        
        if (mysqli_num_rows($result_fines) > 0) {
            
            //echo "Flag4 <br>";
            $row2 = mysqli_fetch_assoc($result_fines);
            // Have not paid the money
            
            $paid = $row2["paid"];
            
            // echo "$paid";
            
            if ($paid == 0) {
                //echo "Flag5 <br>";
                $sql5 = "UPDATE FINES SET fine_amt=$amt WHERE Loan_id=$lid";
                mysqli_query($conn, $sql5);
            }
            
        } else {
            //echo "Flag 5 no exist in FINES";
            $sql6 = "INSERT INTO FINES VALUES('$lid',$amt,0)";
            mysqli_query($conn, $sql6);
        }
    }   echo "</table>";
} else {
    echo "No borrower is currently under Fine state!";
}

echo " <input type='button' onclick=returnHome() name='NA' value='Return Homepage' /><br>	
		<script>
    	function returnHome(){
    	window.location.href='http://localhost:8888/library.html';
		}
		</script>";
mysqli_close($conn);
?>

