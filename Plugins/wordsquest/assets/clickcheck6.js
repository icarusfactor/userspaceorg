// This section is for managing the game and word selection.
var click_check_version="0.9.38";
console.log("CLICK CHECK VERSION:"+click_check_version );	
if (typeof clickcnt === 'undefined') {
   var clickcnt = 1;
   //console.log("INIT CLICKCNT");	
}


function findStrID(ida) {
 if(  $.inArray( ida.toString(10), window.answerpos ) > -1) {return 1;}
 else {return 0;}

 }

function getStrID(ida) {
  //console.log("getStrID"+ida) 
  return ida.toString(10);
 
 }


function button_first(id) {

  		            //console.log("button_first"+id) 
                            window.clickedpos.push( id.toString(10) ); //Save clicks for return to blue.                           
                            //$("#"+id).removeClass("btn-primary").addClass("btn-btn-success");
                            //growCanvas();
                           }

function button_normal() {

  		        //console.log("button_normal") 
                        $("#"+window.clickedpos[0]).removeClass("btn-btn-success").addClass("btn-primary");
                       }

function button_success(id) {
  		             //console.log("button_success"+id) 
                             $("#"+id).removeClass("btn-primary").addClass("btn-success");
                             $("#"+id).css("background-color","green");
                          }



function click_check(id) {
 //console.log("click_check"+id) 
 var ida=id-1;

if( window.clickcnt == 1 ){ window.clickcnt=2;wordsearch_startmatch(ida);button_first(id);return 1;}
if( window.clickcnt == 2 ){ window.clickcnt=1;wordsearch_endmatch(ida);return 2;}

}

