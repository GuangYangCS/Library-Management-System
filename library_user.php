<?php
echo '<link href="library.css" rel="stylesheet">';
$servername = "localhost";
$username   = "root";
$password   = "root";
$dbname     = "library";

$conn = mysqli_connect($servername, $username, $password, $dbname);

$fname_user   = $_POST['fname'];
$lname_user   = $_POST['lname'];
$address_user = $_POST['address'];
$phone_user   = $_POST['phone'];
// To trim the user's input value
$fname        = trim($fname_user);
$lname        = trim($lname_user);
$address      = trim($address_user);
$phone        = trim($phone_user);

// First check if the new user's info exists in the database
$sql     = "SELECT fname FROM BORROWER WHERE (fname='$fname' AND lname='$lname' AND Address='$address')";
$result1 = mysqli_query($conn, $sql);


if (mysqli_num_rows($result1) > 0) {
    // error message!		
    
    //include('library.html');
    
    
    echo ("<script LANGUAGE='JavaScript'>
   	 	window.alert('You are already a member!');
    	window.location.href='http://localhost:8888/library.html';
    	</script>");
    
    
} else {
    if ((!isset($fname) || $fname == '') OR (!isset($lname) || $lname == '') OR (!isset($address) || $address == '')) {
        // echo "case 2";
        echo "All name and address are required!";
    } else {
        // echo "case 3";
        $sql_borrower = "INSERT INTO BORROWER (Card_no, Fname, Lname, Address,Phone)
                   VALUES (NULL,'$fname','$lname','$address','$phone');";
        echo "New borrower is created !";
        
    }
}

$result2 = mysqli_query($conn, $sql_borrower);

echo " <input type='button' onclick=returnHome() name='NA' value='Return Homepage' /><br>	
		<script>
    	function returnHome(){
    	window.location.href='http://localhost:8888/library.html';
		}
		</script>";

mysqli_close($conn);
?>

