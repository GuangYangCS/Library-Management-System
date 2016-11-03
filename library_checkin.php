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

$Book_id2      = $_POST['Book_id2'];
$Card_no2      = $_POST['Card_no2'];
$Student_name2 = $_POST['Student_name2'];

$Book_id2      = trim($Book_id2);
$Card_no2      = trim($Card_no2);
$Student_name2 = trim($Student_name2);

$conn = mysqli_connect($servername, $username, $password, $dbname);

//echo "$Book_id2";
//echo "$Card_no2";
//echo "$Student_name2";

/* Case 1: No value is choosen */
if ((!isset($Book_id2) || $Book_id2 == '') && (!isset($Card_no2) || $Card_no2 == '') && (!isset($Student_name2) || $Student_name2 == '')) {
    echo "Flag case1 ";
    echo "You have to type at least one input. ";
}

/* Case 2: Only Name is choosen */
else if ((!isset($Book_id2) || $Book_id2 == '') && (!isset($Card_no2) || $Card_no2 == '') && (isset($Student_name2))) {
    //echo "Flag case2 " ;
    $sql_checkin = "SELECT B.Book_id, B.Branch_id, B.Card_no, B.Date_out, B.Due_date,B.Date_in
	        FROM BOOK_LOANS AS B, BORROWER AS BO
	        WHERE B.Card_no = BO.Card_no AND
	        ((BO.Fname LIKE '%$Student_name2%') OR (BO.Lname LIKE '%$Student_name2%'))";
}

/* Case 3: Only Card_no2 is choosen */
else if ((!isset($Book_id2) || $Book_id2 == '') && (isset($Card_no2)) && (!isset($Student_name2) || $Student_name2 == '')) {
    //echo "Flag case3 " ;  
    $sql_checkin = "SELECT B.Book_id, B.Branch_id, B.Card_no, B.Date_out, B.Due_date,B.Date_in
	        FROM BOOK_LOANS AS B, BORROWER AS BO
	        WHERE B.Card_no = BO.Card_no AND
	        ( B.Card_no = '$Card_no2')";
}

/* Case 4: Only BookID is choosen */
else if ((isset($Book_id2)) && (!isset($Card_no2) || $Card_no2 == '') && (!isset($Student_name2) || $Student_name2 == '')) {
    //echo "Flag case4 " ;
    $sql_checkin = "SELECT B.Book_id, B.Branch_id, B.Card_no, B.Date_out, B.Due_date,B.Date_in
	        FROM BOOK_LOANS AS B, BORROWER AS BO
	        WHERE B.Card_no = BO.Card_no AND
	        ( B.Book_id = '$Book_id2')";
}

/* Case 5: BookID and Card_no2 are choosen */
else if ((isset($Book_id2)) && (isset($Card_no2)) && (!isset($Student_name2) || $Student_name2 == '')) {
    //echo "Flag case5 " ;
    $sql_checkin = "SELECT B.Book_id, B.Branch_id, B.Card_no, B.Date_out, B.Due_date,B.Date_in
	        FROM BOOK_LOANS AS B, BORROWER AS BO
	        WHERE B.Card_no = BO.Card_no AND
	        ( (B.Book_id = '$Book_id2') AND (B.Card_no = '$Card_no2'))";
}

/* Case 6: BookID and Name are choosen */
else if ((isset($Book_id2)) && (isset($Student_name2)) && (!isset($Card_no2) || $Card_no2 == '')) {
    //echo "Flag case5 " ;
    $sql_checkin = "SELECT B.Book_id, B.Branch_id, B.Card_no, B.Date_out, B.Due_date,B.Date_in
	        FROM BOOK_LOANS AS B, BORROWER AS BO
	        WHERE B.Card_no = BO.Card_no AND
	        ( (B.Book_id = '$Book_id2') AND ((BO.Fname LIKE '%$Student_name2%') OR (BO.Lname LIKE '%$Student_name2%')))";
}

