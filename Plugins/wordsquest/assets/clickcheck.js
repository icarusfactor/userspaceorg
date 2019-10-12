if (typeof clickcnt === 'undefined') {
   var clickcnt = 1;
}

//initial position for line.
ws_initx=0;
ws_inity=0;
ws_clickset=0;


function findStrID(ida) {
 if(  $.inArray( ida.toString(10), window.answerpos ) > -1) {return 1;}
 else {return 0;}

 }

function getStrID(ida) {
  
  return ida.toString(10);
 
 }


function button_first(id) {
                            window.clickedpos.push( id.toString(10) ); //Save clicks for return to blue.                           
                            //$("#"+id).removeClass("btn-primary").addClass("btn-btn-success");
                            growCanvas();
                           }

function button_normal() {
                        $("#"+window.clickedpos[0]).removeClass("btn-btn-success").addClass("btn-primary");
                        //$("#"+window.clickedpos[1]).removeClass("btn-warning").addClass("btn-primary");
                       }

function button_success(id) {
                             //$("#"+id).removeClass("btn-warning").addClass("btn-success");                  
                             $("#"+id).removeClass("btn-primary").addClass("btn-success");
                             $("#"+id).css("background-color","green");
                          }



function click_check(id) {
 $('#wsGameCanvas').css("z-index", "99");  //set overlay for draw canvas for line.
 if( window.wsgc_firstClick==2) {window.wsgc_firstClick=0;window.wsgc_firstMove=0;} //last line canvas click reset.

 var ida=id-1;

if( window.clickcnt == 1 ){ window.clickcnt=2;wordsearch_startmatch(ida);button_first(id);return 1;}
if( window.clickcnt == 2 ){ window.clickcnt=1;wordsearch_endmatch(ida);return 2;}

}

function wordsearch_startmatch(ida) {

//Did it find a match
 if ( findStrID(ida) ) {     
    //now search for connecting end point numbers to match.  
    for(i = 0; i < window.answerpos.length; i+=4) {
      word=window.answerpos[i];
      if ( window.answerpos[i+1] ==  getStrID(ida) ) { window.findingpos.push( word, window.answerpos[i+2] , window.answerpos[i+3] ); }
      if ( window.answerpos[i+2] ==  getStrID(ida) ){window.findingpos.push( word, window.answerpos[i+1] , window.answerpos[i+3] ); }
      //Highlight id as yellow.      
      }     
    
    } else {
    //Dont add anything, but proceed as to not tip off the player.
   
    window.findingpos = [];
    //Still highlight id as yellow.
    }

} 


//Find if number of second clicked item was a match for any word in findingpos array.
function wordsearch_endmatch(ida) {

if ( findStrID(ida) ) {     
              
    
    for(i = 0; i < window.findingpos.length; i+=3) {
      
      word=window.findingpos[i];
      if ( window.findingpos[i+1] == getStrID(ida) ) { 
     
      
      strikeout_word(word);

     //Check if second is smaller than first. If so, swap with first click.     
     if( parseInt(window.findingpos[i+1] ) < parseInt( window.clickedpos[0] )-1) {
    
     var temp=window.findingpos[i+1];
     window.findingpos[i+1]=String( parseInt(window.clickedpos[0]-1) );
     window.clickedpos[0]=temp;
     addWordFound(parseInt(window.clickedpos[0]),parseInt( window.findingpos[i+1]),window.findingpos[i+2]);
     } else { 
      //No need to swap just add word and positions.           
      addWordFound(parseInt(window.clickedpos[0]-1),parseInt( window.findingpos[i+1]),window.findingpos[i+2]);
      }

    }
  }
}
//Switch all buttons to green that have been found.
for(i = 0; i < window.foundpos.length; i++) {button_success( String(parseInt(window.foundpos[i])+1)  );}

window.findingpos = [];window.clickedpos = [];//Clear out for next match test.
} // wordsearch_endmatch



