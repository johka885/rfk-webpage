<?php
$id = $_REQUEST['id'];
$fname = $_REQUEST['fname'];
$lname = $_REQUEST['lname'];
$fullname = "$fname $lname";
$email = $_REQUEST['client_email'];
$message = $_REQUEST['message'];
$captcha = $_REQUEST['antispam'];
$from = "$fullname <$email>";
//$header= "From: $fullname <$email>";
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
if($id == "all"){
$sent = mail("info@rimforsafk.se", "RFK - Allmänt", $message, $header);
}
if($id == "medl"){
$sent = mail("nils.arne.andersson@outlook.com", "RFK - Medlemskap", $message, $header);
}
if($id == "grans"){
$sent = mail("info@rimforsafk.se", "RFK - Gransjön", $message, $header);
}
if($id == "hemsida"){
$sent = mail("info@rimforsafk.se", "RFK - Hemsida", $message, $header);
}
if(!$sent){
    echo('<font color="red"><h2>Ditt mail blev INTE skickat, försök igen!</h2></font>');
}       
else{
    echo('<h2>Tack för ditt mail</h2>');}

?>