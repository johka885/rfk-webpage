<?php 

require 'variables.php';

$base_path = $file_system_path . $root;

if( strcmp( $_POST['main'], "nyheter" ) == 0) {
  $target_path = 'uploads/' . $_POST['main'] . "/" . $_POST['year'] . "/" . $_POST['sub'] . "/";
} else {
  $target_path = 'uploads/' . $_POST['main'] . "/" . $_POST['sub'] . "/";
}

if (!file_exists($base_path . $target_path)) {
    mkdir($base_path . $target_path, 0775, true);
}
$target_path = $target_path . basename( $_FILES['file']['name']); 



move_uploaded_file($_FILES['file']['tmp_name'], $base_path . $target_path);


echo $target_path;
?>