/* Case 7: Card_no2 and Card_no2 are choosen */
else if ((isset($Card_no2)) && (isset($Student_name2)) && (!isset($Book_id2) || $Book_id2 == '')) {
    //echo "Flag case5 " ;
    $sql_checkin = "SELECT B.Book_id, B.Branch_id, B.Card_no, B.Date_out, B.Due_date,B.Date_in
	        FROM BOOK_LOANS AS B, BORROWER AS BO
	        WHERE B.Card_no = BO.Card_no AND
	        ( (B.Card_no = '$Card_no2') AND ((BO.Fname LIKE '%$Student_name2%') OR (BO.Lname LIKE '%$Student_name2%')))";
}

/* Case 8: BookID,Card_no2 and Card_no2 are all choosen */
else if ((isset($Book_id2)) && (isset($Card_no2)) && (isset($Student_name2))) {
    //echo "Flag case8 " ;
    $sql_checkin = "SELECT B.Book_id, B.Branch_id, B.Card_no, B.Date_out, B.Due_date,B.Date_in
	        FROM BOOK_LOANS AS B, BORROWER AS BO
	        WHERE B.Card_no = BO.Card_no AND
	        ( (B.Card_no = '$Card_no2') AND (B.Book_id = '$Book_id2') AND ((BO.Fname LIKE '%$Student_name2%') OR (BO.Lname LIKE '%$Student_name2%')))";
}


$result2 = mysqli_query($conn, $sql_checkin);


if (mysqli_num_rows($result2) > 0) {
    echo "<table style='width:100%'><tr><th>Book ID</th><th>Date Out</th><th>Due Date</th><th>Date In</th><th>Branch ID</th><th>Card Number</th><th>Check In Confirm</th></tr>";

    while ($row = mysqli_fetch_assoc($result2)) {
        // Select avaliable copies at each branch
        $book   = $row["Book_id"];
        $branch = $row["Branch_id"];
        $card   = $row["Card_no"];
        $out    = $row["Date_out"];
        $due    = $row["Due_date"];
        $in     = $row["Date_in"];
        
        //echo "Book ID is : " . $book . ", and Branch ID is : " . $branch . ", and Card Number is : " . $card . ", and Date Out is : " . $out . ", and Due Date is : " . $due . " and Date In is : " . $in;
        
        
        if ($in=='0000-00-00'){
                echo "<tr><td>".$book."</td><td>".$out."</td><td>".$due."</td><td>".$in."</td><td>".$branch."</td><td>".$card."</td><td><form style='display:inline' action='library_finalCheckin.php' method='post'>
		Year<input type='text' size='4' name='Year'/>
		Month<input type='text' size='2'  name='Month'/>
		Day<input type='text' size='2' name='Day' /> 
		<input type='submit' name='checkInButton' value='Check In' />
		<input type='hidden' name='B_id' value='$book' />
		<input type='hidden' name='Br_id' value='$branch' />
		<input type='hidden' name='C_no' value='$card' />
		<input type='hidden' name='D_out' value='$out' />
		<input type='hidden' name='Due_date' value='$due' />
		<input type='hidden' name='D_in' value='$in' />   
		</form></td></tr>";

        }else{
        echo "<tr><td>".$book."</td><td>".$out."</td><td>".$due."</td><td>".$in."</td><td>".$branch."</td><td>".$card."</td><td><input type='submit' name='checkInButton' value='N/A' /></td></tr>";
        
        }
        
        
    } echo "</table>";
} else {
    echo "<br>" . "Search result: 0 result." . "<br>";
    echo "There is no book in the database that match your search!";
}

echo " <input type='button' onclick=returnHome() name='NA' value='Return Homepage' /><br>	
		<script>
    	function returnHome(){
    	window.location.href='http://localhost:8888/library.html';
		}
		</script>";

mysqli_close($conn);
?>

    
