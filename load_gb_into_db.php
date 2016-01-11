<?php

  function file_get_contents_utf8($fn) {
       $content = file_get_contents($fn);
        return mb_convert_encoding($content, 'UTF-8',
            mb_detect_encoding($content, 'UTF-8, ISO-8859-1', true));
  }

  require 'variables.php';
  
  $con=mysqli_connect("rimforsafk.se.mysql","rimforsafk_se",INSERT_PASSWORD_HERE,"rimforsafk_se");
  $sql = "DROP TABLE Guestbook";	
	mysqli_query($con,$sql);
  
  $sql = "CREATE TABLE Guestbook
    (
      ID int AUTO_INCREMENT,
      name varchar(255) NOT NULL ,
      description varchar(255) NOT NULL,
      time varchar(255) NOT NULL,
      PRIMARY KEY (ID)
    )";	
	mysqli_query($con,$sql);
  
  $gb_path = $file_system_path . "/gb/";
  $pattern = "/.*<b>(.*) skrev<\/b><br><br>(.*)/";
  
  for( $i = 15; $i <= 281; $i++ ){
    $file_path = "$gb_path$i.txt";
    
    $text = file_get_contents_utf8($file_path);
    preg_match($pattern, $text, $matches);
    
    $name = $matches[1];
    $message = $matches[2];
    
    $file_path = $gb_path . "d" . "$i.txt";
    $text2 = file_get_contents_utf8($file_path);
    
    $sql = "INSERT INTO Guestbook VALUES (NULL, '" . utf8_encode($name) . "', '" . utf8_encode($message) . "', '" . $text2 . "')";	
	  mysqli_query($con,$sql); 
  }
?>
