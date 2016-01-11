<?php
	
	$num_of_posts = intval($_REQUEST['count']);
	
	$con=mysqli_connect("rimforsafk.se.mysql","rimforsafk_se",INSERT_PASSWORD_HERE,"rimforsafk_se");

	$sql = "SELECT * FROM  Competition WHERE time > '" . date("Y-m-d") . "' ORDER BY time ASC LIMIT 0 , $num_of_posts";
		
	$result = mysqli_query($con,$sql);

		
	
	while($row = mysqli_fetch_array($result)) {
		echo "<div class='gb-post'><h4>" . $row['name'] . "</h4>";
		echo $row['description'];
		echo "<br><i>" . $row['time'] . "</i><hr></div>";
	}
	
?>