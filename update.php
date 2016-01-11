<?php

require 'variables.php';

$content = $_REQUEST['content'];
$main = $_REQUEST['main'];
$sub = $_REQUEST['sub'];
$year = $_REQUEST['year'];

$backup_path = $file_system_path . $root . "backup/" . $main . "/";
if (strcmp( $main, "nyheter") == 0){					
  $backup_path .= $year . "/";		
  $path = $file_system_path . $root . "pages/" . "news/" . $year . "/";
  $file = $sub . '.html';
} else if (strcmp( $main, "gastbok") == 0){
  $path = $file_system_path . $root;
  $file = "gastbok.php";
} else {
  $path = $file_system_path . $root . "pages/" . $main . "/";
  $file = $sub . ".html";
}

if (!file_exists($path . $file)) {
  if (!file_exists($path)) {
    mkdir($path, 0775, true);
  }
} else {
  $old_file = file_get_contents($path . $file);

  $backup_path = $file_system_path . $root . "backup/" . $main . "/";

  if (!file_exists($backup_path)) {
      mkdir($backup_path, 0775, true);
  }

  file_put_contents($backup_path . $sub . "_" . date("Y-m-d_H-i") . ".html", $old_file);
  unset($old_file);
}

if( file_put_contents ( $path . $file , $content) ){
  require 'update_date.php';
  echo "Sparat";
} else{
  echo "Något blev fel försök igen";
}


?>