var images = [];
var root = "/";
var content;

$(document).ready( function(){
	$(".navbar-brand.right").on("click", showOverlay);

	$(".overlay-background").on("click", hideOverlay);
	$(".close").on("click", hideOverlay);
	
	$("#form1").on("submit", function(event){
		event.returnValue = false;
		if(event.preventDefault) event.preventDefault();
	
		if( checkInput() ){
    
      var formData = new FormData(this);
      formData.append("form", "form1");
      formData.append("subject", $("#subject").val());

      sendForm( formData );      
		}
	});
  
	$("#form2").on("submit", function(event){
		event.returnValue = false;
		if(event.preventDefault) event.preventDefault();
	
		if( checkInput2() ){
    
      var formData = new FormData(this);
      formData.append("form", "medlem");
      formData.append("subject", $("#subject").val());
      
			sendForm(formData);
		}
	});
  
	$("#form3").on("submit", function(event){
		event.returnValue = false;
		if(event.preventDefault) event.preventDefault();
	
		if( checkInput3() ){
    
      var formData = new FormData(this);
      formData.append("form", "anmalan");
      formData.append("subject", $("#subject").val());
      
			sendForm(formData);
		}
	});
  
  
	$("#subject").on("change", loadFields);
	
	$(".main-content img").removeAttr("height width");
  
  setTimeout(function(){$("body").removeClass("preload");}, 1000);
  
  var href = location.href;
  
  $("body").on("submit", ".login-form", function(e){  
      e.preventDefault();
      
      $.ajax({
        url: root + "verify.php",
        data: $(this).serialize(),
        success: function(result){
          if(result != "Invalid"){
          
            var d = new Date();
            d.setTime(d.getTime() + 3*60*60*1000);            
            var expires = "expires=" + d.toUTCString();
            
            document.cookie = "username" + "=" + $("#username").val() + "; " + expires;
            document.cookie = "token" + "=" + result + "; " + expires;
            
            
            window.location.reload();
          } else{
            alert(result);
          }
        }
      });
      
      return false;
  });
  
  if(!href.match(/.*\/admin/)){
    if( $(".submenu .active a")[0] == null){
      href = location.href;
    } else {
      href = $(".submenu .active a")[0].href;
    }
  }
  
  if( !location.href.match(/.*\/gastbok\/\d+/ ) ){
    if( window.history.replaceState ){
      var history_text = $("#main-menu .active a").text() + $(".submenu .active a").text();
      window.history.replaceState("RFK - " + history_text, history_text, href);
    }
  }
  
  $("body").on("click", "a", function(e){
    if(this.href.indexOf("medlemskap/blimedlem") != -1){
      e.preventDefault();
      
      $("#subject").val("apply");
      $(".overlay-inner form").hide();
      $("#form2").show();
  
      showOverlay();
    } else if(this.href.indexOf("anmalningsformular") != -1) {
      e.preventDefault();      
      
      var link = this.href;
      
      var name = link.match(/.*\/anmalningsformular\/(.*)\/.*/)[1];
      var year = link.match(/.*\/anmalningsformular\/.*\/(.*)/)[1];
      
      name = name == "gaddrycket" ? "Gäddrycket" : name;
      name = name == "hostrycket" ? "Höstrycket" : name;
      
      $("#subject").append('<option value="register" selected>Anmälning ' + name + " " + year+ '</option>');
      $("#subject").val("register");
      $(".overlay-inner form").hide();
      $("#form3").show();
  
      showOverlay();
    }
  });
  
  $("#gb").on("submit", function(e){
    this.action = root + "newpost.php";
  });
  
  
  $(".overlay-inner form").hide();
  $("#form1").show();
});

function hideOverlay(){
		$(".overlay-inner").hide();
		$(".overlay-background").hide();
}

function checkEmail( $emailObject ){
	
	var element = $emailObject;
	var text = element.value;
			
	if(element.id == "email"){
		var email = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
		if(!email.test(text)){
			$(element).parent().addClass("has-error");
			$(element).next().removeClass("sr-only");
			//$(".overlay-inner button").attr("disabled", "disabled");
			return false;
		}
		else{
			$(element).parent().removeClass("has-error");
			$(element).next().addClass("sr-only");
			//$(".overlay-inner button").removeAttr("disabled");			
		}
	}
	
	return true;
}

function checkInput(){
  var correctInput = checkEmail( $("#email") );
	
  return correctInput;
}

function checkInput2(){
  var correctInput = checkEmail( $("#f2-email") );
	
  return correctInput;
}

function checkInput3(){
  var correctInput = checkEmail( $("#f3-email") );
	
  return correctInput;
}

function loadFields(){

	var form1 = $("#form1");
	var form2 = $("#form2");
	var form3 = $("#form3");
  
  $(".overlay-inner form").hide();

	if( this.value == "common" ){
		form1.show();;    
	}
	else if( this.value == "apply" ){
		form2.show();
	} else {
    form3.show();
  }
  
  $pictureContainer = $("#picture").parent();
  if( this.value == "picture" ){    
    $pictureContainer.removeClass("hidden");
  } else{
    $pictureContainer.addClass("hidden");  
  }
}

function showOverlay(){
		$(".overlay-inner").show();
		$(".overlay-background").show();
		$(".overlay-background").css("height", $(".container").height());
	}
  
  
function sendForm(formData){      
  sent = false;
  $(".status-bar").addClass("loading");
  loadingPercentage = 0;
  $.ajax({
    url: root + "sendcontact.php",
    type: "POST",
    data: formData,
    processData: false,
    contentType: false,
    success: function( data ){
      response = data;
    }
  });
  waitToSend();
}

var loadingPercentage;
var sent;
var response;

function waitToSend(){
  if( !sent ){
    
    var $progress = $("<div class='progress'></div>");
    var $progressBar = $("<div class='progress-bar'></div>");
    var msg = "Skickar";
    
    $progressBar.css("width", loadingPercentage + "%");
    $progress.html( $progressBar );
    $(".status-bar").html( $progress );
    
    loadingPercentage += 0.4 + Math.random()/4 ;
    setTimeout( waitToSend, 10 );
    
    if( loadingPercentage >= 100 ) {
      sent = true;
    }
  } else {
    setTimeout( setStatusText, 300 );
  }
}

function setStatusText(){
  if( response == "success" ){
    $(".status-bar").append( "<p>Tack för ditt mail! </p>" );
    $(".status-bar p").addClass("has-success");
  } else {
    $(".status-bar").append( "<p>Något blev fel, försök igen!" + data + "</p>" );  
    $(".status-bar p").addClass("has-error");    
  }
}