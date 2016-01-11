<?php
	
	$start = intval($_REQUEST['start']);
	$num_of_posts = intval($_REQUEST['count']);
	
	$con=mysqli_connect("rimforsafk.se.mysql","rimforsafk_se",INSERT_PASSWORD_HERE,"rimforsafk_se");

  $start = ( $start - 1 ) * 20;  
  
	$sql = "SELECT * FROM  Guestbook ORDER BY ID DESC LIMIT $start , $num_of_posts";
		
	$result = mysqli_query($con,$sql);

		
	
	while($row = mysqli_fetch_array($result)) {
		echo "<div class='gb-post'><h4>" . utf8_decode($row['name']) . " skrev</h4>";
		echo utf8_decode($row['description']);
		echo "<br><i>" . utf8_decode($row['time']) . "</i><hr></div>";
	}
	
?>