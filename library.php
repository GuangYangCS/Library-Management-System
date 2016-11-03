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

$BookID_user = $_POST['Book_id'];
$Title_user  = $_POST['Title'];
$Author_user = $_POST['Author'];

$BookID = trim($BookID_user);
$Title  = trim($Title_user);
$Author = trim($Author_user);

$conn = mysqli_connect($servername, $username, $password, $dbname);



/* Case 1: No value is choosen */
if ((!isset($BookID) || $BookID == '') && (!isset($Title) || $Title == '') && (!isset($Author) || $Author == '')) {
    //echo "Flag case1 " ;
    echo "You have to type at least one input. ";
}

/* Case 2: Only Author is choosen */
else if ((!isset($BookID) || $BookID == '') && (!isset($Title) || $Title == '') && (isset($Author))) {
    //echo "Flag case2 " ;
    $sql = "SELECT BOOK.Book_id,BOOK.Title,BOOK_AUTHORS.Author_name,BOOK_COPIES.Branch_id,BOOK_COPIES.No_of_copies
	        FROM BOOK,BOOK_AUTHORS,BOOK_COPIES 
	        WHERE BOOK.book_id=BOOK_AUTHORS.book_id AND BOOK.Book_id=BOOK_COPIES.Book_id AND
	        (BOOK_AUTHORS.Author_name LIKE '%$Author%')";
}
/* Case 3: Only Title is choosen */
else if ((!isset($BookID) || $BookID == '') && (isset($Title)) && (!isset($Author) || $Author == '')) {
    //echo "Flag case3 " ;  
    $sql = "SELECT BOOK.Book_id,BOOK.Title,BOOK_AUTHORS.Author_name,BOOK_COPIES.Branch_id,BOOK_COPIES.No_of_copies
	        FROM BOOK,BOOK_AUTHORS,BOOK_COPIES 
	        WHERE BOOK.book_id=BOOK_AUTHORS.book_id AND BOOK.Book_id=BOOK_COPIES.Book_id AND
	        (BOOK.Title LIKE '%$Title%')";
}
/* Case 4: Only BookID is choosen */
else if ((isset($BookID)) && (!isset($Title) || $Title == '') && (!isset($Author) || $Author == '')) {
    //echo "Flag case4 " ;
    $sql = "SELECT BOOK.Book_id,BOOK.Title,BOOK_AUTHORS.Author_name,BOOK_COPIES.Branch_id,BOOK_COPIES.No_of_copies
	        FROM BOOK,BOOK_AUTHORS,BOOK_COPIES 
	        WHERE BOOK.book_id=BOOK_AUTHORS.book_id AND BOOK.Book_id=BOOK_COPIES.Book_id AND
	        (BOOK.Book_id LIKE '%$BookID%')";
}
/* Case 5: BookID and Title are choosen */
else if ((isset($BookID)) && (isset($Title)) && (!isset($Author) || $Author == '')) {
    //echo "Flag case5 " ;
    $sql = "SELECT BOOK.Book_id,BOOK.Title,BOOK_AUTHORS.Author_name,BOOK_COPIES.Branch_id,BOOK_COPIES.No_of_copies
	        FROM BOOK,BOOK_AUTHORS,BOOK_COPIES 
	        WHERE BOOK.book_id=BOOK_AUTHORS.book_id AND BOOK.Book_id=BOOK_COPIES.Book_id AND
	        (BOOK.Book_id LIKE '%$BookID%' AND BOOK.Title LIKE'%$Title%')";
}
/* Case 6: BookID and Author are choosen */
else if ((isset($BookID)) && (!isset($Title) || $Title == '') && (isset($Author))) {
    //echo "Flag case6 " ;
    $sql = "SELECT BOOK.Book_id,BOOK.Title,BOOK_AUTHORS.Author_name,BOOK_COPIES.Branch_id,BOOK_COPIES.No_of_copies
	        FROM BOOK,BOOK_AUTHORS,BOOK_COPIES 
	        WHERE BOOK.book_id=BOOK_AUTHORS.book_id AND BOOK.Book_id=BOOK_COPIES.Book_id AND
	        (BOOK.Book_id LIKE '%$BookID%' AND BOOK_AUTHORS.Author_name LIKE'%$Author%')";
}
/* Case 7: Title and Author are choosen */
else if ((!isset($BookID) || $BookID == '') && (isset($Title)) && (isset($Author))) {
    //echo "Flag case7 " ;
    $sql = "SELECT BOOK.Book_id,BOOK.Title,BOOK_AUTHORS.Author_name,BOOK_COPIES.Branch_id,BOOK_COPIES.No_of_copies
	        FROM BOOK,BOOK_AUTHORS,BOOK_COPIES 
	        WHERE BOOK.book_id=BOOK_AUTHORS.book_id AND BOOK.Book_id=BOOK_COPIES.Book_id AND
	        (BOOK.Book_id LIKE '%$BookID%' AND BOOK.Title LIKE'%$Title%' AND BOOK_AUTHORS.Author_name LIKE'%$Author%')";
}

