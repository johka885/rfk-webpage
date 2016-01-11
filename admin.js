var main, sub, year;
var $link;
var $selected;
var selectBegin, selectEnd;
var href;

window.onload = function(){

  //Parse which page we're currently editing
  var active = $(".submenu .active a")[0];
  if( active ){
    href = active.href;
  } else {
    href = location.href.replace(/(.*)\/admin/, "$1");
  }
   
  var test = href.split("/");
  
  if( test[test.length-3] == "nyheter" ){
    main = test[test.length-3];
    year = test[test.length-2];
    sub = test[test.length-1];
  } else {
    main = test[test.length-2];
    sub = test[test.length-1];
    year = "";
  }
  
  //Save selection for later use i.e. make bold or link
  $(".main-content").on("keydown", function(e){
    saveSelection( e ); 
    var key_code = e.keyCode || e.which;
    if( key_code == 13 ){
      //e.preventDefault();
    }
  });
  
  //Event handler for adding a link
  $(".admin-bar .link").click( function(){  
    var $overlay = $("<div class='link-overlay'></div>");
    var $link = $("<div class='link-overlay-inner'></div>");
    
    $link.append( '<div class="form-group"><label for="url">Url</label>' + 
                  '<div class="input-group">' +
                  '<input type="text" class="form-control" name="url" id="url">' +
                  '<span class="input-group-btn">' + 
                  '<button class="btn btn-default" type="button">OK</button></span></div></div>');
           
    
    $link.append( "<hr>" );
                  
    $link.append( '<div class="form-group"><label for="folder">Bläddra</label>' +
                  '<div class="input-group"><span class="input-group-btn">' + 
                  '<button class="btn btn-default" id="browse" type="button">' + 
                  '<span class="glyphicon glyphicon-folder-open"></span></button></span>' +
                  '<input type="text" class="form-control" name="folder" id="folder">' +
                  '<span class="input-group-btn"><button class="btn btn-default" type="button">OK</button></span></div></div>');                  
    //$link.append("<hr>");
    $link.append('<div class="fileTree"></div>');
    
    $link.appendTo($overlay);
    $overlay.appendTo($("body"));
    
    //Browser server files
    $("#browse").click(function(){
      var fileTreePath = root + "filetree/";
      $.getScript(fileTreePath + "jqueryFileTree.js", function(){      
        var link = document.createElement("link");
        link.href = fileTreePath + 'jqueryFileTree.css';
        link.rel = "stylesheet";
        $("head").append( $(link) );
        
        $(".link-overlay-inner .fileTree").fileTree({
            script: fileTreePath + "connectors/jqueryFileTree.php",
            root: "/customers/5/b/6/rimforsafk.se/httpd.www/"
        }, function(selected){
          selected = selected.replace("/customers/5/b/6/rimforsafk.se/httpd.www/", "/");
          
          $("#folder").val( selected );    
          
        });
      });      
    });
    
    //Wrap current selection: <a href=link>SELECTION</a>
    $("#url").parent().find("button").click( function(){
      var link = $("#url").val();
      if( link != "") {
        if( !link.match(/^http:\/\/.*/) ){
          link = "http://" + link;
        }
        saveLink( link );
      } else {
        alert("Ange url");
      }
    });
    
    $("#folder").next().find("button").click( function(){
      var link = $("#folder").val();
      if( link != "") {
        saveLink( link );
      } else {
        alert("Ingen fil vald");
      }
    });
  });  
  
  function saveLink( url ){
    $("#link_to_change").attr("href", url);
    $("#link_to_change").removeAttr("id");
    
    hideOverlay();
  }
  
  $("body").on("click", ".link-overlay", hideOverlay);

  function hideOverlay(){
    $(".link-overlay").remove();
    $(".admin-bar .link").removeClass("active");
    
    $("#link_to_change").replaceWith( $("#link_to_change").html() );
  }
  
  $("body").on("click", ".link-overlay-inner", function(e){
    e.stopPropagation();
  });
  
  //Event handler for uploading an image
  $(".admin-bar .picture").click( function(){
  
    var $form = $("<form>");
    var $file = $("<input type='file' name='file'>");  
    
    $form.append($file);
    $form.appendTo($("body"));
    
    $file.click();    
    
    $file.change( function(){
      
      var formData = new FormData($form[0]);     
      
      formData.append("main", main);
      formData.append("sub", sub);
      formData.append("year", year);
      
      $.ajax({
        url: root + "upload_file.php",
        data: formData,
        type: "POST",
        cache: false,
        contentType: false,
        processData: false,
        enctype: 'multipart/form-data',
        success: function(data){
          var $img = $("<img>");
          $img.attr("src", root + data);
          
          if( $selected && !$selected.hasClass("main-content") ){
            $selected.append($img);
          } else{
            $(".main-content").prepend($img);          
          }       
          
          $(".admin-bar .picture").removeClass("active");
        },
        error: function(a,b,c){
          alert(a,b,c);
        }
      });
    });
  });
  
  //Upload edited data to server
  $(".save").click( function(){  
    $(this).removeClass("active");    
    $.ajax({
      method: "POST",
      url: root + "update.php",
      data: {
        content: $(".main-content").html(),
        main: main,
        sub: sub,
        year: year
      },
      success: function(data){
        alert(data);
        if( data == "Sparat" ){
          location.href = href;
        }
      }
    });
  });

  
  
  
  var effects = [];
  $(".main-content").on("mousedown", function(){  
    setTimeout(saveSelection, 250);
  });
  
  $(".admin-bar li").click( function(){
  
    saveSelection();
    
    var $current_element = $selected;
        
    if( $current_element[0] ){
                      
      if( $(this).hasClass("active") ){
        if( selectedText == "" ){
        
        } else{
          if( $(this).hasClass("header1") ){
          
            while( $current_element[0].tagName != "H2" ){
              $current_element = $current_element.parent();
              
              if( $current_element[0].tagName == "body" ){
                $(".admin-bar .header1").removeClass("active");
                return;
              }
            }
            
            $current_element.replaceWith( $("<div>").text( $current_element.text()) );
                  
          } else if( $(this).hasClass("bold") ){
            while( $current_element[0].tagName != "B" ){
              $current_element = $current_element.parent();
              
              if( $current_element[0].tagName == "body" ){
                $(".admin-bar .bold").removeClass("active");
                return;
              }
            }
            
            $current_element.replaceWith( $("<div>").text( $current_element.text()) );
          } else if( $(this).hasClass("italic") ){
          
            while( $current_element[0].tagName != "I" ){
              $current_element.replaceWith( $("<div>").text( $current_element.text()) );
              
              if( $current_element[0].tagName == "body" ){
                $(".admin-bar .italic").removeClass("active");
                return;
              }
            }
            
            $current_element.html( 
              $current_element.html().replace(selectedText, 
                  "</i>" + selectedText + "<i>"));
                  
          } else if( $(this).hasClass("link") ){
            while( $current_element[0].tagName != "A" ){
              $current_element = $current_element.parent();
              
              if( $current_element[0].tagName == "body" ){
                $(".admin-bar .link").removeClass("active");
                return;
              }
            }
            
            var parts = $current_element.text().split( selectedText );
            var div = $("<div>");
            div.append($("<a href='" + $current_element[0].href + "'>" + parts[0] + "</a>"));
            div.append(selectedText);
            div.append($("<a href='" + $current_element[0].href + "'>" + parts[1] + "</a>"));
            
            $current_element.replaceWith(div);
          }
        }
      } else {
      
        var html = $current_element.html();        
        
        var first_part = html.substr( 0, selectBegin );
        var second_part = html.substr(selectBegin, html.length - selectBegin);
        
        if( $(this).hasClass("header1") ){  
          if( $current_element.hasClass("main-content") ){
            
          } else{
            $current_element.replaceWith( $("<h2>" + selectedText + "</h2>") );     
          }            
        } else if( $(this).hasClass("bold") ){   
          $current_element.html( first_part + second_part.replace(selectedText, "<b>" + selectedText + "</b>") );              
        } else if( $(this).hasClass("italic") ){   
          $current_element.html( first_part + second_part.replace(selectedText, "<i>" + selectedText + "</i>") );              
        } else if( $(this).hasClass("link") ){
          $current_element.html( first_part + second_part.replace(selectedText, "<a id='link_to_change'>" + selectedText + "</a>") );
        }
        $( this ).removeClass("active");
      }
    }
    
    if( !$(this).hasClass("picture") )
      $(this).toggleClass("active");
      
    tidyUp();
  });
  
  tidyUp();
}



