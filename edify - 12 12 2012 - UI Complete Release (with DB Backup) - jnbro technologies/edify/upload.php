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
<title>Upload Files</title>

<link href="jstyle.css" media="screen" rel="stylesheet" type="text/css" />

       

</head>

<body>


 <?php
           	$username = $_SESSION['username'];
			$password = $_SESSION['password'];

			$designation = $_SESSION['designation'];

			
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
				<p class="title">Upload Files
			
			  
				</p>
	  
	</div>
<?php

echo vmenu($designation);
?>
<div id="pagecontent">
  <?php
								if($designation == 'admin') {
									echo '<ul>';
									echo '<li><a href="uploadDocuments.php">Upload Documents</a></li>';
									echo '<li><a href="uploadHistory.php">Upload History</a></li>';
									echo '</ul><br><br><br>';
								}
							?>
							<form enctype="multipart/form-data" method="post" action="uploader.php">
								<input type="hidden" name="MAX_FILE_SIZE" value="10485760000" />
								<table >
									<tr>
										<td width="100%" colspan=2><b>Choose a file to upload (max 10MB) :</b></td>
									</tr>
									<?php
										if($_GET) {
											$j = $_GET['no'];
											for($i = 0; $i <= $j; $i++){
												echo '<tr><td width="20%">&nbsp;</td><td width="80%"><input size=50 type="file" name="uploadedfile[]"></td></tr>';
											}
											$j++;
											echo "<tr><td width='20%'>&nbsp;</td><td class='td2_mailbox' width='80%'><a href='upload.php?no=$j'>Attach another file >>> </a></td></tr>";
										} else {
											echo '<tr><td width="20%">&nbsp;</td><td width="80%"><input size=50 type="file" name="uploadedfile[]"></td></tr>';
											echo "<tr><td width='20%'>&nbsp;</td><td class='td2_mailbox' width='80%'><a href='upload.php?no=1'>Attach another file >>> </a></td></tr>";
										}
									?>
								</table><br>
								<input type="submit" value="Upload File" id="z">
							</form>
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
