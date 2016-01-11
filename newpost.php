<?php

    date_default_timezone_set('Europe/Stockholm');
	$datetime = date("Y-m-d h:i", time());	
	
	$gbname = utf8_encode($_REQUEST['gbname']);
	$gbmessage =  utf8_encode($_REQUEST['gbmessage']);
	
	$con=mysqli_connect("rimforsafk.se.mysql","rimforsafk_se",INSERT_PASSWORD_HERE,"rimforsafk_se");

	
	$sql = "INSERT INTO Guestbook VALUES (NULL, '" . $gbname . "', '" . $gbmessage . "', '" . $datetime. "')";	
		
		
	if(mysqli_query($con,$sql)){
		header("Location: http://rimforsafk.se/new/gastbok");		
	}
	else{
		echo mysql_errno($con) . ": " . mysql_error($con) . "\n";
	}
	
?>