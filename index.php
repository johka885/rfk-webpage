<?php 

  //Get contents from utf8-encoded file
  function file_get_contents_utf8($fn) {
       $content = @file_get_contents($fn);
       if( !$content ) $content = ""; 
        return mb_convert_encoding($content, 'UTF-8',
            mb_detect_encoding($content, 'UTF-8, ISO-8859-1', true));
  }

	require 'variables.php';

	$err_404 = false;

  //Changes request to root specified in variables
  //i.e request to /page/subpage will be changed to /$root/page/subpage
  if( strcmp( $root, "/" ) != 0 ){
    $current_page = str_replace($root, "", $_SERVER[REQUEST_URI]);
  } else {
    $current_page = substr($_SERVER[REQUEST_URI], 1);
  }

  //Default page is /nyheter
	if( strlen($current_page) < 2){
		$current_page = "nyheter";
	}

  // Request to /page/subpage/ will put $menu_page == page and $submenu_page == subpage
  $current_page = preg_replace("/(.*)\/$/", "$1", $current_page);  
	$menu_page = preg_replace("/\/.*/", "", $current_page);
	$submenu_page = preg_replace("/^[^\/]*\//", "", $current_page);	
  
  // If request ends in /admin go to set admin mode true (will require login)
  $admin = false;
  if(preg_match("/.*\/admin/", $submenu_page)){
    $submenu_page = preg_replace("/(.*)\/admin/", "$1", $submenu_page);
    $admin = true;    
  }
  
  // extract parts from /nyheter/year/submenu/
  if( $menu_page == "nyheter" ){
    $year = preg_replace("/(.*)\/(.*)/", "$1", $submenu_page);
    $submenu_page = preg_replace("/(.*)\/(.*)/", "$2", $submenu_page);
    
    if( intval($year) < 2000 ){
      $year = date('Y');
    }    
    
    if( strcmp( $year, $submenu_page ) == 0){
      $submenu_page = "";
    }
  }
  					
  // extract parts from /gastbok/gastbok/pagenumber
  if( $menu_page == "gastbok" ){
    $page = preg_replace("/(.*)\/(.*)/", "$2", $submenu_page);
    $submenu_page = preg_replace("/(.*)\/(.*)/", "$1", $submenu_page);
    
    if( strcmp( $page, $submenu_page ) == 0 ){
      $page = 1;
    }
  }
    
  // If request ends in /admin go to set admin mode true (will require login)
  if(preg_match("/.*\/admin/", $submenu_page)){
    $submenu_page = preg_replace("/(.*)\/admin/", "$1", $submenu_page);
    $admin = true;    
  }
  
  // Check if requested menupage exists
	$found = 0;
	foreach ($menu as &$value) {
		if( strcmp($menu_page, $value["link"]) == 0 ){
			$menu_name = $value["name"];
			$found = 1;
		}
	}
	unset($value);		
	$err_404 = $err_404 || !$found;

  /*
  if( strcmp($menu_page, "bildspel") == 0 ){
    $bild = true;
    $submenu_page_real = $submenu_page;
  }
  */
  
	if( $found == 0 ){
		$menu_page = "nyheter";
		$menu_name = "Nyheter";
		$found = 1;
	}
	  
  // Check if requested submenupage exists
	if( $found == 1){
		$found = 0;
			
		foreach ($submenus as &$value) {
			if( strcmp($menu_page, $value["name"]) == 0 ){
				$submenu = $value;
				$found = 1;
			}
		}
		unset($value);
		$err_404 = $err_404 || !$found;
		
		if( strcmp($menu_page, $submenu_page) == 0 || strlen($submenu_page) == 0){
			if( !$err_404){ 
        if( strcmp( date('Y'), $year ) == 0 || strcmp( $year, "" ) == 0 ){
          $submenu_page = $submenu["std_page"];
        } else {
          $submenu_page = "januari";
        }
			}
		}
		
		if($found == 1){
		
			$found = 0;
			$submenu = $submenu["menu"];
			foreach ($submenu as &$value) {
				if( strcmp($submenu_page, $value["link"]) == 0 ){
					$found = 1;
				}
			}
			$err_404 = $err_404 || !$found;
			if($found == 1){
			}
		}
	}
  
  /* 
  if( $bild ){
    $menu_page = "bildspel";
    $submenu_page = $submenu_page_real;
    $err_404 = false;
    $year = 2015;
  }
  */
     
  //Check if page exists in old webpage
  headers_sent($filename, $linenum);
  $old = str_replace("old/", "", $_SERVER[REQUEST_URI]);
  if( $err_404 && strcmp( $old, $_SERVER[REQUEST_URI] ) == 0 ){
    $redirect = $domain . "/old" . $_SERVER[REQUEST_URI];
    header("Location: " . $redirect);  
    die(); 
  }