/* Case 8: BookID,Title and Author are all choosen */
else if ((isset($BookID)) && (isset($Title)) && (isset($Author))) {
    //echo "Flag case8 " ;
    $sql = "SELECT BOOK.Book_id,BOOK.Title,BOOK_AUTHORS.Author_name,BOOK_COPIES.Branch_id,BOOK_COPIES.No_of_copies
	        FROM BOOK,BOOK_AUTHORS,BOOK_COPIES 
	        WHERE BOOK.book_id=BOOK_AUTHORS.book_id AND BOOK.Book_id=BOOK_COPIES.Book_id AND
	        (BOOK.Book_id LIKE '%$BookID%' AND BOOK.Title LIKE'%$Title%' AND BOOK_AUTHORS.Author_name LIKE'%$Author%')";
}

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    echo "<table style='width:100%'><tr><th>Book ID</th><th>Title</th><th>Author Name</th><th>Branch Id</th><th>Number of Copies</th><th>Avaliable Copies</th><th>Check Out</th></tr>";

    while ($row = mysqli_fetch_assoc($result)) {
        // Select avaliable copies at each branch
        $Bookid_v1   = $row["Book_id"];
        $Branchid_v1 = $row["Branch_id"];
        
        $sql2 = "SELECT COUNT(*) AS counter 
    	FROM BOOK_LOANS 
    	WHERE BOOK_LOANS.Book_id =$Bookid_v1 AND BOOK_LOANS.Branch_id = $Branchid_v1 AND BOOK_LOANS.Date_in ='0000-00-00'";
        
        $result2 = mysqli_query($conn, $sql2);
        $counter1 = 0;        
        
        if (mysqli_num_rows($result2) > 0) {
            $row2     = mysqli_fetch_assoc($result2);
            $counter1 = $row2["counter"];
        }
        
        //echo "borrowed copy is : "."$counter1";
        $counter2 = $row["No_of_copies"] - $counter1;
        
        //echo "avaliable to borrow copy is : ".$counter2;
        
		//echo "The book id is : " . $row["Book_id"] . "." . " Title is: " . $row["Title"] . "." . " Author name is : " . $row["Author_name"] . "." . " Number of branch_id is : " . $row["Branch_id"] . "." . " <br> Number of copies are : " . $row["No_of_copies"] . " and avaliable number of copies are : " . $counter2;
		
        if ($counter2 == 0) {
        echo "<tr><td>".$row['Book_id']."</td><td>".$row['Title']."</td><td>".$row['Author_name']."</td><td>".$row['Branch_id']."</td><td>".$row['No_of_copies']."</td><td>".$counter2."</td><td><input type='submit' style='display:inline' onclick=printError() name='NA' value='N/A' /><br>	
		<script>function printError()
		{confirm('Copy not available!');
		}
		</script></td></tr>";
        } else {
        echo "<tr><td>".$row['Book_id']."</td><td>".$row['Title']."</td><td>".$row['Author_name']."</td><td>".$row['Branch_id']."</td><td>".$row['No_of_copies']."</td><td>".$counter2."</td><td>
		<form style='display:inline' action='library_checkout.php' method='post'> 
		<input type='submit' name='checkOutButton' value='Check Out' />
		<input type='hidden' name='Bookid_n1' value='$Bookid_v1' />
		<input type='hidden' name='Branchid_n1' value='$Branchid_v1' />
		<input type='hidden' name='AvaliableCopy' value='$counter2' />  
		</form></td></tr>";
        }
        
    }   echo "</table>";
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

    
