<?php
// Create/resets db
$pass = $_REQUEST['pass'];

if( strcmp($pass, "thisisaverysecretpassword12343") == 0){

// Create connection
$con=mysqli_connect("rimforsafk.se.mysql","rimforsafk_se",INSERT_PASSWORD_HERE,"rimforsafk_se");

// Check connection
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
else{
	$sql = "CREATE TABLE Competition 
	(
	PID INT NOT NULL AUTO_INCREMENT, 
	PRIMARY KEY(PID),
	name CHAR(50),
	description CHAR(255),
	time CHAR(25)
	)";
		
		
	if (mysqli_query($con,$sql)) {
	} else {
  echo "Error creating database: " . mysqli_error($con);
	  
	  $delete = "DROP TABLE Competition";
	  mysqli_query($con,$delete);
	  mysqli_query($con,$sql);
	}
	
	$sql = "CREATE TABLE Guestbook 
	(
	PID INT NOT NULL AUTO_INCREMENT, 
	PRIMARY KEY(PID),
	name CHAR(50),
	description CHAR(255),
	time CHAR(25)
	)";
		
	if (mysqli_query($con,$sql)) {
	} else {
	  $delete = "DROP TABLE Guestbook";
	  mysqli_query($con,$delete);
	  mysqli_query($con,$sql);
	}
	/*$sql = 'INSERT INTO Guestbook VALUES (NULL, "Kalle", "Gösfiske!", "2014-09-17 18:00")';	
	
	if (mysqli_query($con,$sql)) {
	} else {
  echo "Error creating table: " . mysqli_error($con);
	}
	
	$result = mysqli_query($con,"SELECT * FROM Guestbook");

		
	
	while($row = mysqli_fetch_array($result)) {
	  echo utf8_decode($row['name'] . " " . $row['description']);
	  echo "<br>";
	}*/
}

}
?>