function wordsearch_startmatch(ida) {

//console.log("WORDSEARCH_STARTMATCH");
//Did it find a match
 if ( findStrID(ida) ) {     
    //console.log("WORDSEARCH_STARTMATCH:FINDSTRID"+ida);
    //Now search for connecting end point numbers to match.  
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

//console.log("WORDSEARCH_ENDMATCH");
if ( findStrID(ida) ) {     
//console.log("WORDSEARCH_ENDMATCH:FINDSTRID"+ida);

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
//console.log("ADDWORDFOUND");

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
//console.log("STRIKEOUT_WORD");

$('span.wordsearchitem:contains('+word+')', document.body).each(function(){      
      $(this).html($(this).html().replace(
            new RegExp(word, 'g'), '<span class="wordsearchitem" style="text-decoration: line-through;" >'+word+'</span>'
      ));
});

 
}


//This section is for mouse position and line drawing movements. 
//Global variables for wsGameCanvas
var wsgc_firstClick=0;
var letsdraw= [];
var mousePos;
var theCanvas = document.getElementById('wsGameCanvas');
window.theCanvas.position = "relative";
window.theCanvas.top = "0"; 
window.theCanvas.left = "0";
var ctx = theCanvas.getContext('2d');
//console.log("INIT CANVAS VALUES");

$(function() {
  grow_canvas();	
// Touch Events.        

$('#wsGameCanvas').on({ 'touchmove': function(e) {
    e.stopPropagation();
    e.preventDefault(); 
    e.stopImmediatePropagation();
    if( window.wsgc_firstClick == 1 ) { start_draw(e);  }  
    //console.log("TOUCH:MOVE");
}});

$('#wsGameCanvas').on({ 'touchstart': function(e) {
    e.stopPropagation();
    e.preventDefault();
    e.stopImmediatePropagation();
    //console.log("TOUCH:START");
    clearCanvas();
    switch( window.wsgc_firstClick ) {
     case 0:
     //Mouse clicked for first time ,lets setup the draw.                   
     set_draw(e);           
     first_click(e);                 
     break;
     default:
     break;
    }
}});

$('#wsGameCanvas').on({ 'touchend': function(e) {
    e.stopPropagation();
    e.preventDefault();
    e.stopImmediatePropagation();
    //console.log("TOUCH:END");
    clearCanvas();
    switch( window.wsgc_firstClick ) {
     case 1:
     start_draw(e);               
     second_click(e);
     break;
     default:
     break;
    }

}});

//Catch all for error in touch events.Try to finish up game action.
$('#wsGameCanvas').on({ 'touchcancel': function(e) {
    e.stopPropagation();
    e.preventDefault();
    e.stopImmediatePropagation();
    //console.log("TOUCH:CANCEL");
    clearCanvas();
    switch( window.wsgc_firstClick ) {
     case 0:
     //Mouse clicked for first time ,lets setup the draw.                   
     set_draw(e);
     first_click(e);
     break;
     case 1:
     second_click(e);

     break;
     default:
     break;
    }

}});


// Mouse events

$('#wsGameCanvas').mousemove(function(e) {
    e.stopPropagation();
    e.preventDefault();	
    e.stopImmediatePropagation();
    if( window.wsgc_firstClick == 1 ) { start_draw(e);  }  
});

$('#wsGameCanvas').mousedown(function(e) {
    e.stopPropagation();
    e.preventDefault();	
    e.stopImmediatePropagation();
    clearCanvas(); 
    switch( window.wsgc_firstClick ) {
     case 0:
     //Mouse clicked for first time ,lets setup the draw. 		    
     set_draw(e);		    
     first_click(e);		    
     break;
     case 1:		    
     second_click(e);		    
		    
     break;		    
     default:
     break;
    }

});


//Used to start line draw over again.
function reset_canvas() {
    letsdraw = [];
    window.ctx.clearRect(0,0,window.theCanvas.width,window.theCanvas.height);
}


function first_click(e) {
    //the command in here will be to activate the second click of the game.
	$('#wsGameCanvas').css("display", "none");
        clickPagePos(e);
	$('#wsGameCanvas').css("display", "block");
     window.wsgc_firstClick=1; //Finished start back over and ready for next word search.   
}


function second_click(e) {
    //the command in here will be to activate the second click of the game.
	$('#wsGameCanvas').css("display", "none");
        clickPagePos(e);
	$('#wsGameCanvas').css("display", "block");
     reset_canvas();  
     window.wsgc_firstClick=0; //Finished start back over and ready for next word search.   
}


function set_draw(e) {
           window.mousePos = getMousePos( window.theCanvas  , e);
           window.letsdraw = { x:window.mousePos.x, y:window.mousePos.y  }
}

function start_draw(e) {
      window.ctx.clearRect(0,0,window.theCanvas.width,window.theCanvas.height);
      window.ctx.strokeStyle = 'blue';
      window.ctx.lineWidth = 3;
      window.ctx.beginPath();
      window.ctx.moveTo(window.letsdraw.x , window.letsdraw.y  );
      window.mousePos = getMousePos( window.theCanvas  , e);
      window.ctx.lineTo( window.mousePos.x , window.mousePos.y );	   
      window.ctx.stroke();
}

function shrink_canvas() {
  window.theCanvas.width =  0;
  window.theCanvas.height = 0;
}

//Reset Canvas for Drawing Line
function grow_canvas() {
  window.theCanvas.width =  0;
  window.theCanvas.height = 0;
  window.theCanvas.width =  300;
  window.theCanvas.height = 350;
}

function clickPagePos(evt) {
      if(evt.type == 'touchstart' || evt.type == 'touchmove' || evt.type == 'touchend' || evt.type == 'touchcancel'){
        var touch = evt.originalEvent.touches[0] || evt.originalEvent.changedTouches[0];
	//console.log( "SCREENXY"+e.clientX+" "+e.clientY );        
	document.elementFromPoint( touch.clientX, touch.clientY).click();
//        console.log("CLICK:TOUCH:X:"+touch.clientX+" Y:"+touch.clientY );
      } else if (evt.type == 'mousedown' || evt.type == 'mouseup' || evt.type == 'mousemove' || evt.type == 'mouseover'|| evt.type=='mouseout' || evt.type=='mouseenter' || evt.type=='mouseleave') {
	document.elementFromPoint( evt.clientX, evt.clientY).click();        
//        console.log("CLICK:MOUSE:X:"+evt.clientX+" Y:"+evt.clientY );
      }
}

// Should work with touch or  mouse events and return x and y position. 
function getMousePos(canvas, evt) {

      var rect = canvas.getBoundingClientRect();
      var out = {x:0, y:0};
      if(evt.type == 'touchstart' || evt.type == 'touchmove' || evt.type == 'touchend' || evt.type == 'touchcancel'){
        var touch = evt.originalEvent.touches[0] || evt.originalEvent.changedTouches[0];
        out.x = touch.clientX - rect.left;
        out.y = touch.clientY - rect.top;
//        console.log("TOUCH:X:"+out.x+" Y:"+out.y );
      } else if (evt.type == 'mousedown' || evt.type == 'mouseup' || evt.type == 'mousemove' || evt.type == 'mouseover'|| evt.type=='mouseout' || evt.type=='mouseenter' || evt.type=='mouseleave') {
        out.x = evt.clientX - rect.left;
        out.y = evt.clientY - rect.top;
//        console.log("MOUSE:X:"+out.x+" Y:"+out.y );
      }
      return out;
    }


});


//Global clear the canvas
function clearCanvas() {
    window.ctx.clearRect(0,0,window.theCanvas.width,window.theCanvas.height);
}
