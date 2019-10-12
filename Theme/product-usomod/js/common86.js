( function( $ ) {
	
	
	var $window = $(window);
	
	
	$window.scroll(function() {
		if ( $(this).scrollTop() > 500 ) {
			$('.to-top').addClass('show');
		} else {
			$('.to-top').removeClass('show');
		}
	}); 
	
	$('.to-top').click( function() {
		$("html, body").animate({ scrollTop: 0 }, 'slow');
		return false;
	});
	
	var	screenSize = 'full';
	
	$window.on('load resize', function() {
		var curScreenSize = 'full';

		if ( matchMedia( 'only screen and (max-width: 1024px)' ).matches ) {
			curScreenSize = 'responsive';
		}

		if ( curScreenSize !== screenSize ) {
			screenSize = curScreenSize;

			if ( curScreenSize === 'responsive' ) {
				var $responsiveMenu = $('#site-navigation').attr('id', 'site-navigation-mobi').hide();
				var hasSubMenu = $('#site-navigation-mobi').find('li:has(ul)');

				$('#header').find('.head-wrap').after($responsiveMenu);
				hasSubMenu.children('ul').hide();
				hasSubMenu.children('a').after('<span class="btn-submenu"></span>');
				$('.btn-menu').removeClass('active');
			} else {
				var $fullMenu = $('#site-navigation-mobi').attr('id', 'site-navigation').removeAttr('style');

				$fullMenu.find('.submenu').removeAttr('style');
				$('#header').find('.col-md-10').append($fullMenu);
				$('.btn-submenu').remove();
			}
		}
	});
	
	$('.btn-menu').on('click', function() {
		$('#site-navigation-mobi').slideToggle(300);
		$(this).toggleClass('active');
	});

	$(document).on('click', '#site-navigation-mobi li .btn-submenu', function(e) {
		$(this).toggleClass('active').next('ul').slideToggle(300);
		e.stopImmediatePropagation()
	});
	
	$('.site-navigation a[href*="#"], .smoothscroll[href*="#"]').on('click',function (e) {
		var target = this.hash;
		var $target = $(target);

		if ( $target.length ) {
			e.preventDefault();
			$('html, body').stop().animate({
				 'scrollTop': $target.offset().top - 100
			}, 900, 'swing');
			
			return false;
		}
	});
	
	
} )( jQuery );

//Add accordion functionality to Wordpress theme 
$( function() { $( "#accordion" ).accordion();  } );



function SizeManager(x) {
  if (uso_xsize.matches) { // If media query matches
   //smaller 
   $("#bannerimg1").removeClass("bannericonone");
   $("#bannerimg1").addClass("bannericonone_sm");    
   $("#bannerimg2").removeClass("bannericontwo");
   $("#bannerimg2").addClass("bannericontwo_sm");    
   $("#bannerimg3").removeClass("bannericonthree");
   $("#bannerimg3").addClass("bannericonthree_sm");

   $("#menuwrapper2").hide();

   $(".APVIDEO").children("iframe").height("150");     
  } else {
   //bigger
   $("#bannerimg1").removeClass("bannericonone_sm");
   $("#bannerimg1").addClass("bannericonone");    
   $("#bannerimg2").removeClass("bannericontwo_sm");
   $("#bannerimg2").addClass("bannericontwo");    
   $("#bannerimg3").removeClass("bannericonthree_sm");
   $("#bannerimg3").addClass("bannericonthree");  
  
   $(".APVIDEO").children("iframe").height("300");
  }
}

//Check window size for responsive actions. 
var uso_xsize = window.matchMedia("(max-width: 900px)");
SizeManager(uso_xsize); // Call listener function at run time
uso_xsize.addListener(SizeManager); // Attach listener function on state changes


//init

$("#bmenu2").menu();

$("#menuwrapper2").hide();

$("#bannerimg2").click( function ()
 {
  //Check if media is larger than set , if not don't process.
  if (!uso_xsize.matches) { $("#menuwrapper2").toggle();}
 });


$("#uso_ws").click( function () {
 console.log("Word Search");
$(".bannerpuzzle").show();
$(".bannercomic1").hide();
$(".bannercomic2").hide();
 });


$("#uso_usrf").click( function () { 
console.log("Userfriendly");
$(".bannerpuzzle").hide();
$(".bannercomic1").hide();
$(".bannercomic2").hide();
});


$("#uso_xkcd").click( function () {
console.log("XKCD");
$(".bannerpuzzle").hide();
$(".bannercomic1").hide();
$(".bannercomic2").show();

});


