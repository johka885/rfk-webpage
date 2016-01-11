<html>
<head>
<title>RFK
</title>
</head>


<body>


<?php 

$fullname = $_REQUEST['fname'];
$email = $_REQUEST['client_email'];
$messagereal = $_REQUEST['message'];
$header= "From: $fullname <$email>";

$messagereal .= "\r\n";
$messagereal .= "\r\n";
$messagereal .= " -  Fångstdatum: ";
$messagereal .= "20";
$messagereal .= $_REQUEST['ar'];
$messagereal .= "-";
$messagereal .= $_REQUEST['manad'];
$messagereal .= "-";
$messagereal .= $_REQUEST['datum'];
$messagereal .= "\r\n";
$messagereal .= "\r\n";

$target_path = "uploads/";

$target_path = $target_path . basename( $_FILES['uploadedfile']['name']); 

move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path);

$filetype = $_FILES["uploadedfile"]["type"];
	 
$to =	'info@rimforsafk.se';
$subject =	'Månadens fisk';
$bound_text =	"Johan är bäst";
$bound =	"--".$bound_text."\r\n";
$bound_last =	"--".$bound_text."--\r\n"; 
 	 
$headers =	"From: $fullname <$email>\r\n"; 
$headers .=	"MIME-Version: 1.0\r\n"
 	."Content-Type: multipart/mixed; boundary=\"$bound_text\"";
 	 
$message .=	"If you can see this MIME than your client doesn't accept MIME types!\r\n" 
 	.$bound; 
 	 
$message .=	"Content-Type: text/html; charset=\"iso-8859-1\"\r\n"
 	."Content-Transfer-Encoding: 7bit\r\n\r\n"
 	. $messagereal
 	.$bound;
 	 
$file =	file_get_contents($target_path); 
 	 
$message .=	"Content-Type: $filetype; name=$filnamn \r\n" 
 	."Content-Transfer-Encoding: base64\r\n"
 	."Content-disposition: attachment; file=$filnamn \r\n"
 	."\r\n"
 	.chunk_split(base64_encode($file))
 	.$bound_last; 


if(mail($to, $subject, $message, $headers)) 
{
     echo 'Tack för ditt bidrag'; 
} else { 
     echo 'Något blev fel, försök igen';
} 



$delete = $target_path;
unlink($delete);

?>

</body>
</html>