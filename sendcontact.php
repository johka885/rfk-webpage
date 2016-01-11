<?php

  require 'variables.php';

  $form_id = $_REQUEST['form'];

  $subject = utf8_decode( $_REQUEST['subject'] );
  $name = utf8_decode( $_REQUEST['name'] );
  $email = utf8_decode( $_REQUEST['email'] );
  $message = utf8_decode( $_REQUEST['message'] );
  
  if( strcmp( $form_id, "medlem" ) == 0 ){
    $membership = $_REQUEST['membership'];
    
    if( strcmp( $membership, "normal" ) == 0 ){
      $membership = "Senior";
    } else if( strcmp( $membership, "family" ) == 0 ){
      $membership = "Familj";
    } else if( strcmp( $membership, "veteran" ) == 0 ){
      $membership = "Pensionär";
    } else if( strcmp( $membership, "junior" ) == 0 ){
      $membership = "Junior";
    } else {
      $membership = "ERROR";
    }
    
    $pnr = utf8_decode( $_REQUEST['pnr'] );
    $adress = utf8_decode( $_REQUEST['adress'] );
    $postnr = utf8_decode( $_REQUEST['postnr'] );
    $ort = utf8_decode( $_REQUEST['ort'] );
    
    $final_message = utf8_decode( "<b>Ansökan om medlemskap</b> <br><br>" );
    $final_message .= "<table>";
    $final_message .= "<tr><td>Medlemstyp:</td> <td>$membership</td></tr>";
    $final_message .= "<tr><td>Name:</td> <td>$name</td></tr>";
    $final_message .= "<tr><td>Adress:</td> <td>$adress</td></tr>";
    $final_message .= "<tr><td>Postnummer:</td> <td>$postnr</td></tr>";
    $final_message .= "<tr><td>Ort:</td> <td>$ort</td></tr>";
    $final_message .= "</table><br>";
    $final_message .= "$message";
    
  } else if( strcmp( $form_id, "anmalan" ) == 0 ) {
  
    $antal = 4;
  
    $lag = $_REQUEST['name'];
    $epost = $_REQUEST['email'];
    $tel = $_REQUEST['phone'];

    $arr = array(0,0,0,0);
    $arr2 = array(0,0,0,0);

    for($i = 1; $i <= $antal; $i++){
      $arr[$i] = $_REQUEST["member$i"];
    }
    
    for($i = 1; $i <= $antal; $i++){
      $str = $_REQUEST["member$i-rfk"];
      if($str == "on"){
        $arr2[$i] = 1;
      } else {      
        $arr2[$i] = 0;
      }
    }

    $message = "Laget $lag har nu anmält sig till Höstrycket 2015. \n";
    $message .= "Telefonnummer: $tel \n";
    $message .= "Laget består av följande personer: \n";

    for($i = 1; $i <= $antal; $i++){
      if(!($arr[$i]) == ""){
        $message .= "$arr[$i]"; 
        $message .= " är";
        if(!$arr2[$i]){
          $message .= " INTE";
        }
        $message .= " medlem i Rimforsa Fiskeklubb. \n";

      }
    }

    $message .= "\n \n Tag med jämna pengar";
    $message = utf8_decode($message);
    $header = "From: $name1 <$epost>";

    mail("info@rimforsafk.se", utf8_decode("Anmälan Höstrycket"), $message, $header);
    mail($epost, utf8_decode("Bekräftelse - Anmälan Höstrycket"), $message, "From: Rimforsa FK <info@rimforsafk.se>");

  } else {
    $final_message = $message;
  }

  $from = "$name <$email>";

  $header    = array

      (

          'MIME-Version: 1.0',

          'Content-Type: text/html; charset="iso-8859-1";',

          'Content-Transfer-Encoding: 7bit',

          'Date: ' . date('r', $_SERVER['REQUEST_TIME']),

          'Message-ID: <' . $_SERVER['REQUEST_TIME'] . md5($_SERVER['REQUEST_TIME']) . '@' . $_SERVER['SERVER_NAME'] . '>',

          'From: ' . $from,

          'Reply-To: ' . $from,

          'Return-Path: ' . $from,

          'X-Mailer: PHP v' . phpversion(),

          'X-Originating-IP: ' . $_SERVER['SERVER_ADDR'],

      );





  $header = implode("\n", $header);

  $sent = true;
  
  if( strcmp( $subject, "common") == 0 ){

    $sent = mail("info@rimforsafk.se", utf8_decode( "RFK - Allmänt" ), $final_message, $header);

  }

  if( strcmp( $subject, "member") == 0 ){

  
    $sent = mail("info@rimforsafk.se", "RFK - Medlemskap", $final_message, $header);

  }

  if( strcmp( $subject, "apply") == 0 ){

    $email = "nils.arne.andersson@outlook.com";
    
    if( $debug ){
      $email = "info@rimforsafk.se";
    }
    
    $sent = mail($email, "RFK - Ny medlem", $final_message, $header);

  }




  if( $sent ){
      echo "success";
  } else {
      echo "error";
  }
  
?>