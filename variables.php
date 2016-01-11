<?php

$debug = false;

$root = "/";
$domain = "http://www.rimforsafk.se";
$file_system_path = "/customers/5/b/6/rimforsafk.se//httpd.www";

$menu = array();
$menu[1] = array("link"=>"nyheter", "name"=>"Nyheter");
$menu[2] = array("link"=>"medlemskap", "name"=>"Medlemskap");
$menu[3] = array("link"=>"fiskevatten", "name"=>"Fiskevatten");
$menu[4] = array("link"=>"tavlingar", "name"=>"Tävlingar");
$menu[5] = array("link"=>"resultat", "name"=>"Resultat");
$menu[6] = array("link"=>"gastbok", "name"=>"Gästbok");

$nyheter_sub = array();
$nyheter_sub[1] = array("link"=>"januari", "name"=>"Januari");
$nyheter_sub[2] = array("link"=>"februari", "name"=>"Februari");
$nyheter_sub[3] = array("link"=>"mars", "name"=>"Mars");
$nyheter_sub[4] = array("link"=>"april", "name"=>"April");
$nyheter_sub[5] = array("link"=>"maj", "name"=>"Maj");
$nyheter_sub[6] = array("link"=>"juni", "name"=>"Juni");
$nyheter_sub[7] = array("link"=>"juli", "name"=>"Juli");
$nyheter_sub[8] = array("link"=>"augusti", "name"=>"Augusti");
$nyheter_sub[9] = array("link"=>"september", "name"=>"September");
$nyheter_sub[10] = array("link"=>"oktober", "name"=>"Oktober");
$nyheter_sub[11] = array("link"=>"november", "name"=>"November");
$nyheter_sub[12] = array("link"=>"december", "name"=>"December");

$nyheter_sub[13] = array("link"=>"2015", "name"=>"2015");
$nyheter_sub[14] = array("link"=>"2014", "name"=>"2014");
$nyheter_sub[15] = array("link"=>"2013", "name"=>"2013");
$nyheter_sub[16] = array("link"=>"2012", "name"=>"2012");
$nyheter_sub[17] = array("link"=>"2011", "name"=>"2011");

$medlemskap_sub = array();
$medlemskap_sub[1] = array("link"=>"info", "name"=>"Information");
$medlemskap_sub[2] = array("link"=>"blimedlem", "name"=>"Bli medlem");

$fiskevatten_sub = array();
$fiskevatten_sub[1] = array("link"=>"jarnlunden", "name"=>"Järnlunden");
$fiskevatten_sub[2] = array("link"=>"asunden", "name"=>"Åsunden");
$fiskevatten_sub[3] = array("link"=>"slatmon", "name"=>"Sjöar vid Slätmon");

$tavlingar_sub = array();
$tavlingar_sub[1] = array("link"=>"alla", "name"=>"Alla tävlingar");

$resultat_sub = array();
$resultat_sub[1] = array("link"=>"2015", "name"=>"2015");
$resultat_sub[2] = array("link"=>"2014", "name"=>"2014");
$resultat_sub[3] = array("link"=>"2013", "name"=>"2013");
$resultat_sub[4] = array("link"=>"2012", "name"=>"2012");
$resultat_sub[5] = array("link"=>"2011", "name"=>"2011");
$resultat_sub[6] = array("link"=>"2010", "name"=>"2010");
$resultat_sub[7] = array("link"=>"2009", "name"=>"2009");

$gastbok_sub = array();
$gastbok_sub[1] = array("link"=>"gastbok", "name"=>"Gästbok");
//$gastbok_sub[2] = array("link"=>"admin", "name"=>"Administrera");

$submenus = array();
$submenus[1] = array("name"=>"nyheter", "menu"=>$nyheter_sub, "std_page"=>$nyheter_sub[intval(date("m"))]["link"]);
$submenus[2] = array("name"=>"medlemskap", "menu"=>$medlemskap_sub, "std_page"=>"info");
$submenus[3] = array("name"=>"fiskevatten", "menu"=>$fiskevatten_sub, "std_page"=>"jarnlunden");
$submenus[4] = array("name"=>"tavlingar", "menu"=>$tavlingar_sub, "std_page"=>"alla");
$submenus[5] = array("name"=>"resultat", "menu"=>$resultat_sub, "std_page"=>date("Y"));
$submenus[6] = array("name"=>"gastbok", "menu"=>$gastbok_sub, "std_page"=>"gastbok");

function err404($page){
	echo "<h3>Bottennapp!<br> <small>Sidan " . $page . " finns inte.</small></h3>";
}

function write_menu($active_page){
	global $menu, $root;
	foreach( $menu as &$value){
		$menuitem = "<li";
		if( strcmp($value["link"], $active_page) == 0){
			$menuitem .= " class='active'";			
		}
		$menuitem .= "><a href='";			
		$menuitem .= $root;			
		$menuitem .= $value["link"];
		$menuitem .= "'>";						
		$menuitem .= $value["name"];					
		$menuitem .= "</a></li>";
		
		echo $menuitem;		
	}
	unset($value);
}


function write_submenu($menu_page, $submenu, $active_page, $year){

	global $root;
  
  $count = 0;
	
	foreach( $submenu as &$value){
		$menuitem = "<li class='sidebar-brand";
		if( strcmp($value["link"], $active_page) == 0){
			$menuitem .= " active";			
		}
		$menuitem .= "'><a href='";
    if( strcmp( $menu_page, "nyheter") == 0 && $count < 12 ){   
      $menuitem .= $root . $menu_page . "/" . $year . "/" . $value["link"];       
    } else if( strcmp( $menu_page, "gastbok") == 0){
      $menuitem .= $root . $menu_page . "/" . $value["link"] . "/1";
    } else{
      $menuitem .= $root . $menu_page . "/" . $value["link"];    
    }
		$menuitem .= "'>";
		$menuitem .= $value["name"];
		$menuitem .= "</a></li>";		
	
		echo $menuitem;
    
    $count = $count + 1;
	}
	unset($value);
}


?>