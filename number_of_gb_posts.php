<?php
	
	$num_of_posts = intval($_REQUEST['count']);
	
	$con=mysqli_connect("rimforsafk.se.mysql","rimforsafk_se",INSERT_PASSWORD_HERE,"rimforsafk_se");

	$sql = "SELECT COUNT(*) FROM  Guestbook";
		
	$result = mysqli_query($con,$sql);
  
  while($row = mysqli_fetch_array($result)) {
		echo $row["COUNT(*)"];
    }
?>