//orientation
//  *0* Horizontal $inc=1
//      startp increment column to endp
//  *1* Vertical  $inc=$this_size
//     startp increment row per placement until endp
//  *2* Diagonal Left TO right $inc = $this->_size +1
//    startp increament row per placement plus one column until endp
//  *3* Diagonal Right To left $inc = $this->size -1
//    startp increment row per placement minus one column until endp
// Manually get grid size from class.ws.grid.php DEFAULT_GRID_SIZE = 13;
//
function addWordFound( start , end , orient ){
//Put stuff here to Find all the blocks for word and place them in the box.
var DEFAULT_GRID_SIZE = 13;


var foundpos_str="";
var offset=0;
switch(orient)
  {
  case "0":
     offset = start;
     while (offset <= end) {
     window.foundpos.push( String(offset) );
     foundpos_str=foundpos_str+" "+ String(offset);     
     offset++;
     }
     
     break; 
 case "1":
     offset = start;
     while (offset <= end) {
     window.foundpos.push( String(offset) );
     foundpos_str=foundpos_str+" "+ String(offset);     
     offset = offset + DEFAULT_GRID_SIZE;      
     }
     
     break; 
 case "2":
     offset = start;
     while (offset <= end) {
     window.foundpos.push( String(offset) );
     foundpos_str=foundpos_str+" "+ String(offset);     
     offset = offset + DEFAULT_GRID_SIZE + 1;
     }
     
     break; 
 case "3":
     offset = start;
     while (offset <= end) {
     window.foundpos.push( String(offset) ); 
     foundpos_str=foundpos_str+" "+ String(offset);     
     offset = offset + DEFAULT_GRID_SIZE - 1;
     }     
     //Just post numbers of current word. 
    
     break;
 }

} // addWordFound


function strikeout_word(word)
{
 

$('span.wordsearchitem:contains('+word+')', document.body).each(function(){      
      $(this).html($(this).html().replace(
            new RegExp(word, 'g'), '<span class="wordsearchitem" style="text-decoration: line-through;" >'+word+'</span>'
      ));
});

 
}



//Global variables for wsGameCanvas
//var wsgc_Offset = $('#wsGameCanvas').offset();
var wsgc_firstMove=0;
var wsgc_firstClick=2;
var letsdraw;

$(function() {
//  var letsdraw ;

  var theCanvas = document.getElementById('wsGameCanvas');
  var ctx = theCanvas.getContext('2d');
  theCanvas.position = "relative";
  theCanvas.top = "0"; //was -500
  theCanvas.left = "0";
  theCanvas.width =  300;
  theCanvas.height = 300;

 
  $('#wsGameCanvas').mousemove(function(e) {

    
     var mousePos = getMousePos( theCanvas  , e);

     if( window.wsgc_firstMove == 0  && window.wsgc_firstClick != 2) {
           window.letsdraw = {
          // x:e.pageX - window.wsgc_Offset.left,
          // y:e.pageY - window.wsgc_Offset.top         
	  x:mousePos.x, y:mousePos.y	   
          }
           window.wsgc_firstMove=1;
           //grow_canvas();
          }
    if( window.wsgc_firstClick != 2){ 
   
    if (window.letsdraw) {
      ctx.clearRect(0,0,theCanvas.width,theCanvas.height);
      ctx.strokeStyle = 'blue';
      ctx.lineWidth = 3;
      ctx.beginPath();
    
      ctx.moveTo(window.letsdraw.x , window.letsdraw.y  );
      //ctx.lineTo(e.pageX - window.wsgc_Offset.left, e.pageY - window.wsgc_Offset.top);
      ctx.lineTo( mousePos.x , mousePos.y );	   
      ctx.stroke();
    }
   }//firstclick check
  });

   $('#wsGameCanvas').mousedown(function(e) {

    letsdraw = null;
    ctx.clearRect(0,0,theCanvas.width,theCanvas.height);
    $('#wsGameCanvas').css("z-index", "-1");

    if(window.wsgc_firstClick != 1 ){ 
   
    window.wsgc_firstMove=0;
    

    //the command in here will be to activate the second click of the game.
    $( "a" ).hover(function(){
                   
          shrink_canvas();
          click_check(this.id);
          $("a").unbind("mouseenter mouseleave");
           }); 
    
     window.wsgc_firstClick=2;     

    }
  });


function shrink_canvas() {

  theCanvas.width =  0;
  theCanvas.height = 0;
 

}


function grow_canvas() {

  theCanvas.width =  300;
  theCanvas.height = 350;
 

}


function getMousePos(canvas, evt) {
	        var rect = canvas.getBoundingClientRect();
	        return {
			          x: evt.clientX - rect.left,
			          y: evt.clientY - rect.top
			        };
	      }

});


function growCanvas() {
var theCanvas = document.getElementById('wsGameCanvas');
    theCanvas.width =  300;
    theCanvas.height = 350;
  

}








 