$("#uso_trnof").click( function () {
console.log("TurnOff.us");
$(".bannerpuzzle").hide();
$(".bannercomic1").show();
$(".bannercomic2").hide();
});



// carousel scroll toggle.


//Initial state
$('#carouselNEWS.toggle').minitoggle({ on: false });
$('#carouselVIDS.toggle').minitoggle({ on: false });
$('#carouselAUDIO.toggle').minitoggle({ on: false });
$('#carouselINFOSEC.toggle').minitoggle({ on: false });
carouselNEWS = $("#slider_1299"); 
carouselVIDS = $("#slider_1316"); 
carouselAUDIO = $("#slider_1310");  
carouselINFOSEC = $("#slider_1429"); 
carouselNEWS.trigger('stop.owl.autoplay');
carouselVIDS.trigger('stop.owl.autoplay');
carouselAUDIO.trigger('stop.owl.autoplay');
carouselINFOSEC.trigger('stop.owl.autoplay');

//on resize reset toggle.
$(document).ready(function() { 
                $(window).resize(function() {                    
                 $('#carouselNEWS.toggle').minitoggle({ on: false });
                 $('#carouselVIDS.toggle').minitoggle({ on: false });
                 $('#carouselAUDIO.toggle').minitoggle({ on: false });
                 $('#carouselINFOSEC.toggle').minitoggle({ on: false });
                 carouselNEWS.trigger('stop.owl.autoplay');
                 carouselVIDS.trigger('stop.owl.autoplay');
                 carouselAUDIO.trigger('stop.owl.autoplay');
                 carouselINFOSEC.trigger('stop.owl.autoplay');
                }); 

            });



$("#carouselVIDS.toggle").on("toggle", function(e){
  if (e.isActive)
     {
     $("#carouselVIDS.result").html("")
      carouselVIDS.trigger('play.owl.autoplay');
      
     }
  else
     {
      $("#carouselVIDS.result").html("<h3>HOLD</h3>")
      carouselVIDS.trigger('stop.owl.autoplay');
     }
});

$("#carouselNEWS.toggle").on("toggle", function(e){
  if (e.isActive)
     {
      $("#carouselNEWS.result").html(" ")
      carouselNEWS.trigger('play.owl.autoplay');
      
     }
  else
     {
      $("#carouselNEWS.result").html("<h3>HOLD</h3>")
      carouselNEWS.trigger('stop.owl.autoplay');
     }
});

$("#carouselAUDIO.toggle").on("toggle", function(e){  
  if (e.isActive)
     {
     $("#carouselAUDIO.result").html(" ")
      carouselAUDIO.trigger('play.owl.autoplay');
      
     }
  else
     {
      $("#carouselAUDIO.result").html("<h3>HOLD</h3>")
      carouselAUDIO.trigger('stop.owl.autoplay');
     }
});


$("#carouselINFOSEC.toggle").on("toggle", function(e){  
  if (e.isActive)
     {
     $("#carouselINFOSEC.result").html(" ")
      carouselINFOSEC.trigger('play.owl.autoplay');
      
     }
  else
     {
      $("#carouselINFOSEC.result").html("<h3>HOLD</h3>")
      carouselINFOSEC.trigger('stop.owl.autoplay');
     }
});
 

//Progmatically load page content to active section of home page. Need to add LOADING section.
// Functions to process async loading operation visually.
function JaxLoader( site_page ){
$('span.loadmedia').show(); 
console.log("SHOW");
loadContent(site_page);
return false;    
}

function loadContent(site_page) {

    $.get( site_page ,function(r){
    var els = $(r).find('#primary,script,style');
    $('#jaxslide').html(els);} );  
   
}


//Set default page to show Virtualspace
JaxLoader( 'ram' );
// for 404! page not to show loading span.
$('span.loadmedia').hide();   



//Setup CLICK event listener 
$('.jaxslide').click(function(e){  
e.preventDefault();

switch(e.target.id) {
  case "RAM":   
    JaxLoader( 'ram' );   
    return false;       //You have to return false from CLICK event to activate AJAX. 
    break;
  case "NEWS":    
    JaxLoader( 'newsfeeds' );    
    return false;
    break;
  case "VIDEO":    
    JaxLoader( 'videocast' );    
    return false;   
    break;
  case "AUDIO":    
    JaxLoader( 'podcast' );   
    return false;    
    break;
  case "INFOSEC":    
    JaxLoader( 'infosec' );    
    return false;   
    break;
  default:   
    $('span.loadmedia').hide();
    return false;  
    
}


    
});


$("body").removeClass("animsition");
$(document).ready( function () {
    $('.tablepress-id-1').DataTable();
} );
