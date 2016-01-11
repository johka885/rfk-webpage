<?php  

    $con = mysqli_connect("rimforsafk.se.mysql","rimforsafk_se",INSERT_PASSWORD_HERE,"rimforsafk_se");

    $sql = "SELECT Token FROM Users WHERE Username like '" . $_REQUEST['username'] . "'";
  
    $result = mysqli_query($con,$sql);
    
    while($row = mysqli_fetch_array($result)) {
      $token = $row['Token'];
    }
    
    $pass = $_REQUEST['password'];
    
    if( strcmp( md5($pass), $token ) == 0 && $pass != "" && $token != ""){      
      echo md5("thisissaltyaf" + md5($pass));
    } else{
      echo 'Invalid';
    }
    
?>