<?php

$today = getdate();

$m = $today['mon'];

if($m==1){
$ma = "januari";
}
if($m==2){
$ma = "februari";
}
if($m==3){
$ma = "mars";
}
if($m==4){
$ma = "april";
}
if($m==5){
$ma = "maj";
}
if($m==6){
$ma = "juni";
}
if($m==7){
$ma = "juli";
}
if($m==8){
$ma = "augusti";
}
if($m==9){
$ma = "september";
}
if($m==10){
$ma = "oktober";
}
if($m==11){
$ma = "november";
}
elseif($m==12){
$ma = "december";
}




$y = $today['year'];
$d = $today['mday'];
$hh = $today['hours'];
$hh++;
if($hh==24){
$hh-=24;
}
$mm = $today['minutes'];
if($mm < 10){
$m = "0";
$m .= "$mm" ;
$datum = "$d $ma $y $hh:$m";
}
else{
$datum = "$d $ma $y $hh:$mm";
}

$gb = "uppdaterad.txt";
$fh = fopen($gb, 'w');
fwrite($fh, $datum);
fclose($fh);

?>