function getSelectionHtml() {      
    var html = "";
    if (typeof window.getSelection != "undefined") {
        var sel = window.getSelection();
        if (sel.rangeCount) {
            var container = document.createElement("div");
            for (var i = 0, len = sel.rangeCount; i < len; ++i) {
                container.appendChild(sel.getRangeAt(i).cloneContents());
            }
            html = container.innerHTML;
        }
    } else if (typeof document.selection != "undefined") {
        if (document.selection.type == "Text") {
            html = document.selection.createRange().htmlText;
        }
    }
    return html;
}

function replaceSelectionWithHtml(html) {
    console.log(html);
    var range, html;
    if (window.getSelection && window.getSelection().getRangeAt) {
        range = window.getSelection().getRangeAt(0);
        range.deleteContents();
        var div = document.createElement("div");
        div.innerHTML = html;
        var frag = document.createDocumentFragment(), child;
        while ( (child = div.firstChild) ) {
            frag.appendChild(child);
        }
        range.insertNode(frag);
    } else if (document.selection && document.selection.createRange) {
        range = document.selection.createRange();
        range.pasteHTML(html);
    }
}

//Remove bad editing 
function tidyUp(){
  $(".main-content h2").each( function(){
    //$(this).html($(this).html());
  });
  
  $(".main-content font").each( function(){
    if( !$(this).parent().hasClass("main-content") ){
      $(this).replaceWith($(this).html());
    }
  });
  
  $(".main-content span").each( function(){
    if( !$(this).parent().hasClass("main-content") ){
      $(this).replaceWith($(this).html());
    }
  });
  
  $(".main-content title").each( function(){
    $(this).remove();
  });

  $(".main-content meta").each( function(){
    $(this).remove();
  });
  
  $(".main-content link").each( function(){
    $(this).remove();
  });
  
  $(".main-content *").each( function(){
    var valid = $(this)[0].tagName != "IMG";
    if( valid && $(this).html() == "" ){
      $(this).replaceWith($(this).html());
    }
  });
}

function saveSelection(e){
  if( e ){
    e.stopPropagation();
  }
  
  $(".admin-bar .active").removeClass("active");
  
  if (window.getSelection)
   selection = window.getSelection();
  else if (document.selection && document.selection.type != "Control")
   selection = document.selection;
  
  var $current_element = $(selection.anchorNode);
  
  if( $current_element[0].nodeName == "#text" ){
    $current_element = $current_element.parent();
  }

  if( $current_element[0].tagName == "H2" ){
    $(".admin-bar .header1").addClass("active");
  }
  
  if( $current_element[0].tagName == "B" ){
    $(".admin-bar .bold").addClass("active");
  }
  
  if( $current_element[0].tagName == "I" ){
    $(".admin-bar .italic").addClass("active");
  }
  
  if( $current_element[0].tagName == "A" ){
    $(".admin-bar .link").addClass("active");
  }
  
  $selected = $current_element;
  selectedText = getSelectionHtml();
  selectBegin = selection.baseOffset;
  selectEnd = selection.extentOffset;
  
  console.log( $selected, selectedText, selectBegin, selectEnd );
}