?><!DOCTYPE html><html>
	<head>
		<meta charset="utf-8">
		<meta id="viewport" name="viewport" content="width=600, initial-scale=1">
		<script>
      //Fix problem with initial scale on certain phones
			var mvp = document.getElementById('viewport');
			
			if (screen.width < 600) {
				var scale= screen.width / 600;
				mvp.setAttribute('content','width=600, initial-scale=' + scale);
			}
			else if(screen.width >= 600 && screen.width < 1024){
				var scale= screen.width / 1024;
				mvp.setAttribute('content','width=1024, initial-scale=' + scale);
			}
			else{
				mvp.setAttribute('content','width=' + screen.width + ', initial-scale=1');				
			}
			
		</script>
		<title>
			Rimforsa Fiskeklubb
		</title>
		
		<script src="http://code.jquery.com/jquery-1.11.0.min.js" type="text/javascript"></script>
		<script src="<?php echo $root; ?>script.js" type="text/javascript"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js" type="text/javascript"></script>
		<link rel="stylesheet" type="text/css" href="<?php echo $root; ?>style.css">
 
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
	</head>
	
  
  <?php 
     //Keep user logged in during same session
     function validate(){
      $user = $_COOKIE["username"];
      $token= $_COOKIE["token"];
      
      $con = mysqli_connect("rimforsafk.se.mysql","rimforsafk_se","gosper","rimforsafk_se");

      $sql = "SELECT Token FROM Users WHERE Username like '" . $user . "'";
    
      $result = mysqli_query($con,$sql);
      
      while($row = mysqli_fetch_array($result)) {
        $real_token = $row['Token'];
      }
      
      $real_token = md5("thisissaltyaf" + $real_token);
               
      if( strcmp( $token, $real_token ) == 0 && $token != "" && $real_token != ""){       
        return true;
      } else{
        return false;
      }
     }
     
     $valid = validate();
     
     //If invalid user display login page     
     if($admin && !$valid ){
      echo '<div>
      <form class="login-form">
        <div class="form-group">
          <label for="username">Användarnamn</label>
          <input type="text" class="form-control" name="username" id="username" placeholder="Skriv användarnamn" />
        </div>
        <div class="form-group">
          <label for="password">Lösenord</label>
          <input type="password" class="form-control" name="password" id="password" placeholder="Skriv lösenord" />
        </div>
        <input type="hidden" name="redirect_page" id="redirect_page" value="' . $_SERVER[REQUEST_URI] . '" />
        <button type="submit" class="btn btn-default">Logga in</button>
      </form></div>';
      
      echo '
      <script>
        $(document).ready( function(){
          $(".container").css("display", "none");      
        });
      </script>';      
    } 
    
  
  ?>
  
  <!DOCTYPE html>
	<body class="preload">
		<div class="container">		
			<div class="navbar navbar-default topbar" role="navigation">
				<div class="container-fluid">
				  <div class="navbar-header">
					<a class="navbar-brand left" href="http://www.facebook.com/RimforsaFiskeklubb">RFK på facebook</a>
					<span class="navbar-brand right">Kontakta oss</span>
				  </div>				  
        </div>
      </div>
      
			<a href="<?php echo $root; ?>">
        <div class="jumbotron"></div>
      </a>
		
			<div class="navbar navbar-default" role="navigation" id="main-menu">
				<div class="container-fluid">
          <div class="navbar-header">
            <div class="navbar-header">
              <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".main-collapse">
              <span class="sr-only">Toggle navigation</span>
              <div class="iconbar">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </div>
              <div class="button-label">MENY</div>
              </button>
            </div>
            <div class="navbar-collapse collapse main-collapse">
              <ul class="nav navbar-nav tabs">              
              <?php
                if( strcmp( $menu_page, "bildspel" ) == 0 ){
                  write_menu("nyheter");
                } else{
                  write_menu($menu_page);
                }
              ?>              
              </ul>
            </div>
          </div>
        </div>
      </div>
			
			<div class="nav sidebar-nav col-xs-3 col-md-2 submenu">
				<div class="container-fluid">				
          <div class="navbar-header">
            <h3>
              <span class="vertical-centered">
                <?php 
                  if( $menu_page == "nyheter" ){
                    echo $menu_name . " " . $year;
                  } else{
                    echo $menu_name;
                  }
                ?>
              </span>
            </h3>			
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".submenu-collapse">
              <span class="sr-only">Toggle navigation</span>
              <div class="iconbar">
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              </div>
              <div class="button-label"><?php echo ucfirst($submenu_page); ?></div>
            </button>
          </div>
				
				<div class="navbar-collapse submenu-collapse collapse">
					<ul class="nav sidebar-nav">
						<?php
                if( strcmp( $menu_page, "bildspel" ) == 0 ){                  
                  write_submenu("nyheter", $submenu, $submenu_page, $year);	
                } else{
                  write_submenu($menu_page, $submenu, $submenu_page, $year);	
                }
						?>
					</ul>
				</div>
        
				</div>
      </div>
			
			<div class="col-xs-9 col-md-8 centerbox">
        <?php
          //Load admin stuff
          if( $admin && $valid ){            
            echo '<script src="' . $root . 'admin.js" type="text/javascript"></script>';
            echo '
                  <div class="admin-bar" role="group" aria-label="...">
                    <ul>
                      <li class="header1 noselect">Rubrik</li>
                      <li class="bold noselect">Fet</li>
                      <li class="italic noselect">Kursiv</li>
                      
                      <li class="link noselect"><a href="#"><span class="glyphicon glyphicon-link"></span> Länk</a></li>
                      <li class="picture noselect"><span class="glyphicon glyphicon-picture"></span> Bild</li>
                    
                      <li class="save"><span class="glyphicon glyphicon-floppy-disk"></span> Spara</li>
                    </ul>
                  </div>
                 ';          
          }
        ?>
				<div class="main-content<?php if($admin){ echo " editable";} ?>" <?php if( $admin ){ echo "contenteditable='true'";} ?> >
					<?php        
						if ($err_404){
              //Display 404 not found page
							err404($current_page);
						}
						else if( strcmp( $menu_page, "gastbok") == 0){
              //Load and display guestbook messages from db
							echo file_get_contents_utf8($file_system_path . $root . "gastbok.php");	
              
              if( !$admin ){
                echo file_get_contents($domain . $root . "getposts.php?count=20&start=$page");					
              }
              
              echo "<span class='center'>";
              
              $page = intval($page);
              
              if( $page != 1 ) {
                echo "<a href='1'>1</a>";
              } else {
                echo "1";
              }
              
              $num_of_posts = intval(file_get_contents($domain . $root . "number_of_gb_posts.php"));
              $num_of_pages = $num_of_posts/20;
              
              for( $i = 2; $i < $num_of_pages; $i++ ){                
                echo " | ";
                if( $page != $i ){
                  echo "<a href='$i'>$i</a>";
                } else {
                  echo "$i";
                }
              }
              
              echo "</span>";
              
            } else if (strcmp( $menu_page, "nyheter") == 0){	
              // Display latest updated news page
							$url = $file_system_path . $root . "pages/" . 'news/' . $year . '/' . $submenu_page . '.html';
							              
              $loaded = false;
              
              $months = array();              
              $months[1] = "januari";        
              $months[2] = "februari";        
              $months[3] = "mars";        
              $months[4] = "april";        
              $months[5] = "maj";        
              $months[6] = "juni";        
              $months[7] = "juli";        
              $months[8] = "augusti";        
              $months[9] = "september";        
              $months[10] = "oktober";        
              $months[11] = "november";        
              $months[12] = "december";
              
              $htm = file_exists ( $url );  
                             
              $fail = false;
              
              $before = true;
              if ( intval($year) < intval(date(Y)) ){
                $before = true;
              } else{
                if( array_search ( $submenu_page , $months ) < intval(date('m'))){
                  $before = true;
                } else{
                  $before = false;
                }
              }
              
              while( !$htm ){     
                if ( !$htm ){
                  $index = array_search ( $submenu_page , $months );
                  if( $index == 1 ){
                    $year = intval($year) - 1;
                    $submenu_page = $months[ 12 ];
                  } else {
                    $submenu_page = $months[ $index - 1 ];
                  }                  
                  
                  
                  $loaded = false;                
                  $url = $file_system_path . $root . "pages/" . 'news/' . $year . '/' . $submenu_page . '.html'; 
                  $test = $test - 1;
                }
                $htm = file_exists ( $url );      
                $fail = true;
              }              
              
              if( $fail && ( $admin || $before ) ){
                
              } else{              
                $htm = file_get_contents_utf8($url);             
                echo $htm;              
              }
              
						}else {				
              //Display requested page
							$url = $file_system_path . $root . "pages/" . $menu_page . "/". $submenu_page . ".html";
							$htm = file_get_contents_utf8($url);
							echo $htm;
						}
					?>
										
				</div>
			</div>
			
			<div class="competitions col-xs-3 col-md-2 right-column">
				<h3><span class="vertical-centered">Kommande tävlingar</span></h3>
				
				<?php 
          //Load next 2 competitions from db
					echo utf8_encode(file_get_contents("http://www.rimforsafk.se" . $root . "getcomps.php?count=2"));
				 ?>
				
				<div class="comp-item last" >
					<a href="<?php echo $root; ?>tavlingar">Alla tävlingar</a>
				</div>
			</div>
				
			<div class="guestbook col-xs-3 col-md-2 right-column">				
				<h3><span class="vertical-centered">Gästbok</span></h3>
				<?php
          //Load the two latest guestbook posts
					echo file_get_contents_utf8("http://www.rimforsafk.se" . $root . "getposts.php?count=2&start=1");
				?>
				<div class="gb-post last">
					<a href="<?php echo $root; ?>gastbok">Till gästboken</a>
				</div>
			</div>
				
			<footer class="col-xs-12 col-md-12">
				<hr>
        <?php        
          //Update latest updated message
          $gb = "uppdaterad.txt";
          $fh = fopen($gb, 'r');
          $datum = fread($fh, filesize($gb));
          fclose($fh);
          echo "Copyright © Rimforsa Fiskeklubb. All rights reserved. <br>Senast ändrad $datum";
			 ?>
      </footer>
			</div>	
		</div>
		
      <div class="overlay-inner col-xs-12 col-md-4">
        <h2>Kontakta RFK</h2>
        <br>
        <div class="form-group">
          <label for="subject">Ärende</label>
          <select id="subject" class="form-control"> 
            <option value="common" selected>Allmänt</option> 
            <option value="apply">Ansök om medlemskap</option> 
            <!--<option value="picture">Skicka in bild</option>-->
          </select>
        </div>
          
          <hr>
          
        <form role="contact" id="form1">
          <div class="form-group">
            <label for="name">Namn</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Skriv namn">
          </div>          
          
          <div class="form-group">
            <label for="email">E-post</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Skriv e-postadress">
            <label class="control-label sr-only" for="email">Felaktig e-post</label>
          </div>
          
          <div class="form-group hidden">
            <label for="picture">Bild</label>
            <input type="file" class="form-control" id="picture" name="picture" accept="image/*">
          </div>
          
          <div class="form-group">
            <label for="message">Meddelande</label>
            <textarea class="form-control" id="message" name="message"> </textarea>
          </div>
          
          
          <button type="submit" class="btn btn-primary">Skicka</button>
         </form>
         
         
        <form role="form" id="form2">	        
          <div class="form-group">							
            <label for="type">Medlemstyp</label>
            <select id="f2-type" name="membership" class="form-control">
              <option value="normal">Senior - 225kr/år</option>
              <option value="family">Familj - 275kr/år</option>
              <option value="veteran">Pensionär - 150kr/år</option>
              <option value="junior">Junior - 75kr/år</option>
            </select>
          </div>
          
          <div class="form-group">
            <label for="name">Namn</label>
            <input type="text" class="form-control" id="f2-name" name="name" placeholder="Skriv för- och efternamn">
          </div>
            
          <div class="form-group">
            <label for="name">E-post</label>
            <input type="text" class="form-control" id="f2-name" name="email" placeholder="Skriv e-postadress">
          </div>
            
          <div class="form-group">
            <label for="adress">Personnummer</label>
            <input type="text" class="form-control" id="f2-pnr" name="pnr" placeholder="Skriv personnummer">
          </div>
            
          <div class="form-group">
            <label for="adress">Adress</label>
            <input type="text" class="form-control" id="f2-adress" name="adress" placeholder="Skriv address">
          </div>
            
          <div class="form-group">
            <label for="pnumber">Postnummer</label>
            <input type="text" class="form-control" id="f2-pnumber" name="postnr" placeholder="Skriv postnummer">
          </div>
          
          <div class="form-group">							
            <label for="padress">Postort</label>
            <input type="text" class="form-control" id="f2-padress" name="ort" placeholder="Skriv postort">
          </div>
          <div class="form-group">
            <label for="message">Övrigt</label>
            <textarea class="form-control" id="f2-message" name="message"> </textarea>
          </div>
          
          
          <button class="btn btn-primary" type="submit">Ansök</button>
          
          
        </form>
        
        <form role="contact" id="form3">
        
        <p>
        Fyll i nedanstående formulär noggrant glöm inte att kryssa för vilka av lagmedlemmarna som är medlemmar i Rimforsa FK. För dem som inte är medlemmar i klubben måste vi betala en avgift till Åsundens Fiskevårdsområde. När du fyllt i formuläret klicka på "Skicka".
        <br><br>
        Startavgift 200 kr/båtlag
        <br><br>
        STARTAVGIFTEN BETALAS VID STARTEN PÅ TÄVLINGSDAGEN
        
        </p>
        <hr>
          <div class="form-group">
            <label for="name">Teamnamn</label>
            <input type="text" class="form-control" id="f3-name" name="name" placeholder="Skriv lagnamn">
          </div>          
          
          <div class="form-group">
            <label for="email">E-post</label>
            <input type="email" class="form-control" id="f3-email" name="email" placeholder="Skriv e-postadress">
            <label class="control-label sr-only" for="email">Felaktig e-post</label>
          </div>
          
          <div class="form-group">
            <label for="phone">Telefonnummer</label>
            <input type="phone" class="form-control" id="f3-phone" name="phone" placeholder="Skriv telefonnummer">
          </div>
          
          <div class="form-inline">
            <div class="form-group">
              <label for="member1" class="member-label">Lagkapten </label>
              <input type="text" class="form-control" id="member1" name="member1" placeholder="Skriv namn">
            </div> 
            <div class="form-group">
              <div class="checkbox">
                <label>
                  Medlem i RFK? <input type="checkbox" id="member1-rfk" name="member1-rfk">
                </label>
              </div>
            </div>
          </div>
          
          <div class="form-inline">
            <div class="form-group">
              <label for="member2" class="member-label">Lagmedlem</label>
              <input type="text" class="form-control" id="member2" name="member2" placeholder="Skriv namn">
            </div> 
            <div class="form-group">
              <div class="checkbox">
                <label>
                  Medlem i RFK? <input type="checkbox" id="member2-rfk" name="member2-rfk">
                </label>
              </div>
            </div>
          </div>
          
          <div class="form-inline">
            <div class="form-group">
              <label for="member3" class="member-label">Lagmedlem</label>
              <input type="text" class="form-control" id="member3" name="member3" placeholder="Skriv namn">
            </div> 
            <div class="form-group">
              <div class="checkbox">
                <label>
                  Medlem i RFK? <input type="checkbox" id="member3-rfk" name="member3-rfk">
                </label>
              </div>
            </div>
          </div>
          
          <div class="form-inline">
            <div class="form-group">
              <label for="member4" class="member-label">Lagmedlem</label>
              <input type="text" class="form-control" id="member4" name="member4" placeholder="Skriv namn">
            </div> 
            <div class="form-group">
              <div class="checkbox">
                <label>
                  Medlem i RFK? <input type="checkbox" id="member4-rfk" name="member4-rfk">
                </label>
              </div>
            </div>
          </div>
            
          <button type="submit" class="btn btn-primary">Skicka</button>
         </form>
        
        <div class="status-bar"></div>
        
        <br><br><hr>      
        <div class="contact-info">
          <b>Ordförande, Andreas Forss</b><br>070-694 21 61<br>vanfossen(a)spray.se
          <br><br>
          <b>Kassör, Per Karlsson</b><br>076-305 22 66<br>firreper(a)gmail.com
        </div>
      
      <div class="close">X</div>      
		</div>
		<div class="overlay-background"></div>
	</body>
</html>