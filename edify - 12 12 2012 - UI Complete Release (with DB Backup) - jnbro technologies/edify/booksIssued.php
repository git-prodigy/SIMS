<?php
    include_once "dbconnect.php";
    include_once "myPhpFunctions.php";
    include_once "staticCalendar.php";
	    include_once "jfunctions.php";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Books Issued</title>

<link href="jstyle.css" media="screen" rel="stylesheet" type="text/css" />

       

</head>

<body>


 <?php
           	$username = $_SESSION['username'];
			$password = $_SESSION['password'];

			$designation = $_SESSION['designation'];
$subject=$_POST['subject'];
			$message=$_POST['message'];
			
			$imageFileExten = '.jpg';
			$imageFileName = $username.$imageFileExten;
			
			  function getQuotation1(){
	            $maxQuery = "SELECT max(id) 'MV' FROM quotation";
	            $maxValue = mysql_fetch_array(mysql_query($maxQuery));
	            $randomNum = rand(1,$maxValue['MV']);
	            $quoteQuery = "SELECT * FROM quotation WHERE id='$randomNum'";
	            $quoteResult = mysql_fetch_assoc(mysql_query($quoteQuery));
	            return $quoteResult['quotation'].'  -  <font color="#BB0000"><i>'.$quoteResult['author'].'</i></font>';
	        }

			$imageFileExten = '.jpg';
			$imageFileName = $username.$imageFileExten;
			
			if($username){
			?>
            
           
<div>


 <?php
echo mainBar();
?>

</div>

<div id="main">
<div id="leftsidebar" >
 


</div>
 <?php
echo rightbar();
?>


<div id="middle" align="center">
<div class="pagegrey">
<div id="pageheader" class="headergrey">
				<p class="title">Library
			
			  
				</p>
	  
	</div>
<?php

echo vmenu($designation);
?>
<div id="pagecontent">
  <div>
    <ul>
    <li></li>
      <div>
        <div>
        
							<ul>
								<li><a href="aboutLibrary.php">About Library</a></li>
								<li><a href="bookStoreRetrive.php">Book Store</a></li>
								<li><a href="suggestion.php">Suggestions</a></li>
								<li><a href="booksRequested.php">Books Requested</a></li>
								<li class="current_page_item"><a href="booksIssued.php">Issued Books</a></li>
								<li><a href="booksHistory.php">Issued Books History</a></li>
							</ul><br><br><br>
							<?php
								$booksReturnList = $_POST['book'];
								$arrayLength = count($booksReturnList);
								for($i = 0; $i < $arrayLength; $i++){
									$id = $booksReturnList[$i];
									$originaldatetime = date("Y-m-d H:i:s");
									$time = 19800;
									$creationDate= strtotime($originaldatetime) + $time;
									$returnedOn = date("Y-m-d",$creationDate);
									$updateQuery = "UPDATE library SET availableCopies = availableCopies + 1 WHERE bookId = (SELECT bookId FROM issuedbooks WHERE id = '$id')";
									mysql_query($updateQuery) or die ("Not able to update the entry in database");
									$insertQuery = "INSERT INTO issuedbookshistory (username, bookId, title, authorName, subject, issuedDate, returnDate, returnedOn) SELECT username, bookId, title, authorName, subject, issuedDate, returnDate, '$returnedOn' from issuedbooks WHERE id='$id'";
									mysql_query($insertQuery) or die ("Cannot insert the data into database");
									$deleteQuery = "DELETE FROM issuedbooks WHERE ID='$id'";
									mysql_query($deleteQuery) or die ("Cannot delete the data from database");
									
									$activityDate = date("Y-m-d G:i:s");
									$activityMsg = 'Updated Library Catalog with a book return by a student/teacher';
									$userActivityQuery = "INSERT INTO useractivities (username, creationdate, activity) VALUES ('$username', '$activityDate', '$activityMsg')";
									mysql_query($userActivityQuery);
								}

								if($designation == 'librarian') {
									if($_POST){
										$issuedbooksRows = mysql_num_rows(mysql_query("SELECT bookId, title, authorName, subject, issuedDate, returnDate, username FROM issuedbooks WHERE username LIKE '$studentName'"));
									} else {
										$issuedbooksRows = mysql_num_rows(mysql_query("SELECT bookId, title, authorName, subject, issuedDate, returnDate, username FROM issuedbooks"));
									}
									$noOfRecords = 10;
									$noOfPages = ceil($issuedbooksRows/$noOfRecords);
									if($_GET['pageNo'] > 0) {
										$pageNo = $_GET['pageNo'];
										if($pageNo >= $noOfPages){
											$pageNo = $noOfPages - 1;
										}
										$nextPage = $pageNo + 1;
										$previousPage = $pageNo - 1;
										$startRow = ($pageNo*$noOfRecords);
									} else {
										$pageNo = 0;
										$nextPage = $pageNo + 1;
										$previousPage = 0;
										$startRow = 0;
									}
									if($_POST['search'] == 'Search Student'){
										$studentName = $_POST['s'];
										$studentName=str_replace("*","%",$studentName);
										$issuedbooksQuery = "SELECT id, bookId, title, authorName, subject, issuedDate, returnDate, username FROM issuedbooks WHERE username LIKE '$studentName' LIMIT ";
										$issuedbooksQuery .= $startRow.', '.$noOfRecords;
										$issuedbooksResult = mysql_query($issuedbooksQuery);
									} else {
										$issuedbooksQuery = "SELECT id, bookId, title, authorName, subject, issuedDate, returnDate, username FROM issuedbooks LIMIT ";
										$issuedbooksQuery .= $startRow.', '.$noOfRecords;
										$issuedbooksResult = mysql_query($issuedbooksQuery);
									}
									echo '<form id="searchform" method="post" action="booksIssued.php">&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="s" size="20" value="'.$_POST['s'].'">';
									echo '&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="search" value="Search Student" id="z"></form><br>';
									echo "<div align='right'><a class='pagination' href='booksIssued.php?pageNo=$previousPage'>Previous Page</a>&nbsp;&nbsp;<a class='pagination' href='booksIssued.php?pageNo=$nextPage'>Next Page</a></div><br>";

									if (!$issuedbooksResult) {
										die("query to show fields from table failed");
									} else {
										$fields_num = mysql_num_fields($issuedbooksResult);
										echo '<form method="post" action="booksIssued.php">';

										echo "<table class='maintable'><tr class='headline'><td width='3%' align='center'>&nbsp;</td>";
										echo "<td width='10%' align='center'><b>Book ID</b></td>";
										echo "<td width='28%' align='center'><b>Title</b></td>";
										echo "<td width='20%' align='center'><b>Author Name</b></td>";
										echo "<td width='15%' align='center'><b>Subject</b></td>";
										echo "<td width='14%' align='center'><b>Issued Date - Return Date</b></td>";
										echo "<td width='10%' align='center'><b>Issued To</b></td></tr>";

										if(mysql_num_rows($issuedbooksResult) > 0){
											while($row = mysql_fetch_array($issuedbooksResult)) {
												echo '<tr class="mainrow">';
												echo '<td class="cell1" width="3%" align="center"><input type="checkbox" name="book[]" value="'.$row['id'].'"></td>';
												echo '<td class="cell1" width="10%" align="center"><b>'.$row['bookId'].'</b></td>';
												echo '<td class="cell1" width="28%"><b>'.$row['title'].'</b></td>';
												echo '<td class="cell1" width="20%"><b>'.$row['authorName'].'</b></td>';
												echo '<td class="cell1" width="15%" align="center"><b>'.$row['subject'].'</b></td>';
												echo '<td class="cell1" width="14%" align="center"><b>Issued Date:<br>'.$row['issuedDate'].'<br><br>Return Date: <br>'.$row['returnDate'].'</b></td>';
												echo '<td class="cell1" width="10%" align="center"><b>'.$row['username'].'</b></td></tr>';
											}
										} else {
											echo '<tr class="mainrow"><td colspan=7 align="center" class="cell1"><b>No Book has been issued.</b></td></tr>';
										}
										mysql_free_result($issuedbooksResult);
										echo '</table><br>';
										echo"<input type='submit' name='request' value='Books Returned' id='m'>";
										echo"&nbsp;&nbsp;&nbsp;&nbsp;<input type='reset' name='reset' value='Reset' id='m'></form>";
									}

								} else {
									$issuedbooksResult = mysql_query("SELECT bookId, title, authorName, subject, issuedDate, returnDate FROM issuedbooks where username='$username'");
									if (!$issuedbooksResult) {
										die("query to show fields from table failed");
									} else {
										$fields_num = mysql_num_fields($issuedbooksResult);
									}
									echo "<table class='maintable'><tr class='headline'><td width='10%' align='center'><b>Book ID</b></td>";
									echo "<td width='29%' align='center'><b>Title</b></td>";
									echo "<td width='20%' align='center'><b>Author Name</b></td>";
									echo "<td width='15%' align='center'><b>Subject</b></td>";
									echo "<td width='13%' align='center'><b>Issued Date</b></td>";
									echo "<td width='13%' align='center'><b>Return Date</b></td></tr>";
									if(mysql_num_rows($issuedbooksResult) > 0){
										while($row = mysql_fetch_array($issuedbooksResult)) {   // printing data at the row level
											echo '<tr class="mainrow"><td class="cell1" width="10%" align="center"><b>'.$row['bookId'].'</b></td>';
											echo '<td class="cell1" width="29%"><b>'.$row['title'].'</b></td>';
											echo '<td class="cell1" width="20%"><b>'.$row['authorName'].'</b></td>';
											echo '<td class="cell1" width="15%" align="center"><b>'.$row['subject'].'</b></td>';
											echo '<td class="cell1" width="13%" align="center"><b>'.$row['issuedDate'].'</b></td>';
											echo '<td class="cell1" width="13%" align="center"><b>'.$row['returnDate'].'</b></td></tr>';
										}
									} else {
										echo '<tr class="mainrow"><td colspan=6 align="center" class="cell1"><b>No Book has been issued.</b></td></tr>';
									}
									mysql_free_result($issuedbooksResult);
									echo '</table><br><br>';
								}

							?>
        </div>
      </div>
      
    </ul>
  </div>
</div>
</div>
		</div>
		<?php
			} else {
		?>
		 <?php
						 loginbox();
						 ?>
		<?php
			}
		?>
        
        <div class="quote" align="center">
                <?php
				echo getQuotation1();
				?>
                </div>
                
		    <div class="footer">
            Powered By ChrisTel Info Solutions (P) Ltd.
        </div>
</body>
</html>
